<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Poster\Upload;


/**
 * Class Command
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Command
{
    /**
     * @var
     */
    public $id;
    /**
     * @var File
     */
    public $file;

    /**
     * Command constructor.
     * @param $id
     * @param File $file
     */
    public function __construct($id, File $file)
    {
        $this->id = $id;
        $this->file = $file;
    }
}