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
            $table->foreignId('application_id');
            $table->string('step');
            $table->json('score');
            $table->unsignedTinyInteger('total_score')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_candidates');
    }
};
