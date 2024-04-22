<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Назва книги...'
                ]
            ])
            ->add('description', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Опис книги...'
                ]
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'label' => 'Автори',
                'choice_label' => function (Author $author) {
                    return $author->getInitials();
                },
                'multiple' => true
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'label' => 'Жанри',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
