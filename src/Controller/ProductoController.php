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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ProductoController extends AbstractController
{
    /**
     * @Route("/tienda", name="tienda")
     */
    public function index(): Response
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->findAll();

        if ($productos) {
            return $this->render('tienda.html.twig', ["productos" => $productos]
            );
        } else
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
        if ($producto) {
            return $this->render('producto/producto_show.html.twig', ["producto" => $producto]
            );
        } else
            return $this->render('producto/producto_show.html.twig', [
                    'producto' => null]
            );
    }

    /**
     * @Route("admin/productos/show/{id}", name="productos_showBack", requirements={"id"="\d+"})
     */
    public function showProBack(int $id)
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $productoRepository->find($id);
        if ($producto) {
            return $this->render('producto/producto_show.html.twig', ["producto" => $producto]
            );
        } else
            return $this->render('producto/producto_show.html.twig', [
                    'producto' => null]
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
     * @Route("/admin/productos/create", name="create_product")
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
            $this->addFlash('success', "El producto " . $producto->getProducto() . " se ha creado correctamente");
            return $this->redirectToRoute('admin');
        }
        return $this->render('back/productos-create.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/admin/productos/edit/{id}", name="productos_edit")
     */
    public function editProduct(int $id, Request $request)
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->find($id);
        $form = $this->createForm(ProductoType::class, $productos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productos = $form->getData();
            if ($posterFile = $form['imagen']->getData()) {
                $filename = bin2hex(random_bytes(6)) . '.' . $posterFile->guessExtension();

                try {
                    $projectDir = $this->getParameter('kernel.project_dir');
                    $posterFile->move($projectDir . '/public/img/productos/', $filename);
                    $productos->setImagen($filename);
                } catch (FileException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                    return $this->redirectToRoute('admin');
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productos);
            $entityManager->flush();
            $this->addFlash('success', "El producto " . $productos->getNombre() . "ha sido editado correctamente!");
            return $this->redirectToRoute('admin');
        }
        return $this->render('back/productos-edit.html.twig', array(
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