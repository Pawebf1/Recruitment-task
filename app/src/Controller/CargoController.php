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
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class CargoController extends AbstractController
{
    #[Route('/', name: 'app_cargo')]
    public function index(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer): Response
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

            try {
                $this->sendEmail($transport);
                $emailSent = true;
            } catch (TransportExceptionInterface $e) {
                $emailSent = false;
            }

        }

        return $this->render('cargo/index.html.twig', [
            'controller_name' => 'CargoController',
            'form' => $transportForm->createView(),
            'cargoNumber' => $cargoNumber,
            'emailSent' => $emailSent ?? null
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendEmail(Transport $transport): void
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html($this->renderView('emails/transport.html.twig', [
                'username' => 'test'
            ]));

        $mailer = new Mailer(\Symfony\Component\Mailer\Transport::fromDsn($_ENV["MAILER_DSN"]));
        $mailer->send($email);
    }
}
