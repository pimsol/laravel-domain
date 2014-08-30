<?php
namespace Pimenta\LaravelDomain\Store;

use App;
use Pimenta\Domain\DomainEvents;
use Pimenta\Domain\Entity;
use Pimenta\Domain\EventStoreInterface;
use Pimenta\Domain\IdentifiesAggregate;

trait EventStoreTrait
{

    protected function storeEvents(DomainEvents $events)
    {
        $store = $this->getStore();
        $store->commit($events);
    }

    protected function handleEvents(Entity $entity)
    {
        $events = $entity->getRecordedEvents();
        $entity->clearRecordedEvents();
        $this->storeEvents($events);
        $this->getDispatcher()->dispatch($events->toArray());
    }

    private function getDispatcher()
    {
        return App::make('Pimenta\LaravelDomain\Events\DispatcherInterface');
    }

    /**
     * @param $name
     * @param IdentifiesAggregate $id
     *
     * @return Entity
     */
    protected function getItem($name, IdentifiesAggregate $id)
    {
        $store = $this->getStore();
        $events = $store->getAggregateHistoryFor($id);

        $item = $name::reconstituteFrom($events);

        return $item;
    }

    /**
     * @return EventStoreInterface
     */
    private function getStore()
    {
        return App::make(EventStoreInterface::class);
    }
}
