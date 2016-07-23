<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Compatibility;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;
use App\Like;
use Carbon\Carbon;
class CleverTapUpdateProfile extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $type;
    protected $userAId;
    protected $userBId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $userAId, $userBId)
    {
        $this->type = $type;
        $this->userAId = $userAId;
        $this->userBId = $userBId;
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