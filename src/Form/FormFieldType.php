<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Тип поля',
                'choices' => [
                    'Текст' => 'text',
                    'Флажок' => 'checkbox',
                    'Выпадающий список' => 'select',
                    'Файл' => 'file'
                ],
                'attr' => ['class' => 'form-control field-type-select']
            ])
            ->add('name', TextType::class, [
                'label' => 'Имя поля',
                'attr' => ['class' => 'form-control']
            ])
            ->add('label', TextType::class, [
                'label' => 'Метка',
                'attr' => ['class' => 'form-control']
            ])
            ->add('required', CheckboxType::class, [
                'label' => 'Обязательное',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('placeholder', TextType::class, [
                'label' => 'Placeholder',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('options', CollectionType::class, [
                'entry_type' => SelectOptionType::class,
                'label' => 'Варианты (для select)',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'attr' => ['class' => 'select-options']
            ])
            ->add('multiple', CheckboxType::class, [
                'label' => 'Множественная загрузка',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('maxFiles', TextType::class, [
                'label' => 'Максимальное количество файлов',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('allowedExtensions', CollectionType::class, [
                'entry_type' => TextType::class,
                'label' => 'Разрешённые расширения',
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'attr' => ['class' => 'allowed-extensions']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}