<?php

namespace Myks92\Vmc\Event\Model;

/**
 * Class Flusher
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Flusher
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Flusher constructor.
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param AggregateRoot ...$roots
     */
    public function flush(AggregateRoot ...$roots): void
    {
        foreach ($roots as $root) {
            $this->dispatcher->dispatch($root->releaseEvents());
        }
    }
}