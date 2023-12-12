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
        $collections = $spaMaker->getCollectionsFromServiceOrParrent($request->query->get("service"), $request->query->get("parrent"));
        $filials = $spaMaker->getFilialsFromCollectionAndService( $request->query->get("parrent"), $request->query->get("service") );


        return $this->json([
            "collections" => $serializer->serializeIt($collections),
            "filials" => $serializer->serializeIt($filials),
        ]);
    }

    #[Route('/api1/spa2/get-noemptycard', name: 'api1_spa2_get-noemptycard', methods: "GET")]
    public function getNoemptycard(CustomSerializer $serializer, SpaMaker $spaMaker, Request $request): Response
    {
        $cards = $spaMaker->getNoEmptyCards(new \DateTime("-10 min"));
        dd($cards);

        return $this->json($cards);
    }
}