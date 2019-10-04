<?php

declare(strict_types=1);

namespace Myks92\Vmc\Event\Model\UseCase\Events\Poster\Upload;


/**
 * Class File
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class File
{
    public $path;
    public $name;
    public $size;

    public function __construct(string $path, string $name, int $size)
    {
        $this->path = $path;
        $this->name = $name;
        $this->size = $size;
    }
}