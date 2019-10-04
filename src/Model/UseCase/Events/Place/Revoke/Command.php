<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Place\Revoke;


/**
 * Class Command
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Command
{
    /**
     * @var int
     */
    public $event;
    /**
     * @var int
     */
    public $id;

    /**
     * Command constructor.
     * @param int $id
     * @param int $event
     */
    public function __construct(int $event, int $id)
    {
        $this->event = $event;
        $this->id = $id;
    }
}