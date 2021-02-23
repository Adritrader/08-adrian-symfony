<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistraController extends AbstractController
{
    /**
     * @Route("/registra", name="registra")
     */
    public function index(): Response
    {
        return $this->render('registra/index.html.twig', [
            'controller_name' => 'RegistraController',
        ]);
    }
}
