<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Producto;
use App\Form\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductoController extends AbstractController
{
    /**
     * @Route("/producto", name="producto")
     */
    public function index(): Response
    {
        return $this->render('producto/index.html.twig', [
            'controller_name' => 'ProductoController',
        ]);
    }

    /**
     * @Route("/movies/{$id}", name="movies_show", requirements={"id"="\d+"})
     */
    public function show(int $id)
    {
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $movieRepository->find($id);
        if ($movie)
        {
            return $this->render('movie/movie_show.html.twig', ["movie"=>$movie]
            );
        }
        else
            return $this->render('movie/movie_show.html.twig', [
                    'movie' => null]
            );
    }

    /**
     * @Route("/movies/filter", name="movies_filter")
     */
    public function filter(Request $request)
    {
        $text = $request->query->getAlnum("text");
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $movieRepository->filterByText($text);
        return $this->render('movie/movie_filter.html.twig', array(
            'movies' => $movies
        ));

    }

    /**
     * @Route("/movies/create", name="movies_create")
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
