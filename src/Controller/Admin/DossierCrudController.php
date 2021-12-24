<?php

namespace App\Controller\Admin;

use App\Entity\Dossier;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DossierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Dossier::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('animal'),
            ChoiceField::new('status')->setChoices(
                [
                    'Nouveau' => 1,
                    'En cours' => 2,
                    'Validé' => 3,
                    'Refusé' => 4,
                ]
            ),
            ImageField::new('adoptionfile')
                ->setUploadDir('public/img/dossier'),
            ImageField::new('identitycard')
                ->setUploadDir('public/img/dossier'),
            AssociationField::new('user'),
        ];
    }
}
