<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();

            $table->string('status')->default('pending'); // pending, paid, preparing, out_for_delivery, delivered, cancelled
            $table->string('order_type')->default('delivery'); // delivery|pickup

            $table->unsignedInteger('subtotal_cents')->default(0);
            $table->unsignedInteger('discount_cents')->default(0);
            $table->unsignedInteger('delivery_fee_cents')->default(0);
            $table->unsignedInteger('total_cents')->default(0);

            $table->string('promo_code')->nullable();
            $table->text('dropoff_location')->nullable();
            $table->text('notes')->nullable();

            $table->string('payment_provider')->default('paystack');
            $table->string('paystack_reference')->nullable()->unique();
            $table->string('payment_status')->default('unpaid'); // unpaid|initialized|paid|failed|refunded
            $table->json('payment_meta')->nullable();

            $table->timestamp('placed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
