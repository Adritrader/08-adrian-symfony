<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Entity\Producto;
use App\Entity\Registra;
use App\Entity\Usuario;
use App\Repository\PedidosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route ("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="admin")
     */
    public function index(): Response
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->lastProducts();
        $usuariosRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuariosRepository->lastUsers();
        $reseravsRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reservas = $reseravsRepository->lastReserves();

        if ($productos || $usuarios || $reservas)
        {
            return $this->render('back/back-index.html.twig', ["productos"=>$productos,
                    "usuarios" => $usuarios, "reservas" => $reservas]
            );
        }
        else
            return $this->render('back/back-index.html.twig', [
                    'productos' => null,
                    'usuarios' => null,
                    'reservas' => null]
            );
    }



    /**
     * @Route("/productos", name="admin_productos")
     */

    public function backProductos(): Response
    {

        $page = filter_input(INPUT_GET , "page");

        if (empty($page)){
            $page = 1;
        }

        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->findAllPaginated($page);

        $paginas = ceil(count($productos)/4);

        if ($productos)
        {
            return $this->render('back/back-productos.html.twig', ["productos"=>$productos, "paginas"=> $paginas]
            );
        }
        else
            return $this->render('back/back-productos.html.twig', [
                    'productos' => null]
            );
    }

    /**
     * @Route("/usuarios", name="admin_usuarios")
     */

    public function backUsuarios(): Response
    {

        $page = filter_input(INPUT_GET , "page");

        if (empty($page)){
            $page = 1;
        }

        $usuariosRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuariosRepository->findAllPaginated($page);

        $paginas = ceil(count($usuarios)/4);

        if ($usuarios)
        {
            return $this->render('back/back-usuarios.html.twig', ["usuarios"=>$usuarios, "paginas" => $paginas]
            );
        }
        else
            return $this->render('back/back-usuarios.html.twig', [
                    'usuarios' => null]
            );
    }

    /**
     * @Route("/reservas", name="admin_reservas")
     */
    public function backReservas(): Response
    {
        $reseravsRepository = $this->getDoctrine()->getRepository(Registra::class);
        $reservas = $reseravsRepository->findAll();


        if ($reservas)
        {
            return $this->render('back/back-reservas.html.twig', ["reservas"=>$reservas]
            );
        }
        else
            return $this->render('back/back-reservas.html.twig', [
                    'reservas' => null]
            );
    }

    /**
     * @Route("/pedidos", name="admin_pedidos")
     */
    public function backPedidos(): Response
    {
        $pedidosRepository = $this->getDoctrine()->getRepository(Pedidos::class);
        $pedidos = $pedidosRepository->findAll();


        if ($pedidos)
        {
            return $this->render('back/back-pedidos.html.twig', ["pedidos"=>$pedidos]
            );
        }
        else
            return $this->render('back/back-pedidos.html.twig', [
                    'pedidos' => null]
            );
    }


}
