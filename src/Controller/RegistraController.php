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

    /**
     * @Route("/admin/reservas/create", name="reservas_createback")
     */
    public function createReserva(Request $request): Response
    {

        $reserva = new Registra();

        $form = $this->createForm(RegistraBackType::class, $reserva);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Coger el id del usuario y asignarlo a la reserva

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

        return $this->render('back/reservas-create.html.twig', array(
            'form' => $form->createView()));
    }


    /**
     * @Route("admin/reservas/show/{id}", name="reservas_showBack", requirements={"id"="\d+"})
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
                    'registra' => null]
            );
    }


    /**
     * @Route("admin/productos/filter", name="back-productos")
     */
    public function filter(Request $request)
    {
        $text = $request->query->getAlnum("text");
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        if (!empty($text))
            $productos = $productoRepository->filterByText($text);
        else
            $productos = $productoRepository->findBy([], ["nombre" => "ASC"]);
        return $this->render('back/back-productos.html.twig', array(
            'productos' => $productos
        ));

    }

    /**
     * @Route("/admin/reservas/edit/{id}", name="reservas_edit")
     */
    public function editProduct(int $id, Request $request)
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
            $this->addFlash('success', "La reserva ha sido editado correctamente");
            return $this->redirectToRoute('admin');
        }

        return $this->render('back/reservas-edit.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/admin/productos/delete/{id}", name="productos_delete")
     */
    public function delete(int $id)
    {
        $entityManager =$this->getDoctrine()->getManager();
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $productoRepository->find($id);

        if ($producto) {
            $entityManager->remove($producto);
            $entityManager->flush();
            $this->addFlash('success', "El producto " . $producto->getNombre() . " ha sido eliminado correctamente!");
            return $this->redirectToRoute('admin');
        }
        return $this->render('back/back-productos.html.twig');
    }

}
