<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BaseJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted.
     * Maximum allowed tries is 3 on process level
     * @var int
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     * Maximum allowed timeout is 300 seconds on PHP level
     * @var int
     */
    public int $timeout = 60;

    /**
     * BaseJob constructor.
     */
    public function __construct()
    {
        $this->queue = config('queue.listener');
    }
}
