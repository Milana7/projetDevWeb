<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreerSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends Controller
{
    /**
     * @Route("/sortie", name="sortie")
     */
    public function index()
    {
        return $this->render('sortie/index.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * Affiche toutes les sorties disponibles
     */
    public function afficherSortiesAction()
    {

    }

    /**
     * @Route("/creerSortie", name="creer_sortie")
    */
    public function creerSortie(Request $request)
    {
        $nouvelleSortie = new Sortie();

        $form = $this->createForm(CreerSortieType::class, $nouvelleSortie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $sortie = $form->getData();
            // TODO Sauvegarder les données en base

            return $this->redirectToRoute('sortie');
        }

        return $this->render('sortie/creerSortie.html.twig', ['form' => $form->createView()]);
    }
}
