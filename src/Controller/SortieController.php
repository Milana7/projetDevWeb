<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="sortie")
 * Class SortieController
 * @package App\Controller
 */
class SortieController extends Controller
{

    /**
     * @Route("/", name="sortie")
     * Affiche toutes les sorties disponibles
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSorties(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie');

        $listSortie = $repository->listSortiesAll();


        return $this->render('sortie/sortie.html.twig',[
            'controller_name' => 'SortieController',
            'listSortie' => $listSortie,
        ]);
    }

    /**
     * @Route("/mesSortiesOrganisees/{idOrg}", name="sortieByIdOrg")
     * Affiche les sorties par identifiant de l'organisateur
     * @param Request $request
     * @param Utilisateur $idOrg
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSortiesByIdOrg(Request $request,$idOrg)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie');

        $listSortie = $repository->listByOrganiser($idOrg);


        return $this->render('sortie/sortiesByOrganiser.html.twig',[
            'controller_name' => 'SortieController',
            'listSortie' => $listSortie,
        ]);
    }

    /**
     * @Route("/sortiesPassees", name="sortiesExpired")
     * Affiche les sorties par identifiant de l'organisateur
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSortiesExpired(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie');

        $listSortie = $repository->listByOrganiser(1);


        return $this->render('sortie/sortiesByOrganiser.html.twig',[
            'controller_name' => 'SortieController',
            'listSortie' => $listSortie,
        ]);
    }
}
