<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/categorie/save', name: 'categorie_save')]
    public function save(Request $request, ManagerRegistry $mr): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash("success", "Categorie bien enregistré");

            return $this->redirectToRoute("product");
        }

        return $this->render('category/save.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
