<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    //Custom de easyAdmin

    public function configureActions(Actions $actions): Actions
    {
        //Custom des icons CRUD (ADD,DETAIL,EDIT,DELETE)
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setIcon('fas fa-shopping-bag')
                    ->addCssClass('btn btn-success');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action
                    ->setIcon('fa fa-edit')
                    ->addCssClass('btn btn-warning');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (
                Action $action
            ) {
                return $action
                    ->setIcon('fa fa-eye')
                    ->addCssClass('btn btn-info');
            })

            ->update(Crud::PAGE_INDEX, Action::DELETE, function (
                Action $action
            ) {
                return $action
                    ->setIcon('fa fa-trash')
                    ->addCssClass('btn btn-outline-danger');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        //On créé les champs de saisi personnaliser pour la saisi d'une categorie
        return [IdField::new('id')->hideOnForm(), TextField::new('name')];
    }

    public function configureFilters(Filters $filters): Filters
    {
        //Creation d'un filtre de recherche
        return $filters->add('name');
    }
}
