<?php

namespace App\Controller;

use App\Entity\Producto;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class HomeController extends AbstractController {

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {

        $pagination = $this->getDoctrine()
            ->getRepository(Producto::class)
            ->getAllProductsPaginated($request, $paginator);

        return $this->render('front/index.html.twig', [
            'controller_name' => 'ProductoController',
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/filter", name="home_filter")
     */
    public function filterTienda(Request $request, PaginatorInterface $paginator)
    {
        $text = $request->query->getAlnum("text");
        $minDate = $request->query->getAlnum("min");
        $maxDate = $request->query->getAlnum("max");


        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        if (!empty($text))
            $pagination = $this->getDoctrine()
                ->getRepository(Producto::class)
                ->getByNameDatePaginated($text, $request, $paginator, $minDate, $maxDate);
        /*if (empty($text) && !empty($minDate || $maxDate))
            $pagination = $this->getDoctrine()
                ->getRepository(Producto::class)
                ->getByNameDatePaginated($text, $request, $paginator, $minDate, $maxDate);*/

        else
            $pagination = $this->getDoctrine()
                ->getRepository(Producto::class)
                ->findAll();

        return $this->render('front/index.html.twig', array(
            'pagination' => $pagination
        ));
    }
}
