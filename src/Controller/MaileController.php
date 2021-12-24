<?php

namespace App\Controller;

use App\Form\MailType;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaileController extends AbstractController
{
    #[Route('/maile', name: 'maile')]
    public function index(Request $request, Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(MailType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $message = (new Swift_Message('Test mail'))
                ->setFrom($contact['email'])
                ->setTo('negrotiburon619@yahoo.com')
                ->setBody(
                    $this->renderView(
                        'mail/bodyemail.html.twig',
                        compact('contact')
                    ),
                    'text/html'
                );

            $mailer->send($message);
        }

        return $this->render('maile/index.html.twig', [
            'mailForm' => $form->createView(),
        ]);
    }
}
