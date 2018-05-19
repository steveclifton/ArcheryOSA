<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendRelationshipConfirmationEmail extends ArcheryOSASender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $userfirstname;
    private $requestuserfirstname;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $userfirstname, $requestuserfirstname)
    {
        $this->email = $email;
        $this->userfirstname = $userfirstname;
        $this->requestuserfirstname = $requestuserfirstname;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if ($this->checkEmailAddress($this->email)) {
            Mail::to($this->email)
                ->send(new ConfirmArcherRelation($this->userfirstname, $this->requestuserfirstname));
        }

    }
}
