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
        Schema::create('org_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('org_id');
            $table->string('wifi_name')->nullable();
            $table->string('wifi_ip')->nullable();
            $table->string('app_version')->nullable();
            $table->string('editor')->nullable();
            $table->bigInteger('sync_id')->default(0);
            $table->string('deleted_at')->nullable();
            $table->bigInteger('global_sync_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_settings');
    }
};
