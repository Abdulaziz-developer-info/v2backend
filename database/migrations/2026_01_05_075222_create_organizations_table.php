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
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('block')->default(0);            
            $table->integer('admin_id')->nullable();
            $table->bigInteger('owner_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('org_type')->nullable();
            $table->string('org_name')->nullable();
            $table->string('address')->nullable();
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('currency')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->integer('product_count')->nullable();
            $table->string('payment')->nullable();
            $table->string('auth')->unique()->nullable();
            $table->string('message')->nullable();
            $table->string('status')->nullable();
            $table->string('creator')->nullable();
            $table->bigInteger('sync_id')->nullable();
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
        Schema::dropIfExists('organizations');
    }
};
