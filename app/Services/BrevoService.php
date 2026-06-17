<?php

namespace App\Services;

use GuzzleHttp\Client;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;

class BrevoService
{
    private TransactionalEmailsApi $api;

    public function __construct()
    {
        $apiKey = (string) config('services.brevo.api_key');

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
        $this->api = new TransactionalEmailsApi(new Client(), $config);
    }

    /**
     * @param array<int, array{email:string, name?:string}> $to
     */
    public function sendTransactional(array $to, string $subject, string $htmlContent, ?string $textContent = null): void
    {
        $senderEmail = (string) config('services.brevo.sender_email');
        $senderName = (string) config('services.brevo.sender_name');

        $email = new SendSmtpEmail([
            'sender' => [
                'email' => $senderEmail,
                'name' => $senderName,
            ],
            'to' => $to,
            'subject' => $subject,
            'htmlContent' => $htmlContent,
            'textContent' => $textContent,
        ]);

        $this->api->sendTransacEmail($email);
    }
}

