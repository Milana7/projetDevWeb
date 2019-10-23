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
     * Affiche toutes les sorties disponibles
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afficherSortiesAction(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie');

        $listeSortie = $repository->listeSortiesAll();


        return $this->render('sortie.html.twig', [
            'controller_name' => 'SortieController',
            'listeSortie' => $listeSortie,
        ]);
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
