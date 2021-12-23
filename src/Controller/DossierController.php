<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Animal;
use App\Entity\Dossier;
use App\Form\DossierType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DossierController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/dossier', name: 'dossier_list')]
    public function index(): Response
    {
        $dossiers = $this->doctrine->getRepository(Dossier::class)->findAll();
        return $this->render('dossier/index.html.twig', [
            'dossiers' => $dossiers
        ]);
    }

    #[Route('/dossier/save/{dossier_id}', name: 'dossier_save')]
    public function updateDossier($dossier_id, Request $request, ManagerRegistry $mr)
    {
        $dossier = $this->doctrine->getRepository(Dossier::class)->find($dossier_id);
        $oldDossier = new Dossier();
        $oldDossier->setIdentitycard($dossier->getIdentitycard());
        $oldDossier->setAdoptionfile($dossier->getAdoptionfile());

        // On appelle la class DossierType qui contient les informations du formulaire pour ajouter un animal
        $form = $this->createForm(DossierType::class);

        $form->handleRequest($request);

        // Si les données sont ok
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('adoptionfile')->getData()) {
                $adoptionfile = $form->get('adoptionfile')->getData();
                $adoptionfileName = md5(uniqid()).'.'. $adoptionfile->guessExtension();
                $dossier->setAdoptionfile($adoptionfileName);

                $adoptionfile->move(
                    $this->getParameter('upload_dossier_file'),
                    $adoptionfileName
                );
            } else {
                $dossier->setAdoptionfile($oldDossier->getAdoptionfile());
            }

            if ($form->get('identitycard')->getData()) {
                $identitycard = $form->get('identitycard')->getData();
                $identitycardFileName = md5(uniqid()).'.'. $identitycard->guessExtension();
                $dossier->setIdentitycard($identitycardFileName);

                $identitycard->move(
                    $this->getParameter('upload_dossier_image'),
                    $identitycardFileName
                );
            } else {
                $dossier->setIdentitycard($oldDossier->getIdentitycard());
            }

            // On le persist et l'enregistre en BDD
            $em = $mr->getManager();
            $em->persist($dossier);
            $em->flush();

            $this->addFlash("success", "Dossier bien enregistré");

            // On retourne sur la liste des animaux
            return $this->redirectToRoute("dossier_show", [
                'dossier_id' => $dossier->getId()
            ]);
        }

        return $this->render("dossier/save.html.twig", [
            'form' => $form->createView(),
            'dossier' => $dossier
        ]);

    }

    #[Route('/create_dossier/{user_id}/{animal_id}', name: 'user_create_dossier')]
    public function createDossier($user_id, $animal_id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->doctrine->getRepository(User::class)->find($user_id);
        $animal = $this->doctrine->getRepository(Animal::class)->find($animal_id);

        if (!$user || !$animal) {
            return $this->redirectToRoute("animal_list");
        }
        $animal->setReserved(true);

        $dossier = new Dossier();
        $dossier->setAnimal($animal);
        $dossier->setUser($user);

        $dossier->setStatus(1);
        $entityManager->persist($animal);
        $entityManager->persist($dossier);
        $entityManager->flush();

        $this->addFlash("success", "Création du dossier bien enregistré");

        return $this->redirectToRoute('dossier_save', [
            'dossier_id' => $dossier->getId()
        ]);
    }

    #[Route('/dossier/{user_id}', name: 'user_dossier_list')]
    public function afficherMesDossiers($user_id): Response
    {
        $user = $this->doctrine->getRepository(User::class)->find($user_id);
        $dossiers = $this->doctrine->getRepository(Dossier::class)->findByUser($user);
        return $this->render('dossier/index.html.twig', [
            'dossiers' => $dossiers
        ]);
    }

    
    #[Route('/dossier/show/{dossier_id}', name: 'dossier_show')]
    public function afficherMonDossier($dossier_id): Response
    {
        $dossier = $this->doctrine->getRepository(Dossier::class)->find($dossier_id);
        $user_id  = $dossier->getUser()->getId();
        return $this->render('dossier/show.html.twig', [
            'dossier' => $dossier,
            'user_id' => $user_id

        ]);
    }
}
