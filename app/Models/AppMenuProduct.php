<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppMenuProduct extends Model
{
    protected $table = 'app_menu_products';

    protected $fillable = [
            'org_id',// Qaysi muassasaga (restoran/do'kon) tegishliligi
            'category_id',// Mahsulot kategoriyasi (ovqatlar, ichimliklar va h.k.)

            // --- ASOSIY MA'LUMOTLAR ---
            'name', // Mahsulotning to'liq nomi
            'product_type', // Turi: 'simple' (tayyor tovar), 'recipe' (tarkibli taom), 'weight' (tortiladigan)
            'unit', // O'lchov birligi: 'pcs' (dona), 'kg', 'port' (porsiya), 'litr'

            // --- IDENTIFIKATORLAR (Do'kon va QR uchun) ---
            'sku', // Mahsulot artikuli (ombor hisobi uchun ichki kod)
            'barcode',// Skaner orqali sotish uchun shtrix-kod
            'qr_code', // QR-menyu yoki maxsus markirovka kodi

            // --- NARX VA TAN NARX ---
            'price', // Mahsulotning asosiy sotuv narxi
            'cost_price', // Ishlab chiqarish tan narxi (xom-ashyodan kelib chiqadi)
            'delivery_price',// Dostavka ilovalari uchun alohida narx (agar kerak bo'lsa)

            // --- KO'RINUVCHANLIK (Visibility) ---
            'is_visible_pos', // Kassa (terminal) ekranida ko'rinishi
            'is_visible_online', // Sayt yoki QR-menyuda ko'rinishi
            'in_stop_list', // Mahsulot vaqtincha yo'qligi (stop-listga tushsa true bo'ladi)

            // --- SOLIQ VA FISKAL (Tax & Fiscal) ---
            'ikpu_code', // MXIK kodi (O'zbekiston fiskal cheki uchun shart)
            'vat_percent', // QQS foiz stavkasi (masalan: 12.00)

            // --- DO'KON VA QIDIRUV (Retail & Search) ---
            'brand_id',// Mahsulot brendi (masalan: Pepsi, Nestle)
            'search_keywords', // Tezkor qidiruv uchun teglar (masalan: "suv, gazli, koka")
            'is_weighable', // Tarozi mahsulotimi? (true bo'lsa kassa og'irlikni so'raydi)

            // --- ISHLAB CHIQARISH (Production) ---
            'prepare_time', // Taom tayyor bo'lishi uchun ketadigan vaqt (minutda)
            'weight_gram', // Tayyor mahsulotning sof og'irligi (grammda)

            // --- MODIFIKATORLAR ---
            'has_modifiers', // Mahsulotning qo'shimchalari bormi? (pishloq, sous va h.k.)

            // --- OMBOR HISOBI (Stock Control) ---
            'stock_quantity',// Ombordagi hozirgi qoldiq miqdori
            'min_stock_level',  // Ogohlantirish uchun minimal qoldiq miqdori
            'track_stock', // Ushbu mahsulot bo'yicha ombor hisobi yuritiladimi?

            // --- CHEGIRMALAR (Discount) ---
            'has_discount', // Mahsulotga chegirma amal qiladimi?
            'is_free', // Tekin mahsulot belgisi (string ko'rinishida)
            'discount_type', // Chegirma turi (foizda yoki naqd pulda)
            'discount_value',// Chegirma summasi yoki foizi
            'discount_x', // "X sotib olinsa..." aksiyasi uchun X miqdori
            'discount_y', // "...Y tekinga beriladi" aksiyasi uchun Y miqdori

            // --- QO'SHIMCHA SOZLAMALAR ---
            'is_active', // Mahsulot umumiy tizimda aktivmi?
            'image_url', // Mahsulot rasmi uchun havola
            'description', // Taom yoki mahsulot haqida batafsil ma'lumot
            'sort_order',// Menyuda chiqish tartib raqami (kichik raqam birinchi chiqadi)

            // --- TIZIM USTUNLARI ---
            'sync_id',// Tashqi tizimlar (cloud) bilan sinxronizatsiya kodi
    ];
}
