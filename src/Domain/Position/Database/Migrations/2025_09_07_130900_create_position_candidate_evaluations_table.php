<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('position_candidate_evaluations', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('creator_id');
            $table->foreignId('position_candidate_id');
            $table->foreignId('user_id');
            $table->string('state');
            $table->string('evaluation', 500)->nullable();
            $table->unsignedTinyInteger('stars')->nullable();
            $table->date('fill_until')->nullable();
            $table->timestamp('reminded_at')->nullable();
            $table->timestamps();

            $table->foreign('creator_id', 'position_candidate_evaluations_creator_foreign')
                ->references('id')
                ->on('users')
                ->restrictOnUpdate()
                ->restrictOnUpdate();

            $table->foreign('user_id', 'position_candidate_evaluations_user_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->foreign('position_candidate_id', 'position_candidate_evaluations_position_candidate_foreign')
                ->references('id')
                ->on('position_candidates')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_candidate_evaluations');
    }
};
