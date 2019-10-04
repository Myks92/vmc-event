<?php

namespace Myks92\Vmc\Event\Model;


/**
 * Interface AggregateRoot
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
interface AggregateRoot
{
    /**
     * @return array
     */
    public function releaseEvents(): array;
}