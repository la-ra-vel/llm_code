<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;
use FlyingApesInc\DeepSearch\Traits\DeepSearchable;

class Client extends Model
{
    use HasFactory, DeepSearchable;

    protected $fillable = [
        'title',
        'fname',
        'lname',
        'mobile',
        'wp_no',
        'email',
        'address',
        'city',
        'pincode',
        // 'visiting_date',
        'gender',
        'occupation',
        'createdBy'
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->fname} {$this->lname}";
    }

    public function client_cases()
    {
        return $this->hasMany(ClientCase::class, 'client_id');
    }




    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('clients');
        });

        static::updated(function () {
            Cache::forget('clients');
        });

        static::deleted(function () {
            Cache::forget('clients');
        });

        static::deleting(function ($client) {

            if ($client->client_cases()->count() > 0) {
                if (request()->ajax()) {
                    abort(422, "Client cannot be deleted because they have associated cases.");
                } else {
                    throw new \Exception("Client cannot be deleted because they have associated cases.");
                }
            }
        });
    }


}
