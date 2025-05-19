<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class CourtCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'createdBy'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class, 'court_categoryID');
    }

    public function court_case(): HasMany
    {
        return $this->hasMany(ClientCase::class, 'court_catID');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('court_category');
        });

        static::updated(function () {
            Cache::forget('court_category');
        });

        static::deleted(function () {
            Cache::forget('court_category');
        });

        static::deleting(function ($category) {
            // Check if the category has any associated courts
            if ($category->courts()->count() > 0) {
                if (request()->ajax()) {
                    abort(422, "Category cannot be deleted because it has associated courts.");
                } else {
                    throw new \Exception("Category cannot be deleted because it has associated courts.");
                }
            }

            if ($category->court_case()->count() > 0) {
                if (request()->ajax()) {
                    abort(422, "Category cannot be deleted because it has associated cases.");
                } else {
                    throw new \Exception("Category cannot be deleted because it has associated cases.");
                }
            }

        });
    }
}
