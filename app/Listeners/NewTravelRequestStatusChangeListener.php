<?php

namespace App\Listeners;

use App\Events\NewTravelRequestStatusChangeEvent;
use App\Mail\NotifyCustomerMail;
use Illuminate\Support\Facades\Mail;

class NewTravelRequestStatusChangeListener
{
    /**
     * Handle the event.
     */
    public function handle(NewTravelRequestStatusChangeEvent $event): void
    {
        $name   = $event->user->name;
        $status = $event->travelRequest->status->value;

        Mail::to('testreceiver@gmail.com')->send(new NotifyCustomerMail($name, $status));
    }
}
