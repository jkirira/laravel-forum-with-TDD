<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer('threads.create', function($view){
            $view->with('channels', \App\Channel::all());
        });
        \View::composer('threads.index', function($view){
            $view->with('channels', \App\Channel::all());
        });

        \View::composer('layouts.app', function($view){
            $view->with('channels', \App\Channel::all());
        });

//        \View::composer('*', function($view){
//            $channels = \Cache::rememberForever('channels', function (){
//                return Channel::all();
//            });
//            $view->with('channels', $channels);
//        });


//        \View::share('channels', Channel::all());

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->isLocal()){
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
