<?php

namespace App\Controller\UserPlace;

use App\Repository\UserRepository;
use App\Services\CardService\CardGetter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkerCardListController extends AbstractController
{
    #[Route('/user-place/worker/card/list', name: 'app_user-place_worker_card_list')]
    public function index(Request $request, UserRepository $ur, CardGetter $card_getter, PaginatorInterface $paginator) : Response
    {
        if ($request->query->get("workerid"))
        {
            $user_id = intval($request->query->get("workerid"));
        } else
        {
            (bool) $user_id = false;
        }
        // dd($request->query->get("workerid"), $user_id);

        if ($this->getUser() && ! in_array("ROLE_ADMIN", $this->getUser()->getRoles()) && ! in_array("ROLE_SERVICE_ADMIN", $this->getUser()->getRoles()))
        {
            // dd($this->getUser()->getRoles());
            $user_id = $this->getUser()->getId();
        }
        $worker = $user_id ? $ur->find($user_id) : null;
        $message = $user_id ? "Работник: " . $worker : "Нет пользователя";

        $cards = $card_getter->getFromUsers([$worker], $request);
        // dd($worker);

        $pagination = $paginator->paginate(
            $cards, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 25 /*limit per page*/
        );





        return $this->render('user_place/worker_card_list.html.twig', [
            "message" => $message,
            'page' => "user place",
            'collection' => $pagination,
            "worker" => $worker
        ]);
    }
}