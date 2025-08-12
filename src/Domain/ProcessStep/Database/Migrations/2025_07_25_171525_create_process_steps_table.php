<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('process_steps', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable();
            $table->string('step', 50);
            $table->boolean('is_repeatable');

            $table->unique(['company_id', 'step'], 'process_steps_company_step_unique');

            $table->foreign('company_id', 'process_steps_company_foreign')
                ->references('id')
                ->on('companies')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('process_steps');
    }
};
