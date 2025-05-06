<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('company_benefits', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('benefit_id');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->restrictOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('benefit_id')
                ->references('id')
                ->on('classifiers')
                ->restrictOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_benefits');
    }
};
