<?php
namespace Pimenta\LaravelDomain\Store;

use DB;
use Pimenta\Domain\AggregateHistory;
use Pimenta\Domain\DomainEvents;
use Pimenta\Domain\EventStoreInterface;
use Pimenta\Domain\IdentifiesAggregate;

class EventStore implements EventStoreInterface
{

    /**
     * @param DomainEvents $events
     *
     * @return void
     */
    public function commit(DomainEvents $events)
    {
        $items = [];
        foreach ($events as $event) {
            $time = new \DateTime('now');
            $utime = microtime();
            $items[] =[
                'time' => $time->format('Y-m-d H:i:s'),
                'utime' => $utime,
                'eventName' => get_class($event),
                'aggregateId' => $event->getAggregateId(),
                'eventData' => serialize($event),
            ];
        }

        if (count($items)) {
            DB::table('domain_events')->insert($items);
        }
    }

    /**
     * @param IdentifiesAggregate $id
     *
     * @return AggregateHistory
     */
    public function getAggregateHistoryFor(IdentifiesAggregate $id)
    {
        $items = DB::table('domain_events')->where('aggregateId', $id)
            ->orderBy('time')
            ->orderBy('utime')
            ->get();

        $events = [];

        foreach ($items as $item) {
            $events[] = unserialize($item->eventData);
        }

        return new AggregateHistory($id, $events);
    }

}
