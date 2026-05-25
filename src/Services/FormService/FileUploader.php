<?php

declare(strict_types=1);

namespace App\Services\FormService;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Сервис для обработки загрузки файлов в формах
 */
class FileUploader
{
    private string $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Загрузка файлов
     * 
     * @param UploadedFile[] $files Массив загружаемых файлов
     * @param int $formId ID формы
     * @param int $submissionId ID записи формы
     * @param array $fieldConfig Конфигурация поля (maxFiles, allowedExtensions)
     * @return array Массив путей к загруженным файлам
     * @throws \Exception Если произошла ошибка при загрузке
     */
    public function upload(array $files, int $formId, int $submissionId, array $fieldConfig = []): array
    {
        $uploadedFiles = [];
        
        // Проверка максимального количества файлов
        $maxFiles = $fieldConfig['maxFiles'] ?? 5;
        if (count($files) > $maxFiles) {
            throw new \Exception("Превышено максимальное количество файлов: {$maxFiles}");
        }
        
        // Проверка разрешенных расширений
        $allowedExtensions = $fieldConfig['allowedExtensions'] ?? [];
        
        foreach ($files as $file) {
            // Проверка расширения файла
            if (!empty($allowedExtensions)) {
                $fileExtension = $file->guessClientExtension();
                if (!$fileExtension || !in_array(strtolower($fileExtension), $allowedExtensions)) {
                    throw new \Exception("Файл {$file->getClientOriginalName()} имеет недопустимое расширение");
                }
            }
            
            // Проверка MIME-типа для безопасности
            $mimeType = $file->getMimeType();
            if (!$this->isValidMimeType($mimeType)) {
                throw new \Exception("Файл {$file->getClientOriginalName()} имеет недопустимый MIME-тип");
            }
            
            // Проверка размера файла (максимум 5МБ)
            if ($file->getSize() > 5 * 1024 * 1024) {
                throw new \Exception("Файл {$file->getClientOriginalName()} превышает максимальный размер 5МБ");
            }
            
            // Создание директории для загрузки
            $uploadDir = sprintf('%s/forms/%d/%d', $this->getTargetDirectory(), $formId, $submissionId);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Генерация уникального имени файла
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessClientExtension();
            
            try {
                $file->move($uploadDir, $fileName);
                $uploadedFiles[] = sprintf('/uploads/forms/%d/%d/%s', $formId, $submissionId, $fileName);
            } catch (FileException $e) {
                throw new \Exception("Ошибка при загрузке файла: ". $e->getMessage());
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Проверка допустимых MIME-типов
     */
    private function isValidMimeType(string $mimeType): bool
    {
        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
            'image/gif',
            'text/plain',
            'application/zip',
            'application/x-rar-compressed',
        ];
        
        return in_array($mimeType, $allowedMimeTypes);
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}