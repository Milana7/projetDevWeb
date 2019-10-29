<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\ModifierSiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GererSitesController extends Controller
{
    /**
     * @Route("/gererSites",name="gererSites")
     */
    public function listSites()
    {
        $listSites = $this->getDoctrine()->getRepository(Site::class)->listSites();
        dump($listSites);
        return $this->render('gererSites/gererSites.html.twig', [
            'controller_name' => 'GererSitesController',
            'listSite' => $listSites,
        ]);
    }

    /**
     * @Route("/modifierSite/{idSite}", name="_modifierSite")
     *
     */
    public function modifierSite (Request $request, EntityManagerInterface $em, $idSite) {
        $site= $em->getRepository(Site::class)->find($idSite);

        $form = $this->createForm(ModifierSiteType::class, $site);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // Ajout en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);
            $em->flush();

            return $this->redirectToRoute("gererSites");

        }

        return $this->render('gererSites/modifierSite.html.twig', ['form' => $form->createView()]);


    }


    /**
     * @Route("/supprimerSite/{idSite}",name="_supprimerSite")
     * @param EntityManagerInterface $em
     * @param $idSite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function supprimerSite (EntityManagerInterface $em, $idSite) {

        $site = $em->getRepository(Site::class)->find($idSite);
        $site->setIsActif(false);
        //suppression en BDD
        $em = $this->getDoctrine()->getManager();
        $em->persist($site);

        $em->flush();

        return $this->redirectToRoute("gererSites");

    }
}
