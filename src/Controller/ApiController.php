<?php


namespace App\Controller;

use App\Entity\Producto;
use App\Form\UsuarioType;
use App\Repository\ProductoRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
* @Route("/api/v1/productos")
*/
class ApiController extends AbstractController
{
    /**
     * @Route("/", name="api_productos_links", methods={"GET"})
     * @param Request $request
     * @param ProductoRepository $productoRepository
     * @return JsonResponse
     */
    public function index(Request $request, ProductoRepository $productoRepository): JsonResponse
    {
        $producto = $productoRepository->findAll();

        return new JsonResponse($producto, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_producto_show", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request,  ?Producto $producto): JsonResponse
    {

        if (!empty($producto))
            return new JsonResponse($producto, Response::HTTP_OK);

        else
            return new JsonResponse("error", Response::HTTP_NOT_FOUND);
    }


}