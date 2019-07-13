<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAndAdminFixtures extends Fixture
{
    public function load(ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $candidate = new User();
        $admin = new User();

        $candidate
            ->setEmail('mail@mail.com')
            ->setPassword(
                $passwordEncoder->encodePassword($candidate, 'password')
            );
        $admin
            ->setEmail('admin@mail.com')
            ->setPassword(
                $passwordEncoder->encodePassword($admin, 'password')
            )
            ->setIsAdmin(true);

        $manager->persist($candidate, $admin);
        $manager->flush();
    }
}
