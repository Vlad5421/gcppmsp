<?php


//////////
// Велосипедный сереализатор сущностей
/////////


namespace App\Services;

use App\Entity\Card;
use App\Entity\Collections;
use App\Entity\Filial;
use App\Entity\FilialService;
use App\Entity\Service;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CustomSerializer
{
    private Request $request;

    public function __construct(protected RequestStack $requestStack,)
    {
        $this->request = $this->requestStack->getCurrentRequest();
    }
    public function serializeIt(array $collection): array
    {
        if (!count($collection) > 0){
            return [];
        }
        $class = get_class($collection[0]);
        $colls = [];
        foreach ($collection as $entity){
//            dd(get_class($entity));
            $colls[] = $this->getArray($class, $entity);
        }
        return $colls;
    }

    protected function getArray(string $name, $entity): array
    {
        $protcol = $this->request->getPort() == "443" ? $protcol = "https" : "https";
        switch (true) {
            case $name == "App\Entity\Collections":
                /** @var Collections $entity */
                $arr = [
                    "id" => $entity->getId(),
                    "name" => $entity->getName(),
                    "parrent" => $entity->getCollection()?->getId(),
                ];
                break;
            case $name == "App\Entity\FilialService":
                /** @var FilialService $entity */
                /** @var Service $service */
                $service = $entity->getService();
                $arr= [
                    "id" => $service->getId(),
                    "name" => $service->getName(),
                    "duration" => $service->getDuration(),
                    "price" => $service->getPrice(),
                    "image" => $protcol . "://" . $this->request->getHttpHost(). "/uploads/logos/" . $service->getServiceLogo(),
                ];
                break;
            case ($name == "Proxies\__CG__\App\Entity\Filial" || $name =="App\Entity\Filial"):
                /** @var Filial $entity */
                $filial_img = $entity->getImage() ? $entity->getImage() : "logo-dom.jpg";
                $collection = $entity->getCollection();
                $arr= [
                    "id" => $entity->getId(),
                    "name" => $entity->getName(),
                    "address" => $entity->getAddress(),
                    "image" => $protcol . "://" . $this->request->getHttpHost(). "/uploads/gallery/" . $filial_img,
                    "collection" => $collection->getId(),
                    "collection_name" => $collection->getName(),
                    "service_count" => $entity->getFilialServices()->count(),
                ];
                break;
            case ($name == "Proxies\__CG__\App\Entity\User" || $name == "App\Entity\User"):
                /** @var User $entity */
                $arr= [
                    "id" => $entity->getId(),
                    "name" => $entity->getFIO(),
                    "email" => $entity->getEmail(),
                ];
                break;
            case $name == "App\Entity\Card":
                /** @var Card $entity */
                $arr= [
                    "start" => $entity->getStart(),
                    "end" => $entity->getEndTime(),
                ];
                break;
            case ($name == "Proxies\__CG__\App\Entity\Service" || $name == "App\Entity\Service" ):
                /** @var Service $entity */
                $arr= [
                    "id" => $entity->getId(),
                    "name" => $entity->getName(),
                    "duration" => $entity->getDuration(),
                    "price" => $entity->getPrice(),
                    "image" => $protcol . "://" . $this->request->getHttpHost(). "/uploads/logos/" . $entity->getServiceLogo(),
                ];
                break;
        }

        return $arr;
    }
}