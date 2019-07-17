<?php

namespace App\Form;

use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use App\Repository\JobCategoryRepository;
use App\Entity\User;

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
                // 'allow_delete' => false,
                // 'download_uri' => false,
                // 'download_label' => false,
            ])
            ->add('passportFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => true,
            ])
            ->add('resumeFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => true,
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
            ->add('availability', ChoiceType::class, [
                'choices' => [
                    'misc.yes' => true,
                    'misc.no' => false,
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
