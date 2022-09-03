<?php

namespace App\Repository;

use App\Entity\Complect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Complect|null find($id, $lockMode = null, $lockVersion = null)
 * @method Complect|null findOneBy(array $criteria, array $orderBy = null)
 * @method Complect[]    findAll()
 * @method Complect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Complect::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Complect $entity, bool $flush = true): void
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
    public function remove(Complect $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
    * @return Complect[] Returns an array of Complect objects
    */
    public function findAllByFilial($value)
    {
        return $this->createQueryBuilder('complect')
            ->andWhere('complect.filial = :val')
            ->setParameter('val', $value)
            ->leftJoin('complect.service', 'service')
            ->addSelect('service')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findNoDelited()
    {
        $qb = $this->createQueryBuilder('complect');
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findAllWithSearch(?string $search, bool $withShowDeleted = false)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');
        $qb = $this->createQueryBuilder('complect')->getQuery()->getResult();
        $this->getEntityManager()->getFilters()->enable('softdeleteable');
        if ($search){
            $qb
                ->andWhere('complect.name LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }
        if ($withShowDeleted){
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        return $qb;

    }


}
