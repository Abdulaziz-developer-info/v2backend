<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'users';
    protected $fillable = [
        'block',       // 0 = aktiv, 1 = bloklangan
        'active',     // 0 = noaktiv, 1 = aktiv
        'org_id',  // Foydalanuvchi qaysi restoran/orgga tegishli
        'is_guest', // Foydalanuvchi qaysi restoran/orgga tegishli

        'session_id',      // qaysi sessiya bilan ishlayotgani 
        'logged_in',      // login qilib kirgan bolsa 1 boladi va shu user accountiga boradi

        // Rol va yaratgan shaxs
        'role',        // admin, kassir, ofitsant, oshpaz, mijoz, kuryer, yetkazuvchi
        'creator',     // kim yaratgan (foydalanuvchi ID yoki nomi)

        // Shaxsiy ma'lumotlar
        'name', // tuliq ismi
        'login', // login
        'password', // paroli kyinchalik
        'avatar', // rasmi
        'email', // email
        'phone', // telefon raqami
        'phone_verified', // telefon tasdiqlanganmi 
        'verification_code', // telefon nomer tasdiqlagan kod

        // Ijtimoiy tarmoqlar (username / nickname)
        'instagram',   // Instagram nickname
        'telegram',    // Telegram username
        'whatsapp',    // WhatsApp raqami
        'twitter',     // Twitter username
        'facebook',    // Facebook username

        // Provider va tokenlar
        'provider',    // social login provider
        'provider_id', // provider id
        'auth',        // API auth
        'token',       // session yoki API token

        'sync_id',
        'deleted_at',

        // Qo‘shimcha
        'note',        // qo‘shimcha izohlar
        'message',     // foydalanuvchi bilan bog‘liq xabarlar
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }


}
