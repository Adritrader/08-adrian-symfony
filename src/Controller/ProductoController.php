<?php
declare(strict_types=1);
namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(Request $request, PaginatorInterface $paginator): Response
    {

        $pagination = $this->getDoctrine()
            ->getRepository(Producto::class)
            ->getAllProductsPaginated($request, $paginator);

        return $this->render('front/tienda.html.twig', [
            'controller_name' => 'ProductoController',
            'pagination' => $pagination
        ]);
    }


    /**
     * @Route("/tienda/categoria-champu", name="categoria_champu")
     */
    public function categoriaChampu(): Response
    {

        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->findAllPaginatedChampus();

        $paginas = ceil(count($productos)/4);

        if ($productos) {
            return $this->render('front/categoria_champu.html.twig', ["productos" => $productos,
                    "paginas" => $paginas]
            );
        } else
            return $this->render('front/categoria_champu.html.twig', [
                    'productos' => null]
            );
    }
    /**
     * @Route("/tienda/categoria-tratamiento", name="categoria_tratamiento")
     */
    public function categoriaTratamientos(): Response
    {

        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->findAllPaginatedTratamientos();

        $paginas = ceil(count($productos)/4);

        if ($productos) {
            return $this->render('front/categoria_tratamientos.html.twig', ["productos" => $productos,
                    "paginas" => $paginas]
            );
        } else
            return $this->render('front/categoria_tratamientos.html.twig', [
                    'productos' => null]
            );
    }
    /**
     * @Route("/tienda/categoria-accesorio", name="categoria_accesorio")
     */
    public function categoriaAccesorio(): Response
    {

        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->findAllPaginatedAccesorios();

        $paginas = ceil(count($productos)/4);

        if ($productos) {
            return $this->render('front/categoria_accesorios.html.twig', ["productos" => $productos,
                    "paginas" => $paginas]
            );
        } else
            return $this->render('front/categoria_accesorios.html.twig', [
                    'productos' => null]
            );
    }



    /**
     *@Route("/tienda/{id}/show", name="productos_show", requirements={"id"="\d+"})
     */
    public function show(int $id)
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $productoRepository->find($id);

        if ($producto) {
            return $this->render('producto/productoFront.html.twig', ["producto" => $producto]
            );
        } else
            return $this->render('producto/productoFront.html.twig', [
                    'producto' => null]
            );
    }

    /**
     *@Route("admin/productos/{id}/show/", name="productos_showBack", requirements={"id"="\d+"})
     */
    public function showProBack(int $id)
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $productoRepository->find($id);
        if ($producto) {
            return $this->render('producto/productoBack.html.twig', ["producto" => $producto]
            );
        } else
            return $this->render('producto/productoBack.html.twig', [
                    'producto' => null]
            );
    }




    /**
     * @Route("admin/productos/filter", name="back_productos_filter", methods={"GET","POST"})
     */
    public function filter(Request $request, PaginatorInterface $paginator)
    {
        $text = $request->query->getAlnum("text");
        $minDate = $request->query->getAlnum("min");
        $maxDate = $request->query->getAlnum("max");


        if (!empty($text))
            $pagination = $this->getDoctrine()
                ->getRepository(Producto::class)
                ->getByNameDatePaginated($text, $request, $paginator, $minDate, $maxDate);
        /*if (empty($text) && !empty($minDate || $maxDate))
            $pagination = $this->getDoctrine()
                ->getRepository(Producto::class)
                ->getByNameDatePaginated($text, $request, $paginator, $minDate, $maxDate);*/

        if (empty($text))
            $pagination = $this->getDoctrine()
                ->getRepository(Producto::class)
                ->getByNameDatePaginated($text, $request, $paginator, $minDate, $maxDate);

        return $this->render('back/back-productos.html.twig', array(
            'pagination' => $pagination
        ));

    }


    /**
     * @Route("/tienda/filter", name="tienda_filter")
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

        return $this->render('front/tienda.html.twig', array(
            'pagination' => $pagination
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
            $this->addFlash('success', "El producto " . $producto->getNombre() . " se ha creado correctamente");

            //Logger

            $logger = new Logger('producto');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha creado el producto ' . $producto->getNombre());

            return $this->redirectToRoute('admin');
        }
        return $this->render('producto/productos-create.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/admin/productos/{id}/edit", name="productos_edit", requirements={"id"="\d+"})
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

            //Logger

            $logger = new Logger('producto');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha editado el producto ' . $productos->getNombre());

            return $this->redirectToRoute('admin');
        }
        return $this->render('producto/productos-edit.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     *@Route("/admin/productos/{id}/delete", name="productos_delete", requirements={"id"="\d+"})
     */
    public function delete(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');

        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $productoRepository->find($id);

        return $this->render('producto/delete-producto.html.twig', ["producto" => $producto]);
    }

    /**
     *@Route("/admin/productos/{id}/destroy", name="productos_destroy", requirements={"id"="\d+"})
     */
    public function destroy(int $id)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN',
            null, 'Acceso restringido a administradores');

        $entityManager =$this->getDoctrine()->getManager();
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $producto = $productoRepository->find($id);

        if ($producto) {
            $entityManager->remove($producto);
            $entityManager->flush();
            $this->addFlash('success', "El producto " . $producto->getNombre() . " ha sido eliminado correctamente!");

            //Logger

            $logger = new Logger('producto');
            $logger->pushHandler(new StreamHandler('app.log', Logger::DEBUG));
            $logger->info('Se ha borrado el producto ' . $producto->getNombre());

            return $this->redirectToRoute('admin');
        }
        return $this->render('back/back-productos.html.twig');
    }
}