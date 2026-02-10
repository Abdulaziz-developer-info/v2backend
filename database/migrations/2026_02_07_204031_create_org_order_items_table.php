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
        Schema::create('org_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Tizim identifikatorlari
            $table->unsignedBigInteger('org_id')->index(); // Tashkilotni ajratish uchun (Multi-tenant)
            $table->string('uuid')->unique(); // Flutter Hive'dan keladigan takrorlanmas ID (Sync uchun)
            $table->string('check_id')->index()->nullable(); // Fiskal chek yoki qog'oz chek raqami
            $table->string('day_id')->index()->nullable(); // Smuna (Smena) yopilganda hisobotlarni guruhlash uchun

            // Bog'lanishlar
            $table->foreignId('order_id')->constrained('org_orders')->onDelete('cascade'); // Asosiy chekka bog'lanish
            $table->unsignedBigInteger('product_id')->index(); // Mahsulot yoki Taom IDsi
            $table->unsignedBigInteger('taker_id')->nullable(); // Buyurtmani qabul qilgan xodim (Ofitsant/Sotuvchi)
            $table->unsignedBigInteger('user_id')->nullable();   // user id xuranda yaniy

            // Miqdor va Birlik
            $table->decimal('quantity', 12, 3); // Sotilgan miqdor (3 ta nol - kg/metr aniqligi uchun shart)

            // soliq uchun
            $table->decimal('tax_rate', 5, 2)->default(0); // Soliq foizi (masalan: 12.00%)
            $table->decimal('tax_amount', 15, 2)->default(0); // Hisoblangan soliq summasi

            // Narxlar va Moliya
            $table->decimal('cost_price', 15, 2); // Sotilgan vaqtdagi tan narxi (Foydani hisoblash uchun)
            $table->decimal('unit_price', 15, 2); // Sotilgan vaqtdagi narxi (Aksiyasiz, modifikatorsiz)
            $table->decimal('base_unit_price', 15, 2)->nullable(); // Mahsulotning asl (aksiyasiz) narxi (Statistika uchun)
            $table->decimal('total_item_price', 15, 2); // Yakuniy summa: (qty * price) + modifikatlar - chegirma

            // Restoran va Maxsus funksiyalar
            $table->json('modifiers')->nullable(); // Qo'shimchalar: pishloq, sous va h.k. (JSON formatda narxi bilan)
            $table->string('comment')->nullable(); // Maxsus talablar: "Piyozsiz", "Muzsiz bo'lsin"
            $table->string('status')->default('pending'); // Taom holati: pending, cooking, ready, served, void

            // Aksiya va Marketing
            $table->boolean('is_free')->default(false)->index(); // 1+1 yoki sovg'a sifatida berilganmi?
            $table->string('promo_type')->nullable(); // Aksiya turi: BOGO, HappyHour, EmployeeMeal
            $table->unsignedBigInteger('promotion_id')->nullable(); // Qaysi aksiya qo'llanganining IDsi

            // Xavfsizlik va Audit
            $table->string('void_reason')->nullable(); // Agar mahsulot bekor qilinsa, sababi (Audit uchun)
            $table->string('message')->nullable(); // message (foydalanuvchi tomonidan)
            $table->softDeletes(); // O'chirilgan mahsulotlarni bazada saqlash (Buxgalteriya talabi)
            $table->timestamps(); // Yaratilgan va tahrirlangan vaqt
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_order_items');
    }
};
