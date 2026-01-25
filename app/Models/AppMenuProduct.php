<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppMenuProduct extends Model
{
    protected $table = 'app_menu_products';

    protected $fillable = [
        'org_id', // organization id
        'category_id', // category id
        'sort', // tartib raqami
        'name', // nomi
        'price', // narxi 
        'delivery_price', // yetkazib berish narxi
        'cost_price', // tannarxi
        'unit', // o'lchov birligi
        'stock_quantity', // omborda mavjud soni
        'tax_rate', // soliq stavkasi
        'product_type', // mahsulot turi cambo simple, variable va boshqalar yaniy bu kambomi boshqami turlari
        'is_active', // faolmi
        'is_free', // bepulmi 1 yozilsa birinchisi bepul 2 yozilsa ikkitasi bepul qolgani pullik 
        'discount_type', // chegirma turi foiz yoki summa
        'discount_value', // chegirma qiymati
        'discount_x', // x dona olsa y dona bepul
        'discount_y', // x dona olsa y dona bepul
        'image_url', // rasm url
        'message', // izohlar mahsulot haqida hisoh yozadida 
    ];
}
