<?php

namespace App\Form;

use App\Entity\Collections;
use App\Entity\Filial;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class FilialFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Filial|null $filial */
        $filial = $options['data'] ?? null;

        if (!$filial || !$filial->getImage()){
            $imageConstarints[] = new NotNull([
                'message' => 'не выбрано изображение статьи',
            ]);
        }

        $imageConstarints = [
            new Image([
                'maxSize' => '4M',
                'minWidth' => '200',
                'minWidthMessage' => 'Должно быть не менее чем 480x300 px',
                'minHeight' => '200',
                'minHeightMessage' => 'Должно быть не менее чем 480x300 px',
                'allowPortrait' => false,
                'allowPortraitMessage' => 'Изображение должно быть горизонтальным'
            ]),
        ];

        $builder
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => $imageConstarints,
                'label' => 'Изображение',
            ])
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
