<?php

namespace App\Form;

use App\Entity\Collections;
use App\Entity\Filial;
use App\Entity\Schedule;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

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
            ->add('pn_start', TimeType::class, [
                'mapped' => false,
                'label' => 'Пн. с:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('pn_end', TimeType::class, [
                'mapped' => false,
                'label' => 'до:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('vt_start', TimeType::class, [
                'mapped' => false,
                'label' => 'Вт. с:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('vt_end', TimeType::class, [
                'mapped' => false,
                'label' => 'до:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('sr_start', TimeType::class, [
                'mapped' => false,
                'label' => 'Ср. с:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('sr_end', TimeType::class, [
                'mapped' => false,
                'label' => 'до:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('cht_start', TimeType::class, [
                'mapped' => false,
                'label' => 'Чт. с:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('cht_end', TimeType::class, [
                'mapped' => false,
                'label' => 'до:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('pt_start', TimeType::class, [
                'mapped'=> false,
                'label' => 'Пт. с:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('pt_end', TimeType::class, [
                'mapped' => false,
                'label' => 'до:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('sb_start', TimeType::class, [
                'mapped'=> false,
                'label' => 'Сб. с:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('sb_end', TimeType::class, [
                'mapped' => false,
                'label' => 'до:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('vs_start', TimeType::class, [
                'mapped'=> false,
                'label' => 'Вс. с:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('vs_end', TimeType::class, [
                'mapped' => false,
                'label' => 'до:',
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'input'  => 'datetime',
                'required' => false,
                'widget' => 'single_text',
            ])

//            ->add('day')
//            ->add('start')
//            ->add('endTime')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Schedule::class,
        ]);
    }
}
