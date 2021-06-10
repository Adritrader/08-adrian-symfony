<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */

    public function index(): Response
    {
        $productoRepository = $this->getDoctrine()->getRepository(Producto::class);
        $productos = $productoRepository->findAll();

        if ($productos)
        {
            return $this->render('back/index.html.twig', ["productos"=>$productos]
            );
        }
        else
            return $this->render('back/index.html.twig', [
                    'productos' => null]
            );
    }

    /**
     * @Route("/back-productos", name="back-productos")
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
     * @Route("/back-usuarios", name="back-usuarios")
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
