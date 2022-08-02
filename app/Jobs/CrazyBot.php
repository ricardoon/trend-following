<?php

namespace App\Jobs;

use App\Models\Position;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CrazyBot
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $positions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->positions = Position::whereIn('user_id', [11, 13])->active()->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->positions->count() === 0) {
            Log::info('No active positions for this asset and granularity.', [
                'asset' => $this->asset,
            ]);
        } else {
        }
    }
}
