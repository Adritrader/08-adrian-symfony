<?php

namespace App\Controller;

use App\Entity\Registra;
use App\Form\RegistraType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

            $user = $this->getUser();
            $reserva->setUsuarioId($user->getId());
            var_dump($reserva);
            $reserva = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reserva);
            $entityManager->flush();
            $this->addFlash('success', "La reserva se ha creado correctamente");
            return $this->redirectToRoute('home');
        }
        return $this->render('registra/index.html.twig', array(
            'form' => $form->createView()));
    }

}
