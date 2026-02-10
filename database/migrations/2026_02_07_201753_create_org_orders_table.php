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
        Schema::create('org_orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Identifikatsiya va Sinxronizatsiya
            $table->unsignedBigInteger('org_id')->index(); // Tashkilot/Filial IDsi (Multi-branch uchun)
            $table->string('uuid')->unique(); // Flutter'da yaratilgan unique ID (Dublikat zakaz tushmasligi uchun)
            $table->string('check_id')->index()->nullable(); // Mijozga beriladigan chek raqami (masalan: #0045)
            $table->string('day_id')->index()->nullable();   // Ish kuni/Smena IDsi (Kunlik hisobotlarni yopish uchun)

            // Mas'ul shaxslar
            $table->unsignedBigInteger('cashier_id')->nullable(); // To'lovni qabul qilgan kassa xodimi
            $table->unsignedBigInteger('taker_id')->nullable();   // Buyurtmani tizimga kiritgan (Ofitsant/Sotuvchi)
            $table->unsignedBigInteger('user_id')->nullable();   // user id xuranda yaniy

            // soliq uchun 
            $table->decimal('total_tax', 15, 2)->default(0);
            $table->string('fiscal_sign')->nullable(); // Soliqdan kelgan fiskal belgi
            $table->text('qr_code_url')->nullable();   // Chek uchun QR kod linki
            $table->decimal('tax_amount', 15, 2)->default(0); // Tasdiqlangan soliq summasi

            // Holat va Tur
            $table->string('order_type')->default('pos'); // pos, delivery, dine_in (stolda), take_away
            $table->string('order_status')->default('pending'); // pending, completed, cancelled, refunded

            // Moliyaviy blok (Asosiy hisob-kitob)
            $table->decimal('subtotal', 15, 2); // Mahsulotlarning asl narxi (Hali chegirma va ustamalar qo'shilmagan)

            // Chegirma (Discount)
            $table->string('discount_type')->nullable(); // 'percent' (%) yoki 'fixed' (so'm)
            $table->decimal('discount_amount', 15, 2)->default(0); // Chegirma summasi

            // Xizmat haqi (Service Fee - Restoranlar uchun)
            $table->string('service_fee_type')->nullable(); // 'percent' yoki 'fixed'
            $table->decimal('service_fee', 15, 2)->default(0); // Xizmat uchun hisoblangan summa

            // Dastavka (Delivery)
            $table->string('delivery_fee_type')->nullable(); // 'fixed' yoki 'distance_based'
            $table->decimal('delivery_fee', 15, 2)->default(0); // Yetkazib berish summasi

            // Yakuniy summa
            $table->decimal('total_amount', 15, 2); // Mijoz to'lashi kerak bo'lgan jami summa (Net Total)

            // Vaqt ko'rsatkichlari
            $table->timestamp('completed_at')->nullable(); // To'lov to'liq amalga oshirilgan aniq vaqt

            // Audit va Xavfsizlik
            $table->softDeletes(); // O'chirilgan zakazlarni arxivda saqlash (Audit uchun)
            $table->timestamps(); // Yaratilgan va o'zgartirilgan vaqtlar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_orders');
    }
};
