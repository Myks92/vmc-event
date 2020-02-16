<?php

declare(strict_types=1);


namespace Myks92\Vmc\Event\Service\Uploader;


use Endroid\QrCode\QrCode;
use yii\base\Exception;
use yii\helpers\FileHelper;

class QRUploader
{
    /**
     * @var string
     */
    private $baseUrl;
    /**
     * @var QrCode
     */
    private $qrCode;

    /**
     * FileUploader constructor.
     * @param string $baseUrl
     * @param QrCode $qrCode
     */
    public function __construct(string $baseUrl, QrCode $qrCode)
    {
        $this->baseUrl = $baseUrl;
        $this->qrCode = $qrCode;
    }

    /**
     * @param QRUploadedFile $file
     * @return File
     * @throws Exception
     */
    public function upload(QRUploadedFile $file): File
    {
        $path = $this->generateUrl();
        $name = $file->getName() . '.png';
        $fileName = $path . '/' . $name;

        FileHelper::createDirectory($path);

        $qrCode = $this->qrCode;
        $qrCode->setText($file->getContent());
        $qrCode->setSize(500);
        $qrCode->setEncoding('UTF-8');
        $qrCode->writeFile($fileName);

        return new File($path, $name, $qrCode->getSize());
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

    /**
     * @return string
     */
    public function generateUrl(): string
    {
        return $this->baseUrl;
    }
}