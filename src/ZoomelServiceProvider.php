<?php

namespace Danielmlozano\Zoomel;

use Illuminate\Support\ServiceProvider;
use Danielmlozano\Zoomel\Zoomel;
use Illuminate\Support\Facades\Route;

class ZoomelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerPublishing();
    }

    /**
     * Setup the configuration for Zoomel.
     *
     * @return void
     */
    private function configure(){
        $this->mergeConfigFrom(
            __DIR__.'/../config/zoomel.php','zoomel'
        );
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes(){
        if(Zoomel::$register_routes){
            Route::group([
                'prefix' => 'zoomel',
                'namespace' => 'Danielmlozano\Zoomel\Http\Controllers',
                'as' => 'zoomel.'
            ],function(){
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });
        }
    }

    /**
     * Register the package migrations.
     *
     * @return void
     */
    private function registerMigrations(){
        if(Zoomel::$register_migrations && $this->app->runningInConsole()){
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing(){
        if($this->app->runningInConsole()){
            $this->publishes([
                __DIR__.'/../config/zoomel.php' => config_path('zoomel.php'),
            ], 'zoomel-config');
        }
    }



}
