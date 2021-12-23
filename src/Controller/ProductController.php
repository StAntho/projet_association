<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\SearchProduct;
use App\Form\ProductType;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $repository = $this->repository;
    }

    #[Route('/products', name: 'products')]
    public function index(Request $request, ProductRepository $repository): Response
    {
        $search = new SearchProduct();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(SearchProductType::class, $search);
        $form->handleRequest($request);
        $products = $repository->productSearch($search);

        return $this->render('product/index.html.twig', [
            'all' => $products,
            'form' => $form->createView(),
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
            return $this->redirectToRoute('products');
        }

        return $this->render('product/save.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/show/{id}', name: 'product_show')]
    public function show(Product $product)
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
