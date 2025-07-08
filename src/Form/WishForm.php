<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Wish;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class WishForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', options:[
                'label'=> 'Your idea',
            ])
            ->add('description', options:[
                'label'=> 'Please describe it!',
                'required' => false,])
            ->add('imageFilename', FileType::class, [
                'label' => 'Image (JPG / PNG file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '1M',
                        extensions: ['jpg', 'png'],
                        extensionsMessage: 'Please upload a valid image file.',
                    )
                ],
            ])
            -> add('category', EntityType::class, [
                'label'=> "Category",
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => "---Choose a category---",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
