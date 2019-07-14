<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\JobCategoryRepository;
use App\Entity\JobOffer;
use App\Entity\Client;

class JobOfferType extends AbstractType
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
            ->add('reference')
            ->add('title')
            ->add('description')
            ->add('active', ChoiceType::class, [
                'choices' => [
                    'admin.misc.yes' => true,
                    'admin.misc.no' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('note')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'form.fulltime' => 'fulltime',
                    'form.partTime' => 'partTime',
                    'form.temporary' => 'temporary',
                    'form.freelance' => 'freelance',
                    'form.seasonal' => 'seasonal'
                ]
            ])
            ->add('location')
            ->add('salary', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'min' => '0',
                    'step' => '50',
                ],
            ])
            ->add('jobCategory', ChoiceType::class, [
                'choices' => $jobCategoriesByName
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'companyName'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
