<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Entity\Animal;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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


    public function configureFields(string $pageName): iterable
    {

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextareaField::new('description'),
            DateTimeField::new('DateArrived')->setColumns('col-sm-6 col-lg-5 col-xxl-3'),
            IntegerField::new('age'),
            TextField::new('race'),
            BooleanField::new('sterilised'),
            BooleanField::new('reserved'),
            ImageField::new('picture')
                ->setUploadDir('public/img/uploads'),
            AssociationField::new('type'),
            ChoiceField::new('gender')->setChoices(
                [
                    'Male' => 'Male',
                    'Femelle' => 'Femelle',
                ]
            ),
        ];
    }
}
