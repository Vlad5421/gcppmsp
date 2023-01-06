<?php

namespace App\Repository;

use App\Entity\SchedyleInterval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SchedyleInterval>
 *
 * @method SchedyleInterval|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchedyleInterval|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchedyleInterval[]    findAll()
 * @method SchedyleInterval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchedyleIntervalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchedyleInterval::class);
    }

    public function add(SchedyleInterval $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SchedyleInterval $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SchedyleInterval[] Returns an array of SchedyleInterval objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SchedyleInterval
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
