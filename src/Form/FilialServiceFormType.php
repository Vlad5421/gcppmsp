<?php

namespace App\Form;

use App\Entity\Collections;
use App\Entity\Filial;
use App\Entity\FilialService;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilialServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filial',EntityType::class, [
                'class' => Filial::class,
                'invalid_message' => 'не-не-не',
                'label' => 'Филиал',
            ])
            ->add('service',EntityType::class, [
                'class' => Service::class,
                'invalid_message' => 'не-не-не',
                'label' => 'Услуга',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilialService::class,
        ]);
    }
}
