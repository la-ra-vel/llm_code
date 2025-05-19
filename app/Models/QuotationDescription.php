<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDescription extends Model
{
    use HasFactory;
    protected $fillable = ['quotation_id', 'description', 'amount', 'date', 'createdBy'];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
    public function setDateAttribute($value)
    {
        date_default_timezone_set(config('app.timezone'));

        if ($value) {
            $this->attributes['date'] = date('Y-m-d H:i:s', strtotime($value));
        } else {
            $this->attributes['date'] = date('Y-m-d H:i:s');
        }
    }
}
