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
        Schema::create('org_discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('org_id')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 15, 2)->nullable();
            $table->string('message')->nullable();
            $table->string('creator')->nullable();
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
        Schema::dropIfExists('org_discounts');
    }
};
