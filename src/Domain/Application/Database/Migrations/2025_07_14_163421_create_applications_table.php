<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('applications', static function (Blueprint $table): void {
            $table->id();
            $table->uuid();
            $table->foreignId('position_id');
            $table->string('language', 2);
            $table->string('source', 20);
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('phone_prefix', 10);
            $table->string('phone_number', 20);
            $table->string('linkedin')->nullable();
            $table->timestamps();

            $table->foreign('position_id', 'applications_position_foreign')
                ->references('id')
                ->on('positions')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
