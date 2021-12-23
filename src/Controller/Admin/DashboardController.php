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
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Projet Association');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Animals'),
            MenuItem::linkToCrud('Animals', 'fas fa-dog', Animal::class),
            MenuItem::linkToCrud('Espèces', 'fas fa-dog', Type::class),

            MenuItem::section('Products'),
            MenuItem::linkToCrud('Products', 'fas fa-euro-sign', Product::class),
            MenuItem::linkToCrud('Catégories', 'fas fa-euro-sign', Category::class),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Dossiers', 'fa fa-user', Dossier::class),
        ];
    }
}
