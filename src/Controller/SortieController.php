<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FiltreSortie;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Entity\Ville;
use App\Form\AnnulerSortieType;
use App\Form\CreerSortieType;
use App\Form\FiltreSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="sorties")
 * Class SortieController
 * @package App\Controller
 */
class SortieController extends Controller
{
    /**
     * Affiche toutes les sorties disponibles
     *
     * @Route("/")
     * @param Request $request
     * @return Response
     */
    public function listSorties(Request $request)
    {
        $filtre = new FiltreSortie();

        $form = $this->createForm(FiltreSortieType::class, $filtre);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
        }
        $repositoryS = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Sortie');

        $listSortie = $repositoryS->listSortiesAll($filtre, $this->getUser()->getId());
        $user = new Sortie();
        $u = $user->getUtilisateurs()->current();

        $repositoryU = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:Utilisateur');

        $listUser = $repositoryU->findAll();
        $dateJour = new \DateTime('now');

        return $this->render('sortie/sortie.html.twig', [
            'controller_name' => 'SortieController',
            'listSortie' => $listSortie,
            'listUser' => $listUser,
            'dateJour' => $dateJour,
            'filtre' => $form->createView(),
        ]);
    }


    /**
     * Affiche les sorties qui ont une date expirée
     *
     * @Route("/sortiesPassees", name="sortiesExpired")
     * @param Request $request
     * @return Response
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

    // CREATION SORTIE

    /**
     * @Route("/creerSortie", name="creer_sortie")
     * Traitement du formulaire de création de sortie (affichage vue ou création en base)
     */
    public function creerSortie(Request $request)
    {
        // Récupération de l'organisateur (utilisateur en session)
        $organisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($this->getUser()->getId());

        $nouvelleSortie = new Sortie();

        $form = $this->createForm(CreerSortieType::class, $nouvelleSortie);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $site = $this->getDoctrine()->getRepository(Site::class)->find($organisateur->getSite())->getNom();
            $form->get('villeOrganisatrice')->setData($site);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            // On adapte l'état en fonction du bouton sélectionné (publier/enregistrer)
            if ($form->getClickedButton() && 'save' === $form->getClickedButton()->getName()) {
                $etat = $this->getDoctrine()->getRepository(Etat::class)->find(1);
            }
            if ($form->getClickedButton() && 'publish' === $form->getClickedButton()->getName()) {
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

            return $this->redirectToRoute('sortiesapp_sortie_listsorties');
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
    public function getLieuxByVilleId(Request $request)
    {
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
    public function getDetailsLieu(Request $request)
    {
        $idLieu = $request->query->get('idLieu');
        $repo = $this->getDoctrine()->getRepository(Lieu::class);

        $detailsLieu = $repo->findById($idLieu);

        return new JsonResponse($detailsLieu);
    }

    // AFFICHER UNE SORTIE

    /**
     * @Route("/afficherSortie/{sortieId}", name="_afficherSortie")
     * Affichage d'une sortie
     * @param Request $request
     * @param $sortieId
     * @return Response
     */
    public function afficherSortie(Request $request, $sortieId)
    {
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($sortieId);
        dump($sortie);
        $form = $this->createForm(CreerSortieType::class, $sortie);
        $site = $sortie->getOrganisateur()->getSite();
        dump($site);
        $form->get('villeOrganisatrice')->setData($this->getDoctrine()->getRepository(Site::class)->find($site)->getNom());
        $form->handleRequest($request);
        return $this->render('sortie/creerSortie.html.twig', ['form' => $form->createView()]);
    }

    // INSCRIPTION/DESISTEMENT

    /**
     * Inscris un utilisateur a une sortie
     * @Route("/inscription/{sortieId}", name="_inscription", methods={"GET"})
     * @return RedirectResponse
     * @throws Exception
     */
    public function inscription($sortieId)
    {
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($sortieId);
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($this->getUser()->getId());
        $etat = $sortie->getEtat();

        if ($etat->getId() === 2) {
            $now = new \DateTime('now');
            // TODO verification sur nb max d'inscriptions + nb inscription en cours
            if ($sortie->getDateLimiteInscription() > $now) {

                $sortie->addUtilisateur($utilisateur);

                $em = $this->getDoctrine()->getManager();
                $em->persist($sortie);
                $em->flush();

                return $this->redirectToRoute('sortiesapp_sortie_listsorties');
            }
        } else {
            return
                $this->addFlash('warning','Vous ne pouvez pas vous inscire à cette sortie');
        }
        // TODO return errorMsg

        return $this->redirectToRoute('sortiesapp_sortie_listsorties');
    }

    /**
     * Supprime l'inscription d'un utilisateur a une sortie
     * @Route("/desistement/{sortieId}", name="_desistement", methods={"GET"})
     * @return RedirectResponse
     * @throws Exception
     */
    public function desistement($sortieId)
    {
        $sortie = $this->getDoctrine()->getRepository(Sortie::class)->find($sortieId);
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($this->getUser()->getId());

        $now = new \DateTime('now');
        if ($sortie->getDateHeureDebut() > $now) {

            $sortie->removeUtilisateur($utilisateur);

            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('sortiesapp_sortie_listsorties');
        }
        // TODO return errorMsg
        return "ERROR";
    }

    /**
     * @Route("/annulerSortie/{id}", name="sortie_annulerSortie")
     */
    public function annulerSortie(int $id, Request $request, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Sortie::class);
        $sortie = $repo->find($id);
        dump($sortie);

        $sortieForm = $this->createForm(AnnulerSortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $sortie = $sortieForm->getData();

            $repo = $em->getRepository(Etat::class);
            $etat = $repo->find(6);
            $sortie->setEtat($etat);

            dump($sortie);

            $sortie->setId(6);

            $error = false;

            if (!$error) {
                $em->persist($sortie);
                $em->flush();

                $this->addFlash("success", "La sortie a été annulée !");
                return $this->redirectToRoute("sortiesapp_sortie_listsorties", ["id" => $sortie->getId()]);
            }
        }
        return $this->render("sortie/annulerSortie.html.twig", [
            "sortieForm" => $sortieForm->createView(),
            "sortie" => $sortie
        ]);
    }
}
