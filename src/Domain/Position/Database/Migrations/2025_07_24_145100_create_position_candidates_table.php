<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('position_candidates', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('position_id');
            $table->foreignId('candidate_id');
            $table->foreignId('application_id')->nullable();
            $table->json('score');
            $table->unsignedTinyInteger('total_score')->nullable();
            $table->timestamps();

            $table->foreign('position_id', 'position_candidates_position_foreign')
                ->references('id')
                ->on('positions')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->foreign('candidate_id', 'position_candidates_candidate_foreign')
                ->references('id')
                ->on('candidates')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->foreign('application_id', 'position_candidates_application_foreign')
                ->references('id')
                ->on('applications')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_candidates');
    }
};
