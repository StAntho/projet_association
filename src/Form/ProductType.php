<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductType extends AbstractType
{
    /*
        Méthode qui construit le formulaire pour créer un produit
        Les différents champs: name, description, category, price, quantity, image
        Le champ category est l'entité Category qui est une relation 
        à un produit. EntityType nous retournera la liste des catégories dans le choice_label
    */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité',
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG/PNG)',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
