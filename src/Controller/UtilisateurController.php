<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ModifierMotDePasseType;
use App\Form\ModifierProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UtilisateurController extends Controller
{

    /**
     * @Route("/utilisateur/{id}", name="utilisateur_detailProfil", requirements={"id": "\d+"})
     */
    public function detailProfil(int $id, EntityManagerInterface $em, Request $request)
    {
        // On récupère l'url dont vient l'utilisateur afin de l'utiliser pour revenir en arrière si besoin
        $session = $request->getSession();
        $referer = filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);

        $session->set('referer', $referer);

        $repo = $em->getRepository(Utilisateur::class);
        $utilisateur = $repo->find($id);

        dump($utilisateur);

        if (empty($utilisateur->getImageName())) {
            $utilisateur->setImageName('image/user.jpg');
        } else {
            $utilisateur->setImageName('images/profils/' . $utilisateur->getImageName());
        }

        return $this->render("utilisateur/afficherProfil.html.twig", ["utilisateur" => $utilisateur]);
    }

    /**
     * @Route("/back", name="_back")
     */
    public function back(Request $request)
    {
        // On recupere le referer en session (referer = url dont vient l'utilisateur)
        $session = $request->getSession();
        $referer = $session->get('referer');

        if ($referer == null || empty($referer)) {
            return $this->redirectToRoute('sortiesapp_sortie_listsorties');
        }
        return $this->redirect($referer);
    }

    /**
     * @Route("/utilisateur/modifierProfil", name="utilisateur_modifierProfil")
     */
    public function modifierProfil(Request $request, EntityManagerInterface $em)
    {
        $utilisateur = $this->getUser();

        $utilisateurForm = $this->createForm(ModifierProfilType::class, $utilisateur);
        $utilisateurForm->handleRequest($request);

        if ($utilisateurForm->isSubmitted() && $utilisateurForm->isValid()) {
            $em->persist($utilisateur);
            $em->flush();

            $this->addFlash("success", "Le profil a été modifié !");
            return $this->redirectToRoute("utilisateur_detailProfil", ["id" => $utilisateur->getId()]);
        }

        if (empty($utilisateur->getImageName())) {
            $utilisateur->setImageName('image/user.jpg');
        } else {
            $utilisateur->setImageName('images/profils/' . $utilisateur->getImageName());
        }

        return $this->render("utilisateur/modifierProfil.html.twig", [
            "utilisateurForm" => $utilisateurForm->createView(), "utilisateur" => $utilisateur
        ]);
    }

    /**
     * @Route("/utilisateur/modifierMotDePasse", name="utilisateur_modifierMotDePasse")
     */
    public function modifierMotDePasse(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $utilisateur = $this->getUser();

        $motDePasseForm = $this->createForm(ModifierMotDePasseType::class, $utilisateur);
        $motDePasseForm->handleRequest($request);

        if ($motDePasseForm->isSubmitted() && $motDePasseForm->isValid()) {
            $password = $passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($password);
            $error = false;

            if (!$error) {
                $em->persist($utilisateur);
                $em->flush();

                $this->addFlash("success", "Le mot de passe a été modifié !");
                return $this->redirectToRoute("utilisateur_detailProfil", ["id" => $utilisateur->getId()]);
            }
        }

        return $this->render("utilisateur/modifierMotDePasse.html.twig", [
            "motDePasseForm" => $motDePasseForm->createView()
        ]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('utilisateur/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {

    }

    /**
     * @Route("/gestionUtilisateur", name="_gestionUtilisateur")
     */
    public function getUtilisateurs(EntityManagerInterface $em)
    {
        $utilisateurs = $em->getRepository(Utilisateur::class)->findAll();
        return $this->render('utilisateur/listeUtilisateurs.html.twig', ['utilisateurs' => $utilisateurs]);
    }

    /**
     * @Route("/changerStatutUtilisateurs", name="_changerStatutUtilisateurs")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return JsonResponse
     */
    public function changerStatutUtilisateurs(EntityManagerInterface $em, Request $request)
    {
        $selectedElements = $request->get('selectedElements');
        $items = explode(',', $selectedElements);
        $userRepo = $em->getRepository(Utilisateur::class);
        if (count($items) > 0) {
            foreach ($items as $userId) {
                $utilisateur = $userRepo->find($userId);
                $utilisateur->setActif(!$utilisateur->getActif());
            }
            $em->flush();
        }
        $utilisateurs = $em->getRepository(Utilisateur::class)->findAllUser();
        return new JsonResponse($utilisateurs);
    }
}
