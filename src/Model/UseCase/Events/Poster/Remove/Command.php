<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Poster\Remove;


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
     * @var string
     */
    public $file;

    /**
     * Command constructor.
     * @param $id
     * @param string $file
     */
    public function __construct($id, string $file)
    {
        $this->id = $id;
        $this->file = $file;
    }
}