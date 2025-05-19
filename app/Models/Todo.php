<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'date', 'time', 'is_complete', 'createdBy'];

    public static function boot()
    {
        parent::boot();

        // Handle the 'creating' event
        static::creating(function ($model) {
            $model->setDefaultDateAndTime();
        });

        // Handle the 'updating' event
        static::updating(function ($model) {
            $model->setDefaultDateAndTime();
        });
    }

    // Method to set default date and time
    protected function setDefaultDateAndTime()
    {
        date_default_timezone_set(config('app.timezone'));

        if (empty($this->date)) {
            $this->date = date('Y-m-d');
        }

        if (empty($this->time)) {
            $this->time = date('h:i A', strtotime(date('Y-m-d H:i:s')));
        }

        if (empty($this->createdBy)) {
            $this->createdBy = Auth::guard('web')->user()->id;
        }
    }
}
