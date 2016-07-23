<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Compatibility;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;

class CleverTapUpdateProfile extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $data;
    protected $identity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($identity, $data)
    {
        $this->data = $data;
        $this->identity = $identity;
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