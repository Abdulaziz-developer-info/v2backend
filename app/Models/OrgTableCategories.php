<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgTableCategories extends Model
{
    protected $table = 'org_table_categories';

    protected $fillable = [
        'org_id',
        'name',
        'creator',
        'sort',
        'sync_id',
    ];
}
