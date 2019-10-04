<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Status\Reject;


use DomainException;
use Myks92\Vmc\Event\Model\Entity\Events\EventRepository;
use Myks92\Vmc\Event\Model\Entity\Events\Id;
use Myks92\Vmc\Event\Model\Flusher;
use RuntimeException;
use Throwable;
use yii\db\StaleObjectException;

/**
 * Class Handler
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class Handler
{
    /**
     * @var EventRepository
     */
    private $events;
    /**
     * @var Flusher
     */
    private $flusher;

    /**
     * Handler constructor.
     * @param EventRepository $events
     * @param Flusher $flusher
     */
    public function __construct(EventRepository $events, Flusher $flusher)
    {
        $this->events = $events;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws Throwable
     * @throws DomainException
     * @throws RuntimeException
     * @throws StaleObjectException
     */
    public function handle(Command $command): void
    {
        $event = $this->events->get(new Id($command->id));
        $event->reject();
        $this->events->save($event);
        $this->flusher->flush($event);
    }
}