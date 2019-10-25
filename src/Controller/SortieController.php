<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\CreerSortieType;
use App\Entity\Utilisateur;
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
     * @Route("/", name="_sortie")
     * Affiche toutes les sorties disponibles
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSorties()
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
            //TODO A modifier pour récupérer l'utilisateur en session
            $organisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find(1);

            // On adapte l'état en fonction du bouton sélectionné (publier/enregistrer)
            if ($form->getClickedButton() && 'save' === $form->getClickedButton()->getName()){
                $etat = $this->getDoctrine()->getRepository(Etat::class)->find(1);
            }
            if($form->getClickedButton() && 'publish' === $form->getClickedButton()->getName()){
                $etat = $this->getDoctrine()->getRepository(Etat::class)->find(2);
            }

            $lieu = $this->getDoctrine()->getRepository(Lieu::class)->find($form->get('idLieu')->getData());

            // Construction de la sortie à insérer en base
            $sortie = $form->getData();
            $sortie->setOrganisateur($organisateur);
            $sortie->setEtat($etat);
            $sortie->setLieu($lieu);

            // Ajout en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('sortie_sortie');
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
    public function getDetailsLieu(Request $request){
        $idLieu = $request->query->get('idLieu');
        $repo = $this->getDoctrine()->getRepository(Lieu::class);

        $detailsLieu = $repo->findById($idLieu);

        return new JsonResponse($detailsLieu);
    }
}
