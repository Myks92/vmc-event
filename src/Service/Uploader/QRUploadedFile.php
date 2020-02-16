<?php

declare(strict_types=1);

namespace Myks92\Vmc\Event\Service\Uploader;


/**
 * Class QRFile
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class QRUploadedFile
{
    /**
     * @var string
     */
    private $content;
    /**
     * @var string
     */
    private $name;

    /**
     * File constructor.
     * @param string $content
     * @param string $name
     */
    public function __construct(string $content, string $name)
    {
        $this->content = $content;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}