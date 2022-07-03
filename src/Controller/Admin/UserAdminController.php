<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserComplectFormType;
use App\Form\UserFormType;
use App\Repository\ComplectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAdminController extends AbstractController
{

    #[
        Route('/admin/user/edit/{id}', name: 'app_admin_user_edit')
    ]

    public function userAddComplect(Request $request, User $user, EntityManagerInterface $em): Response
    {
//        $user = $userRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            /** @var User $user */
            $user = $form->getData();


            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_booking_filials');

        }

        return $this->render('admin/service_admin/complect_user_add.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }

}