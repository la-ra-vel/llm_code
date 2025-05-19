<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = ['quotation_no', 'date', 'time', 'subject', 'client_name', 'client_mobile', 'client_address', 'status', 'createdBy'];

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
    public function quotation_description()
    {
        return $this->hasMany(QuotationDescription::class,'quotation_id','id');
    }

    public function setDateAttribute($value)
    {
        try {
            // Attempt to parse the date in 'd F, Y' format
            $date = Carbon::createFromFormat('d F, Y', $value);
            $this->attributes['date'] = $date->format('Y-m-d');
        } catch (\Exception $e) {
            // Log the error or handle it as needed

            // Optionally, you could set a default value or throw an exception
            // $this->attributes['date'] = null;
            throw new \InvalidArgumentException('Invalid date format provided for date.');
        }
    }

    public function setTimeAttribute($value)
    {
        date_default_timezone_set(config('app.timezone'));

        if ($value) {
            $this->attributes['time'] = date('H:i:s', strtotime($value));
        } else {
            $this->attributes['time'] = date('H:i:s');
        }
    }


    protected static function boot()
    {
        parent::boot();

        // Adding the creating event
        static::creating(function ($model) {
            $latestQuotation = self::latest('quotation_no')->first();
            $model->quotation_no = $latestQuotation ? $latestQuotation->quotation_no + 1 : 1;
        });

        static::created(function () {
            Cache::forget('quotations');
        });

        static::updated(function () {
            Cache::forget('quotations');
        });

        static::deleted(function () {
            Cache::forget('quotations');
        });
    }

    public static function getNextQuotationNo()
    {
        $latestQuotation = self::latest('quotation_no')->first();
        return $latestQuotation ? $latestQuotation->quotation_no + 1 : 1;
    }
}
