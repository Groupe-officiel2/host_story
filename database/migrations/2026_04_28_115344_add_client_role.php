<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        DB::table('roles')->insert([
            'name' => 'client',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
