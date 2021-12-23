<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Product;
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
        $dateNow = new \DateTime();
        $dateStart = new \DateTime();
        $dateStart->setTimestamp($dateNow->getTimestamp() - 2592000);

        $animals = $this->doctrine->getRepository(Animal::class)->findByDateArrivedThirtyDays($dateStart, $dateNow);
        $products = $this->doctrine->getRepository(Product::class)->findAll();
        return $this->render('home/index.html.twig', [
            'animals' => $animals,
            'products' => $products
        ]);
    }
}
