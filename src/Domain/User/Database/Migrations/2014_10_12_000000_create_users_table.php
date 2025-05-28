<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table): void {
            $table->id();
            $table->string('language', 2);
            $table->string('timezone')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('prefix', 10)->nullable();
            $table->string('postfix', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->unique('users_email_unique');
            $table->string('password');
            $table->rememberToken();
            $table->boolean('notification_crucial_mail')->default(1);
            $table->boolean('notification_crucial_app')->default(1);
            $table->boolean('notification_technical_mail')->default(1);
            $table->boolean('notification_technical_app')->default(1);
            $table->boolean('notification_marketing_mail')->default(1);
            $table->boolean('notification_marketing_app')->default(1);
            $table->boolean('notification_application_mail')->default(1);
            $table->boolean('notification_application_app')->default(1);
            $table->string('agreement_ip');
            $table->timestamp('agreement_accepted_at');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
