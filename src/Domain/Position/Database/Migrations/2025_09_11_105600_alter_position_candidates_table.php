<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('position_candidates', static function (Blueprint $table): void {
            $table->unsignedTinyInteger('priority')->default(0)->after('total_score');
        });
    }

    public function down(): void
    {
        Schema::table('position_candidates', static function (Blueprint $table): void {
            $table->dropColumn('priority');
        });
    }
};
