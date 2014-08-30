<?php
namespace Pimenta\LaravelDomain;

use Illuminate\Support\ServiceProvider;
use Pimenta\LaravelDomain\Store\EventStore;

class LaravelDomainProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Pimenta\Domain\EventStoreInterface', function () {
            return new EventStore();
        });

        $this->app->bind('Pimenta\LaravelDomain\Events\DispatcherInterface',
            'Pimenta\LaravelDomain\Events\EventDispatcher');
    }

}
