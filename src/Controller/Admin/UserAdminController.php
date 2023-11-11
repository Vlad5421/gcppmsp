<?php

namespace App\Controller\Admin;


use App\Entity\Service;
use App\Entity\User;
use App\Entity\UserService;
use App\Form\UserComplectReferenceFormType;
use App\Form\UserFormType;
use App\Form\UserServiceFormType;
use App\Repository\ComplectRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\UserServiceRepository;
use App\Services\Admin\CsvReader;
use App\Services\Admin\ScheduleImporter;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserAdminController extends AbstractController
{

    #[
        Route('/admin/user/all', name: 'app_admin_users'),
        IsGranted('ROLE_SERVICE_ADMIN')
    ]
    public function adminArticles(UserRepository $userRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $pagination = $paginator->paginate(
            $userRepository->findAllWithSearch(
                $request->query->get('q'),
                $request->query->has('showDeleted')
            ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 5 /*limit per page*/
        );


        return $this->render('admin/user_admin/list_users.html.twig', [
            'page' => '',
            'collection' => $pagination,
        ]);
    }

    #[Route('/admin/user/create', name: 'app_admin_user_create')]
    public function userCreate(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
//        $user = $userRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            $user->setPassword($passwordHasher->hashPassword($user, '123456'));


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
    #[Route('/admin/user/edit/{id}', name: 'app_admin_user_edit')]
    public function userEdit(User $user, Request $request, ScheduleImporter $userMaker): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $userMaker->saveUser($form->getData());
            $this->addFlash('flash_message', 'Польователь изменён');
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/user_admin/user_create.twig', [
            'form' => $form->createView(),
            'page' => "Редактирование данных работника"
        ]);
    }

    #[Route('/admin/user-service/create', name: 'app_admin_userservice_create')]
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