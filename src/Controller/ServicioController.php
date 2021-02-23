<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicioController extends AbstractController
{
    /**
     * @Route("/servicio", name="servicio")
     */
    public function index(): Response
    {
        return $this->render('servicio/index.html.twig', [
            'controller_name' => 'ServicioController',
        ]);
    }
}
