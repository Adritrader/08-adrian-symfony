<?php
declare(strict_types=1);
namespace App\Controller;

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

    private array $movies = [
        ["id" => "2", "title" => "Ava", "tagline" => "Kill. Or be killed",
            "release_date" => "25/09/2020"],
        ["id" => "3", "title" => "Bill &Ted Face the Music",
            "tagline" => "The future awaits", "release_date" => "24/09/2020"],
        ["id" => "4", "title" => "Hard Kill",
            "tagline" => "Take on a madman. Save the world.", "release_date" => "14/09/2020"],
        ["id" => "5", "title" => "The Owners", "tagline" => "",
            "release_date" => "10/05/2020"],
        ["id" => "6", "title" => "The New Mutants",
            "tagline" => "It's time to face your demons.", "release_date" => "20/04/2020"],
    ];


    /**
     * @Route("/productos/{id}", name="productos_show")
     */
    public function show($id)
    {
        $result = array_filter($this->movies,
            function ($movie) use ($id) {
                return $movie["id"] == $id;
            });
        if (count($result) > 0) {
            $response = "";
            $result = array_shift($result);
            $response .= "<ul><li>" . $result["title"] . "</li>" .
                "<li>" . $result["tagline"] . "</li>" .
                "<li>" . $result["release_date"] . "</li></ul>";
            return new Response("<html><body>$response</body></html>");
        } else
            return new Response("Movie not found");
    }
}
