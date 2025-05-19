<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Country extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'status', 'createdBy'];

    public function states(): HasMany
    {
        return $this->hasMany(State::class, 'country_id');
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, State::class);
    }

    public function canBeDeleted()
    {
        // Check if any courts are linked to the country's cities
        return !$this->cities()->whereHas('courts')->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('country');
        });

        static::updated(function () {
            Cache::forget('country');
        });

        static::deleted(function () {
            Cache::forget('country');
        });
    }
}
