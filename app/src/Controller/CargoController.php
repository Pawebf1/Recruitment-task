<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cargo;
use App\Entity\Transport;
use App\Form\Type\CargoType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CargoController extends AbstractController
{
    #[Route('/', name: 'app_cargo')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $transport = new Transport();
        $cargo = new Cargo();
        
        $cargoForm = $this->createForm(CargoType::class, [$transport, $cargo]);
        $cargoForm->handleRequest($request);

        if ($cargoForm->isSubmitted() && $cargoForm->isValid()) {
            $entityManager = $doctrine->getManager();

            $transport->setTransportFrom($cargoForm->get('transportFrom')->getData());
            $transport->setTransportTo($cargoForm->get('transportTo')->getData());
            $transport->setPlane($cargoForm->get('plane')->getData());
            $transport->setDate($cargoForm->get('date')->getData());

            $filesString = '';
            foreach ($request->files->get("cargo")['document'] as $document) {
                $filesString .= $document->getClientOriginalName() . ' ';
            }
            $transport->setDocuments($filesString);

            $entityManager->persist($transport);
            $entityManager->flush();


            $cargo->setName($cargoForm->get('transportFrom')->getData());
            $cargo->setWeight((double)$cargoForm->get('weight')->getData());
            $cargo->setType($cargoForm->get('type')->getData());
            $cargo->setTransportID($transport);
            $entityManager->persist($cargo);
            $entityManager->flush();
        }

        return $this->render('cargo/index.html.twig', [
            'controller_name' => 'CargoController',
            'form' => $cargoForm->createView()
        ]);
    }
}
