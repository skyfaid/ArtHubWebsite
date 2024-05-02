<?php

namespace App\Controller;

use App\Entity\Vente;
use App\Repository\OeuvreRepository;
use App\Repository\VenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    private $stripeSK;
    private $stripeClient;
    public function __construct(string $stripeSK)
    {
        $this->stripeSK = $stripeSK; 
        $this->stripeClient = new StripeClient($stripeSK);// Injected via services.yaml
    }
    

   


    #[Route('/payment', name: 'payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
    

    #[Route('/checkout', name: 'checkout')]
    public function checkout(SessionInterface $session, OeuvreRepository $oeuvreRepository, EntityManagerInterface $entityManager): Response
    {
        $cart = $session->get('cart', []);
        if (empty($cart)) {
            return $this->redirectToRoute('cart_empty');
        }

        $line_items = [];
        $venteIds = [];
        foreach ($cart as $id => $quantity) {
            $oeuvre = $oeuvreRepository->find($id);
            if (!$oeuvre) {
                continue;
            }

            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $oeuvre->getTitre()],
                    'unit_amount' => (int) ($oeuvre->getPrix() * 100),
                ],
                'quantity' => $quantity,
            ];

            $vente = new Vente();
            $vente->setOeuvre($oeuvre);
            $vente->setQuantite($quantity);
            $vente->setPrixVente($oeuvre->getPrix() * $quantity);
            $vente->setDateVente(new \DateTime());
            $entityManager->persist($vente);
            $entityManager->flush();
            $venteIds[] = $vente->getId();
        }

        $session->set('vente_ids', $venteIds);

        if (empty($line_items)) {
            return $this->redirectToRoute('cart_error');
        }

        $checkout_session = $this->stripeClient->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($checkout_session->url, 303);
}
    


    #[Route('/success-url', name: 'success_url')]
    public function successUrl(SessionInterface $session , VenteRepository $venteRepository): Response
    {
        $venteIds = $session->get('vente_ids', []);

    if (!$venteIds) {
        throw $this->createNotFoundException('Aucune vente ID enregistrée dans la session.');
    }

    $ventes = $venteRepository->findBy(['id' => $venteIds]);

    if (!$ventes) {
        throw $this->createNotFoundException('Ventes non trouvées avec les IDs fournis.');
    }

    return $this->render('payment/success.html.twig', ['ventes' => $ventes]);

    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
}
