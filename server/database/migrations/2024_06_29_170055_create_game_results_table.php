<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('game_results', function (Blueprint $table) {
            $table->id();
            // game_idカラムはgameテーブルのidと外部キー制約
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->integer('winner_disc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('game_results');
    }
};
