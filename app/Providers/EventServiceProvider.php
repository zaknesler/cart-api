<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Orders\OrderCreated' => [
            'App\Listeners\Orders\ProcessPayment',
            'App\Listeners\Orders\EmptyCart',
        ],

        'App\Events\Orders\OrderPaymentFailed' => [
            'App\Listeners\Orders\Payments\MarkOrderPaymentFailed',
        ],

        'App\Events\Orders\OrderPaid' => [
            'App\Listeners\Orders\Payments\CreateTransaction',
            'App\Listeners\Orders\Payments\MarkOrderProcessing',
        ],

        'Illuminate\Auth\Events\Registered' => [
            'Illuminate\Auth\Listeners\SendEmailVerificationNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
