<?php

namespace App\Events;

use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    /**
     * Handle received Stripe webhooks.
     *
     * @param  \Laravel\Cashier\Events\WebhookReceived  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        logger('WebhookReceived');
        logger(json_encode($event->payload));

        if ($event->payload['type'] === 'invoice.payment_succeeded') {

        }
    }
}
