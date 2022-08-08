<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendQueueEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    public $timeout = 7200; // 2 hours
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $input['subject'] = $this->details['sub'];
        $input['message'] = $this->details['msg'];

        $data = $this->details['mails'];
        foreach ($data as $key => $value) {
            $input['email'] = $value->email;
            $input['name'] = $value->name;
            \Mail::send('mail.Test_mail', ['data' => $input], function($message) use($input){
                $message->to($input['email'], $input['name'])
                    ->subject($input['subject']);
            });
        }
    }
}
