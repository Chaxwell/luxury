<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('gender')
            ->add('firstName')
            ->add('lastName')
            ->add('phoneNumber')
            ->add('profilePicture')
            ->add('currentLocation')
            ->add('address')
            ->add('country')
            ->add('nationality')
            ->add('birthDate')
            ->add('birthPlace')
            ->add('passport')
            ->add('resume')
            ->add('experience')
            ->add('description')
            ->add('note')
            ->add('availability')
            // ->add('jobCategory')
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('isAdmin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
        ]);
    }
}
