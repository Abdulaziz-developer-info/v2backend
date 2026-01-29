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
        Schema::create('app_menu_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('org_id');
            $table->bigInteger('category_id');
            $table->integer('sort')->default(0);
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->decimal('delivery_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->string('unit')->default('pcs');
            $table->bigInteger('stock_quantity')->nullable();
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->string('product_type')->default('simple');
            $table->boolean('is_active')->default(true);
            $table->string('is_free')->default(false);
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 12, 2)->nullable();
            $table->integer('discount_x')->nullable();
            $table->integer('discount_y')->nullable();
            $table->string('image_url')->nullable();
            $table->string('qr_bar_cade')->nullable();
            $table->text('message')->nullable();
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
        Schema::dropIfExists('app_menu_products');
    }
};
