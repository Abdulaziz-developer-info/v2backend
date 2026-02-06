<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('org_table_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('org_id');
            $table->string('name')->nullable();
            $table->string('creator')->nullable();
            $table->string('sort')->default(0);
            $table->bigInteger('sync_id')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_table_categories');
    }
};
