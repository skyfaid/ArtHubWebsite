<?php

namespace App\Controller;

use App\Entity\Vente;
use App\Repository\OeuvreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, OeuvreRepository $oeuvreRepository): Response
    {
        $cart = $session->get('cart', []);
        $oeuvres = [];
    
        foreach ($cart as $id => $quantity) {
            $oeuvre = $oeuvreRepository->find($id);
            if ($oeuvre) {
                $oeuvres[] = [
                    'oeuvre' => $oeuvre,
                    'quantity' => $quantity
                ];
            }
        }
        return $this->render('OeuvreClient/panier.html.twig', [
            'items' => $oeuvres,
        ]);
    }


    #[Route('/cart/add/{id}', name: 'cart_add')]
public function add(int $id, SessionInterface $session): Response
{
    $cart = $session->get('cart', []);

    if (!empty($cart[$id])) {
        $cart[$id]++;
    } else {
        $cart[$id] = 1;
    }

    $session->set('cart', $cart);

    return $this->redirectToRoute('app_cart');
}

#[Route('/cart/remove/{id}', name: 'cart_remove')]
public function remove(int $id, SessionInterface $session, OeuvreRepository $oeuvreRepository): Response
{
    $cart = $session->get('cart', []);
    $response = ['success' => false];

    if (isset($cart[$id])) {
        unset($cart[$id]);
        $session->set('cart', $cart);
        $response['success'] = true;

        // Calculez le nouveau total ici en parcourant le panier mis à jour
        $newTotal = array_sum(
            array_map(
                function ($id, $quantity) use ($oeuvreRepository) {
                    $oeuvre = $oeuvreRepository->find($id);
                    return $oeuvre ? $oeuvre->getPrix() * $quantity : 0;
                },
                array_keys($cart),
                $cart
            )
        );

        $response['newTotal'] = $newTotal;
    }

    return $this->json($response);
}


// ...
/**
 * @Route("/cart/update/{id}", name="route_pour_modifier_quantite")
 */
public function updateQuantity(Request $request, SessionInterface $session, OeuvreRepository $oeuvreRepository, $id): Response
{
    $action = $request->request->get('action');
    $cart = $session->get('cart', []);

    if (isset($cart[$id])) {
        if ($action === 'increment') {
            $cart[$id]++;
        } elseif ($action === 'decrement' && $cart[$id] > 1) {
            $cart[$id]--;
        }

        $session->set('cart', $cart);

        // Récupère l'œuvre pour obtenir le prix et calcule le nouveau total
        $oeuvre = $oeuvreRepository->find($id);
        $newQuantity = $cart[$id];
        $newTotal = array_sum(
            array_map(
                function ($id, $quantity) use ($oeuvreRepository) {
                    $oeuvre = $oeuvreRepository->find($id);
                    return $oeuvre ? $oeuvre->getPrix() * $quantity : 0;
                },
                array_keys($cart),
                $cart
            )
        );

        return $this->json([
            'success' => true,
            'id' => $id,
            'newQuantity' => $newQuantity,
            'newTotal' => $newTotal,
        ]);
    }

    return $this->json(['success' => false]);
}
// ...

#[Route('/cart/checkout', name: 'cart_checkout')]
public function checkout(SessionInterface $session, OeuvreRepository $oeuvreRepository): Response
{
    $cart = $session->get('cart', []);

    // Assurez-vous de démarrer une transaction ou de gérer les erreurs proprement
    $entityManager = $this->getDoctrine()->getManager();

    foreach ($cart as $id => $quantity) {
        $oeuvre = $oeuvreRepository->find($id);
        if ($oeuvre) {
            $vente = new Vente();
            // Configurez votre entité Vente en conséquence
            $vente->setOeuvre($oeuvre);
            $vente->setQuantite($quantity);
            $vente->setPrixvente($oeuvre->getPrix() * $quantity);
            $vente->setDatevente(new \DateTime());

            // Persistez l'entité Vente
            $entityManager->persist($vente);
        }
    }

    // Appliquez les changements dans la base de données
    $entityManager->flush();

    // Effacer le panier après le paiement
    $session->set('cart', []);

    // Redirection vers une page de confirmation ou autre
    return new RedirectResponse($this->generateUrl('app_cart'));
}

}
