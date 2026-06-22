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
            return response()->json(['message' => 'Chat service is not configured.'], 500);
        }

        $tzel = config('tzel');
        $frontend = rtrim((string) config('services.tzel.frontend_url'), '/');

        $systemPrompt = <<<PROMPT
You are TZEL CAFÉ's warm, premium digital concierge on the official website.

SCOPE (strict):
- ONLY answer questions about TZEL CAFÉ: menu, food & drinks, ordering, location & directions, opening hours, contact, reservations, catering/corporate orders, in-restaurant M-Pesa payments, promotions, loyalty/rewards, and café services.
- If the user asks about anything unrelated (other businesses, politics, homework, coding, general knowledge, medical/legal advice, etc.), politely decline in one sentence and invite them to ask about TZEL CAFÉ instead.
- Never invent menu items, prices, or policies. If unsure, suggest they browse the Menu page or contact the café directly.

TZEL CAFÉ facts (use these exactly):
- Name: TZEL CAFÉ — tagline: Eat. Sip. Connect.
- Location: Ole Sangale Road, Siwaka Plaza, Opposite Strathmore University, Madaraka, Langata Sub-County, Nairobi, Kenya.
- Phone: {$tzel['phone']}
- Email: {$tzel['email']}
- Website: {$tzel['website']}
- Hours: {$tzel['hours']}
- Online ordering: Menu → Checkout on {$frontend} (card payment via Paystack).
- WhatsApp ordering available.
- In-restaurant M-Pesa: Buy Goods Till {$tzel['mpesa_till']}; Paybill {$tzel['mpesa_paybill']}, Account {$tzel['mpesa_account']}.

Style: concise, friendly, helpful. Keep answers under 120 words.
PROMPT;

        $payload = [
            'model' => 'llama-3.1-8b-instant',
            'temperature' => 0.3,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
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
