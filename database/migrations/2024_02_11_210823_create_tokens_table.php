<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('tokens', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->unsignedTinyInteger('type')->index('tokens_type_index');
            $table->string('token')->index('tokens_token_index');
            $table->text('data');
            $table->timestamp('valid_until');
            $table->timestamps();

            $table->unique([
                'type',
                'token',
            ], 'tokens_type_token_unique');

            $table->foreign('user_id', 'tokens_user_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
