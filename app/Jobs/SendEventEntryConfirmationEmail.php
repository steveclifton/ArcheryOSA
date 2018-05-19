<?php

namespace App\Jobs;

use App\Mail\SendEntryConfirmationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEventEntryConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $firstname;
    private $eventname;
    private $eventurl;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $firstname, $eventname, $eventurl)
    {
        $this->email = $email;
        $this->firstname = $firstname;
        $this->eventname = $eventname;
        $this->eventurl = $eventurl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ( filter_var($this->email, FILTER_VALIDATE_EMAIL) ) {
            Mail::to($this->email)
                ->send(new SendEntryConfirmationEmail(ucwords($this->eventname), ucwords($this->firstname), $this->eventurl));
        }
    }
}
