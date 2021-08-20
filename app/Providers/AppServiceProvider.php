<?php

namespace App\Providers;

use App\Http\Controllers\LoggerControler;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer("administrator",function($view){
            
            LoggerControler::Logger();
        });
    }
}
