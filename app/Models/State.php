<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id', 'status', 'createdBy'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'state_id');
    }

    public function canBeDeleted()
    {
        // Check if the state has any cities
        return !$this->cities()->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('state');
        });

        static::updated(function () {
            Cache::forget('state');
        });

        static::deleted(function () {
            Cache::forget('state');
        });
    }
}
