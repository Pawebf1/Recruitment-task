<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CargoController extends AbstractController
{
    #[Route('/cargo', name: 'app_cargo')]
    public function index(): Response
    {
        return $this->render('cargo/index.html.twig', [
            'controller_name' => 'CargoController',
        ]);
    }
}