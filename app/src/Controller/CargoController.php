<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cargo;
use App\Entity\Transport;
use App\Form\Type\CargoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CargoController extends AbstractController
{
    #[Route('/', name: 'app_cargo')]
    public function index(Request $request): Response
    {
        $transport = new Transport();
        $cargo = new Cargo();
        $cargoForm = $this->createForm(CargoType::class, [$transport, $cargo]);

        return $this->render('cargo/index.html.twig', [
            'controller_name' => 'CargoController',
            'form' => $cargoForm->createView()
        ]);
    }
}
