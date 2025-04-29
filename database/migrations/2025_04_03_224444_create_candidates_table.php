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
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique('candidates_email_unique');
            $table->string('phone_prefix')->nullable();
            $table->string('phone')->nullable();
            $table->string('linkedin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
