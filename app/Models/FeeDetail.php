<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_case_pid',
        'fee_description_id',
        'amount',
        'remarks',
        'createdBy'
    ];

    public function client_case()
    {
        return $this->belongsTo(ClientCase::class);
    }
    public function fee_description()
    {
        return $this->belongsTo(FeeDescription::class, 'fee_description_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($feeDetail) {
            // Delete related payment details
            // PaymentDetail::where('client_case_pid', $feeDetail->client_case_pid)
            //     ->where('fee_description_id', $feeDetail->fee_description_id)
            //     ->delete();
            $data = PaymentDetail::where('client_case_pid', $feeDetail->client_case_pid)
                ->where('fee_description_id', $feeDetail->fee_description_id)
                ->get();
            if ($data->count() > 0) {
                if (request()->ajax()) {
                    abort(422, "Fee Detail cannot be deleted because they have associated case payments.");
                } else {
                    throw new \Exception("Fee Detail cannot be deleted because they have associated case payments.");
                }
            }
        });
    }
}
