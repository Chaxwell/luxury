<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\JobCategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    protected $jobCategoryRepository;

    public function __construct(JobCategoryRepository $jobCategoryRepository)
    {
        $this->jobCategoryRepository = $jobCategoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $jobCategoriesByName = [];

        foreach($this->jobCategoryRepository->findAll() as $jobCategory) {
            $jobCategoriesByName[$jobCategory->getName()] = strtolower($jobCategory->getName());
        }

        $builder
            ->add('email')
            ->add('password', PasswordType::class)
            ->add('passwordValidation', PasswordType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'admin.male' => 'male',
                    'admin.female' => 'female'
                ],
            ])
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
            ->add('experience', ChoiceType::class, [
                'choices' => [
                    'admin.06months' => '06months',
                    'admin.612months' => '612months',
                    'admin.12years' => '12years',
                    'admin.2years' => '2years',
                    'admin.5years' => '5years',
                    'admin.10years' => '10years',
                ]
            ])
            ->add('description')
            ->add('availability', ChoiceType::class, [
                'choices' => [
                    'admin.yes' => true,
                    'admin.no' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('jobCategory', ChoiceType::class, [
                'choices'  => $jobCategoriesByName
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
        ]);
    }
}
