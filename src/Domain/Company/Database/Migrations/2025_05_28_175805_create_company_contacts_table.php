<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('company_contacts', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('note', 300)->nullable();
            $table->string('company_name')->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->restrictOnUpdate()
                ->cascadeOnDelete();

            $table->unique(['company_id', 'email'], 'company_contacts_company_email_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_contacts');
    }
};
