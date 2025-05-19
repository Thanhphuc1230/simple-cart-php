<?php

namespace Thanhphuc1230\ShoppingCart;

use Illuminate\Support\ServiceProvider;

class ShoppingCartServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('cart', function($app) {
            return new Cart($app['session'], $app['events']);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/cart.php' => config_path('cart.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/config/cart.php', 'cart'
        );
    }
} 