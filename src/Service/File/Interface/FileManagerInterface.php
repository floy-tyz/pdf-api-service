<?php

namespace App\Service\File\Interface;

use App\Entity\File;

interface FileManagerInterface
{
    /**
     * Получить абсолютный путь до файла
     * @return string абсолютный путь к файлу
     */
    public function getFileAbsolutePath(string $filepath): string;

    /**
     * Заполнить сущность исходя из параметров файла
     */
    public function parseAndFillFileEntity(File $file, string $filePath): void;

    /**
     * Переместить файл в хранилище
     * @param string $fileUuid
     * @param string $filepath
     * @param string $filename
     * @param string|null $extension
     * @return string относительный путь к файлу
     */
    public function moveFileToStorage(string $fileUuid, string $filepath, string $filename, ?string $extension): string;

    /**
     * Получить путь до директории временных файлов
     */
    public function getTempDirectoryPath(): string;

    /**
     * Получить путь до временного файла
     */
    public function getTempFilePath(): string;

    /**
     * Удалить файл или директорию
     */
    public function remove(string $path): void;
}
