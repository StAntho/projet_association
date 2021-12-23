<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeController extends AbstractController
{
    #[Route('/type/save', name: 'type_save')]
    public function save(Request $request, ManagerRegistry $mr): Response
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($type);
            $em->flush();

            $this->addFlash("success", "Espèce bien enregistrée");

            return $this->redirectToRoute("animal_list");
        }

        return $this->render('category/save.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
