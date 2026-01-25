<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelDefaultMenuCategory extends Model
{
    protected $table = 'panel_default_menu_categories';

    protected $fillable = [
        'name',
        'creator',
    ];
}
