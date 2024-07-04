<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ["GET"])]
    public function index(): Response
    {
//        return $this->render('/pages/index.html.twig');

        return $this->json(['api' => 'easypdf.ru']);
    }
}
