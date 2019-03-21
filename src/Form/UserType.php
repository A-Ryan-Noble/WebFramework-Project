<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('roles')
            /*->add(
                'roles', ChoiceType::class, [
                    'choices' => [
                        'User' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN'
                    ],
                    'expanded' => true,
                    'multiple' => false,
                ]
            )*/
            ->add('password')
            ->add(' ', SubmitType::class, array('label' => 'Create User'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
