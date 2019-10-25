<?php

namespace App\Controller;

use App\Entity\FiltreSortie;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Form\FiltreSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sorties", name="sorties")
 * Class SortieController
 * @package App\Controller
 */
class SortieController extends Controller
{
    /**
     * Filtrer les sorties sur la page d'accueil
     *
     * @Route("/sortiesFiltrees", name="sortiesFiltrees")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    /*    public function listSortiesFiltrated(Request $request)
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
        }*/

    /**
     * Affiche toutes les sorties disponibles
     *
     * @Route("/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSorties(Request $request)
    {
        /**
         * @var FiltreSortieType $filtre
         */
        $filtre = new FiltreSortie();
       // $form = $this->createFormBuilder(FiltreSortieType::class)->getData();

        $filtre = $this->createForm(FiltreSortieType::class, $filtre);

        /*if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
        }*/

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie');

        $listSortie = $repository->listSortiesAll();

        return $this->render('sortie/sortie.html.twig', [
            'controller_name' => 'SortieController',
            'listSortie' => $listSortie,
            'filtre' => $filtre->createView(),
        ]);
    }

    /**
     * Affiche les sorties par identifiant de l'organisateur
     *
     * @Route("/mesSortiesOrganisees/{idOrg}", name="sortieByIdOrg")
     * @param Request $request
     * @param Utilisateur $idOrg
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSortiesByIdOrg(Request $request, $idOrg)
    {

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie');

        $listSortie = $repository->listByOrganiser($idOrg);


        return $this->render('sortie/sortiesByOrganiser.html.twig', [
            'controller_name' => 'SortieController',
            'listSortie' => $listSortie,
        ]);
    }

    /**
     * Affiche les sorties qui ont une date expirée
     *
     * @Route("/sortiesPassees", name="sortiesExpired")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSortiesExpired(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie');

        $listSortie = $repository->listSortiesExpired();


        return $this->render('sortie/sortiesExpired.html.twig', [
            'controller_name' => 'SortieController',
            'listSortie' => $listSortie,
        ]);
    }
}
