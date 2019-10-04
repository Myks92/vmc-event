<?php

declare(strict_types=1);

namespace Myks92\Vmc\Event\Service\Uploader;


use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class FileUploader
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class FileUploader
{
    /**
     * @var string
     */
    private $basUrl;

    /**
     * FileUploader constructor.
     * @param string $basUrl
     */
    public function __construct(string $basUrl)
    {
        $this->basUrl = $basUrl;
    }

    /**
     * @param UploadedFile $file
     * @return File
     * @throws Exception
     */
    public function upload(UploadedFile $file): File
    {
        $path = $this->generateUrl();
        $name = time() . '.' . $file->getExtension();
        $fileName = $path . '/' . $name;
        FileHelper::createDirectory($path);
        $file->saveAs($fileName);
        return new File($path, $name, $file->size);
    }

    /**
     * @return string
     */
    public function generateUrl(): string
    {
        return $this->basUrl;
    }

    /**
     * @param string $name
     */
    public function remove(?string $name): void
    {
        if (is_file($fileName = $this->generateUrl() . '/' . $name)) {
            unlink($fileName);
        }
    }
}