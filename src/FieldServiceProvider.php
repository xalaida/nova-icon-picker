<?php

namespace Nevadskiy\Nova\Icon;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('icon-field', __DIR__.'/../dist/js/field.js');
            Nova::style('icon-field', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
