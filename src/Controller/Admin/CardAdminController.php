<?php

namespace App\Controller\Admin;

use App\Repository\CardRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardAdminController extends AbstractController
{
    #[Route('/manage-panel/card/all', name: 'app_admin_card_all'), IsGranted('ROLE_SERVICE_ADMIN')]
    public function adminCards(UserRepository $userRepository, CardRepository $cardRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if ($request->query->get('q'))
            $users = $userRepository->findAllWithSearch($request->query->get('q'));
        else
            $users = null;
        if ($users){
            $cards = [];
            foreach ($users as $user) {
                $card_list = $cardRepository->findAllWithUser(
                    $user,
                    $request->query->has('showDeleted')
                );
                $cards = array_merge($cards, $card_list);
            }
        } else {
            $cards = $cardRepository->findAll();
        }


        $pagination = $paginator->paginate(
            $cards, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 25 /*limit per page*/
        );


        return $this->render('admin/card_admin/list_cards.html.twig', [
            'page' => 'Список записей',
            'collection' => $pagination,
        ]);
    }
}
