<?php

namespace App\Repository;

use App\Entity\UserComplectReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserComplectReference|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserComplectReference|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserComplectReference[]    findAll()
 * @method UserComplectReference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserComplectReferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserComplectReference::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(UserComplectReference $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(UserComplectReference $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return UserComplectReference[] Returns an array of UserComplectReference objects
      */

    public function findByComplect($value)
    {
        return $this->createQueryBuilder('ucomref')
            ->andWhere('ucomref.Complect = :val')
            ->setParameter('val', $value)
            ->innerJoin('ucomref.worker', 'user')
            ->orderBy('ucomref.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?UserComplectReference
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
