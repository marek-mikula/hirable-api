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
            $table->foreignId('position_process_step_id')->nullable();
            $table->foreignId('position_candidate_id');
            $table->foreignId('user_id');
            $table->string('type');
            $table->date('date')->nullable();
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
            $table->string('place')->nullable();
            $table->string('evaluation', 500)->nullable();
            $table->string('name')->nullable();
            $table->string('interview_form')->nullable();
            $table->string('interview_type')->nullable();
            $table->boolean('rejected_by_candidate')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->string('refusal_reason')->nullable();
            $table->string('task_type')->nullable();
            $table->string('offer_state')->nullable();
            $table->string('offer_job_title')->nullable();
            $table->string('offer_company')->nullable();
            $table->json('offer_employment_forms')->nullable();
            $table->string('offer_place')->nullable();
            $table->integer('offer_salary')->nullable();
            $table->string('offer_salary_currency')->nullable();
            $table->string('offer_salary_frequency')->nullable();
            $table->string('offer_workload')->nullable();
            $table->string('offer_employment_relationship')->nullable();
            $table->date('offer_start_date')->nullable();
            $table->string('offer_employment_duration')->nullable();
            $table->date('offer_certain_period_to')->nullable();
            $table->integer('offer_trial_period')->nullable();
            $table->string('note', 500)->nullable();
            $table->date('real_start_date')->nullable();
            $table->timestamps();

            $table->foreign('position_process_step_id', 'position_candidate_actions_position_process_step_foreign')
                ->references('id')
                ->on('position_process_steps')
                ->nullOnDelete()
                ->restrictOnUpdate();

            $table->foreign('position_candidate_id', 'position_candidate_actions_position_candidate_foreign')
                ->references('id')
                ->on('position_candidates')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->foreign('user_id', 'position_candidate_actions_user_foreign')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_candidate_actions');
    }
};
