<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('classifiers', static function (Blueprint $table): void {
            $table->id();
            $table->string('type');
            $table->string('value');

            $table->unique([
                'type',
                'value',
            ], 'classifiers_type_value_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classifiers');
    }
};
