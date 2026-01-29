<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppMenuCategory extends Model
{
    protected $table = 'app_menu_categories';

    protected $fillable = [
        'org_id', // organization identifier
        'name', // category name
        'sort', // tartib raqami
        'is_active', // faollik holati
        'sync_id',
        'deleted_at'
    ];
}
