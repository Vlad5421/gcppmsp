<?php

namespace App\Controller\UserPlace;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkerCardListController extends AbstractController
{
    #[Route('/user-place/worker/card/list', name: 'app_user-place_worker_card_list')]
    public function index(Request $request, UserRepository $ur) : Response
    {
        if ($request->query->get("workeid"))
        {
            $user_ud = intval($request->query->get("workeid"));
        } else
        {
            $user_id = false;
        }
        if ($this->getUser())
        {
            $user_id = $this->getUser()->getId();
        }

        $message = $user_id ? "Работник: " . $ur->find($user_id) : "Нет пользователя";
        return $this->render('user_place/worker_card_list.html.twig', [
            'controller_name' => 'WorkerCardListController',
            "message" => $message,
            'page' => "user place",
        ]);
    }
}