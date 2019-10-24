<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\CreerSortieType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @Route("/creerSortie", name="creer_sortie")
     * Traitement du formulaire de création de sortie (affichage vue ou création en base)
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

    /**
     * Retourne la liste des lieux en fonction de l'id passé en paramètre
     * Retour au format json
     * @Route("/getLieuxByVille", name="_getLieuxByVille", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getLieuxByVilleId(Request $request){
        $idVille = $request->query->get('idVille');
        $repo = $this->getDoctrine()->getRepository(Lieu::class);
        $listeLieux = $repo->findByIdVille($idVille);

        return new JsonResponse($listeLieux);
    }

    /**
     * Retourne les détails du lieu dont l'id est passé en paramètre
     * Retour au format json
     * @Route("/getDetailsLieu", name="_getDetailsLieu", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getDetailsLieux(Request $request){
        $idLieu = $request->query->get('idLieu');
        $repo = $this->getDoctrine()->getRepository(Lieu::class);
        $detailsLieu = $repo->findById($idLieu);

        return new JsonResponse($detailsLieu);
    }
}
