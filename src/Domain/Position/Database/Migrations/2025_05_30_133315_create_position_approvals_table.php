<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('position_approvals', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('model_has_position_id'); // todo make nullable
            $table->foreignId('position_id');
            $table->string('state', 20);
            $table->string('note', 300)->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->foreign('model_has_position_id', 'position_approvals_model_foreign')
                ->references('id')
                ->on('model_has_positions')
                ->restrictOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('position_id', 'position_approvals_position_foreign')
                ->references('id')
                ->on('positions')
                ->restrictOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_approvals');
    }
};
