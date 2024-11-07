<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use App\Repository\VisitorRepository;
use App\Services\CardService\CardGetter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardAdminController extends AbstractController
{
    #[Route('/manage-panel/card/all', name: 'app_admin_card_all')]
    public function adminCards(UserRepository $userRepository, CardGetter $card_getter, Request $request, PaginatorInterface $paginator) : Response
    {

        // $card = $cardRepository->find(19);
        // dd($card->isDeleted());

        if ($request->query->get('q'))
        {
            $users = $userRepository->findAllWithSearch($request->query->get('q'));
        } else
        {
            $users = [null];
        }
        $cards = $card_getter->getFromUsers($users, $request);


        // $cards = [];
        // foreach ($users as $user)
        // {
        //     $cards = array_merge(
        //         $cards,
        //         $cardRepository->findAllWithFilters(
        //             user: $user,
        //             withShowDeleted: $request->query->has('showDeleted'),
        //             onlyFuture: $request->query->has('onlyFuture'),
        //         ));
        // }

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

    #[Route('/manage-panel/card/edit/{id}', name: 'app_admin_card_edit')]
    public function edit(Card $card) : Response
    {

        return $this->render('admin/card_admin/edit_cards.html.twig', [
            'page' => 'Запись на консультацию',
            'card' => $card,
        ]);
    }

    #[Route('/manage-panel/card/delete/{id}', name: 'app_admin_card_delete')]
    public function delete(Card $card, EntityManagerInterface $em, VisitorRepository $vr, CardRepository $cr) : Response
    {
        $visitor = $card->getVisitors();
        // $vr->remove($card->)
        // $cr->removeWithVisitor($card);
        $cr->remove($card);

        $this->addFlash('flash_message', 'Запись удалена');

        return $this->redirectToRoute("app_admin_card_all");
    }


}