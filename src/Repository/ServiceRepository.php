<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Service $entity, bool $flush = true): void
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
    public function remove(Service $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAllWithSearch(?string $search, bool $withShowDeleted = false)
    {
        $qb = $this->createQueryBuilder('service');
        if ($search){
            $qb
                ->andWhere('service.name LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }
        if ($withShowDeleted){
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        return $qb->getQuery()->getResult();

    }
    public function findWithId(?int $id, bool $withShowDeleted = false)
    {
        $qb = $this->createQueryBuilder('service');
        if ($id){
            $qb
                ->andWhere('service.id = :id')
                ->setParameter('id', $id)
            ;
        }
        if ($withShowDeleted){
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        return $qb->getQuery()->getResult();

    }


}
