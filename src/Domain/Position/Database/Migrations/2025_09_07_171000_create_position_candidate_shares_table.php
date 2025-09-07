<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('position_candidate_shares', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('position_candidate_id');
            $table->morphs('model');
            $table->timestamps();

            $table->foreign('user_id', 'position_candidate_shares_user_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->foreign('position_candidate_id', 'position_candidate_shares_position_candidate_foreign')
                ->references('id')
                ->on('position_candidates')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_candidate_shares');
    }
};
