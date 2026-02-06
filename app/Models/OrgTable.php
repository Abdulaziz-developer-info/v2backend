<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgTable extends Model
{
    protected $table = 'org_tables';

    protected $fillable = [
        'org_id',
        'category_id',
        // Asosiy ma'lumotlar
        'name',
        'number',
        'capacity',
        'sort',
        'is_active',
        'status', // Masalan: 'empty', 'occupied', 'reserved'

        // Xizmat va Chegirma
        'service_type', // none, percent, fixed
        'service_value',

        // UI/Dizayn parametrlari (Flutter bilan moslik)
        'pos_x',
        'pos_y',
        'width',
        'height',
        'shape', // square, rectangle
        'border_radius',
        'icon_code',
        'color',

        // Tizim ustunlari
        'sync_id',
    ];
}
