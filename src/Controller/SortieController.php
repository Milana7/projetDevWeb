<?php

namespace App\Controller;

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
}
