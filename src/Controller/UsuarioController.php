<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;




class UsuarioController extends AbstractController
{
    /**
     * @Route("/usuario", name="usuario")
     */
    public function index(): Response
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

        return $this->render('usuario/index.html.twig',  array(
            'form' => $form->createView()));

    }



    /**
     * @Route("/register", name="register")
     */
    public function create(Request $request)
    {
        $usuario = new Usuario();

        $form = $this->createForm(UsuarioType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();
            if ($posterFile = $form['avatar']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension("");
                dump($filename);
                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/', $filename);
                    $usuario->setAvatar($filename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                    return $this->redirectToRoute('auth/index.html.twig');
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();
            return $this->redirectToRoute('auth/index.html.twig');
        }
        return $this->render('auth/register.html.twig', array(
            'form' => $form->createView()));
    }
}
