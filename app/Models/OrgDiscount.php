<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgDiscount extends Model
{
    protected $table = 'org_discounts';

    protected $fillable = [
        'org_id',
        'discount_type',
        'discount_value',
        'message',
        'creator',
        'sync_id',
    ];
}
