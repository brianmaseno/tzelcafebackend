<?php

namespace Tests\Feature;

use App\Models\Promotion;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ApiPromotionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_promotions_list_and_validate(): void
    {
        Promotion::create([
            'name' => 'Test Promo',
            'code' => 'TEST10',
            'type' => 'percent',
            'value' => 10,
            'usage_limit' => null,
            'used_count' => 0,
            'starts_at' => Carbon::now()->subDay(),
            'ends_at' => Carbon::now()->addMonth(),
            'is_active' => true,
        ]);

        $this->getJson('/api/promotions')->assertOk()->assertJsonFragment(['code' => 'TEST10']);

        $this->postJson('/api/promotions/validate', [
            'code' => 'TEST10',
            'subtotal' => 1000,
        ])->assertOk()->assertJsonPath('data.discount', 100);
    }
}
