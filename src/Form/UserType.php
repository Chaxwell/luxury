<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\JobCategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    protected $jobCategoryRepository;

    public function __construct(JobCategoryRepository $jobCategoryRepository)
    {
        $this->jobCategoryRepository = $jobCategoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $jobCategoriesByName = [];

        foreach ($this->jobCategoryRepository->findAll() as $jobCategory) {
            $jobCategoriesByName[$jobCategory->getName()] = strtolower($jobCategory->getName());
        }

        $builder
            ->add('email')
            ->add('password', PasswordType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'form.male' => 'male',
                    'form.female' => 'female'
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
            ->add('resumeFile', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '10240k',
                        'mimeTypes' => [
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload an image.'
                    ])
                ],
            ])
            ->add('experience', ChoiceType::class, [
                'choices' => [
                    'form.06months' => '06months',
                    'form.612months' => '612months',
                    'form.12years' => '12years',
                    'form.2years' => '2years',
                    'form.5years' => '5years',
                    'form.10years' => '10years',
                ]
            ])
            ->add('description')
            ->add('note')
            ->add('availability', ChoiceType::class, [
                'choices' => [
                    'admin.misc.yes' => true,
                    'admin.misc.no' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('jobCategory', ChoiceType::class, [
                'choices'  => $jobCategoriesByName
            ])
            ->add('isAdmin', ChoiceType::class, [
                'choices' => [
                    'admin.misc.yes' => true,
                    'admin.misc.no' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // 'allow_extra_fields' => true,
        ]);
    }
}
