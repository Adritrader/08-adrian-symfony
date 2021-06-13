<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Entity\Usuario;
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

        if ($productos)
        {
            return $this->render('back/index.html.twig', ["productos"=>$productos,
                    "usuarios" => $usuarios]
            );
        }
        else
            return $this->render('back/index.html.twig', [
                    'productos' => null]
            );
    }



    /**
     * @Route("/productos", name="admin_productos")
     */

    public function backProductos(): Response
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->findAll();

        if ($productos)
        {
            return $this->render('back/back-productos.html.twig', ["productos"=>$productos]
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
        $usuariosRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $usuariosRepository->findAll();

        if ($usuarios)
        {
            return $this->render('back/back-usuarios.html.twig', ["usuarios"=>$usuarios]
            );
        }
        else
            return $this->render('back/back-usuarios.html.twig', [
                    'usuarios' => null]
            );
    }

}
