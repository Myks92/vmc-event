<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Move;


/**
 * Class Command
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Command
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $dateFrom;
    /**
     * @var string
     */
    public $dateTo;

    /**
     * Command constructor.
     * @param int $id
     * @param string $dateFrom
     * @param string $dateTo
     */
    public function __construct($id, string $dateFrom, string $dateTo)
    {
        $this->id = $id;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
}