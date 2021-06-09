<?php

namespace Lorito\Html;

class HtmlServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->alias('html', HtmlBuilder::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('html', static function ($app) {
            return new HtmlBuilder($app->make('url'), $app->make('view'));
        });
    }




}