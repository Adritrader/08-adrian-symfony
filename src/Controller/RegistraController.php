<?php

namespace App\Controller;

use App\Entity\Registra;
use App\Form\RegistraType;
use App\Form\RegistraBackType;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Monolog\Handler\StreamHandler;

class RegistraController extends AbstractController
{
    /**
     * @Route("/reservas/create", name="reservas_create")
     */
    public function index(Request $request): Response
    {

        $reserva = new Registra();

        $form = $this->createForm(RegistraType::class, $reserva);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Coger el id del usuario y asignarlo a la reserva

            $user = $this->getUser();
            $reserva->setUsuario($user);
            $reserva->setActive(true);

            //Obtener datos del formulario

            $reserva = $form->getData();

            // Guardar la reserva en la BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reserva);
            $entityManager->flush();

            //Mensaje Flash

            $this->addFlash('success', "La reserva se ha creado correctamente");

            //Logger

            $logger = new Logger('reserva');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha creado una nueva reserva para el usuario ' . $user->getUsername());

            //Si esta bien redirige a la pagina de home

            return $this->redirectToRoute('home');
        }

        //Si no se ha podido realizar vuelve a enviar a la pagina de registro.

        return $this->render('registra/index.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("admin/reservas/filter", name="back_reservas_filter")
     */
    public function filter(Request $request)
    {

        $page = $request->query->getAlnum("page");

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');

        $text = $request->query->getAlnum("text");
        $reservasRepository = $this->getDoctrine()->getRepository(Registra::class);

        if (empty($page)){
            $page = 1;
        }

        if (!empty($text)) {
            $reservas = $reservasRepository->filterByText($text);
            $paginas = ceil(count($reservas) / 4);
        }

        if(empty($reservas)){

            $reservas = $reservasRepository->findAll();
            $paginas = ceil(count($reservas) / 4);

        }

        return $this->render('back/back-reservas.html.twig', array(
            'reservas' => $reservas,
            'paginas' => $paginas

        ));

    }

    /**
     * @Route("admin/reservas/create", name="reservas_createBack")
     */
    public function createReserva(Request $request): Response
    {

        $reserva = new Registra();

        $form = $this->createForm(RegistraBackType::class, $reserva);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reserva->setActive(true);

            //Obtener datos del formulario

            $reserva = $form->getData();

            // Guardar la reserva en la BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reserva);
            $entityManager->flush();

            //Mensaje Flash

            $this->addFlash('success', "La reserva se ha creado correctamente");

            //Logger

            $logger = new Logger('reserva');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha creado una nueva reserva');

            //Si esta bien redirige a la pagina de home

            return $this->redirectToRoute('admin');
        }

        //Si no se ha podido realizar vuelve a enviar a la pagina de registro.

        return $this->render('registra/reservas-create.html.twig', array(
            'form' => $form->createView()));
    }


    /**
     * @Route("admin/reservas/{id}/show", name="reservas_showBack", requirements={"id"="\d+"})
     */
    public function showReservasBack(int $id)
    {
        $registraRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reserva = $registraRepository->find($id);

        if ($reserva) {
            return $this->render('registra/reserva_show.html.twig', ["reserva" => $reserva]
            );
        } else
            return $this->render('registra/reserva_show.html.twig', [
                    'reserva' => null]
            );
    }



    /**
     * @Route("admin/reservas/{id}/edit", name="reservas_edit")
     */
    public function editReserva(int $id, Request $request)
    {
        $reservasRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reservas = $reservasRepository->find($id);
        $form = $this->createForm(RegistraBackType::class, $reservas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reservas = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservas);
            $entityManager->flush();
            $this->addFlash('success', "La reserva ha sido editada correctamente");
            return $this->redirectToRoute('admin');
        }

        return $this->render('registra/reservas-edit.html.twig', array(
            'form' => $form->createView()));
    }


    /**
     * @Route("admin/reservas/{id}/delete", name="reservas_delete")
     */
    public function deleteReserva(int $id)
    {

        $registraRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reserva = $registraRepository->find($id);


        return $this->render('registra/reserva_delete.html.twig',  ["reserva" => $reserva]);
    }

    /**
     *@Route("admin/reservas/{id}/destroy", name="reservas_destroy", requirements={"id"="\d+"})
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

            //FLASH MESSAGE

            $this->addFlash('success', "La reserva ha sido eliminada correctamente");

            return $this->redirectToRoute('admin');
        }

        return $this->render('back/back-index.html.twig');
    }

}
