<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('settings', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->string('key');
            $table->text('data');
            $table->timestamps();

            $table->unique([
                'user_id',
                'key',
            ], 'settings_user_key_unique');

            $table->foreign('user_id', 'settings_user_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
