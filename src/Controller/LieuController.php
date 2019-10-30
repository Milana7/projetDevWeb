<?php


namespace App\Controller;


use App\Entity\Lieu;
use App\Form\GererLieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class LieuController extends Controller
{

    /**
     * @Route("/addLieu", name="_ajouterLieu")
     *
     */
    public function ajouter(Request $request, EntityManagerInterface $em)
    {
        $lieu = new Lieu();

        $lieuForm = $this->createForm(GererLieuType::class, $lieu);
        $lieuForm->handleRequest($request);


       if ($lieuForm->isSubmitted() && $lieuForm->isValid()) {

            $em->persist($lieu);
            $em->flush();

            $this->addFlash("success", "Le lieu a été créée");
            return $this->redirectToRoute('sortiescreer_sortie');

        }
     return $this->render('sortie/lieu.html.twig', ['form' => $lieuForm->createView()]);
    }


}