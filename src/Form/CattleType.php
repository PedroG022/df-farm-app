<?php

namespace App\Form;

use App\Entity\Cattle;
use App\Entity\Farm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CattleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Código',
                'attr' => [
                    'placeholder' => 'Insira o código do gado'
                ]
            ])
            ->add('milk_per_week', NumberType::class, [
                'label' => 'Leite',
                'attr' => [
                    'placeholder' => 'Insira a quantidade de leite produzida por semana (L)'
                ]
            ])
            ->add('feed', NumberType::class, [
                'label' => 'Ração',
                'attr' => [
                    'placeholder' => 'Insira a quantidade de ração consumida por semana (kg)'
                ]
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Peso',
                'attr' => [
                    'placeholder' => 'Insira o peso do animal (kg)'
                ]
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Data de nascimento',
            ])
            ->add('farm', EntityType::class, [
                'label' => 'Fazenda',
                'class' => Farm::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['label'],
                'attr' => [
                    'class' => 'btn btn-primary w-100'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cattle::class,
        ]);
    }
}
