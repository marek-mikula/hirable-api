<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('position_candidates', static function (Blueprint $table): void {
            $table->foreignId('step_id')->after('application_id');

            $table->foreign('step_id', 'position_candidates_step_foreign', )
                ->references('id')
                ->on('position_process_steps')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('position_candidates', static function (Blueprint $table): void {
            $table->dropForeign('position_candidates_step_foreign');
            $table->dropColumn('step_id');
        });
    }
};
