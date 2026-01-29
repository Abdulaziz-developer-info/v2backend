<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountSession extends Model
{
    protected $table = 'account_sessions';

    protected $fillable = [
        'status',
        'org_id',
        'user_id',
        'main_account',
        'seen',
        'token',
        
        'ip_address',
        'location',
        'device_type',
        'device_name',
        'device_id',

        'last_activity_at',
        'login_at',
        'logout_at',
        'is_active',
        'fcm_token',
        'sync_id',
        'deleted_at'
    ];
}
