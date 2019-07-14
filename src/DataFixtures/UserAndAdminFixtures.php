<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;

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
            )
            ->setCreatedAt()
            ->setUpdatedAt();
        $admin
            ->setEmail('admin@mail.com')
            ->setPassword(
                $this->passwordEncoder->encodePassword($admin, 'password')
            )
            ->setIsAdmin(true)
            ->setCreatedAt()
            ->setUpdatedAt();

        $manager->persist($candidate);
        $manager->persist($admin);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['userAndAdmin'];
    }
}
