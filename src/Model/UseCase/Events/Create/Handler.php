<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Create;


use DateTimeImmutable;
use DomainException;
use Myks92\Vmc\Event\Model\Entity\Events\Category;
use Myks92\Vmc\Event\Model\Entity\Events\Date;
use Myks92\Vmc\Event\Model\Entity\Events\Event;
use Myks92\Vmc\Event\Model\Entity\Events\EventRepository;
use Myks92\Vmc\Event\Model\Flusher;
use RuntimeException;
use Throwable;

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
     * @return Event
     * @throws Throwable
     * @throws DomainException
     * @throws RuntimeException
     */
    public function handle(Command $command): Event
    {
        $event = Event::create(
            $command->name,
            new Category($command->category),
            new Date(new DateTimeImmutable($command->dateFrom), new DateTimeImmutable($command->dateTo)),
            $command->description,
            $command->owner
        );

        $event->revokeContacts();
        foreach ($command->contacts as $contact) {
            $event->addContact($contact->type, $contact->value);
        }

        $event->revokeUrls();
        foreach ($command->urls as $url) {
            $event->addUrl($url->type, $url->value);
        }

        $this->events->add($event);
        $this->flusher->flush($event);

        return $event;
    }
}