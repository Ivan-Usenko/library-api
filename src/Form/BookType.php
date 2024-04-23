<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                'multiple' => true,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Книга повинна мати хоча б одного автора!'
                    ])
                ]
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'label' => 'Жанри',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'constraints' => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Книга повинна мати хоча б один жанр!'
                    ])
                ]
            ])
            ->add('releaseDate', null, [
                'widget' => 'single_text',
                'label' => 'Дата виходу'
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
