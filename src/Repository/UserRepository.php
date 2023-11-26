<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
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
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAllWithSearch(?string $search=null, bool $withShowDeleted = false)
    {

        $qb = $this->createQueryBuilder('user');
        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        if ($search){
            $qb
                ->andWhere('user.FIO LIKE :search')
                ->setParameters(['search' => "%$search%"])
            ;
        }
        if ($withShowDeleted){
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        return $qb->getQuery()->getResult();

    }
    public function findOneByFioLike($search): User|false
    {
        // Вернет ТОЛЬКО первого найденого, если 0, тогда веренёт false
        $qb = $this->createQueryBuilder('user')
            ->andWhere("user.FIO LIKE :serch")
            ->setParameter('serch', "%$serch%")
            ->getQuery()->getResult()
        ;

        if (count($qb) > 0 ) return $qb[0];


        else return false;
    }
}
