<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UsuarioController extends AbstractController
{
    /**
     * @Route("/perfil/{id}", name="perfil", requirements={"id"="\d+"})
     */
    public function show(int $id)
    {
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRepository->find($id);
        if ($usuario)
        {
            return $this->render('usuario/perfil.html.twig', ["usuario"=>$usuario]
            );
        }
        else
            return $this->render('usuario/perfil.html.twig', [
                    'usuario' => null]
            );
    }

    /**
     * @Route("admin/usuarios/filter", name="usuarios_filter")
     */
    public function filter(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');
        $text = $request->query->getAlnum("text");
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        if (!empty($text))
            $usuarios = $usuarioRepository->filterByText($text);
        else
            $usuarios = $usuarioRepository->findBy([], ["nombre" => "ASC"]);
        return $this->render('back/back-usuarios.html.twig', array(
            'usuarios' => $usuarios
        ));

    }

    /**
     * @Route("admin/usuarios/perfil/{id}", name="perfil_back", requirements={"id"="\d+"})
     */
    public function showUserBack(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRepository->find($id);
        if ($usuario)
        {
            return $this->render('back/perfil-back.html.twig', ["usuario"=>$usuario]
            );
        }
        else
            return $this->render('back/perfil-back.html.twig', [
                    'usuario' => null]
            );
    }



    /**
     * @Route("/register", name="register")
     */
    public function create(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $usuario = new Usuario();

        $form = $this->createForm(UsuarioType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();
            if ($posterFile = $form['avatar']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/', $filename);
                    $usuario->setAvatar($filename);
                } catch (FileException $e) {
                    $this->addFlash('danger', $e->getMessage());
                    return $this->redirectToRoute('login');
                }
            }

            //Encriptamos la contraseña

            $hashedPassword = $encoder->encodePassword($usuario, $usuario->getPassword());
            $usuario->setPassword($hashedPassword);

            //Asignamos el rol de usuario

            $usuario->setRole("ROLE_USER");


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();



            $this->addFlash('success', "Se ha registrado correctamente");


            return $this->redirectToRoute('perfil', ["id" => $usuario->getId()]);
        }
        return $this->render('auth/register.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/admin/usuarios/edit/{id}", name="usuarios_edit")
     */
    public function editUsuario(int $id, Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRepository->find($id);
        $form = $this->createForm(UsuarioType::class, $usuarios);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuarios = $form->getData();
            if ($posterFile = $form['avatar']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/', $filename);
                    $usuarios->setAvatar($filename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                    return $this->redirectToRoute('admin');
                }
            }

            $updated = date("Y-m-d", time());
            $usuarios->setUpdatedAt($updated);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuarios);
            $entityManager->flush();
            $this->addFlash('success', "El usuario " . $usuarios->getNombre() . " ha sido editado correctamente!");
            return $this->redirectToRoute('admin');
        }
        return $this->render('usuario/usuario-edit.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/admin/usuarios/delete/{id}", name="usuarios_delete")
     */
    public function delete(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');

        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRepository->find($id);


        return $this->render('usuario/delete-usuario.html.twig', ["usuario" => $usuario]);


    }

    /**
     * @Route("/admin/usuarios/delete/{id}/yes", name="usuarios_destroy")
     */
    public function destroy(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');
        $entityManager =$this->getDoctrine()->getManager();
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRepository->find($id);

        if ($usuario) {
            $entityManager->remove($usuario);
            $entityManager->flush();
            $this->addFlash('success', "El usuario " . $usuario->getNombre() . " ha sido eliminado correctamente!");
            return $this->redirectToRoute('admin');
        }
        return $this->render('back/back-usuarios.html.twig');
    }
}
