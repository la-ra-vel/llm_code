<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FeeDescription extends Model
{
    use HasFactory;
    protected $fillable = ['name','status','createdBy'];

    public function payment_details()
    {
        return $this->hasMany(PaymentDetail::class, 'fee_description_id', 'id');
    }

    public function fee_details()
    {
        return $this->hasMany(FeeDetail::class, 'fee_description_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('fee_description');
        });

        static::updated(function () {
            Cache::forget('fee_description');
        });

        static::deleted(function () {
            Cache::forget('fee_description');
        });

        static::deleting(function ($record) {


            if ($record->fee_details()->count() > 0) {
                if (request()->ajax()) {
                    abort(422, "record cannot be deleted because they have associated case.");
                } else {
                    throw new \Exception("record cannot be deleted because they have associated case.");
                }
            }
        });
    }
}
