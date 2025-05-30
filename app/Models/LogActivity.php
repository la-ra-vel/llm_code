<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject', 'ip', 'agent','user_id','date','time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
