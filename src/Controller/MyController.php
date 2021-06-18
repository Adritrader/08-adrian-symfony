<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyController extends AbstractController
{

    /**
     * @Route("/servicios", name="servicios")
     */
    public function servicios(): Response
    {
        return $this->render('front/servicios.html.twig');

    }


    /**
     * @Route("/quienes-somos", name="quienes_somos")
     */
    public function quienesSomos(): Response
    {
        return $this->render('front/quienes-somos.html.twig');

    }

    /**
     * @Route("/blog", name="blog")
     */
    public function blog(): Response
    {
        return $this->render('front/blog.html.twig');

    }

    /**
     * @Route("/galeria", name="galeria")
     */
    public function galeria(): Response
    {
        return $this->render('front/galeria.html.twig');

    }

    /**
     * @Route("/contacto", name="contacto")
     */
    public function contacto(): Response
    {
        return $this->render('front/contacto.html.twig');

    }


}
