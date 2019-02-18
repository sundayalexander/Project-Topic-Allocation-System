<?php

namespace App\Repository;

use App\Entity\AllocatedTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AllocatedTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllocatedTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllocatedTopic[]    findAll()
 * @method AllocatedTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllocatedTopicRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AllocatedTopic::class);
    }

    // /**
    //  * @return AllocatedTopic[] Returns an array of AllocatedTopic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AllocatedTopic
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
