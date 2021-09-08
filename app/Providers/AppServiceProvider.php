<?php

namespace App\Providers;

use App\Filters\GameFilterer;
use App\Formatters\Formatter;
use App\Formatters\GameHtmlFormatter;
use App\Http\Controllers\GameController;
use App\Http\Livewire\SearchBox;
use App\Models\Game;
use Illuminate\Support\ServiceProvider;

/*
 * All service providers are registered in the config/app.php
 * configuration file in the providers array
 */

class AppServiceProvider extends ServiceProvider
{
    /*
     * When the service provider is loaded by the framework, it will
     * automatically check for these properties and register their bindings
     *
     */

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
//        ServerProvider::class => DigitalOceanServerProvider::class,
    ];

    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
//        DowntimeNotifier::class => PingdomDowntimeNotifier::class,
//        ServerProvider::class => ServerToolsProvider::class,
    ];

    /**
     * Register any application services.
     *
     * Only bind things into the service container. You should never
     * attempt to register any event listeners, routes, or any other
     * piece of functionality. Otherwise, you may accidentally use a service
     * that is provided by a service provider which has not loaded yet
     *
     * you always have access to the $app property which provides access
     * to the service container
     *
     * @return void
     */
    public function register()
    {

    // --- Examples ---
        /*$this->app->singleton(Connection::class, function ($app) {
            return new Connection(config('riak'));
        });*/

//        $this->app->bind(Formatter::class, GameHtmlFormatter::class);
        
        /*$this->app->bindMethod([Game::class, 'setFormatter'],
            function ($model, $app) {
                return $model->setFormatter(
                    $app->make(GameHtmlFormatter::class)
                );
            }
        );*/
    // ---

        // to set a specific implementation for an interface depending
        // on the class that calls it
        $this->app->when(Game::class)
            ->needs(Formatter::class)
            ->give(GameHtmlFormatter::class);

        $this->app->bindMethod([SearchBox::class, 'mount'],
            function ($model, $app) {
                return $model->mount(
                    $app->make(GameHtmlFormatter::class)
                );
            }
        );

        $this->app->when(GameController::class)
            ->needs(Formatter::class)
            ->give(GameHtmlFormatter::class);
    }

    /**
     * Bootstrap any application services.
     *
     * This method is called after all other service providers have been
     * registered, meaning you have access to all other services that have
     * been registered by the framework
     *
     * The service container will automatically inject any dependencies
     * that are type hinted for this method
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

/*
 * If your provider is only registering bindings in the service container,
 * you may choose to defer its registration until one of the registered
 * bindings is actually needed. Deferring the loading of such a provider
 * will improve the performance of your application, since it is not loaded
 * from the filesystem on every request
 *
 * To defer the loading of a provider, implement the
 * \Illuminate\Contracts\Support\DeferrableProvider interface and define
 * a provides method. The provides method should return the service container
 * bindings registered by the provider
 */