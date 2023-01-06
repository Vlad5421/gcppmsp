<?php

namespace App\Form;

use App\Entity\Filial;
use App\Entity\Schedule;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, ['label' => 'Название расписания (видно только в админке)'])
            ->add('worker',EntityType::class, [
                'class' => User::class,
                'invalid_message' => 'не-не-не',
                'label' => 'Специалист',
            ])
            ->add('filial',EntityType::class, [
                'class' => Filial::class,
                'invalid_message' => 'не-не-не',
                'label' => 'Филиал',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Schedule::class,
        ]);
    }
}
