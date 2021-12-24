<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\SearchProduct;
use App\Form\ProductType;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProductController
 * @package App\Controller
 */

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

    //Créé un chemin et la fonction qui permet de filtrer les produits
    #[Route('/products', name: 'products')]
    public function index(
        Request $request,
        ProductRepository $repository
    ): Response {
        $search = new SearchProduct();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(SearchProductType::class, $search);
        #$form = $this->createForm(AddToCartType::class, $search);
        $form->handleRequest($request);
        $products = $repository->productSearch($search);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $item = $form->getData();
        //     $item->setProduct($product);

        //     $cart = $cartManager->getCurrentCart();
        //     $cart
        //         ->addItem($item)
        //         ->setUpdatedAt(new \DateTime());

        //     $cartManager->save($cart);

        //     return $this->redirectToRoute('product.detail', ['id' => $product->getId()]);
        // }

        return $this->render('product/index.html.twig', [
            'all' => $products,
            'form' => $form->createView(),
        ]);
    }

    //Créé le chemin et la fonction qui permet d'enregistrer un produit dans la base de donnée
    #[Route('/product/save', name: 'product_save', methods: ["POST", "GET"])]
    public function save(Request $request, ManagerRegistry $mr)
    {
        //On créé un nouveau produit
        $product = new Product();
        // On appelle la class PostType qui contient les informations du formulaire pour ajouter un produit
        // et on créé le formulaire lié
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        // Si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $imageName = md5(uniqid()) . '.' . $image->guessExtension();

            $image->move(
                // $this->getParameter permet de récupérer la valeur d'un paramètre définit dans le fichier
                // de config services.yaml
                $this->getParameter('product_uploade_file'),
                $imageName
            );
            $product->setImage($imageName);
            // On le persist et l'enregistre en BDD
            $em = $mr->getManager();
            $em->persist($product);
            $em->flush();
            // On génère un message flash qui apparaîtra sur la page d'accueil pour valider l'enregistrement
            // du produit auprès de l'utilisateur
            $this->addFlash('success', 'Produit ajouté avec succes');
            // On retourne sur la page qui liste produit
            return $this->redirectToRoute('products');
        }
        // On charge le template save en lui passant le formulaire dont on a besoin
        return $this->render('product/save.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Créé le chemin et la fonction qui permet de voir un seul produit
    //en fonction de l'id
    #[Route('/product/show/{id}', name: 'product_show')]
    public function show(Product $product)
    {
        //On créé la vue pour afficher le produit
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
