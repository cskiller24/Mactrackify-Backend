<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Account;
use App\Models\Sale;
use App\Models\Track;
use App\Models\Warehouse;
use App\Models\WarehouseItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TeamSeeder::class);

        Account::factory()->count(3)->create();
        Warehouse::factory()->create();
        WarehouseItem::factory()->count(5)->create();

        Track::factory()->create([
            'brand_ambassador_id' => 3,
            'latitude' => 14.587630862589878,
            'longitude' => 120.97644216086593,
            'location' => 'General Luna St, Intramuros, Manila, 1002 Metro Manila'
        ]);

        Track::factory()->create([
            'brand_ambassador_id' => 3,
            'latitude' => 14.587048116892802,
            'longitude' => 120.97684181033308,
            'location' => 'General Luna St, Intramuros, Manila, 1002 Metro Manila'
        ]);

        Track::factory()->create([
            'brand_ambassador_id' => 3,
            'latitude' => 14.586599050829152,
            'longitude' => 120.97736350015671,
            'location' => 'General Luna St, Intramuros, Manila, 1002 Metro Manila'
        ]);

        Track::factory()->create([
            'brand_ambassador_id' => 3,
            'latitude' => 14.586665619615705,
            'longitude' => 120.97786334283589,
            'location' => 'General Luna St, Intramuros, Manila, 1002 Metro Manila'
        ]);

        Track::factory()->create([
            'brand_ambassador_id' => 3,
            'latitude' => 14.58694334288652,
            'longitude' => 120.9783632533321,
            'location' => 'General Luna St, Intramuros, Manila, 1002 Metro Manila'
        ]);
    }
}
