<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->foreignId('company_id')->after('id');
            $table->string('company_role')->after('company_id');

            $table->foreign('company_id', 'users_company_foreign')
                ->references('id')
                ->on('companies')
                ->cascadeOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropColumns('users', ['company_id', 'company_role']);
    }
};
