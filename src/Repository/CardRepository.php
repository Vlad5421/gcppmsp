<?php

namespace App\Repository;

use App\Entity\Card;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Card $entity, bool $flush = true): void
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
    public function remove(Card $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getTrueCards(int $specialist_id, string $date, int $session_id)
    {
        $qb = $this->createQueryBuilder('card')
            ->andWhere('card.specialist = :specialist')
            ->andWhere('card.date = :date')
            ->andWhere('card.session = :session')
            ->setParameter('specialist', $specialist_id)
            ->setParameter('date', $date)
            ->setParameter('session', $session_id)
            ;
        $query = $qb->getQuery();

        return ($query->execute()) ;

    }

    public function findAllWithUser(?User $user, bool $withShowDeleted = false)
    {
        $yesterday = new \DateTime('-1 day');
        $qb = $this->createQueryBuilder('card')
            ->andWhere('card.date > :date')
            ->setParameter('date', $yesterday)
        ;

        if ($user){
            $qb->andWhere('card.specialist = :user')->setParameter('user', $user->getId());
        }
        if ($withShowDeleted){
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        return $qb
            ->orderBy('card.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
