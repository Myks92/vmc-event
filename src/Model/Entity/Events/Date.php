<?php


namespace Myks92\Vmc\Event\Model\Entity\Events;

use DateTimeImmutable;

/**
 * Class Date
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Date
{
    /**
     * @var DateTimeImmutable
     */
    private $from;
    /**
     * @var DateTimeImmutable
     */
    private $to;

    /**
     * Date constructor.
     * @param DateTimeImmutable $from
     * @param DateTimeImmutable $to
     */
    public function __construct(DateTimeImmutable $from, DateTimeImmutable $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getFrom(): DateTimeImmutable
    {
        return $this->from;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getTo(): DateTimeImmutable
    {
        return $this->to;
    }
}