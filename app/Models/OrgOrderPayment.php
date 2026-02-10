<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgOrderPayment extends Model
{
    protected $table = 'org_order_payments';

    protected $fillable = [
        'org_id',
        'user_id',
        'payment_id',
        'order_id',
        'check_id',
        'day_id',
        'reminder_time',
        'total_amount',
        'message',
        'creator',
        'sync_id',
    ];
}
