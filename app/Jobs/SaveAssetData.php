<?php

namespace App\Jobs;

use App\Libraries\Binance;
use App\Models\Asset;
use App\Models\History;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveAssetData implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $assets;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->assets = Asset::all();
        $this->handle();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(5);
        foreach ($this->assets as $asset) {
            $binance = new Binance(env('BINANCE_API_KEY'), env('BINANCE_API_SECRET'));
            $candle = $binance->trade()->getCandle([
                'symbol' => $asset->code,
                'interval' => '1m',
                'limit' => 2,
            ]);

            try {
                History::create([
                    'asset_id' => $asset->id,
                    'date' => date('Y-m-d H:i:s', $candle[0][0] / 1000),
                    'open' => $candle[0][1],
                    'high' => $candle[0][2],
                    'low' => $candle[0][3],
                    'close' => $candle[0][4],
                    'adj_close' => $candle[0][4],
                    'volume' => $candle[0][5],
                ]);
            } catch (\Exception $e) {
                Log::info('Can\'t save asset candle.', [
                    'schedule' => 'SaveAssetData',
                    'asset' => $asset->code,
                    'error' => $e->getMessage(),
                ]);
                continue;
            }
        }
    }
}
