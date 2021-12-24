<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Entity\Creditcard;
use App\Form\DonationType;
use App\Form\CreditcardType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DonationController extends AbstractController
{
    #[Route('/donation', name: 'donation')]
    public function makeDonation(Request $request, ManagerRegistry $mr): Response
    {
        $donation = new Donation;

        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('donation_creditcard', [
                'donation_amount' => $donation->getAmount()
            ]);
        }
        return $this->render("donation/donate.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /*
        The method create a form to fill the creditcard data. And display the amount of donation. 
        If the form is submitted and is valid, we will create a donation and insert to database
        Then add flash to the main page (layout.html.twig)
    */
    #[Route('/donation/creditcard/{donation_amount}', name: 'donation_creditcard')]
    public function inputCreditcard($donation_amount, Request $request, ManagerRegistry $mr): Response
    {
        $creditcard = new Creditcard();

        $formCreditcard = $this->createForm(CreditcardType::class, $creditcard);

        $formCreditcard->handleRequest($request);

        $error = "";

        if ($formCreditcard->isSubmitted() && $formCreditcard->isValid()) {
            $donation = new Donation();
            $donation->setAmount($donation_amount);
            $donation->setDateDonation(new \Datetime());

            $em = $mr->getManager();
            $em->persist($donation);
            $em->flush();
            if ($error != "") {
                $this->addFlash("danger", $error);
            } else {
                $this->addFlash("success", "Donation bien effectué, merci à vous !");
                return $this->redirectToRoute('index');
            }
        }
        return $this->render("donation/index.html.twig", [
            'donation_amount' => $donation_amount,
            'form_creditcard' => $formCreditcard->createView()
        ]);
    }

}
