<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgOrderItem extends Model
{
    protected $table = 'org_order_items';

    protected $fillable = [
        'org_id', // Tashkilotni ajratish uchun (Multi-tenant)
        'uuid', // Flutter Hive'dan keladigan takrorlanmas ID (Sync uchun)
        'check_id', // Fiskal chek yoki qog'oz chek raqami
        'day_id', // Smuna (Smena) yopilganda hisobotlarni guruhlash uchun

        // Bog'lanishlar
        'order_id', // Asosiy chekka bog'lanish
        'product_id', // Mahsulot yoki Taom IDsi
        'taker_id', // Buyurtmani qabul qilgan xodim (Ofitsant/Sotuvchi)
        'user_id',   // user id xuranda yaniy

        // Miqdor va Birlik
        'quantity', // Sotilgan miqdor (3 ta nol - kg/metr aniqligi uchun shart)

        // soliq uchun
        'tax_rate', // Soliq foizi (masalan: 12.00%)
        'tax_amount', // Hisoblangan soliq summasi

        // Narxlar va Moliya
        'cost_price',// Sotilgan vaqtdagi tan narxi (Foydani hisoblash uchun)
        'unit_price',// Sotilgan vaqtdagi narxi (Aksiyasiz, modifikatorsiz)
        'base_unit_price', // Mahsulotning asl (aksiyasiz) narxi (Statistika uchun)
        'total_item_price',// Yakuniy summa: (qty * price) + modifikatlar - chegirma

        // Restoran va Maxsus funksiyalar
        'modifiers', // Qo'shimchalar: pishloq, sous va h.k. (JSON formatda narxi bilan)
        'comment', // Maxsus talablar: "Piyozsiz", "Muzsiz bo'lsin"
        'status', // Taom holati: pending, cooking, ready, served, void

        // Aksiya va Marketing
        'is_free', // 1+1 yoki sovg'a sifatida berilganmi?
        'promo_type', // Aksiya turi: BOGO, HappyHour, EmployeeMeal
        'promotion_id', // Qaysi aksiya qo'llanganining IDsi

        // Xavfsizlik va Audit
        'void_reason', // Agar mahsulot bekor qilinsa, sababi (Audit uchun)
    ];
}
