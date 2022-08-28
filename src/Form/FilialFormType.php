<?php

namespace App\Form;

use App\Entity\Collections;
use App\Entity\Filial;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilialFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, ['label' => 'Название Филиала'])
            ->add('address',TextType::class, ['label' => 'Адрес Филиала'])
            ->add('collection',EntityType::class, [
                'class' => Collections::class,
                'invalid_message' => 'не-не-не',
                'label' => 'Коллекция',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filial::class,
        ]);
    }
}
