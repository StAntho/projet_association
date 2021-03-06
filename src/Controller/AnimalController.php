<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Entity\SearchAnimal;
use App\Entity\Type;
use App\Form\SearchAnimalType;
use App\Repository\AnimalRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;

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
            'form' => $form->createView()
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

            // On rentre le champs par défaut
            $animal->setReserved(false);

            // On met la date de sauvegarde
            $animal->setDateArrived(new \DateTime());

            // On le persist et l'enregistre en BDD
            $em = $mr->getManager();
            $em->persist($animal);
            $em->flush();

            // On notifie l'utilisateur que l'animal est bien enregistré
            $this->addFlash("success", "Animal bien enregistré");

            // On retourne sur la liste des animaux
            return $this->redirectToRoute("animal_list");
        }

        return $this->render("animal/save.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route('/animal/update/{animalId}', name: 'animal_update', methods: ['POST', 'GET'])]
    public function update($animalId, Request $request): Response
    {
        // On sélectionne le bonne animal selon l'ID
        $animal = $this->doctrine->getRepository(Animal::class)->find($animalId);

        // On récupère l'ancienne image de notre animal
        $oldAnimal = new Animal;
        $oldAnimal->setPicture($animal->getPicture());

        $picture = new File($this->getParameter('upload_file').$animal->getPicture());
        $animal->setPicture($picture);

        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si une photo a été rajoutée alors on mets la nouvelle l'image
            if ($form->get('picture')->getData() !== null) {
                $picture = $form->get('picture')->getData();
                $pictureName = md5(uniqid()).'.'. $picture->guessExtension();
    
                $picture->move(
                    // $this->getParameter permet de récupérer la valeur d'un paramètre définit dans le fichier
                    // de config services.yaml
                    $this->getParameter('upload_file'),
                    $pictureName
                );
                $animal->setPicture($pictureName);
            } else {
                // Sinon on remets l'ancienne image
                $animal->setPicture($oldAnimal->getPicture());
            }

            // On le persist et l'enregistre en BDD
            $em = $this->doctrine->getManager();
            $em->persist($animal);
            $em->flush();

            // On notifie l'utilisateur que l'animal a bien été modifié
            $this->addFlash("success", "Animal a bien été modifié");

            // On retourne sur la liste des animaux
            return $this->redirectToRoute("animal_list");
        }

        return $this->render("animal/update.html.twig", [
            'form' => $form->createView(),
            'animal' => $animal
        ]);
    }

    #[Route("/animal/delete/{animalId}", name: 'animal_delete')]
    public function delete($animalId): Response
    {
        $animal = $this->doctrine->getRepository(Animal::class)->find($animalId);

        // Suppression de l'animal
        $em = $this->doctrine->getManager();
        $em->remove($animal);
        $em->flush();

        return $this->redirectToRoute('animal_list');
    }

    #[Route('/animal/show/{id}', name: 'animal_show')]
    public function show(Animal $animal)
    {
        return $this->render("animal/show.html.twig", [
            'animal' => $animal
        ]);
    }
}
