<?php

namespace App\Repository;

use App\Entity\TeamAway;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TeamAway|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamAway|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamAway[]    findAll()
 * @method TeamAway[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamAwayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamAway::class);
    }

    // /**
    //  * @return TeamAway[] Returns an array of TeamAway objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeamAway
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
