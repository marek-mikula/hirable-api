<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('model_has_files', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('file_id');
            $table->morphs('fileable');
            $table->timestamps();
            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_has_files');
    }
};
