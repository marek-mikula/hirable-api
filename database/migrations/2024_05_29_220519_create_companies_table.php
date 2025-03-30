<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('companies', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique('companies_email_unique');
            $table->string('id_number')->unique('companies_id_number_unique');
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
