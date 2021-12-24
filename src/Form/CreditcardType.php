<?php

namespace App\Form;


use App\Entity\Creditcard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class CreditcardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentYear = date("Y"); 
        $choiceMonthExpiration = $this->createChoiceMonthExpiration();
        $choiceYearExpiration = $this->createChoiceYearExpiration($currentYear);

        $builder
            ->add('creditcardnumber', IntegerType::class, [
                'label' => "Saisissez votre numéro de carte bancaire"
            ])
            ->add('monthExpiration', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 12
                ]
            ])
            ->add('yearExpiration', IntegerType::class, [
                'attr' => [
                    'min' => 2021,
                    'max' => 2050
                ]
            ])
            ->add('cvv', IntegerType::class, [
                'attr' => [
                    'min' => 100,
                    'max' => 999
                ]
            ])
            ->add('Valider', SubmitType::class)
        ;

    }

    public function createChoiceMonthExpiration() {
        $choices = array();
        for ($i=0 ; $i <= 12; $i++) { 
            $choices[] = $i + 1;
        }
        return $choices;
    }

    /*
        create choiceYearExpiration from dateNowYearValue, and add 30 years value from now
    */
    public function createChoiceYearExpiration($dateNowYearValue){
        $choices = array();
        for ($i=$dateNowYearValue ; $i < $dateNowYearValue + 30; $i++) { 
            $choices[] = $i;
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Creditcard::class
        ]);
    }
}
