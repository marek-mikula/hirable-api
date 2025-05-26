<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('files', static function (Blueprint $table): void {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->string('mime');
            $table->string('path');
            $table->string('extension');
            $table->unsignedBigInteger('size');
            $table->json('data');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
