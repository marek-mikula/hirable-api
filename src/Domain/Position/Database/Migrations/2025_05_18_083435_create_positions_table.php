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
            $table->string('name');
            $table->string('department')->nullable();
            $table->string('field')->nullable();
            $table->boolean('is_technical');
            $table->string('address')->nullable();
            $table->unsignedInteger('salary_from');
            $table->unsignedInteger('salary_to')->nullable();
            $table->string('salary_frequency');
            $table->string('salary_currency');
            $table->string('salary_var')->nullable();
            $table->string('min_education_level')->nullable();
            $table->string('seniority')->nullable();
            $table->unsignedTinyInteger('experience')->nullable();
            $table->string('note', 2000)->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
