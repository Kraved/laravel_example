<?php

namespace App\Services;

use App\Mail\ApplicationReceived;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendApplicationCreatedNotification($applicationId)
    {
        $application = Application::findOrFail($applicationId);

        $emailsSetting = trim(setting('site_email'));

        if ($emailsSetting) {
            $emails = array_map('trim', explode(',', $emailsSetting));
            if ($emails) {
                foreach ($emails as $email) {
                    if (preg_match("/[0-9a-z]+@[a-z]/", $email)) {
                        Mail::to($email)->send(new ApplicationReceived($application));
                    }
                }
            }
        }
    }
}
