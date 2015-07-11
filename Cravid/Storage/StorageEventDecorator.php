<?php

namespace Cravid\Storage;

class StorageEventDecorator extends StorageDecorator
{
    use \Cravid\Event\DecoratorTrait;

    /**
     * @param \Cravid\Storage\Storage           $storage    The storage object.
     * @param \Cravid\Event\DispatcherInterface $dispatcher The dispatcher object.
     */
    public function __construct(Storage $storage, \Cravid\Event\DispatcherInterface $dispatcher)
    {
        parent::__construct($storage);

        $this->dispatcher = $dispatcher;
    }

    /**
     * Adds an event listener that listens on the BEFORE event.
     *
     * @param callable $callback  The listener.
     * @param int      $priority  The higher this value, the earlier an event
     *                            listener will be triggered in the chain (defaults to 0).
     */
    public function before(callable $listener, $priority = 0)
    {
        $this->on(EventType::BEFORE, $listener, $priority);
    }

    /**
     * Adds an event listener that listens on the AFTER event.
     *
     * @param callable $callback  The listener.
     * @param int      $priority  The higher this value, the earlier an event
     *                            listener will be triggered in the chain (defaults to 0).
     */
    public function after(callable $listener, $priority = 0)
    {
        $this->on(EventType::AFTER, $listener, $priority);
    }

    /**
     * Adds an event listener that listens on the drivers BEFORE event.
     *
     * @param callable $callback  The listener.
     * @param int      $priority  The higher this value, the earlier an event
     *                            listener will be triggered in the chain (defaults to 0).
     */
    public function beforeDriver(callable $listener, $priority = 0)
    {
        $this->on(Driver\EventType::BEFORE, $listener, $priority);
    }

    /**
     * Adds an event listener that listens on the drivers AFTER event.
     *
     * @param callable $callback  The listener.
     * @param int      $priority  The higher this value, the earlier an event
     *                            listener will be triggered in the chain (defaults to 0).
     */
    public function afterDriver(callable $listener, $priority = 0)
    {
        $this->on(Driver\EventType::AFTER, $listener, $priority);
    }

    /**
     * Adds an event listener that listens on the EXCEPTION event.
     *
     * @param callable $callback  The listener.
     * @param int      $priority  The higher this value, the earlier an event
     *                            listener will be triggered in the chain (defaults to 0).
     */
    public function onException(callable $listener, $priority = 0)
    {
        $this->on(EventType::EXCEPTION, $listener, $priority);
    }
}