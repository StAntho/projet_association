<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Product;
use App\Entity\Donation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        /*
            La page d'accueil doit afficher tous les animaux qui sont arrivés moins de 30 jours.
            Pour cela, nous modifions le timeStamp de dateStart (dateNow - 30jours en seconde)
            Puis nous requetons vers le Repository de Animal par la méthode:
            findByDateArrivedThirtyDays afin de le retourner vers home/index.html.twig

            La page d'accueil affiche également la somme des donations
        */
        $dateNow = new \DateTime();
        $dateStart = new \DateTime();
        $dateStart->setTimestamp($dateNow->getTimestamp() - 2592000);

        $total_donation = $this->doctrine->getRepository(Donation::class)->findAll();

        $animals = $this->doctrine->getRepository(Animal::class)->findByDateArrivedThirtyDays($dateStart, $dateNow);
        $products = $this->doctrine->getRepository(Product::class)->findAll();
        return $this->render('home/index.html.twig', [
            'animals' => $animals,
            'products' => $products,
            'total_donation' => $total_donation
        ]);
    }
}
