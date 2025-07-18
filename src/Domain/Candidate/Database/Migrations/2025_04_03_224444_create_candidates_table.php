<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('candidates', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id');
            $table->string('language', 2);
            $table->string('gender', 1)->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('phone_prefix', 10);
            $table->string('phone_number', 20);
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('github')->nullable();
            $table->string('portfolio')->nullable();
            $table->date('birth_date')->nullable();
            $table->json('working_skills');
            $table->timestamps();

            $table->unique(['company_id', 'email'], 'candidates_company_email_unique');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
