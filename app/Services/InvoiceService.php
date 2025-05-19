<?php

namespace App\Services;

use App\Models\ClientCase;
use Illuminate\Support\Facades\Cache;

use App\Services\NewCase\PaymentDetailsService;
use App\Services\NewCase\FeeDetailsService;

class InvoiceService
{
    protected $paymentDetailService;
    protected $feeDetailService;
    /**
     * Create a new class instance.
     */
    public function __construct(
        PaymentDetailsService $paymentDetailService,
        FeeDetailsService $feeDetailService
    ) {
        $this->paymentDetailService = $paymentDetailService;
        $this->feeDetailService = $feeDetailService;
    }
    /******************************************************************************/
    public function getAllInvoices()
    {
        $data = Cache::remember('cases', 60, function () {
            return ClientCase::with([
                'client' => function ($query) {
                    $query->select('id', 'fname', 'lname', 'mobile','email');
                }
            ])
                ->select('client_cases.id', 'client_cases.client_id', 'client_cases.caseID', 'client_cases.invoice_no','client_cases.counter_invoice_download')
                ->withSum('fee_details as total_fee_amount', 'amount') // Calculate the sum of the 'amount' column for fee_details
                ->withSum('payment_details as total_payment_amount', 'amount') // Calculate the sum of the 'amount' column for payment_details
                ->orderBy('client_cases.id', 'DESC')
                ->get();
        });

        return $data;
    }

    public function getAmountsByFeeDescription($clientCasePid)
    {
        $clientCase = ClientCase::with([
            'client' => function ($query) {
                // You can specify columns for the related model here if needed
                $query->select('id', 'fname', 'lname', 'pincode','email','address'); // Example: Only select 'id' and 'name' columns from roles
            },
            'payment_details.fee_description',
            'fee_details.fee_description'
        ])
        ->select('id','client_id','caseID','invoice_no')
            ->where('id', $clientCasePid)
            ->first();

        if (!$clientCase) {
            return [];
        }

        $paymentDetails = $clientCase->payment_details;
        $feeDetails = $clientCase->fee_details;
        $client = $clientCase->client;

        $mergedResults = [];
        $totalFeeAmount = 0;
        $totalPaymentAmount = 0;

        foreach ($feeDetails as $feeDetail) {
            $feeDescriptionId = $feeDetail->fee_description_id;
            if (!isset($mergedResults[$feeDescriptionId])) {
                $mergedResults[$feeDescriptionId] = [
                    'fee_description' => $feeDetail->fee_description->name,
                    'fee_amount' => 0,
                    'payment_amount' => 0
                ];
            }
            $mergedResults[$feeDescriptionId]['fee_amount'] += $feeDetail->amount;
            $totalFeeAmount += $feeDetail->amount;
        }

        foreach ($paymentDetails as $paymentDetail) {
            $feeDescriptionId = $paymentDetail->fee_description_id;
            if (!isset($mergedResults[$feeDescriptionId])) {
                $mergedResults[$feeDescriptionId] = [
                    'fee_description' => $paymentDetail->fee_description->name,
                    'fee_amount' => 0,
                    'payment_amount' => 0
                ];
            }
            $mergedResults[$feeDescriptionId]['payment_amount'] += $paymentDetail->amount;
            $totalPaymentAmount += $paymentDetail->amount;
        }

        return [
            'clientCase' => $clientCase,
            'client' => $client,
            'results' => $mergedResults,
            'total_fee_amount' => $totalFeeAmount,
            'total_payment_amount' => $totalPaymentAmount
        ];
    }
}
