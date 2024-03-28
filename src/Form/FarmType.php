<?php

namespace App\Form;

use App\Entity\Farm;
use App\Entity\Veterinarian;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FarmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nome da fazenda:',
                'attr' => ['placeholder' => 'Insira o nome da fazenda']
            ])
            ->add('hectares', NumberType::class, [
                'label' => 'Hectáres:',
                'attr' => ['placeholder' => 'Insira o tamanho da fazenda, em hectáres']
            ])
            ->add('responsible', TextType::class, [
                'label' => 'Responsável:',
                'attr' => ['placeholder' => 'Insira o nome do responsável pela fazenda']
            ])
            ->add('veterinarians', EntityType::class, [
                'class' => Veterinarian::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Veterinários:',
                'required' => false,
                'expanded' => true,])
            ->add('submit', SubmitType::class, [
                'label' => $options['label'],
                'attr' => [
                    'class' => 'btn-primary w-100'
                ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Farm::class,
        ]);
    }
}
