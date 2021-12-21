<?php

namespace App\Controller;

use App\Entity\Animal;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnimalController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/animal', name: 'animal')]
    public function index(): Response
    {
        $animals = $this->doctrine->getRepository(Animal::class)->findAll();
        return $this->render('animal/index.html.twig', [
            'animals' => $animals,
        ]);
    }

}
