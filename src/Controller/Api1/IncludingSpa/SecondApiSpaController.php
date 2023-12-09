<?php

namespace App\Controller\Api1\IncludingSpa;

use App\Services\CustomSerializer;
use App\Services\UniversalGetData\SpaMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecondApiSpaController  extends AbstractController
{
    #[Route('/api1/spa2/get-collections', name: 'api1_spa2_get-collections', methods: "GET")]
    public function getCollections(CustomSerializer $serializer, SpaMaker $spaMaker, Request $request): Response
    {
        $filials= $spaMaker->getFilialsFromCollection( $request->query->get("parrent") );
//        dd($filials);

        return $this->json([
            "collections" => $serializer->serializeIt(  $spaMaker->getCollections( $request->query->get("parrent") ) ),
            "filials" => $serializer->serializeIt($filials),
        ]);
    }
}