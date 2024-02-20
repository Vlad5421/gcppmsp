<?php

namespace App\Controller\Admin;


use App\Entity\User;
use App\Entity\UserService;
use App\Form\UserFormType;
use App\Form\UserServiceFormType;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\UserServiceRepository;
use App\Services\CollectionsGetter\UserCollectionsGetter;
use App\Services\CustomSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserAdminController extends AbstractController
{

    #[
        Route('/manage-panel/user/all', name: 'app_admin_user_all'),
        IsGranted('ROLE_SERVICE_ADMIN')
    ]
    public function adminArticles(UserRepository $userRepository, Request $request, PaginatorInterface $paginator, CustomSerializer $serialiser): Response
    {

        $users = $userRepository->findAllWithSearch($request->query->get('q') ? $request->query->get('q') : null);
        $us = $serialiser->serializeIt($users);

        $pagination = $paginator->paginate(
            $us,
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 50 /*limit per page*/
        );


        return $this->render('admin/list_entitys.html.twig', [
            'page' => '',
            'entity' => '_user',
            'collection' => $pagination,
            'exlude_columns' =>[],
        ]);
    }

    #[Route('/manage-panel/user/create', name: 'app_admin_user_create')]
    public function userCreate(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, UserRepository $ur): Response
    {
//        $user = $userRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $form->getData();
            $user_with_email = $ur->findBy(["email" => $user->getEmail()]);
            if (count($user_with_email) > 0){
                $this->addFlash('flash_message', '!!! Польователь с таким email зарегистрирован ранее.');

                return $this->redirectToRoute('app_admin_user_create');
            }
            $user->setPassword($passwordHasher->hashPassword($user, '%Gcppmsp_QW%'));


            $em->persist($user);
            $em->flush();
            $this->addFlash('flash_message', 'Польователь создан');

            return $this->redirectToRoute('app_admin_user_create');

        }

        return $this->render('admin/user_admin/user_create.twig', [
            'form' => $form->createView(),
            'page' => 'Регистрация работника'
        ]);
    }
    #[Route('/manage-panel/user/edit/{id}', name: 'app_admin_user_edit')]
    public function userEdit(User                        $user,
                             Request                     $request,
                             EntityManagerInterface      $em,
                             UserPasswordHasherInterface $passwordHasher,
                             UserServiceRepository       $usr,
                             CustomSerializer $serialiser,
                             UserCollectionsGetter $userCollectionsGetter,
    ): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $user->setPassword($passwordHasher->hashPassword($user, '%Gcppmsp_QW%'));
            $em->persist($user);
            $em->flush();

            $this->addFlash('flash_message', 'Польователь изменён');
            return $this->redirectToRoute('app_admin_user_all');
        }
        $uss = $userCollectionsGetter->getServices($user);
//        dd($uss);
        $sdsd = count($uss) > 0 ? $serialiser->serializeIt($uss): null;
        $resp_array = [
            'form' => $form->createView(),
            'page' => "Редактирование данных работника",
        ];
        if ($sdsd) $resp_array['services'] = $sdsd;

        return $this->render('admin/user_admin/user_create.twig', $resp_array);
    }

    #[Route('/manage-panel/user-service/create', name: 'app_admin_userservice_create')]
    public function userAddService(
        Request $request,
        EntityManagerInterface $em,
        UserServiceRepository $usRepo
    ): Response
    {
        $form = $this->createForm(UserServiceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var UserService $complect */
            $complect = $form->getData();

            $check = $usRepo->findOneBy(['worker' => $complect->getWorker(), "service" => $complect->getService()]);

            if ($check){
                $this->addFlash('flash_message', "!ВНИМАНИЕ. Этому специалисту уже назначена эта услуга");
            } else {
                $em->persist($complect);
                $em->flush();
                $this->addFlash('flash_message', 'Услуга назначена специалисту');
            }

            return $this->redirectToRoute('app_admin_userservice_create');

        }

        return $this->render('admin/user_admin/user_service_add.html.twig', [
            'form' => $form->createView(),
            'page' => 'Назначить услугу специалисту'
        ]);
    }

}