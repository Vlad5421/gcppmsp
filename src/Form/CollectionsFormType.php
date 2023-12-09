<?php

namespace App\Form;

use App\Entity\Collections;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Название коллекции'])
            ->add('collection', EntityType::class, [
                'required' =>false,
                'multiple' =>false,
                'expanded' =>true,
                'class' => Collections::class,
                'label' => 'form.order.collection',
//                'label_translation_parameters' => [
//                    '%parrent_collection%'
//                ],
            ])
//            ->add('type', null, ['label' => 'Тип коллекции'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Collections::class,
        ]);
    }
}
