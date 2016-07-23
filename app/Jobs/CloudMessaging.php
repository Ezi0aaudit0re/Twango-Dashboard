<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Compatibility;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;
use App\Profile;

class CloudMessaging extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $data;
    protected $deviceOs;
    protected $deviceId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($deviceOs, $deviceId, $data)
    {
        $this->data = $data;
        $this->deviceOs = $deviceOs;
        $this->deviceId = $deviceId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        

    }

     
}