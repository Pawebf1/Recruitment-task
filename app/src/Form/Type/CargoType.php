<?php
declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transportFrom', TextType::class)
            ->add('transportTo', TextType::class)
            ->add('plane', TextType::class)
            ->add('documents', TextType::class, [
                'required' => false,
            ])
            ->add('date', DateType::class)
            ->add('name', TextType::class)
            ->add('weight', TextType::class)
            ->add('type', TextType::class)
            ->add('submit', SubmitType::class);
    }
}