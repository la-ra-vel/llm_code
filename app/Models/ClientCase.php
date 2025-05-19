<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class ClientCase extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_no',
        'caseID',
        'client_name',
        'client_mobile',
        'client_id',
        'court_catID',
        'court_case_no',
        'case_court_address',
        'case_location',
        'responded_adv',
        'responded_adv_mobile',
        'fir_no',
        'case_acts',
        'case_legal_matter',
        'opponent_name',
        'opponent_mobile',
        'opponent_address',
        'case_start_date',
        'case_end_date',
        'status',
        'counter_invoice_download',
        'createdBy'
    ];

    protected $casts = [
        'case_acts' => 'array',
        'case_start_date' => 'datetime',
        'case_end_date' => 'datetime'
    ];

    public function getCaseStartDate()
    {
        return $this->case_start_date ? $this->case_start_date->format('d F, Y') : null;
    }

    public function getCaseEndDate()
    {
        return $this->case_end_date ? $this->case_end_date->format('d F, Y') : null;
    }

    public static function getClientArray($data)
    {

        $dataArr = [];
        foreach ($data as $key => $value) {
            $dataArr[] = [
                'id' => $value->id,
                'name' => (string) $value->fname . ' ' . $value->lname
            ];
        }
        return $dataArr;
    }

    public static function getCaseActsArray($data)
    {

        $dataArr = [];
        foreach ($data as $key => $value) {
            $dataArr[] = [
                'id' => $value->id,
                'name' => $value->name
            ];
        }
        return $dataArr;
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function court_category()
    {
        return $this->belongsTo(CourtCategory::class, 'court_catID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    public function fee_details()
    {
        return $this->hasMany(FeeDetail::class, 'client_case_pid');
    }

    public function action_details()
    {
        return $this->hasMany(ActionDetail::class, 'client_case_pid');
    }
    public function payment_details()
    {
        return $this->hasMany(PaymentDetail::class, 'client_case_pid');
    }
    public function document_details()
    {
        return $this->hasMany(DocumentDetail::class, 'client_case_pid');
    }

    public function court()
    {
        return $this->belongsTo(Court::class, 'case_court_address', 'court_name');
    }

    protected static function boot()
    {
        parent::boot();

        // Adding the creating event
        static::creating(function ($model) {
            $latestCase = self::latest('invoice_no')->first();
            $model->invoice_no = $latestCase ? $latestCase->invoice_no + 1 : 1;
        });

        static::created(function () {
            Cache::forget('cases');
        });

        static::updated(function () {
            Cache::forget('cases');
        });

        static::deleted(function () {
            Cache::forget('cases');
        });
    }

    public static function getNextInvoiceNo()
    {
        $latestCase = self::latest('invoice_no')->first();
        return $latestCase ? $latestCase->invoice_no + 1 : 1;
    }

    public static function getNextCaseNo()
    {
        $latestCase = self::latest('caseID')->first();
        return $latestCase ? $latestCase->caseID + 1 : 1;
    }
}
