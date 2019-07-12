<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\JobOffer;
use App\Repository\JobCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

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
                    'admin.yes' => true,
                    'admin.no' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('note')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'admin.fulltime' => 'fulltime',
                    'admin.partTime' => 'partTime',
                    'admin.temporary' => 'temporary',
                    'admin.freelance' => 'freelance',
                    'admin.seasonal' => 'seasonal'
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
            // ->add('closedAt')
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
