<?php
namespace Pimenta\LaravelDomain\Events;

use Laracasts\Commander\Events\EventDispatcher as LaracastsEventDispatcher;

class EventDispatcher implements DispatcherInterface
{

    /**
     * @var LaracastsEventDispatcher
     */
    private $dispatcher;

    public function __construct(LaracastsEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch($events)
    {
        $this->dispatcher->dispatch($events);
    }

}
