<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgPayment extends Model
{
    protected $table = 'org_payments';

    protected $fillable = [
        'org_id',
        'pay_name',
        'pay_text',
        'pay_type',
        'active',
        'message',
        'creator',
        'sync_id',
    ];
}
