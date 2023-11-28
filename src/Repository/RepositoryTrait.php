<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

trait RepositoryTrait
{
    public function getCount($field, $value)
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere("u.".$field." = :val")
            ->setParameters(["val" => $value])
            ->getQuery()
            ->getSingleScalarResult();
    }

}