<?php

namespace Myks92\Vmc\Event\Model\Entity\Events;


use RuntimeException;
use Throwable;
use yii\db\StaleObjectException;

/**
 * Class EventRepository
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class EventRepository
{
    /**
     * @param Id $id
     * @return Event
     * @throws RuntimeException
     */
    public function get(Id $id): Event
    {
        if (!$event = Event::findOne($id->getValue())) {
            throw new RuntimeException('Model not found');
        }
        return $event;
    }

    /**
     * @param Event $event
     * @throws RuntimeException
     * @throws Throwable
     */
    public function add(Event $event): void
    {
        if (!$event->getIsNewRecord()) {
            throw new RuntimeException('Model not exists');
        }
        $event->insert(false);
    }

    /**
     * @param Event $event
     * @throws RuntimeException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function save(Event $event): void
    {
        if ($event->getIsNewRecord()) {
            throw new RuntimeException('Model not exists');
        }
        $event->update(false);
    }

    /**
     * @param Event $event
     * @throws RuntimeException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function delete(Event $event): void
    {
        if (!$event->delete()) {
            throw new RuntimeException('Deleting error.');
        }
    }
}