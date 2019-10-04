<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Place\Assign;


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
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $street;
    /**
     * @var int
     */
    public $city;

    /**
     * Command constructor.
     * @param int $event
     * @param string $name
     * @param string $street
     * @param int $city
     */
    public function __construct(int $event, string $name, string $street, int $city)
    {
        $this->name = $name;
        $this->street = $street;
        $this->city = $city;
        $this->event = $event;
    }
}