<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';
    protected $fillable = [
        'block', // blocklash 1 block 2 bann
        'admin_id', // biriktirilgan admin
        'owner_id', // organizatsiya user id si
        'branch_id', // filial true false 
        'org_type', // organizatsion type bu nima qiladi bunda masalan restaranga yetkazib berish kichik fast food katta fililali restaranlar ni ajratish uchun shunchaki 
        'org_name', // organizatsiya nomi
        'address', //  manzil text 
        'location', // google yandex map
        'phone', // telefon raqami
        'logo', //  logosi
        'currency', //  valyutasi
        'timezone', // qaysi vaqt mintaqasida
        'start', // oylik boshlangan sanasi
        'end', //  oylik tugas sanasi
        'product_count', //  ruxsat berilgan mahsulot soni
        'payment', //  tulov summasi
        'auth', // auth uchun qr kod hash
        'message', //  eslatma text
        'status', // organizatsiya xolati
        'sync_id',
        'deleted_at'
    ];

    public function orgSettings()
    {
        return $this->belongsTo(OrgSettings::class, 'id', 'org_id');
    }
}
