<?php

namespace App\Repository;

use App\Entity\Filial;
use App\Entity\FilialService;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FilialService>
 *
 * @method FilialService|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilialService|null
 * findOneBy(array $criteria, array $orderBy = null)
 * @method FilialService[]    findAll()
 * @method FilialService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilialServiceRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilialService::class);
    }

    public function add(FilialService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FilialService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
     public function findFilialsFromService(Service $service):array
     {
         $fil_ser = $this->findBy(["service"=>$service]);
         $filials = [];
         foreach ($fil_ser as $item) {
             $filials[] = $item->getFilial();
         }
         return $filials;
     }
    public function findServicesFromFilial(Filial $filial):array
    {
        $fil_ser = $this->findBy(["filial"=>$filial]);
        $srvices = [];
        foreach ($fil_ser as $item) {
            $srvices[] = $item->getService();
        }
        return $srvices;
    }
    public function findServiceFilialReference(Service $service, Filial $filial)
    {
        return $this->findBy(["service"=>$service, "filial"=>$filial]);
    }

}
