<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;

class CaseAct extends Model
{
    use HasFactory;

    protected $fillable = ['name','status','createdBy'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'createdBy');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('case_acts');
        });

        static::updated(function () {
            Cache::forget('case_acts');
        });

        static::deleted(function () {
            Cache::forget('case_acts');
        });

        static::deleting(function ($caseAct) {
            // Fetch all case_acts from the cases table
            $casesWithActs = DB::table('client_cases')
                ->whereJsonContains('case_acts', (string) $caseAct->id)
                ->count();

            // If the caseAct is found in any case's case_acts column, prevent deletion
            if ($casesWithActs > 0) {
                if (request()->ajax()) {
                    abort(422, "This act cannot be deleted because it is associated with one or more cases.");
                } else {
                    throw new Exception("This act cannot be deleted because it is associated with one or more cases.");
                }
            }
        });
    }
}
