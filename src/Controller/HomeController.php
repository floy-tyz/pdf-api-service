<?php

namespace App\Controller;

use App\Entity\Conversion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/{any?}', name: 'index', requirements: ['any'=>".*"], methods: ["GET"], priority: -1)]
    public function index(): Response
    {
        return $this->render('/pages/index.html.twig');
    }
}
