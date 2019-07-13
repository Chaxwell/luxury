<?php

namespace App\DataFixtures;

use App\Entity\JobCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class JobCategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $jobCategories = [
            'Commercial',
            'Retail sales',
            'Creative',
            'Technology',
            'Marketing & PR',
            'Fashion & luxury',
            'Management & HR'
        ];

        foreach ($jobCategories as $category) {
            $jobCategory = new JobCategory();
            $jobCategory->setName($category);
            $manager->persist($jobCategory);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['jobCategory'];
    }
}
