<?php

namespace App\Repository;

use App\Entity\Team;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function getEchiquierPossibleTeams(Tournament $tournament)
    {
        $query = "SELECT t.id, t.rank, t.name, GROUP_CONCAT(t2.id, ',') as can_play_with
        FROM team t
        LEFT JOIN team t2 ON t.id != t2.id
        LEFT JOIN match m ON
            ((m.team_home_id = t.id AND m.team_away_id = t2.id)
            OR
            (m.team_away_id = t.id AND m.team_home_id = t2.id))
        WHERE t.tournament_id = ?
            AND t2.tournament_id = ?
            AND m.id IS NULL
        GROUP BY t.id";

        $rsm = new ResultSetMapping();
        $query = $this->getEntityManager()->createNativeQuery($query, $rsm);
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('rank', 'rank');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('can_play_with', 'can_play_with');
        $query->setParameter(1, $tournament->getId());
        $query->setParameter(2, $tournament->getId());


        $results = $query->getResult();
        $teams = $tournament->getTeams();
        $teamById = [];
        foreach ($teams as $team)
        {
            $teamById[$team->getId()] = $team;
        }
        foreach ($teams as $team)
        {
            foreach ($results as $result)
            {
                if ($result['id'] == $team->getId())
                {
                    $canPlays = explode(',', $result['can_play_with']);
                    foreach ($canPlays as $canPlay)
                    {
                        $team->addPossibleRival($teamById[$canPlay]);
                    }
                }
            }
        }
    }

    // /**
    //  * @return Team[] Returns an array of Team objects
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
    public function findOneBySomeField($value): ?Team
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
