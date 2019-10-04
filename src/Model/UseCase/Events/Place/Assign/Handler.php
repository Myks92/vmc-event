<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Place\Assign;


use DomainException;
use Myks92\Vmc\Event\Model\Entity\Events\EventRepository;
use Myks92\Vmc\Event\Model\Entity\Events\Id;
use Myks92\Vmc\Event\Model\Entity\Places\PlaceRepository;
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
     * @var PlaceRepository
     */
    private $places;

    /**
     * Handler constructor.
     * @param EventRepository $events
     * @param PlaceRepository $places
     * @param Flusher $flusher
     */
    public function __construct(EventRepository $events, PlaceRepository $places, Flusher $flusher)
    {
        $this->events = $events;
        $this->flusher = $flusher;
        $this->places = $places;
    }

    /**
     * @param Command $command
     * @throws RuntimeException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function handle(Command $command): void
    {
        $event = $this->events->get(new Id($command->event));
        $place = $this->places->findOrAddByNameAndStreetAndCity($command->name, $command->street, $command->city);
        $event->assignPlace($place->getId());
        $this->events->save($event);
        $this->flusher->flush($event);
    }
}