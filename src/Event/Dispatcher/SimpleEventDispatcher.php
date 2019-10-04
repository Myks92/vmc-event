<?php

namespace Myks92\Vmc\Event\Event\Dispatcher;

use Myks92\Vmc\Event\Model\EventDispatcherInterface;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\di\NotInstantiableException;

/**
 * Class SimpleEventDispatcher
 * @author Maxim Vorozhtsov <myks1992@mail.ru>
 */
class SimpleEventDispatcher implements EventDispatcherInterface
{
    /**
     * @var Container
     */
    private $container;
    /**
     * @var array
     */
    private $listeners;

    /**
     * SimpleEventDispatcher constructor.
     * @param Container $container
     * @param array $listeners
     */
    public function __construct(Container $container, array $listeners)
    {
        $this->container = $container;
        $this->listeners = $listeners;
    }

    /**
     * @param array $events
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatchOne($event);
        }
    }

    /**
     * @param $event
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function dispatchOne($event): void
    {
        $eventName = get_class($event);
        if (array_key_exists($eventName, $this->listeners)) {
            foreach ($this->listeners[$eventName] as $listenerClass) {
                $listener = $this->resolveListener($listenerClass);
                $listener($event);
            }
        }
    }

    /**
     * @param $listenerClass
     * @return callable
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function resolveListener($listenerClass): callable
    {
        return [$this->container->get($listenerClass), 'handle'];
    }
}