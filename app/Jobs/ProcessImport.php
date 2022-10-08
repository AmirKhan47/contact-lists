<?php

namespace App\Jobs;

use App\Models\UserContact;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessImport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $header;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $header)
    {
        $this->data = $data;
        $this->header = $header;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $list) {
            $contactData = array_combine($this->header, $list);
            // send sms using twilio
//            $message = $twilio->messages->create($contactData['phone'], ["body" => "Welcome to our portal.", "from" => "+12058561234"]);
//            Log::debug($message->sid.' sms sent to '.$contactData['phone']);
            Log::info('sms sent to '.$contactData['phone']);
            UserContact::create($contactData);
        }
    }
}
