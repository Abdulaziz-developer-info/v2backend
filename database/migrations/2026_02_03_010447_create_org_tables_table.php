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
        Schema::create('org_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('org_id')->index();
            $table->foreignId('category_id')->nullable()->index();

            // Asosiy ma'lumotlar
            $table->string('name');
            $table->integer('number')->default(0);
            $table->integer('capacity')->default(1);
            $table->integer('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('status')->nullable(); // Masalan: 'empty', 'occupied', 'reserved'

            // Xizmat va Chegirma
            $table->string('service_type')->default('none'); // none, percent, fixed
            $table->decimal('service_value', 15, 2)->default(0);

            // UI/Dizayn parametrlari (Flutter bilan moslik)
            $table->double('pos_x')->default(0);
            $table->double('pos_y')->default(0);
            $table->double('width')->default(100.0);
            $table->double('height')->default(100.0);
            $table->string('shape')->default('square'); // square, rectangle
            $table->double('border_radius')->default(12.0);
            $table->string('icon_code')->nullable();
            $table->string('color')->default('#FFFFFF');

            // Tizim ustunlari
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
        Schema::dropIfExists('org_tables');
    }
};
