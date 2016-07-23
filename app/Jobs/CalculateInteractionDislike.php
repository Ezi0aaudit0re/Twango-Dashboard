<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Compatibility;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalculateInteractionDislike extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $userAId;
    protected $userBId;
    protected $gender;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userAId, $userBId, $gender)
    {
        $this->userAId = $userAId;
        $this->userBId = $userBId;
        $this->gender = $gender;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
    }

    private function _calculateStd($compatible){
        return sqrt(array_sum(array_map([$this,"_stdSquare"], $compatible, array_fill(0,count($compatible), (array_sum($compatible) / count($compatible))))) / (count($compatible)-1));
    }

    private static function _stdSquare($x, $mean){
        return pow($x - $mean, 2);
    }
}
