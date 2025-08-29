<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('position_candidate_actions', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('position_candidate_id');
            $table->string('type', 20);
            $table->string('state', 20);

            // common attributes
            $table->timestamp('datetime_start')->nullable();
            $table->timestamp('datetime_end')->nullable();
            $table->string('note', 500)->nullable();
            $table->string('address')->nullable();
            $table->string('instructions')->nullable();
            $table->string('result')->nullable();

            // action-specific attributes
            $table->string('interview_form')->nullable();
            $table->string('interview_type')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->string('refusal_reason')->nullable();
            $table->string('test_type')->nullable();

            $table->foreign('position_candidate_id')
                ->references('id')
                ->on('position_candidates')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_candidate_actions');
    }
};
