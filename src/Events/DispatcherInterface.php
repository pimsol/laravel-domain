<?php
namespace Pimenta\LaravelDomain\Events;

interface DispatcherInterface
{
    public function dispatch($events);
}
