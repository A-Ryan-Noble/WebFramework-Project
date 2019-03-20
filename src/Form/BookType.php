<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('barcode')
            ->add('author')
            ->add('genre')
            ->add('startingBid', MoneyType::class,[
                'scale' => 2,
                'currency' => 'EUR',
                'compound' => false,
            ])
            ->add('bid')
            ->add('bidAccepted')
            ->add('commentQuestion')
            ->add('answerQs')
            ->add('user',EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'username',
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
