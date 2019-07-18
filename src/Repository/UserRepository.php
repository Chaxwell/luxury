<?php

namespace App\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\User;
use App\Entity\JobOffer;
use App\Entity\Candidature;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
        $this->em = $this->getEntityManager();
        $this->connectionManager = $this->getEntityManager()->getConnection();
        $this->userTable = $this->em->getClassMetadata(User::class)->getTableName();
        $this->candidatureTable = $this->em->getClassMetadata(Candidature::class)->getTableName();
    }

    public function countCandidates(): int
    {
        $listOfCandidates = $this->connectionManager
            ->prepare("SELECT COUNT(*) as count FROM {$this->userTable}");
        $listOfCandidates->execute();

        return $listOfCandidates->fetch()['count'];
    }

    public function isProfileComplete(UserInterface $candidate): bool
    {
        $isProfileComplete = $this->connectionManager
            ->prepare("SELECT is_profile_complete FROM {$this->userTable} WHERE id = ?");
        $isProfileComplete->execute(array($candidate->getId()));

        if ($isProfileComplete->fetch()['is_profile_complete'] === null) {
            return $this->checkProfile($candidate);
        } else {
            return true;
        }
    }

    /**
     * Returns true if it found the profile to be complete, else returns false.
     */
    public function checkProfile(UserInterface $candidate): bool
    {
        $userInfos = $this->connectionManager
            ->prepare("SELECT gender, first_name, last_name, profile_picture, current_location, address, country, nationality, birth_place, passport, resume, experience, description, job_category FROM {$this->userTable} WHERE id = ?");
        $userInfos->execute(array($candidate->getId()));
        $userInfosFetched = $userInfos->fetch();

        $error = 0;

        foreach (array_values($userInfosFetched) as $userInfo) {
            if ($userInfo === null) {
                $error += 1;
            }
        }

        if ($error === 0) {
            $makeProfileComplete = $this->connectionManager
                ->prepare("UPDATE {$this->userTable} SET is_profile_complete = 1 WHERE {$this->userTable}.id = ?");
            $makeProfileComplete->execute(array($candidate->getId()));

            return true;
        } else {
            return false;
        }
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
