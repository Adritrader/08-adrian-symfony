<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyController extends AbstractController
{
    /**
     * @Route("/my", name="my")
     */
    public function index(): Response
    {
        return $this->render('my/index.html.twig');

}

    public function servicios(): Response
    {
        return $this->render('servicios.html.twig');

    }

    public function tienda(): Response
    {
        return $this->render('tienda.html.twig');

    }

    public function quienesSomos(): Response
    {
        return $this->render('quienes-somos.html.twig');

    }

    public function blog(): Response
    {
        return $this->render('blog.html.twig');

    }

    public function galeria(): Response
    {
        return $this->render('galeria.html.twig');

    }

    public function contacto(): Response
    {
        return $this->render('contacto.html.twig');

    }


}
