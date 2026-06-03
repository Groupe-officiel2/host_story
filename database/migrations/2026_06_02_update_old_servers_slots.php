<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les anciens serveurs avec slots=1 à slots=10
        // (1 était la valeur par défaut avant l'implémentation des labels)
        DB::table('servers')
            ->where('slots', '=', 1)
            ->update(['slots' => 10, 'updated_at' => DB::raw('NOW()')]);
        
        // Log
        $count = DB::table('servers')->where('slots', '=', 10)->count();
        \Log::info("Migration: {$count} anciens serveurs mis à jour avec slots=10");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: les serveurs sont à 10, on ne peut pas revenir sans perdre de données
        // Donc on ne fait rien
        \Log::warning("Revert migration: Les serveurs restent à leurs slots actuels");
    }
};
