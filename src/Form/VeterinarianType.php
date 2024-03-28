<?php

namespace App\Form;

use App\Entity\Farm;
use App\Entity\Veterinarian;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VeterinarianType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nome',
                'attr' => [
                    'placeholder' => 'Nome do veterinário'
                ]
            ])
            ->add('crmv', TextType::class, [
                'label' => 'CRMV',
                'attr' => [
                    'placeholder' => 'Código CRMV'
                ]
            ])
            ->add('farms', EntityType::class, [
                'label' => 'Fazendas',
                'class' => Farm::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'expanded' => true,
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
            'data_class' => Veterinarian::class,
        ]);
    }
}
