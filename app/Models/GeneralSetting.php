<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $table = 'general_settings';

    protected $casts = [
        'smtp_config' => 'object',
        'login_page' => 'object'
    ];
}
