<?php

namespace Myks92\Vmc\Event\Widget\Events;

use Myks92\Vmc\Event\ReadModel\Events\EventFetcher;
use yii\base\Widget;

class EventsLast extends Widget
{
    public $limit = 6;
    /**
     * @var EventFetcher
     */
    private $fetcher;

    public function __construct(EventFetcher $fetcher, $config = [])
    {
        parent::__construct($config);
        $this->fetcher = $fetcher;
    }

    public function run(): string
    {
        $events = $this->fetcher->findLastActive($this->limit);
        return $this->render('events-last', [
            'events' => $events
        ]);
    }
}