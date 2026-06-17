<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;

class MenuItemController extends Controller
{
    public function index(): JsonResponse
    {
        $items = MenuItem::query()
            ->with(['category:id,slug,name'])
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $items->map(function (MenuItem $item) {
                return [
                    'id' => (string) $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->price_cents / 100,
                    'currency' => 'KES',
                    'category' => $item->category?->slug,
                    'categoryLabel' => $item->category?->name,
                    'image' => $item->image_path ? url($item->image_path) : null,
                    'rating' => $item->rating,
                    'featured' => $item->is_featured,
                ];
            }),
        ]);
    }
}
