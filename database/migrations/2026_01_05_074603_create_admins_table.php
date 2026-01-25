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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            // holat
            $table->integer('block')->default(0); // 0-active, 1-block, 2-banned

            // asosiy ma'lumotlar
            $table->string('name')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->string('role')->nullable();
            $table->string('message')->nullable();

            // 🔹 KLIENT BILAN ALOQA UCHUN
            $table->string('phone')->nullable();        // telefon
            $table->string('telegram')->nullable();     // telegram username yoki link
            $table->string('instagram')->nullable();    // instagram username
            $table->string('whatsapp')->nullable();     // whatsapp raqam yoki link

            // 🔹 ISH VAQTLARI
            $table->time('work_start')->nullable();     // ish boshlanish vaqti
            $table->time('work_end')->nullable();       // ish tugash vaqti
            $table->string('work_days')->nullable();    // ["Mon","Tue","Wed"]

            // auth
            $table->rememberToken();

            // vaqtlar
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
