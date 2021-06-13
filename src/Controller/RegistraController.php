<?php

namespace App\Controller;

use App\Entity\Registra;
use App\Form\RegistraType;
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
            $reserva->setUsuarioId($user->getId());

            //Obtener datos del formulario

            $reserva = $form->getData();

            // Guardar la reserva en la BD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reserva);
            $entityManager->flush();

            //Mensaje Flash

            $this->addFlash('success', "La reserva se ha creado correctamente");

            //Logger

            $logger = new Logger('channel-name');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha creado una nueva reserva para el usuario ' . $user->getUsername());

            //Si esta bien redirige a la pagina de home

            return $this->redirectToRoute('home');
        }

        //Si no se ha podido realizar vuelve a enviar a la pagina de registro.

        return $this->render('registra/index.html.twig', array(
            'form' => $form->createView()));
    }

}
