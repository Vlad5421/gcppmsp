<?php

namespace App\Form;

use App\Entity\Complect;
use App\Entity\Filial;
use App\Entity\Schedule;
use App\Entity\Service;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComplectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        /** @var Complect|null $complect */
//        $complect = $options['data'] ?? null;

        $builder
            ->add('filial',EntityType::class, [
                'class' => Filial::class,
                'label'=>'Выберите филиал',
                'placeholder' => 'Филиал',
                'invalid_message' => 'не-не-не',
            ])
            ->add('service',EntityType::class, [
                'class' => Service::class,
                'label' => 'Выберите услугу',
                'placeholder' => 'Услуга',
                'invalid_message' => 'не-не-не',
            ])
            ->add('schedule',EntityType::class, [
                'class' => Schedule::class,
                'label' => 'Выберите расписание',
                'placeholder' => 'Расписание',
                'invalid_message' => 'не-не-не',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Complect::class,
        ]);
    }
}
