<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Status\Cancel;


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
    public $reason;

    /**
     * Command constructor.
     * @param $id
     * @param string $reason
     */
    public function __construct($id, string $reason)
    {
        $this->id = $id;
        $this->reason = $reason;
    }
}