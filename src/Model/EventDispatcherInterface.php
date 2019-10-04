<?php

namespace Myks92\Vmc\Event\Model;


/**
 * Interface EventDispatcherInterface
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
interface EventDispatcherInterface
{
    /**
     * @param array $events
     */
    public function dispatch(array $events): void;
}