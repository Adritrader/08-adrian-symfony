<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductoController extends AbstractController
{
    /**
     * @Route("/tienda", name="tienda")
     */
    public function index(): Response
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->findAll();

        if ($productos)
        {
            return $this->render('tienda.html.twig', ["productos"=>$productos]
            );
        }
        else
            return $this->render('tienda.html.twig', [
                    'productos' => null]
            );
    }



    /**
     * @Route("/productos/{$id}", name="productos_show", requirements={"id"="\d+"})
     */
    public function show(int $id)
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $productoRepository->find($id);
        if ($producto)
        {
            return $this->render('producto/producto_show.html.twig', ["producto"=>$producto]
            );
        }
        else
            return $this->render('producto/producto_show.html.twig', [
                    'producto' => null]
            );
    }


    /**
     * @Route("/-/filter", name="movies_filter")
     */
    public function filter(Request $request)
    {
        $text = $request->query->getAlnum("text");
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        if (!empty($text))
            $movies = $movieRepository->filterByText($text);
        else
            $movies = $movieRepository->findBy([], ["title" => "ASC"]);
        return $this->render('movies_filter.html.twig', array(
            'movies' => $movies
        ));

    }


    /**
     * @Route("/productos/create", name="movies_create")
     */
    public function create(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movie);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('movie/create.html.twig', array(
            'form' => $form->createView()));

    }

    /**
     * @Route("/movies/{id}/edit", name="movies_edit")
     */
    public function edit(int $id, Request $request)
    {
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $movieRepository->find($id);
        var_dump($movie);

        $form = $this->createFormBuilder($movie)
            ->add('id', HiddenType::class)
            ->add('title', TextType::class)
            ->add('tagline', TextType::class)
            ->add('overview', TextareaType::class)
            ->add('releaseDate', DateType::class,
                ['widget' => "single_text"]
            )
            ->add('poster', TextType::class)
            ->add('genre', EntityType::class,
                ['class' => Genre::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Select a genre',
                ]
            )
            ->add('create', SubmitType::class, array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movie);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('movie/create.html.twig', array(
            'form' => $form->createView()));

    }
}
