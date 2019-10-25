<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{
    /**
     * @Route("/main", name="main")
     */
    public function index()
    {

        return $this->redirectToRoute('sortiesapp_sortie_listsorties');

    }
}
