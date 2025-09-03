<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('model_has_positions', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('position_id');
            $table->morphs('model');
            $table->string('role');
            $table->timestamps();

            $table->foreign('position_id', 'model_has_positions_position_foreign')
                ->references('id')
                ->on('positions')
                ->restrictOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_has_positions');
    }
};
