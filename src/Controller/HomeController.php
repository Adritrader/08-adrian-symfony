<?php
declare(strict_types=1);
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class HomeController extends AbstractController {

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $now = new \DateTime();

        $this->logger->info("Access on {$now->format("Y/m/d H:i:s")}");
        return $this->render("home.html.twig");
    }

}
