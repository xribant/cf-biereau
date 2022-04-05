<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Flasher\Toastr\Prime\ToastrFactory;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer, ToastrFactory $toastr): Response
    {
        $contact = [];

        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $email = (new TemplatedEmail())
                ->from('admin@cfbiereau.be')
                ->to(new Address('xavier@mywebcreation.be'))
                ->subject('Nouveau message')
                ->htmlTemplate('mails/index.html.twig')
                ->context([
                    'contact' => $contact
                ])
            ;

            try {
                $mailer->send($email);

                $toastr
                    ->success('<strong>Message reçu! <br>Nous y répondrons dans les meilleurs délais.</strong>')
                    ->timeOut(5000)
                    ->progressBar()
                    ->closeButton()
                    ->positionClass('toast-top-center')
                    ->flash()
                ;

                return $this->redirectToRoute('app_home');

            } catch (TransportExceptionInterface $e) {
                echo $e->getMessage();
            }

        }

        return $this->render('contact/index.html.twig', [
            'active_menu' => 'contact',
            'form' => $form->createView()
        ]);
    }

}
