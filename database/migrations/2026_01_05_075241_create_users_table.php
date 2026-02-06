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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Status va tashkilot
            $table->integer('block')->default(0);       // 0 = aktiv, 1 = bloklangan
            $table->integer('active')->default(0);      // 0 = noaktiv, 1 = aktiv
            $table->bigInteger('org_id')->nullable()->index();   // Foydalanuvchi qaysi restoran/orgga tegishli
            $table->boolean('is_guest')->default(false); // foydalanuvchi accountini yurgizadimi
            
            // account sessiyalar 
            $table->integer('session_id')->nullable();      // qaysi sessiya bilan ishlayotgani 
            $table->integer('logged_in')->nullable();      // login qilib kirgan bolsa 1 boladi va shu user accountiga boradi

            // Rol va yaratgan shaxs
            $table->string('role')->nullable();         // admin, kassir, ofitsant, oshpaz, mijoz, kuryer, yetkazuvchi
            $table->string('creator')->nullable();      // kim yaratgan (foydalanuvchi ID yoki nomi)

            // Shaxsiy ma'lumotlar
            $table->string('name')->nullable();
            $table->string('login')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->string('verification_code')->nullable();

            // Ijtimoiy tarmoqlar (username / nickname)
            $table->string('instagram')->nullable();    // Instagram nickname
            $table->string('telegram')->nullable();     // Telegram username
            $table->string('whatsapp')->nullable();     // WhatsApp raqami
            $table->string('twitter')->nullable();      // Twitter username
            $table->string('facebook')->nullable();     // Facebook username

            // Provider va tokenlar
            $table->string('provider')->nullable();     // social login provider
            $table->string('provider_id')->nullable();  // provider id
            $table->string('auth')->nullable();         // API auth
            $table->string('token')->nullable();        // session yoki API token

            // Qo‘shimcha
            $table->string('note')->nullable();         // qo‘shimcha izohlar
            $table->string('message')->nullable();      // foydalanuvchi bilan bog‘liq xabarlar
            
            // updated uchun
            $table->bigInteger('sync_id')->default(0);
            $table->string('deleted_at')->nullable();

            // Timestamps
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
