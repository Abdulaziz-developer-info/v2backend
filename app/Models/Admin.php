<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';
    protected $fillable = [
            'block', // 0-active, 1-block, 2-banned

            'name',
            'password',
            'avatar',
            'role',
            'message',

            'phone',       // telefon
            'telegram',    // telegram username yoki link
            'instagram',   // instagram username
            'whatsapp',    // whatsapp raqam yoki link

            'work_start',    // ish boshlanish vaqti
            'work_end',      // ish tugash vaqti
            'work_days',   // ["Mon","Tue","Wed"]
    ];
    protected $hidden = ['password'];
}
