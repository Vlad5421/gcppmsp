<?php

namespace App\Services\CardService;

use App\Repository\CardRepository;
use Symfony\Component\HttpFoundation\Request;

class CardGetter
{
    private CardRepository $repository;

    public function __construct(CardRepository $repository)
    {
        $this->repository = $repository;
    }
    public function getFromUsers(array $users, Request $request) : array
    {
        $cards = [];
        foreach ($users as $user)
        {
            $cards = array_merge(
                $cards,
                $this->repository->findAllWithFilters(
                    user: $user,
                    withShowDeleted: $request->query->has('showDeleted'),
                    onlyFuture: $request->query->has('onlyFuture'),
                ));
        }

        return $cards;
    }
}