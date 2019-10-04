<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Status\Activate;


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
     * Command constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
}