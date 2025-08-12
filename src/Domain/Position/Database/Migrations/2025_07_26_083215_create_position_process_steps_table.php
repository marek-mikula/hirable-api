<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('position_process_steps', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('position_id');
            $table->string('step', 50);
            $table->string('label', 50)->nullable();
            $table->unsignedTinyInteger('order');
            $table->boolean('is_fixed');
            $table->boolean('is_repeatable');

            $table->foreign('position_id', 'position_process_steps_position_foreign')
                ->references('id')
                ->on('positions')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_process_steps');
    }
};
