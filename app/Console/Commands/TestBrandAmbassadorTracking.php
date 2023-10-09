<?php

namespace App\Console\Commands;

use App\Models\Track;
use Illuminate\Console\Command;

class TestBrandAmbassadorTracking extends Command
{
    protected $signature = 'app:test-ba-track {--user-id?}';

    protected $description = 'Set a random brand ambassador a random gps location';

    public function handle()
    {
        if($this->option('user-id')) {
            Track::factory()->create([
                'brand_ambassador_id' => $this->option('user-id')
            ]);
        } else {
            Track::factory()->create();
        };

        return Command::SUCCESS;
    }
}
