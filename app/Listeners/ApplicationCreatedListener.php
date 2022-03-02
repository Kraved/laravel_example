<?php

namespace App\Listeners;

use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ApplicationCreatedListener implements ShouldQueue
{
    private NotificationService $notificationService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->notificationService->sendApplicationCreatedNotification($event->getApplicationId());
    }
}
