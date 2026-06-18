<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GroqChatController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $apiKey = (string) config('services.groq.api_key');
        if ($apiKey === '') {
            return response()->json(['message' => 'Chat service is not configured.'], 503);
        }

        $payload = [
            'model' => 'llama-3.1-8b-instant',
            'temperature' => 0.4,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are TZEL CAFÉ's warm, premium concierge. Be concise, helpful, and friendly. If asked about ordering, guide them to the Menu and Checkout. Keep answers under 120 words.",
                ],
                ['role' => 'user', 'content' => $data['message']],
            ],
        ];

        $res = Http::withToken($apiKey)
            ->acceptJson()
            ->post('https://api.groq.com/openai/v1/chat/completions', $payload);

        if (! $res->ok()) {
            return response()->json(['message' => 'Chat service error.'], 502);
        }

        $reply = (string) ($res->json('choices.0.message.content') ?? '');

        return response()->json([
            'data' => [
                'reply' => $reply,
            ],
        ]);
    }
}

