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
    Schema::create('subscriptions', function (Blueprint $table) {
    $table->id();
    $table->foreignUuid('user_id')->constrained();
    $table->foreignId('plan_id')->constrained();
    $table->string('paypal_subscription_id');
    $table->string('status');
    $table->timestamp('next_billing_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
