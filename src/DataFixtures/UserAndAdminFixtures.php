<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UserAndAdminFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $candidate = new User();
        $admin = new User();

        $candidate
            ->setEmail('mail@mail.com')
            ->setPassword(
                $this->passwordEncoder->encodePassword($candidate, 'password')
            );
        $admin
            ->setEmail('admin@mail.com')
            ->setPassword(
                $this->passwordEncoder->encodePassword($admin, 'password')
            )
            ->setIsAdmin(true);

        $manager->persist($candidate, $admin);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['userAndAdmin'];
    }
}
