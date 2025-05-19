<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_case_pid',
        'payment_date',
        'amount',
        'fee_description_id',
        'payment_mode',
        'remarks',
        'createdBy'
    ];

    public function client_case()
    {
        return $this->belongsTo(ClientCase::class);
    }
    public function fee_description()
    {
        return $this->belongsTo(FeeDescription::class,'fee_description_id','id');
    }

    public function setPaymentDateAttribute($value)
    {
        try {
            // Attempt to parse the date in 'd F, Y' format
            $date = Carbon::createFromFormat('d F, Y', $value);
            $this->attributes['payment_date'] = $date->format('Y-m-d');
        } catch (\Exception $e) {
            // Log the error or handle it as needed

            // Optionally, you could set a default value or throw an exception
            // $this->attributes['payment_date'] = null;
            throw new \InvalidArgumentException('Invalid date format provided for payment_date.');
        }
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}
