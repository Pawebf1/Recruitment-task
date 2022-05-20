<?php
declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Transport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transportFrom')
            ->add('transportTo')
            ->add('plane', ChoiceType::class, [
                'choices' => [
                    'Airbus A380 (max weight for one cargo 35 tons)' => 'Airbus A380',
                    'Boeing 747 (max weight for one cargo 38 tons)' => 'Boeing 747',
                ],
            ])
            ->add('documents', FileType::class, [
                'required' => false,
                'multiple' => true,
                'mapped' => false,

            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),

            ])
            ->add('cargos', CollectionType::class, [
                'entry_type' => CargoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,

            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary'
                ]
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transport::class,
        ]);
    }
}