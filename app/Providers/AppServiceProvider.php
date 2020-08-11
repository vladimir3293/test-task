<?php

namespace App\Providers;

use App\Services\OrdersXMLService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singltone = ['Helpers/Interfaces/OrdersServiceInterface'=> 'Services/OrdersXMLService'];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(OrdersXMLService::class)
            ->needs(Filesystem::class)
            ->give(function () {
                return Storage::disk('xmlOrders');
            });
        $this->app->bind(
            'App\Helpers\Interfaces\OrdersServiceInterface',
            'App\Services\OrdersXMLService'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
