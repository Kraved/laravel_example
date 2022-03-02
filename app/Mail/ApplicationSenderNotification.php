<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationSenderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Application
     */
    private $application;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->application->name;

        return $this->view('emails.applications.created', ['name' => $name])->subject('Заявка принята.');

    }
}
