<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $contact = ContactMessage::create($data);

        try {
            $adminEmail = (string) config('services.tzel.admin_notification_email');
            if ($adminEmail !== '') {
                $html = view('emails.contact-notification', ['contact' => $contact])->render();
                app(\App\Services\BrevoService::class)->sendTransactional(
                    [['email' => $adminEmail, 'name' => 'TZEL Admin']],
                    'TZEL CAFÉ — New contact message',
                    $html
                );
            }
        } catch (\Throwable) {
            // non-blocking
        }

        return response()->json([
            'data' => ['message' => 'Thank you. We received your message and will respond soon.'],
        ], 201);
    }
}
