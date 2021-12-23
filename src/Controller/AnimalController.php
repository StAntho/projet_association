<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Entity\SearchAnimal;
use App\Form\SearchAnimalType;
use App\Repository\AnimalRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnimalController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine, AnimalRepository $repository)
    {
        $this->doctrine = $doctrine;
        $this->repository = $repository;
    }

    #[Route('/animal', name: 'animal_list')]
    public function index(Request $request, AnimalRepository $repository): Response
    {
        $search = new SearchAnimal();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(SearchAnimalType::class, $search);
        $form->handleRequest($request);
        $animals = $repository->animalSearch($search);

        return $this->render('animal/index.html.twig', [
            'all' => $animals,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/animal/save', name: 'animal_save')]
    public function save(Request $request, ManagerRegistry $mr)
    {
        // On créé un nouvel animal
        $animal = new Animal;
        // On appelle la class AnimalType qui contient les informations du formulaire pour ajouter un animal
        // et on créé le formulaire lié
        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($request);

        // Si les données sont ok
        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();
            $pictureName = md5(uniqid()) . '.' . $picture->guessExtension();

            $picture->move(
                // $this->getParameter permet de récupérer la valeur d'un paramètre définit dans le fichier
                // de config services.yaml
                $this->getParameter('upload_file'),
                $pictureName
            );
            $animal->setPicture($pictureName);

            $animal->setReserved(false);

            $animal->setSterilised(false);

            $animal->setDateArrived(new \DateTime());

            // On le persist et l'enregistre en BDD
            $em = $mr->getManager();
            $em->persist($animal);
            $em->flush();

            $this->addFlash("success", "Animal bien enregistré");

            // On retourne sur la liste des animaux
            return $this->redirectToRoute("animal_list");
        }

        return $this->render("animal/save.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route('/animal/show/{id}', name: 'animal_show')]
    public function show(Animal $animal)
    {
        return $this->render("animal/show.html.twig", [
            'animal' => $animal
        ]);
    }
}
