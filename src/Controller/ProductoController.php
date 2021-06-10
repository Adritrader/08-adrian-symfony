<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Producto;
use App\Form\ProductoType;
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
     * @Route("/back-productos", name="back-productos")
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
     * @Route("/back-productos/create", name="create_product")
     */
    public function createProduct(Request $request)
    {
        $producto = new Producto();

        $form = $this->createForm(ProductoType::class, $producto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $producto = $form->getData();
            if ($posterFile = $form['imagen']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();
                dump($filename);
                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/productos/', $filename);
                    $producto->setImagen($filename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                    return $this->redirectToRoute('admin');
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($producto);
            $entityManager->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->render('back/productos-create.html.twig', array(
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
