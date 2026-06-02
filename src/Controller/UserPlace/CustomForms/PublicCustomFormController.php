<?php
declare(strict_types=1);

namespace App\Controller\UserPlace\CustomForms;

use App\Entity\CustomForm;
use App\Entity\FormSubmission;
use App\Repository\FormSubmissionRepository;
use App\Services\FormService\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





#[Route('/user-place/forms')]
class PublicCustomFormController extends AbstractController
{

    #[Route('/show/{number}', name: 'user_place_form_show', methods: ['GET'])]
    public function showForm(int $number): Response
    {

        return $this->render('user_place/form/show.html.twig', [
            'page' => 'user place',
            'number' => $number,
        ]);
    }


    #[Route('/get-structure/{id}', name: 'user_place_form_get_structure', methods: ['GET'])]
    public function getFormStructure(CustomForm $form): Response
    {
        if (!$form->isIsActive()) {
            throw $this->createNotFoundException('Форма не найдена или не активна');
        }
        return $this->json($form,200);
    }

    #[Route('/submit-form/{id}', name: 'user_place_form_submit', methods: ['POST'])]
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
            $fieldName = $field['label'];
            
            if ($field['type'] === 'file') {
                // Обработка файлов
                $files = $request->files->get('files', []) ?? [];
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
                        return $this->redirectToRoute('user_place_form_show', ['number' => $form->getId()]);
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
        
        return $this->redirectToRoute('user_place_form_show', ['number' => $form->getId()]);
    }
}