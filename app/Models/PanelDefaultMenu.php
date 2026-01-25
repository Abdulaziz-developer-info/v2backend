<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelDefaultMenu extends Model
{
    protected $table = 'panel_default_menus';
    protected $fillable = [
        'creator',
        'category_id',
        'name',
        'image',
        'description',
        'price',
    ];
}
