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
    Schema::create('plans', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->integer('slots');
    $table->decimal('price', 8, 2);
    $table->string('interval'); // monthly or yearly
    $table->string('paypal_plan_id');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
