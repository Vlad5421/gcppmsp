<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        /** @var Article|null $article */
        $article = $options['data'] ?? null;

        $imageConstarints = [
            new Image([
                'maxSize' => '2M',
                'minWidth' => '480',
                'minWidthMessage' => 'Должно быть не менее чем 480x300 px',
                'minHeight' => '300',
                'minHeightMessage' => 'Должно быть не менее чем 480x300 px',
                'allowPortrait' => false,
                'allowPortraitMessage' => 'Изображение должно быть горизонтальным'
            ]),
        ];

        if (!$article || !$article->getMainImage()){
            $imageConstarints[] = new NotNull([
                'message' => 'не выбрано изображение статьи',
            ]);
        }


        $builder
            ->add('title')
            ->add('type')
//            ->add('slug')
            ->add('aplication')
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => true,
//                'constraints' => $imageConstarints,
                'label' => 'Изображение страницы',
            ])
            ->add('imageCollection', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'label' => 'Галлерея файлов для страницы',
            ])
            ->add('body')
            ->add('customCss')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
