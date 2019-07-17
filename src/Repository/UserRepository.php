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

    // REMOVE:
    public function listCandidatures(JobOffer $jobOffer, UserInterface $candidate): ?array
    {
        $listOfCandidatures = $this->connectionManager
            ->prepare("SELECT {$this->candidatureTable}.id FROM {$this->candidatureTable} WHERE {$this->candidatureTable}.user_id = ? AND {$this->candidatureTable}.job_offer_id = ?");
        $listOfCandidatures->execute(array($candidate->getId(), $jobOffer->getId()));

        return $listOfCandidatures->fetchAll();
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
