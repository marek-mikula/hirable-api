<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->foreignId('company_id')->after('id');
            $table->string('company_role', 20)->after('company_id');
            $table->boolean('company_owner')->default(false);

            $table->foreign('company_id', 'users_company_foreign')
                ->references('id')
                ->on('companies')
                ->restrictOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->dropForeign('users_company_foreign');
            $table->dropColumn(['company_id', 'company_role']);
        });
    }
};
