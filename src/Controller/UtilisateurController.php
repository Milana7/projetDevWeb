<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ModifierMotDePasseType;
use App\Form\ModifierProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UtilisateurController extends Controller
{

    /**
     * @Route("/utilisateur/{id}", name="utilisateur_detailProfil", requirements={"id": "\d+"})
     */
    public function detailProfil(int $id, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Utilisateur::class);
        $utilisateur = $repo->find($id);

        return $this->render("utilisateur/afficherProfil.html.twig", ["utilisateur" => $utilisateur]);
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

            $error = false;

            //file
            try {
                $file = $utilisateurForm->get('fileTemp')->getData();
                //fichier optionnel
                if ($file != null) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $fileDownload = md5(uniqid(mt_rand(), true)) . '.' . $extension;

                    $file->move($this->getParameter('download_dir'), $fileDownload);
                    $utilisateur->setFile($fileDownload);
                }
            } catch (\Exception $e) {
                //Si il y a une erreur : bloquer l'insertion
                dump($e->getMessage());

                //Ajout d'une erreur depuis le controller
                $utilisateurForm->get('fileTemp')->addError(new FormError("Une erreur est survenue avec le fichier !"));
                $error = true;
            }

            if (!$error) {
                $em->persist($utilisateur);
                $em->flush();

                $this->addFlash("success", "Le profil a été modifié !");
                return $this->redirectToRoute("utilisateur_detailProfil", ["id" => $utilisateur->getId()]);
            }
        }

        return $this->render("utilisateur/modifierProfil.html.twig", [
            "utilisateurForm" => $utilisateurForm->createView()
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

}
