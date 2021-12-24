<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Animal;
use App\Entity\Dossier;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Type;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Projet Association');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Animals'),
            MenuItem::linkToCrud('Animals', 'fas fa-dog', Animal::class),
            MenuItem::linkToCrud('Species', 'fas fa-paw', Type::class),

            MenuItem::section('Products'),
            MenuItem::linkToCrud(
                'Products',
                'fas fa-store-alt',
                Product::class
            ),
            MenuItem::linkToCrud(
                'Catégories',
                'fas fa-shopping-bag',
                Category::class
            ),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Folders', 'fas fa-folder', Dossier::class),
        ];
    }
}
