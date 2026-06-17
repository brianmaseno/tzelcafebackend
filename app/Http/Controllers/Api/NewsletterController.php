<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'locale' => ['nullable', 'string', Rule::in(['en', 'sw'])],
        ]);

        NewsletterSubscriber::query()->updateOrCreate(
            ['email' => $data['email']],
            [
                'locale' => $data['locale'] ?? 'en',
                'subscribed_at' => now(),
            ]
        );

        return response()->json([
            'data' => ['message' => 'Thank you for subscribing to TZEL CAFÉ updates.'],
        ]);
    }
}
