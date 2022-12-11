<?php

namespace App\Repository;

use App\Entity\ScheduleInterval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScheduleInterval>
 *
 * @method ScheduleInterval|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduleInterval|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduleInterval[]    findAll()
 * @method ScheduleInterval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleIntervalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduleInterval::class);
    }

    public function add(ScheduleInterval $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ScheduleInterval $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ScheduleInterval[] Returns an array of ScheduleInterval objects
//     */
    public function findBySchedule($value): array
    {
        for ($day = 1; $day<=7; $day++){
            $intervals[$day] = $this->createQueryBuilder('si')
                ->andWhere("si.schedule = :val")
                ->setParameter("val", $value)
                ->andWhere("si.day = $day")
                ->orderBy('si.start', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }
        return $intervals;
    }

//    public function findOneBySomeField($value): ?ScheduleInterval
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
