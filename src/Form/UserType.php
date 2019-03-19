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
//            ->add('roles')
//            ->add('user_roles', ChoiceType::class, [
//                'choices' => [
//                    'User' => '[ROLE_USER]',
//                    'Admin' => '[ROLE_ADMIN]'
//                ]], ['property_path' => false])

            ->add(
                'roles', ChoiceType::class, [
                    'choices' => [
                        'User' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN'
                    ],
                    'expanded' => true,
                    'multiple' => false,
                ]
            )
            ->add('password')
            ->add(' ', SubmitType::class, array('label' => 'Create User'))
        ;
    }

    /*


    attempt 3:

     ->add(
                'roles',  ChoiceType::class, [
                'choices' => ['ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_USER' => 'ROLE_USER']
            ])

    attempt 1:

    ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_ADMIN'=> ["ROLE_ADMIN"],
                    'ROLE_USER'=> ["ROLE_USER"],
                ]
            ])


    attempt 2:

     ->add('roles', ChoiceType::class, array(
                    'choices' => array(
                        'ROLE_ADMIN'=> 'ROLE_ADMIN',
                        'ROLE_USER'=> 'ROLE_USER',
                    )
                )
            )

     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
