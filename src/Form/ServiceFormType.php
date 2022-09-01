<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class ServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Service|null $service */
        $service = $options['data'] ?? null;

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

        if (!$service || !$service->getServiceLogo()){
            $imageConstarints[] = new NotNull([
                'message' => 'не выбрано изображение статьи',
            ]);
        }

        $builder
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => $imageConstarints,
                'label' => 'Изображение услуги',
            ])
            ->add('name', TextType::class, ['label' => 'Название услуги'])
            ->add('price',TextType::class, ['label' => 'Стоимость услуги'])
            ->add('duration', IntegerType::class, ['label' => 'Продолжительность приема (мин)'] )
//            ->add('serviceLogo',FileType::class, ['label' => 'Изображение для услуги'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
