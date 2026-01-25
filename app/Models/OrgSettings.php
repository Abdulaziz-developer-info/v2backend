<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgSettings extends Model
{
    protected $table = 'org_settings';

    protected $fillable = [
        'org_id',
        'wifi_name', //wfiri nomi
        'wifi_ip', // wifi ip
        'app_version', // app versiyasi
        'editor', // tahrirchi
        'sync_id', // yangilanish versiyasi idsi
        'global_sync_id', // global yangilanish versiyasi idsi (1,2,3,4,5,6,7,8,9,10)
    ];
}
