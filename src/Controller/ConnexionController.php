<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends Controller
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function index()
    {
        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }
}
