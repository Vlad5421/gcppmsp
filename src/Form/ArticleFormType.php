<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mainImage', FileType::class, [
                'label' => 'Картинка статьи',
                'mapped' => false,
//                'required' => $requireImage,
//                'constraints' => $imageConstarints,
            ])
            ->add('title', null, ['label' => 'Название'])
            ->add('body', null, ['label' => 'Текст записи', 'attr' => ['rows' => 10]])
            ->add('publishedAt', null, ['label' => 'Дата публикации', 'widget' => 'single_text'])
            ->add('autor',EntityType::class, [
                'class' => User::class,
                'label' => 'Автор:',
                'placeholder' => 'автор',
                'invalid_message' => 'не-не-не',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
