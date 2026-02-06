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
            $table->bigIncrements('id'); // Mahsulotning unikal identifikatori
            $table->unsignedBigInteger('org_id')->index(); // Qaysi muassasaga (restoran/do'kon) tegishliligi
            $table->unsignedBigInteger('category_id')->index(); // Mahsulot kategoriyasi (ovqatlar, ichimliklar va h.k.)

            // --- ASOSIY MA'LUMOTLAR ---
            $table->string('name'); // Mahsulotning to'liq nomi
            $table->string('product_type')->default('simple'); // Turi: 'simple' (tayyor tovar), 'recipe' (tarkibli taom), 'weight' (tortiladigan)
            $table->string('unit')->default('pcs'); // O'lchov birligi: 'pcs' (dona), 'kg', 'port' (porsiya), 'litr'

            // --- IDENTIFIKATORLAR (Do'kon va QR uchun) ---
            $table->string('sku')->nullable()->unique(); // Mahsulot artikuli (ombor hisobi uchun ichki kod)
            $table->string('barcode')->nullable()->index(); // Skaner orqali sotish uchun shtrix-kod
            $table->string('qr_code')->nullable(); // QR-menyu yoki maxsus markirovka kodi

            // --- NARX VA TAN NARX ---
            $table->decimal('price', 15, 2)->default(0); // Mahsulotning asosiy sotuv narxi
            $table->decimal('cost_price', 15, 2)->default(0); // Ishlab chiqarish tan narxi (xom-ashyodan kelib chiqadi)
            $table->decimal('delivery_price', 15, 2)->default(0); // Dostavka ilovalari uchun alohida narx (agar kerak bo'lsa)

            // --- KO'RINUVCHANLIK (Visibility) ---
            $table->boolean('is_visible_pos')->default(true); // Kassa (terminal) ekranida ko'rinishi
            $table->boolean('is_visible_online')->default(true); // Sayt yoki QR-menyuda ko'rinishi
            $table->boolean('in_stop_list')->default(false); // Mahsulot vaqtincha yo'qligi (stop-listga tushsa true bo'ladi)

            // --- SOLIQ VA FISKAL (Tax & Fiscal) ---
            $table->string('ikpu_code', 20)->nullable()->index(); // MXIK kodi (O'zbekiston fiskal cheki uchun shart)
            $table->decimal('vat_percent', 5, 2)->default(0); // QQS foiz stavkasi (masalan: 12.00)

            // --- DO'KON VA QIDIRUV (Retail & Search) ---
            $table->unsignedBigInteger('brand_id')->nullable()->index(); // Mahsulot brendi (masalan: Pepsi, Nestle)
            $table->text('search_keywords')->nullable(); // Tezkor qidiruv uchun teglar (masalan: "suv, gazli, koka")
            $table->boolean('is_weighable')->default(false); // Tarozi mahsulotimi? (true bo'lsa kassa og'irlikni so'raydi)

            // --- ISHLAB CHIQARISH (Production) ---
            $table->integer('prepare_time')->default(0); // Taom tayyor bo'lishi uchun ketadigan vaqt (minutda)
            $table->integer('weight_gram')->default(0); // Tayyor mahsulotning sof og'irligi (grammda)

            // --- MODIFIKATORLAR ---
            $table->boolean('has_modifiers')->default(false); // Mahsulotning qo'shimchalari bormi? (pishloq, sous va h.k.)

            // --- OMBOR HISOBI (Stock Control) ---
            $table->decimal('stock_quantity', 15, 2)->default(0); // Ombordagi hozirgi qoldiq miqdori
            $table->integer('min_stock_level',  )->default(0); // Ogohlantirish uchun minimal qoldiq miqdori
            $table->boolean('track_stock')->default(true); // Ushbu mahsulot bo'yicha ombor hisobi yuritiladimi?

            // --- CHEGIRMALAR (Discount) ---
            $table->boolean('has_discount')->default(false); // Mahsulotga chegirma amal qiladimi?
            $table->integer('is_free')->default(0); // Tekin mahsulot belgisi (string ko'rinishida)
            $table->string('discount_type')->nullable(); // Chegirma turi (foizda yoki naqd pulda)
            $table->decimal('discount_value', 12, 2)->nullable(); // Chegirma summasi yoki foizi
            $table->integer('discount_x')->nullable(); // "X sotib olinsa..." aksiyasi uchun X miqdori
            $table->integer('discount_y')->nullable(); // "...Y tekinga beriladi" aksiyasi uchun Y miqdori

            // --- QO'SHIMCHA SOZLAMALAR ---
            $table->boolean('is_active')->default(true); // Mahsulot umumiy tizimda aktivmi?
            $table->string('image_url')->nullable(); // Mahsulot rasmi uchun havola
            $table->text('description')->nullable(); // Taom yoki mahsulot haqida batafsil ma'lumot
            $table->integer('sort_order')->default(0); // Menyuda chiqish tartib raqami (kichik raqam birinchi chiqadi)

            // --- TIZIM USTUNLARI ---
            $table->bigInteger('sync_id')->default(0); // Tashqi tizimlar (cloud) bilan sinxronizatsiya kodi
            $table->softDeletes(); // O'chirilgan mahsulotni bazadan butunlay yo'qotmaslik (tiklash imkoniyati)
            $table->timestamps(); // Yaratilgan va tahrirlangan vaqtni hisobga olish
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
