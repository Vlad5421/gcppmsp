<?php

namespace App\Repository;

use App\Entity\Filial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Filial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Filial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Filial[]    findAll()
 * @method Filial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilialRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    private CollectionsRepository $collectionsRepository;

    public function __construct(ManagerRegistry $registry, CollectionsRepository $collectionsRepository)
    {
        parent::__construct($registry, Filial::class);
        $this->collectionsRepository = $collectionsRepository;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Filial $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param string $column
     * @return null|Filial[]
     */
    public function findAllSort(string $column, $search = null)
    {
        $qb = $this->createQueryBuilder('filial');
        if ($search){
            $collections = $this->collectionsRepository->findOneOrAllByName($search);
            $id_one = $collections[0]->getId();
            $qb->andWhere("filial.collection = $id_one");
            for ($i = 1; $i < count($collections); $i++) {
                    $col_id = $collections[$i]->getId();
                    $qb->orWhere("filial.collection = $col_id");
            }

        }

        return $qb
            ->orderBy("filial.".$column, 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Filial $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Filial[] Returns an array of Filial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Filial
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
