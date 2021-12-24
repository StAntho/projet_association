<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Entity\Animal;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\DomCrawler\Field\FileFormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AnimalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }

    //Custom de easyAdmin

    public function configureActions(Actions $actions): Actions
    {
        //Custom des icons CRUD (ADD,DETAIL,EDIT,DELETE)
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setIcon('fas fa-paw')
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
        //On créé les champs de saisi personnaliser pour la saisi d'un animal
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextareaField::new('description'),
            DateTimeField::new('DateArrived')->setColumns(
                'col-sm-6 col-lg-5 col-xxl-3'
            ),
            IntegerField::new('age'),
            TextField::new('race'),
            BooleanField::new('sterilised'),
            BooleanField::new('reserved'),
            ImageField::new('picture')->setUploadDir('public/img/uploads'),
            //La propriété AssociationField permet de faire la liaison entre la table animal et type
            //pour permettre la selection
            AssociationField::new('type'),
            ChoiceField::new('gender')->setChoices([
                'Male' => 'Male',
                'Femelle' => 'Femelle',
            ]),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        //Creation d'un filtre de recherche
        return $filters
            ->add('name')
            ->add('type')
            ->add('gender');
    }
}
