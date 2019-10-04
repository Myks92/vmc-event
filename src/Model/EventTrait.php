<?php

namespace Myks92\Vmc\Event\Model;

/**
 * Trait EventTrait
 * @package Myks92\Vmc\Event\Model
 */
trait EventTrait
{
    /**
     * @var array
     */
    private $events = [];

    /**
     * @return array
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    /**
     * @param $event
     */
    protected function recordEvent($event): void
    {
        $this->events[] = $event;
    }
}