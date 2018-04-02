<?php

namespace App\Listeners;

use App\Events\EmployeeBorrowCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployeeBorrowCreatedEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EmployeeBorrowCreatedEvent  $event
     * @return void
     */
    public function handle(EmployeeBorrowCreatedEvent $event)
    {
        //
    }
}
