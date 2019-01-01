<?php

namespace App\Providers;

use App\Cart\Cart;
use App\Cart\Payments\PaymentGateway;
use Illuminate\Support\ServiceProvider;
use App\Cart\Payments\Gateways\StripePaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        PaymentGateway::class => StripePaymentGateway::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cart::class, function ($app) {
            if ($app->auth->user()) {
                $app->auth->user()->load([
                    'cart.stock',
                ]);
            }

            return new Cart($app->auth->user());
        });
    }
}
