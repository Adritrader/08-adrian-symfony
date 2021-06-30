<?php

namespace App\Controller;

use App\Entity\Registra;
use App\Entity\Usuario;
use App\Form\EditUsuarioType;
use App\Form\EditPassUsuarioType;
use App\Form\RegistraType;
use App\Form\UsuarioBackType;
use App\Form\UsuarioType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
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
     * @Route("/perfil", name="perfil")
     */
    public function show()
    {

        return $this->render('usuario/perfil.html.twig');

    }

    /**
     * @Route("admin/usuarios/filter", name="usuarios_filter")
     */
    public function filter(Request $request)
    {

        $page = $request->query->getAlnum("page");

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');
        $text = $request->query->getAlnum("text");
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);

        if (empty($page)){
            $page = 1;
        }

        if (!empty($text)) {
            $usuarios = $usuarioRepository->filterByText($text);
            $paginas = ceil(count($usuarios) / 4);
        }

        if(empty($usuarios)){

            $usuarios = $usuarioRepository->findAll();
            $paginas = ceil(count($usuarios) / 4);

        }

        return $this->render('back/back-usuarios.html.twig', array(
            'usuarios' => $usuarios,
            'paginas' => $paginas

        ));

    }

    /**
     * @Route("admin/usuarios/{id}/perfil", name="perfil_back", requirements={"id"="\d+"})
     */
    public function showUserBack(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuario = $usuarioRepository->find($id);
        if ($usuario)
        {
            return $this->render('usuario/perfil-back.html.twig', ["usuario"=>$usuario]
            );
        }
        else
            return $this->render('usuario/perfil-back.html.twig', [
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

                    if(empty($filename)){

                     $usuario->setAvatar("nofoto.jpg");

                    } else {

                        $usuario->setAvatar($filename);

                    }
                } catch (FileException $e) {
                    $this->addFlash('danger', $e->getMessage());
                    return $this->redirectToRoute('login');
                }
            }

            //Encriptamos la contraseña

            $hashedPassword = $encoder->encodePassword($usuario, $usuario->getPassword());
            $usuario->setPassword($hashedPassword);


            // Asignamos la fecha de actualización, por defecto es la fecha de creación del usuario

            $updated = date("Y-m-d H:i:s", time());
            $usuario->setUpdatedAt($updated);

            // Guardamos el usuario en la BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();

            //Logger

            $logger = new Logger('usuario');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha registrado un usuario nuevo');

            // Flash message

            $this->addFlash('success', "Se ha registrado correctamente");


            return $this->redirectToRoute('perfil', ["id" => $usuario->getId()]);
        }
        return $this->render('auth/register.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("admin/usuarios/create", name="usuarios_createBack")
     */
    public function createBack(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $usuario = new Usuario();

        $form = $this->createForm(UsuarioBackType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();
            if ($posterFile = $form['avatar']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/', $filename);

                    if(empty($filename)){

                        $usuario->setAvatar("nofoto.jpg");

                    } else {

                        $usuario->setAvatar($filename);

                    }
                } catch (FileException $e) {
                    $this->addFlash('danger', $e->getMessage());
                    return $this->redirectToRoute('login');
                }
            }

            //Encriptamos la contraseña

            $hashedPassword = $encoder->encodePassword($usuario, $usuario->getPassword());
            $usuario->setPassword($hashedPassword);


            // Asignamos la fecha de actualización, por defecto es la fecha de creación del usuario

            $updated = date("Y-m-d H:i:s", time());
            $usuario->setUpdatedAt($updated);

            // Guardamos el usuario en la BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();

            //Logger

            $logger = new Logger('usuario');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha registrado un usuario nuevo');

            // Flash message

            $this->addFlash('success', "Se ha registrado correctamente");


            return $this->redirectToRoute('admin');
        }
        return $this->render('usuario/usuario_createBack.html.twig', array(
            'form' => $form->createView()));
    }



    /**
     * @Route("/admin/usuarios/{id}/edit", name="usuarios_edit")
     */
    public function editUsuario(int $id, Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRepository->find($id);
        $form = $this->createForm(UsuarioBackType::class, $usuarios);
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

            //LOGGER

            $logger = new Logger('usuario');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha editado el usuario' . $usuarios->getNombre() . 'correctamente');

            return $this->redirectToRoute('admin');
        }
        return $this->render('usuario/usuario_edit.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     *@Route("/admin/usuarios/{id}/delete", name="usuarios_delete", requirements={"id"="\d+"})
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
     *@Route("/admin/usuarios/{id}/destroy", name="usuarios_destroy", requirements={"id"="\d+"})
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

            //LOGGER

            $logger = new Logger('usuario');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info("El usuario " . $usuario->getNombre() . " ha sido eliminado");

            return $this->redirectToRoute('admin');
        }
        return $this->render('back/back-usuarios.html.twig');
    }

    /**
     * @Route("/perfil/{id}/verReservas", name="reservas_show", requirements={"id"="\d+"})
     */
    public function showReservasUser(int $id)
    {
        $registraRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reservas = $registraRepository->activeReservesById($id);


        if ($reservas) {
            return $this->render('usuario/reservas_usuario.html.twig', ["reservas" => $reservas]
            );
        } else
            return $this->render('usuario/reservas_usuario.html.twig', [
                    'reservas' => null]
            );
    }

    /**
     * @Route("/perfil/{id}/edit", name="myuser_edit")
     */
    public function editMyUser(int $id, Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRepository->find($id);
        $form = $this->createForm(EditUsuarioType::class, $usuarios);
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

            //LOGGER

            $logger = new Logger('usuario');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha editado el usuario' . $usuarios->getNombre() . 'correctamente');

            return $this->redirectToRoute('admin');
        }
        return $this->render('usuario/usuario_editFront.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/perfil/{id}/editPass", name="myuser_editpass")
     */
    public function editPassUser(int $id, Request $request, UserPasswordEncoderInterface $encoder)
    {

        $this->denyAccessUnlessGranted('ROLE_USER',
            null, 'Acceso restringido a usuarios');
        $usuarioRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuarioRepository->find($id);
        $form = $this->createForm(EditPassUsuarioType::class, $usuarios);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Encriptamos la contraseña

            $hashedPassword = $encoder->encodePassword($usuarios, $usuarios->getPassword());
            $usuarios->setPassword($hashedPassword);

            $usuarios = $form->getData();
            $updated = date("Y-m-d", time());
            $usuarios->setUpdatedAt($updated);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuarios);
            $entityManager->flush();
            $this->addFlash('success', "El usuario " . $usuarios->getNombre() . " ha sido editado correctamente!");

            //LOGGER

            $logger = new Logger('usuario');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha editado el usuario' . $usuarios->getNombre() . 'correctamente');

            return $this->redirectToRoute('admin');
        }
        return $this->render('usuario/usuario_editPass.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/perfil/reservas/{id}/edit", name="reservas_editUser")
     */
    public function editReserva(int $id, Request $request)
    {
        $reservasRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reservas = $reservasRepository->find($id);
        $form = $this->createForm(RegistraType::class, $reservas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservas = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservas);
            $entityManager->flush();
            $this->addFlash('success', "La reserva ha sido editado correctamente");
            return $this->redirectToRoute('perfil', array("id" => $id));
        }

        return $this->render('usuario/usuario_editReserva.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/perfil/reservas/{id}/show", name="reserva_showUser", requirements={"id"="\d+"})
     */
    public function showReservaUser(int $id)
    {
        $registraRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reserva = $registraRepository->find($id);

        if ($reserva) {
            return $this->render('usuario/usuario_reservaShow.html.twig', ["reserva" => $reserva]
            );
        } else
            return $this->render('usuario/usuario_reservaShow.html.twig', [
                    'reserva' => null]
            );
    }

    /**
     * @Route("/perfil/reservas/{id}/delete", name="usuario_reservaDelete")
     */
    public function deleteReserva(int $id)
    {

        $registraRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reserva = $registraRepository->find($id);


        return $this->render('usuario/usuario_registraDelete.html.twig',  ["reserva" => $reserva]);
    }

    /**
     *@Route("/perfil/reservas/{id}/destroy", name="usuarios_reservaDestroy", requirements={"id"="\d+"})
     */
    public function destroyReserva(int $id)
    {

        $entityManager =$this->getDoctrine()->getManager();
        $registraRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reserva = $registraRepository->find($id);

        if ($reserva) {

            $reserva->setActive(false);
            $entityManager->persist($reserva);
            $entityManager->flush();

            //LOGGER

            $logger = new Logger('reserva');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info("La reserva ha sido eliminada");

            return $this->redirectToRoute('perfil', array("id" => $id));
        }

        return $this->render('usuario/reservas_usuario.html.twig');
    }
}
