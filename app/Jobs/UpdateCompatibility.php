<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\User;
use App\Profile;
use App\Compatibility;
use App\Norm;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;
class UpdateCompatibility extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $user;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $type)
    {
        $this->user = $user;
        $this->type = $type;
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
