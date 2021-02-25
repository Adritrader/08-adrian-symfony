<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
            ->add('apellidos', TextType::class)
            ->add('overview', TextareaType::class)
            ->add('releaseDate', DateType::class)
            ->add('poster', TextType::class)
            ->add('create', SubmitType::class, array('label' => 'Create'))
            ->getForm();
        return $this->render('create.html.twig', array(
            'form' => $form->createView()));
    }
}
