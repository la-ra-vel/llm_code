<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ActionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_case_pid',
        'hearing_date',
        'note',
        'createdBy'
    ];

    public function setHearingDateAttribute($value)
    {
        try {
            // Attempt to parse the date in 'd F, Y' format
            $date = Carbon::createFromFormat('d F, Y', $value);
            $this->attributes['hearing_date'] = $date->format('Y-m-d');
        } catch (\Exception $e) {
            // Log the error or handle it as needed

            // Optionally, you could set a default value or throw an exception
            // $this->attributes['hearing_date'] = null;
            throw new \InvalidArgumentException('Invalid date format provided for hearing_date.');
        }
    }

    public function client_case()
    {
        return $this->belongsTo(ClientCase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}
