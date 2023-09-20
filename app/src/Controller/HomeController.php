<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/{any?}', name: 'index', requirements: ["any" => ".*"], methods: ["GET"])]
    public function index(MessageBusInterface $bus): Response
    {
        return $this->render('/pages/index.html.twig');
    }
}
