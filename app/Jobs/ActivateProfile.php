<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Support\Collection;
use App\User;
use App\Profile;
use App\Compatibility;
use App\Norm;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;
class ActivateProfile extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
