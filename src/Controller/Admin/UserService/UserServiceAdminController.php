<?php

namespace App\Controller\Admin\UserService;

use App\Repository\UserServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserServiceAdminController extends AbstractController
{

    #[Route('/manage-panel/userservice/delete/{userId}', name: 'app_admin_userservice_delete', methods: ['POST'])]
    public function userserviceDelete(
        int $userId,
        Request $request,
        UserServiceRepository $userServiceRepository,
        EntityManagerInterface $entityManager
    ): RedirectResponse {
        // Получаем ID услуги из тела POST-запроса
        $serviceId = $request->request->get('serviceId');

        if (! $serviceId)
        {
            $massage = 'ID услуги не указан';
            // return $this->json([
            //     'success' => false,
            //     'message' => 'ID услуги не указан'
            // ], Response::HTTP_BAD_REQUEST);
        }

        // Ищем сущность UserService по обоим ID
        $userService = $userServiceRepository->findOneBy([
            'worker' => $userId,
            'service' => $serviceId
        ]);

        if (! $userService instanceof UserService)
        {
            $massage = 'Связь пользователь-услуга не найдена';
            // return $this->json([
            //     'success' => false,
            //     'message' => 'Связь пользователь-услуга не найдена'
            // ], Response::HTTP_NOT_FOUND);
        }

        try
        {
            // Удаляем сущность
            $entityManager->remove($userService);
            $entityManager->flush();

            $massage = 'Связь успешно удалена';
            // return $this->json([
            //     'success' => true,
            //     'message' => 'Связь успешно удалена'
            // ]);
        } catch (\Exception $e)
        {
            // Обработка ошибок
            $message = 'Ошибка при удалении: '.$e->getMessage();
            // return $this->json([
            //     'success' => false,
            //     'message' => 'Ошибка при удалении: '.$e->getMessage()
            // ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->addFlash('flash_message', $massage);
        return $this->redirectToRoute("app_admin_user_edit", ['id' => $userId]);


    }



}