<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Visitor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'ФИО *'])
            ->add('email', null, ['label' => 'Электронная почта *'])
            ->add('phoneNumber', null, ['label' => 'Номер телефона *'])
            ->add('ageChildren', null, ['label' => 'Возраст ребёнка *'])
            ->add('reason', null, ['label' => 'Опишите причину обращения:'])
            ->add('consultForm', null, ['label' => 'Выберите форму консультации'])
            ->add('consent',CheckboxType::class, ['label' => 'Даю согласие на обработку персональных данных:'])
//            ->add('card', EntityType::class, [
//                'class' => Card::class,
//                'invalid_message' => 'не-не-не',
//                'disabled' => true,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visitor::class,
        ]);
    }
}
