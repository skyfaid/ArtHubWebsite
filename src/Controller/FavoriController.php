<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FavoriController extends AbstractController
{
 /**
     * @Route("/favori/toggle/{id}", name="toggle_favori", methods={"POST"})
     */
    public function toggleFavori(Request $request, SessionInterface $session, Oeuvre $oeuvre): Response
    {
        $favoris = $session->get('favoris', []);

        if (array_key_exists($oeuvre->getId(), $favoris)) {
            // Supprimer des favoris
            unset($favoris[$oeuvre->getId()]);
        } else {
            // Ajouter aux favoris
            $favoris[$oeuvre->getId()] = $oeuvre->getTitre();
        }

        $session->set('favoris', $favoris);

        return $this->json(['status' => 'success', 'favoris' => $favoris]);
    }
}
