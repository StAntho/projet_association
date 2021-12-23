<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\DomCrawler\Field\FileFormField;

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
            TextEditorField::new('description'),
            DateTimeField::new('DateArrived')->setColumns('col-sm-6 col-lg-5 col-xxl-3'),
            IntegerField::new('age'),
            TextField::new('race'),
            BooleanField::new('sterilised'),
            BooleanField::new('reserved'),
            ImageField::new('picture')
                ->setUploadDir('public/img/uploads'),
            AssociationField::new('type')->setFormTypeOptions([
                'choices' => false,
            ])
                ->autocomplete(),
        ];
    }
}
