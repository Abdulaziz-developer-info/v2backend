<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->default('pending');
            $table->bigInteger('org_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('main_account')->nullable();
            $table->integer('seen')->nullable();
            $table->string('token')->nullable();

            $table->string('ip_address')->nullable();
            $table->string('location')->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_name')->nullable();
            $table->string('device_id')->nullable();

            $table->dateTime('login_at')->nullable();
            $table->dateTime('last_activity_at')->nullable();
            $table->dateTime('logout_at')->nullable();
            $table->integer('is_active')->default(0);
            $table->text('fcm_token')->nullable();
            $table->bigInteger('sync_id')->default(0);
            $table->string('deleted_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_sessions');
    }
};
