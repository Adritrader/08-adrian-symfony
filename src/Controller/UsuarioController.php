<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/usuario", name="usuario")
     */
    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }

    /**
     * @Route("/usuario/create", name="usuario_create")
     */
    public function create()
    {
        $usuario = new Usuario();
        $form = $this->createFormBuilder($usuario)
            ->add('nombre', TextType::class)
            ->add('', TextType::class)
            ->add('overview', TextareaType::class)
            ->add('releaseDate', DateType::class)
            ->add('poster', TextType::class)
            ->add('create', SubmitType::class, array('label' => 'Create'))
            ->getForm();
        return $this->render('create.html.twig', array(
            'form' => $form->createView()));
    }
}
