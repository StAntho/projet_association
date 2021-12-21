<?php

namespace App\Form;

use App\Entity\Animal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                "label" => "Name:",
                "attr" => ["class" => "form-floating mb-3"]
            ])
            ->add('Age', IntegerType::class, [
                "attr" => ["class" => "form-floating mb-3"]
            ])
            ->add('Type', TextType::class, [
                "label" => "Type:",
                "attr" => ["class" => "form-floating mb-3"]
            ])
            ->add('Race', TextType::class, [
                "label" => "Race:",
                "attr" => ["class" => "form-floating mb-3"]
            ])
            ->add('Description', TextareaType::class, [
                "label" => "Description de l'animal"
            ])
            ->add('picture', FileType::class, ["label" => "Image (JPEG/PNG)"])
            ->add('Gender', ChoiceType::class, [
                'choices'  => [
                    'Mâle' => 'Mâle',
                    'Femelle' => 'Femelle'
                ]
            ])
            ->add("Ajouter", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}