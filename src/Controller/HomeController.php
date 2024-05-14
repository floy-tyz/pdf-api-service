<?php

namespace App\Controller;

use App\Entity\Conversion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ["GET"])]
    public function index(): Response
    {
        return $this->render('/pages/index.html.twig');
    }

    #[Route('/conversion/{guid}', name: 'conversion.item', methods: ["GET"])]
    public function conversion(Conversion $conversion): Response
    {
        dd($conversion);
    }
}
