<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApiNewsletterTest extends TestCase
{
    use DatabaseTransactions;

    public function test_newsletter_subscribe(): void
    {
        $this->postJson('/api/newsletter/subscribe', [
            'email' => 'subscriber@example.com',
        ])->assertOk();

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'subscriber@example.com',
        ]);
    }
}
