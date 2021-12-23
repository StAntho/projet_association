<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\AddToCartType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    #[Route('/product', name: 'product')]
    public function index(): Response
    {
        $products = $this->doctrine->getRepository(Product::class)->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/save', name: 'product_save', methods: ["POST", "GET"])]
    public function save(Request $request, ManagerRegistry $mr)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $imageName = md5(uniqid()) . '.' . $image->guessExtension();

            $image->move(
                $this->getParameter('product_uploade_file'),
                $imageName
            );
            $product->setImage($imageName);

            $em = $mr->getManager();
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Produit ajouté avec succes');
            return $this->redirectToRoute('product');
        }

        return $this->render('product/save.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
