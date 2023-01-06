<?php

namespace App\Repository;

use App\Entity\Holiday;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Holiday>
 *
 * @method Holiday|null find($id, $lockMode = null, $lockVersion = null)
 * @method Holiday|null findOneBy(array $criteria, array $orderBy = null)
 * @method Holiday[]    findAll()
 * @method Holiday[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HolidayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Holiday::class);
    }

    public function add(Holiday $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Holiday $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithActual(bool $showUnActual = false): null|array
    {
        $qb = $this->createQueryBuilder('holiday');
        if (!$showUnActual){
            $qb
                ->andWhere('holiday.startdate >= :search')
                ->setParameter('search', new \DateTime("now"))
            ;
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Holiday[] Returns an array of Holiday objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Holiday
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findNowUsersHoliday(User $user, \DateTime $date)
    {
        return $this->createQueryBuilder('holiday')
            ->andWhere('holiday.worker = :user')
            ->andWhere('holiday.startdate <= :nowDay')
            ->andWhere('holiday.enddate >= :nowDay')
            ->setParameters(['user'=>$user, 'nowDay'=>$date])
            ->getQuery()
            ->getResult()
        ;
    }
}
