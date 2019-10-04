<?php


namespace Myks92\Vmc\Event\Model\UseCase\Events\Edit;


use DomainException;
use Myks92\Vmc\Event\Model\Entity\Events\Category;
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

        $event->revokeContacts();
        foreach ($command->contacts as $contact) {
            $event->addContact($contact->type, $contact->value);
        }

        $event->revokeUrls();
        foreach ($command->urls as $url) {
            $event->addUrl($url->type, $url->value);
        }

        $event->edit(
            $command->name,
            new Category($command->category),
            $command->description
        );

        $this->events->save($event);
        $this->flusher->flush($event);
    }
}