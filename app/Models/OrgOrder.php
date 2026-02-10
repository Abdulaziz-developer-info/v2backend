<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgOrder extends Model
{
    protected $table = 'org_orders';

    protected $fillable = [
        // Identifikatsiya va Sinxronizatsiya
        'org_id', // Tashkilot/Filial IDsi (Multi-branch uchun)
        'uuid', // Flutter'da yaratilgan unique ID (Dublikat zakaz tushmasligi uchun)
        'check_id', // Mijozga beriladigan chek raqami (masalan: #0045)
        'day_id',   // Ish kuni/Smena IDsi (Kunlik hisobotlarni yopish uchun)

        // Mas'ul shaxslar
        'cashier_id', // To'lovni qabul qilgan kassa xodimi
        'taker_id',   // Buyurtmani tizimga kiritgan (Ofitsant/Sotuvchi)
        'user_id',   // user id xuranda yaniy

        // soliq uchun 
        'total_tax',
        'fiscal_sign', // Soliqdan kelgan fiskal belgi
        'qr_code_url',   // Chek uchun QR kod linki
        'tax_amount', // Tasdiqlangan soliq summasi

        // Holat va Tur
        'order_type', // pos, delivery, dine_in (stolda), take_away
        'order_status', // pending, completed, cancelled, refunded

        // Moliyaviy blok (Asosiy hisob-kitob)
        'subtotal', // Mahsulotlarning asl narxi (Hali chegirma va ustamalar qo'shilmagan)

        // Chegirma (Discount)
        'discount_type', // 'percent' (%) yoki 'fixed' (so'm)
        'discount_amount', // Chegirma summasi

        // Xizmat haqi (Service Fee - Restoranlar uchun)
        'service_fee_type', // 'percent' yoki 'fixed'
        'service_fee', // Xizmat uchun hisoblangan summa

        // Dastavka (Delivery)
        'delivery_fee_type', // 'fixed' yoki 'distance_based'
        'delivery_fee', // Yetkazib berish summasi

        // Yakuniy summa
        'total_amount', // Mijoz to'lashi kerak bo'lgan jami summa (Net Total)

        // Vaqt ko'rsatkichlari
        'completed_at', // To'lov to'liq amalga oshirilgan aniq vaqt
    ];
}
