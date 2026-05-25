<?php

declare(strict_types=1);

namespace App\Controller\Admin\FormService;

use App\Entity\CustomForm;
use App\Form\CustomFormType;
use App\Repository\CustomFormRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_FORM_ADMIN")
 */
#[Route('/manage-panel/forms')]
class CustomFormController extends AbstractController
{
    #[Route('', name: 'app_admin_form_service_custom_form_index', methods: ['GET'])]
    public function index(CustomFormRepository $customFormRepository): Response
    {
        $forms = $customFormRepository->findAll();

        return $this->render('admin/form_admin/custom_form/index.html.twig', [
            'forms' => $forms,
        ]);
    }

    #[Route('/create', name: 'admin_form_service_custom_form_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customForm = new CustomForm();
        $form = $this->createForm(CustomFormType::class, $customForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customForm);
            $entityManager->flush();

            $this->addFlash('success', 'Форма успешно создана');

            return $this->redirectToRoute('app_admin_form_service_custom_form_index');
        }

        return $this->render('admin/form_admin/custom_form/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_form_service_custom_form_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CustomForm $customForm, EntityManagerInterface $entityManager): Response
    {

        // dd($customForm);
        $form = $this->createForm(CustomFormType::class, $customForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Форма успешно обновлена');

            return $this->redirectToRoute('app_admin_form_service_custom_form_index');
        }

        return $this->render('admin/form_admin/custom_form/edit.html.twig', [
            'form' => $form->createView(),
            'custom_form' => $customForm,
        ]);
    }

    // #[Route('/{id}/delete', name: 'admin_form_service_custom_form_delete', methods: ['POST'])]
    // public function delete(Request $request, CustomForm $customForm, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$customForm->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($customForm);
    //         $entityManager->flush();
    //         $this->addFlash('success', 'Форма успешно удалена');
    //     }

    //     return $this->redirectToRoute('app_admin_form_service_custom_form_index');
    // }
    #[Route('/{id}/delete', name: 'admin_form_service_custom_form_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $customForm = $entityManager->getRepository(CustomForm::class)->find($id);

        if (!$customForm) {
            $this->addFlash('error', 'Запрашиваемой формы не существует');
            return $this->redirectToRoute('app_admin_form_service_custom_form_index');
        } else {
            $entityManager->remove($customForm);
            $entityManager->flush();
            $this->addFlash('success', 'Форма успешно удалена');
        }

        // if ($this->isCsrfTokenValid('delete'.$customForm->getId(), $request->request->get('_token'))) {
        //     $entityManager->remove($customForm);
        //     $entityManager->flush();
        //     $this->addFlash('success', 'Форма успешно удалена');
        // } else {
        //     $this->addFlash('error', 'Неверный CSRF-токен');
        // }

        return $this->redirectToRoute('app_admin_form_service_custom_form_index');
    }
}