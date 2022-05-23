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
    #[Route('/', name: 'app_transport')]
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
            $filesPath = [];
            foreach ($request->files->get("transport")['documents'] as $document) {
                $newFilename = $document->getClientOriginalName() . '-' . uniqid() . '.' . $document->guessExtension();
                $filesString .= $newFilename . ' ';
                $filesPath[] = $newFilename;
                $document->move($this->getParameter('documents_directory'), $newFilename);
            }
            $transport->setDocuments($filesString);


            $entityManager->persist($transport);

            foreach ($transport->getCargos() as $cargo) {
                $cargo->setTransportID($transport);
                $entityManager->persist($cargo);
            }

            $entityManager->flush();

            try {
                $this->sendEmail($transport, $filesPath);
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
    private function sendEmail(Transport $transport, array $filesPath): void
    {
        if ($transport->getPlane() == "Airbus A380")
            $to = "airbus@lemonmind.com";
        else
            $to = "boeing@lemonmind.com";

        $subject = "Transport " . $transport->getDate()->format('Y-m-d');
        $email = (new Email())
            ->from('no-reply@lemonmind.com')
            ->to($to)
            ->subject($subject)
            ->html($this->renderView('emails/transport.html.twig', [
                'transport' => $transport
            ]));

        foreach ($filesPath as $filepath)
            $email->attachFromPath($this->getParameter('documents_directory') . '/' . $filepath, $filepath);


        $mailer = new Mailer(\Symfony\Component\Mailer\Transport::fromDsn($_ENV["MAILER_DSN"]));
        $mailer->send($email);
    }


    #[Route('/transport/list', name: 'app_transport_list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $transports = $doctrine->getRepository(Transport::class)->findAll();

        return $this->render('cargo/list.html.twig', [
            'transports' => $transports
        ]);
    }

    #[Route('/cargo/list', name: 'app_cargo_list')]
    public function cargoList(Request $request, ManagerRegistry $doctrine): Response
    {
        if ($request->query->get("id") !== null)
            $id = $request->query->get("id");
        else
            return $this->render('cargo/cargoList.html.twig', [
                'id' => false]);

        $transport = $doctrine->getRepository(Transport::class)->findBy(['id' => $id]);
        $cargos = $doctrine->getRepository(Cargo::class)->findBy(['transportID' => $transport]);

        return $this->render('cargo/cargoList.html.twig', [
            'cargos' => $cargos,
            'id' => true
        ]);
    }
}
