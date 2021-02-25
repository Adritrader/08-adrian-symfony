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
     * @Route("/producto", name="producto")
     */
    public function index(): Response
    {
        return $this->render('producto/index.html.twig', [
            'controller_name' => 'ProductoController',
        ]);
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
     * @Route("/productos/filter", name="productos_filter")
     */
    public function filter(Request $request)
    {
        $text = $request->query->getAlnum("text");
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $productoRepository->filterByText($text);
        return $this->render('productos/productos_filter.html.twig', array(
            'productos' => $producto
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
