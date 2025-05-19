<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Court extends Model
{
    use HasFactory;

    protected $fillable = ['city_id','court_categoryID','location','court_name','court_room_no','description','status','createdBy'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'createdBy');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourtCategory::class,'court_categoryID');
    }

    public function client_cases()
    {
        return $this->hasMany(ClientCase::class, 'case_court_address', 'court_name');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('courts');
        });

        static::updated(function () {
            Cache::forget('courts');
        });

        static::deleted(function () {
            Cache::forget('courts');
        });
    }
}
