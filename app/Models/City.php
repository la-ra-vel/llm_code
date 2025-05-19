<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name','state_id','status','createdBy'];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class,'state_id');
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, State::class);
    }

    public function courts() {
        return $this->hasMany(Court::class, 'city_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('city');
        });

        static::updated(function () {
            Cache::forget('city');
        });

        static::deleted(function () {
            Cache::forget('city');
        });
    }
}
