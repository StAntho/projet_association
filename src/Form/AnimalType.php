<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnimalType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'animal',
                'attr' => ['class' => 'form-floating mb-3']
            ])
            ->add('age', IntegerType::class, [
                'attr' => ['class' => 'form-floating mb-3']
            ])
            ->add('type', EntityType::class, [
                'label' => 'Espèce',
                'class' => Type::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name'
            ])
            ->add('race', TextType::class, [
                'label' => 'Race',
                'attr' => ['class' => 'form-floating mb-3']
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description de l'animal"
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image (JPEG/PNG)',
                'required' => false
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Mâle' => 'Mâle',
                    'Femelle' => 'Femelle'
                ]
            ])
            ->add('sterilised', ChoiceType::class, [
                'choices' => [
                    'Oui' => 1,
                    'Non' => 0
                ]
            ])
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class
        ]);
    }
}
