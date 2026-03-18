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
        Schema::create('server_user', function (Blueprint $table) {
            $table->foreignUuid('server_id')
            ->constrained('servers')
            ->onUpdate('cascade')
            ->onDelete('cascade'); 

            $table->foreignUuid('user_id')
            ->constrained('users')
            ->onUpdate('cascade')
            ->onDelete('cascade'); 

            $table->string('role')->default('owner');

            $table->primary(['server_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_user');
    }
};
