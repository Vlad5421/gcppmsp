<?php

namespace App\Controller\Admin\UserService;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserDeletingController extends AbstractController
{
    #[Route('/manage-panel/user/delete/{id}', name: 'app_admin_user_delete')]
    public function index(User $user, Request $request, UserRepository $user_service) : Response
    {
        if ($request->query->get('confirmDeleting'))
        {
            // dd("жопа новый год");
            $user_service->remove($user);
            $this->addFlash('flash_message', 'Пользователь отключен');
            return $this->redirectToRoute("app_admin_user_all");
        }


        return $this->render('admin/user_admin/user_delete.twig', [
            'user' => $user,
            'page' => "deleting_user",
            'controller_name' => 'UserDeletingController',
        ]);
    }
}