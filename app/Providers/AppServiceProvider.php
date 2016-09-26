<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Cidade;
use App\Bairro;
use App\Observers\AuditoriaObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Cidade::observe(AuditoriaObserver::class);
        Bairro::observe(AuditoriaObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
