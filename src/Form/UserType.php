<?php

namespace App\Form;

use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use App\Repository\JobCategoryRepository;
use App\Entity\User;

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
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'form.male' => 'male',
                    'form.female' => 'female'
                ],
            ])
            ->add('firstName')
            ->add('lastName')
            ->add('phoneNumber')
            ->add('currentLocation')
            ->add('address')
            ->add('country')
            ->add('nationality')
            ->add('birthDate')
            ->add('birthPlace')
            ->add('profilePictureFile', VichImageType::class, [
                'required' => false,
                'image_uri' => false,
            ])
            ->add('passportFile', VichFileType::class, [
                'required' => false,
            ])
            ->add('resumeFile', VichFileType::class, [
                'required' => false,
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

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();

                if(!$user || $user->getId() === null) {
                    $form
                        ->add('password', PasswordType::class)
                        ->add('passwordValidation', PasswordType::class);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // 'allow_extra_fields' => true,
        ]);
    }
}
