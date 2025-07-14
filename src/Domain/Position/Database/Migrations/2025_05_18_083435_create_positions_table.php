<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('positions', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('user_id');
            $table->string('state', 20);
            $table->date('approve_until')->nullable();
            $table->string('approve_message', 500)->nullable();
            $table->unsignedTinyInteger('approve_round')->nullable();
            $table->string('name');
            $table->string('department')->nullable();
            $table->string('field')->nullable();
            $table->json('workloads');
            $table->json('employment_relationships');
            $table->json('employment_forms');
            $table->unsignedSmallInteger('job_seats_num');
            $table->string('description', 2000);
            $table->boolean('is_technical');
            $table->string('address')->nullable();
            $table->unsignedInteger('salary_from');
            $table->unsignedInteger('salary_to')->nullable();
            $table->string('salary_type');
            $table->string('salary_frequency');
            $table->string('salary_currency');
            $table->string('salary_var')->nullable();
            $table->json('benefits');
            $table->string('min_education_level')->nullable();
            $table->string('seniority')->nullable();
            $table->unsignedTinyInteger('experience')->nullable();
            $table->string('hard_skills', 2000)->nullable();
            $table->unsignedTinyInteger('organisation_skills');
            $table->unsignedTinyInteger('team_skills');
            $table->unsignedTinyInteger('time_management');
            $table->unsignedTinyInteger('communication_skills');
            $table->unsignedTinyInteger('leadership');
            $table->json('language_requirements');
            $table->string('note', 2000)->nullable();
            $table->unsignedTinyInteger('hard_skills_weight');
            $table->unsignedTinyInteger('soft_skills_weight');
            $table->unsignedTinyInteger('language_skills_weight');
            $table->boolean('share_salary');
            $table->boolean('share_contact');
            $table->string('common_token')->nullable();
            $table->string('intern_token')->nullable();
            $table->string('referral_token')->nullable();
            $table->timestamps();

            $table->foreign('company_id', 'position_company_foreign')
                ->references('id')
                ->on('companies')
                ->restrictOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('user_id', 'position_user_foreign')
                ->references('id')
                ->on('users')
                ->restrictOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
