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

    /*
        Pour mettre à jour un dossier, on récupère l'id du dossier
    */
    #[Route('/dossier/save/{dossier_id}', name: 'dossier_save')]
    public function updateDossier($dossier_id, Request $request, ManagerRegistry $mr)
    {
        $dossier = $this->doctrine->getRepository(Dossier::class)->find($dossier_id);
        $oldDossier = new Dossier();
        /*
            On vérifie si le dossier existe déjà, afin de récupérer les anciennes valeurs des fichiers:
                identitycard et adoptionfile du dossier.
        */
        if (!$dossier) {
            $oldDossier->setIdentitycard($dossier->getIdentitycard());
            $oldDossier->setAdoptionfile($dossier->getAdoptionfile());
        }

        // On appelle la class DossierType qui contient les informations du formulaire pour ajouter un animal
        $form = $this->createForm(DossierType::class);

        $form->handleRequest($request);

        /*
            Si le formulaire est soumis et valide, on récupère les fichiers afin de les renommer
            par mdp5 suivi de l'extension du fichier:
            - adoptionfile sera move dans le paramètre de services.yaml upload_dossier_file: 
            /public/file/dossier/
            - identitycard sera move dans le paramètre upload_dossier_image:
            /public/img/dossier/
            Lorsque les fichiers sont bien upload, on met à jour l'object $dossier, puis on persist 
            et sauvegarde vers la BDD
         */
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
            $dossier->setStatus(2);

            // On le persist et l'enregistre en BDD
            $em = $mr->getManager();
            $em->persist($dossier);
            $em->flush();

            //On ajoute un flash qui sera affiché dans le header layout.html.twig
            $this->addFlash("success", "Dossier bien enregistré");

            // On retourne sur la liste des animaux
            return $this->redirectToRoute("dossier_show", [
                'dossier_id' => $dossier->getId()
            ]);
        }

        /*
            Sinon on retourne vers le formulaire de la page de mise 
        */
        return $this->render("dossier/save.html.twig", [
            'form' => $form->createView(),
            'dossier' => $dossier
        ]);

    }

    /*
        Lorsqu'on créé un dossier, on récupère l'id de l'utilisateur ainsi que l'animal qui a été
        choisi en adoption par l'utilisateur
    */
    #[Route('/create_dossier/{user_id}/{animal_id}', name: 'user_create_dossier')]
    public function createDossier($user_id, $animal_id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->doctrine->getRepository(User::class)->find($user_id);
        $animal = $this->doctrine->getRepository(Animal::class)->find($animal_id);

        /*
            Si l'animal ou l'utilisateur n'existe pas, retourne vers la route "animal_list"
        */
        if (!$user || !$animal) {
            return $this->redirectToRoute("animal_list");
        }
        /*
            On met la valeur de l'animal: "reserved" à true lorsque le dossier est créé
            le status d'un dossier: 1 == nouveau, 2 == en cours, 3 == validé et 4 == rejeté
        */
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

    /*
        Liste l'ensemble des dossiers qui est relié à l'id user passé en paramètre et
        redirige vers la page 'dossier/index.html.twig'
    */
    #[Route('/dossier/{user_id}', name: 'user_dossier_list')]
    public function afficherMesDossiers($user_id): Response
    {
        $user = $this->doctrine->getRepository(User::class)->find($user_id);
        $dossiers = $this->doctrine->getRepository(Dossier::class)->findByUser($user);
        return $this->render('dossier/index.html.twig', [
            'dossiers' => $dossiers
        ]);
    }

    /*
        Affiche le dossier avec l'id dossier passé en paramètre
    */
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
