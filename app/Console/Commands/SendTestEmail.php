<?php

namespace App\Console\Commands;

use App\Services\BrevoService;
use Illuminate\Console\Command;
use Throwable;

class SendTestEmail extends Command
{
    protected $signature = 'email:test {email : Recipient email address}';

    protected $description = 'Send a test transactional email via Brevo';

    public function handle(BrevoService $brevo): int
    {
        $email = (string) $this->argument('email');

        try {
            $brevo->sendTransactional(
                [['email' => $email, 'name' => 'TZEL Guest']],
                'TZEL CAFÉ — Test email',
                '<p>This is a test email from TZEL CAFÉ. If you received this, Brevo is configured correctly.</p>'
            );
            $this->info("Test email sent to {$email}.");

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('Failed to send: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
