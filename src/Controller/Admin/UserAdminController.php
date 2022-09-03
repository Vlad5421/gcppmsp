<?php

namespace App\Controller\Admin;


use App\Entity\Service;
use App\Entity\User;
use App\Form\UserComplectReferenceFormType;
use App\Form\UserFormType;
use App\Repository\ComplectRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            'page' => 'Список услуг',
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
            'page' => 'Создать услугу'
        ]);
    }
    #[Route('/admin/user/edit/{id}', name: 'app_admin_user_edit')]
    public function userEdit(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();
            $user->setPassword($passwordHasher->hashPassword($user, '123456'));


            $em->persist($user);
            $em->flush();
            $this->addFlash('flash_message', 'Польователь изменён');

            return $this->redirectToRoute('app_admin_users');

        }

        return $this->render('admin/user_admin/user_create.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }

    #[Route('/admin/user-complect/create', name: 'app_admin_user_complect_create')]
    public function userAddComplect(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ComplectRepository $complectRepository,
    ): Response
    {
        $form = $this->createForm(UserComplectReferenceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
//            dd($data);
//
//            $user = $userRepository->findOneBy(['FIO' => $data['FIO']]);
//            $complect = $complectRepository->findOneBy(['complect' => $data['cmplect_name']]);
//            dd($user);
//
//            /** @var UserComplectReference $ucomref */
//            $ucomref = (new UserComplectReference())
//                ->setComplect($complect)
//                ->setWorker($user);
//            dd($ucomref);

            $em->persist($data);
            $em->flush();
            $this->addFlash('flash_message', 'Комплект с услугой добавлен к пользователю');

            return $this->redirectToRoute('app_admin_user_complect_create');

        }

        return $this->render('admin/service_admin/complect_user_add.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }

}