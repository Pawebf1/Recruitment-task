<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cargo;
use App\Entity\Transport;
use App\Form\Type\TransportType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class CargoController extends AbstractController
{
    #[Route('/', name: 'app_cargo')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $cargoNumber = 1;
        if ($request->query->get("cargo_number") !== null) {
            $cargoNumber = $request->query->get("cargo_number");
        }

        $transport = new Transport();

        for ($i = 0; $i < $cargoNumber; $i++) {
            $transport->getCargos()->add(new Cargo());
        }

        $transportForm = $this->createForm(TransportType::class, $transport);
        $transportForm->handleRequest($request);


        if ($transportForm->isSubmitted() && $transportForm->isValid()) {
            $entityManager = $doctrine->getManager();


            $filesString = '';
            foreach ($request->files->get("transport")['documents'] as $document) {
                $filesString .= $document->getClientOriginalName() . ' ';
            }
            $transport->setDocuments($filesString);


            $entityManager->persist($transport);

            foreach ($transport->getCargos() as $cargo) {
                $cargo->setTransportID($transport);
                $entityManager->persist($cargo);
            }


            $entityManager->flush();


            $body = '<p>cos</p>>';
            $email = (new TemplatedEmail())
                ->from('transport@samoloty.com')
                ->to('jakisemail@samolot.com')
                ->subject("Transport")
                ->htmlTemplate('emails/transport.html.twig')
                ->context([
                    'username' => 'foo',
                ]);


            $mailer = new Mailer(\Symfony\Component\Mailer\Transport::fromDsn($_ENV["MAILER_DSN"]));
            $mailer->send($email);
        }

        return $this->render('cargo/index.html.twig', [
            'controller_name' => 'CargoController',
            'form' => $transportForm->createView(),
            'cargoNumber' => $cargoNumber
        ]);
    }
}
