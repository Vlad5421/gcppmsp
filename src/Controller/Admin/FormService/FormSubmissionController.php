<?php

declare(strict_types=1);

namespace App\Controller\Admin\FormService;

use App\Entity\CustomForm;
use App\Entity\FormSubmission;
use App\Repository\FormSubmissionRepository;
use App\Services\FormService\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
#[Route('/admin/forms')]
class FormSubmissionController extends AbstractController
{
    #[Route('/{id}/card', name: 'admin_form_service_form_submission_card', methods: ['GET'])]
    public function card(Request $request, CustomForm $form, FormSubmissionRepository $formSubmissionRepository): Response
    {
        $cardId = $request->query->getInt('card_id');
        
        if ($cardId) {
            $submission = $formSubmissionRepository->find($cardId);
            
            if (!$submission || $submission->getForm()->getId() !== $form->getId()) {
                throw $this->createNotFoundException('Запись не найдена');
            }
            
            return $this->render('admin/form_admin/form_submission/card_detail.html.twig', [
                'form' => $form,
                'submission' => $submission,
            ]);
        }
        
        $submissions = $formSubmissionRepository->findBy(['form' => $form], ['submittedAt' => 'DESC']);
        
        return $this->render('admin/form_admin/form_submission/card.html.twig', [
            'form' => $form,
            'submissions' => $submissions,
        ]);
    }

    #[Route('/user-place/form/{id}', name: 'user_place_form_show', methods: ['GET'])]
    public function showForm(CustomForm $form): Response
    {
        if (!$form->isIsActive()) {
            throw $this->createNotFoundException('Форма не найдена или не активна');
        }

        // Создаём форму на основе сущности CustomForm
        $formView = $this->createForm(CustomForm::class, $form)->createView();

        return $this->render('user_place/form/show.html.twig', [
            'form' => $formView,
            'page' => 'user place',
        ]);
    }

    #[Route('/user-place/form/{id}', name: 'user_place_form_submit', methods: ['POST'])]
    public function submitForm(Request $request, CustomForm $form, EntityManagerInterface $entityManager, FileUploader $formFileUploader): Response
    {
        if (!$form->isIsActive()) {
            throw $this->createNotFoundException('Форма не найдена или не активна');
        }
        
        $data = [];
        $formStructure = $form->getStructure();
        
        // Создаем запись формы для получения ID
        $submission = new FormSubmission();
        $submission->setForm($form);
        
        // Если пользователь авторизован, сохраняем его ID
        if ($this->getUser()) {
            $submission->setUserId($this->getUser()->getId());
        }
        
        $entityManager->persist($submission);
        $entityManager->flush(); // Сохраняем, чтобы получить ID записи
        
        // Обработка данных формы
        foreach ($formStructure as $field) {
            $fieldName = $field['name'];
            
            if ($field['type'] === 'file') {
                // Обработка файлов
                $files = $request->files->get('files', [])[$fieldName] ?? [];
                $uploadedFiles = [];
                
                if (!empty($files)) {
                    try {
                        // Подготовка конфигурации для загрузки файлов
                        $fieldConfig = [];
                        if (isset($field['maxFiles'])) {
                            $fieldConfig['maxFiles'] = $field['maxFiles'];
                        }
                        if (isset($field['allowedExtensions'])) {
                            $fieldConfig['allowedExtensions'] = $field['allowedExtensions'];
                        }
                        
                        // Загрузка файлов
                        $uploadedFiles = $formFileUploader->upload($files, $form->getId(), $submission->getId(), $fieldConfig);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Ошибка при загрузке файлов: ' . $e->getMessage());
                        // Удаляем запись формы при ошибке загрузки файлов
                        $entityManager->remove($submission);
                        $entityManager->flush();
                        return $this->redirectToRoute('user_place_form_show', ['id' => $form->getId()]);
                    }
                }
                
                $data[$fieldName] = $uploadedFiles;
            } else {
                $data[$fieldName] = $request->request->get($fieldName, '');
            }
        }
        
        // Обновляем данные записи формы
        $submission->setData($data);
        $entityManager->flush();
        
        $this->addFlash('success', 'Форма успешно отправлена');
        
        return $this->redirectToRoute('user_place_form_show', ['id' => $form->getId()]);
    }
}