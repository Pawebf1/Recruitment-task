<?php
declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class CargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transportFrom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter city'
                ]
            ])
            ->add('transportTo', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter city'
                ]
            ])
            ->add('plane', ChoiceType::class, [
                'choices' => [
                    'Airbus A380 (max weight for one cargo 35 tons)' => 'Airbus A380',
                    'Boeing 747 (max weight for one cargo 38 tons)' => 'Boeing 747',
                ],
            ])
            ->add('document', FileType::class, [
                'required' => false,
                'multiple' => true,

            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),

            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter cargo name'
                ]
            ])
            ->add('weight', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter cargo weight'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Normal cargo' => 'Normal cargo',
                    'Dangerous cargo' => 'Dangerous cargo',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary'
                ]
            ]);
    }
}