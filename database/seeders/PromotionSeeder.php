<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        Promotion::updateOrCreate(
            ['code' => 'TZEL10'],
            [
                'name' => 'Welcome 10% Off',
                'type' => 'percent',
                'value' => 10,
                'usage_limit' => null,
                'used_count' => 0,
                'starts_at' => Carbon::now()->subDay(),
                'ends_at' => Carbon::now()->addYear(),
                'is_active' => true,
            ]
        );
    }
}
