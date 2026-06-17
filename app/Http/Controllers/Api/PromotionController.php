<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PromotionController extends Controller
{
    public function index(): JsonResponse
    {
        $now = Carbon::now();
        $promotions = Promotion::query()
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'type', 'value', 'starts_at', 'ends_at']);

        return response()->json(['data' => $promotions]);
    }

    public function validateCode(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $discount = $this->discountForCode($data['code'], (int) round($data['subtotal'] * 100));

        if ($discount === null) {
            return response()->json(['message' => 'Invalid or expired promo code.'], 422);
        }

        return response()->json([
            'data' => [
                'code' => strtoupper($data['code']),
                'discount' => $discount / 100,
                'currency' => 'KES',
            ],
        ]);
    }

    public static function discountForCode(string $code, int $subtotalCents): ?int
    {
        $promotion = Promotion::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->first();

        if (! $promotion) {
            return null;
        }

        $now = Carbon::now();
        if ($promotion->starts_at && $now->lt($promotion->starts_at)) {
            return null;
        }
        if ($promotion->ends_at && $now->gt($promotion->ends_at)) {
            return null;
        }
        if ($promotion->usage_limit !== null && $promotion->used_count >= $promotion->usage_limit) {
            return null;
        }

        if ($promotion->type === 'fixed') {
            return min((int) $promotion->value, $subtotalCents);
        }

        $percent = max(0, min(100, (int) $promotion->value));

        return (int) round($subtotalCents * ($percent / 100));
    }
}
