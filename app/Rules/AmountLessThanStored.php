<?php

namespace App\Rules;

use App\Models\FeeDetail;
use App\Models\PaymentDetail;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Rule;

class AmountLessThanStored implements ValidationRule
{
    protected $clientCasePid;
    protected $feeDetailId;

    public function __construct($clientCasePid, $feeDetailId)
    {
        $this->clientCasePid = $clientCasePid;
        $this->feeDetailId = $feeDetailId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Retrieve the fee detail records by the provided client_case_pid and fee_detail_id
        $feeDetails = FeeDetail::where('fee_description_id', $this->feeDetailId)
            ->where('client_case_pid', $this->clientCasePid)
            ->get();

        // Debug: Print the retrieved fee details
        if ($feeDetails->isEmpty()) {
            $fail('No fee details found for the specified client case and fee detail ID.');
            return;
        }

        // Sum the amounts
        $feeDetailAmount = $feeDetails->sum('amount');

        // Retrieve the total payment amount for the provided client_case_pid and fee_detail_id
        $paymentDetailAmount = PaymentDetail::where('fee_description_id', $this->feeDetailId)
            ->where('client_case_pid', $this->clientCasePid)
            ->sum('amount');

        $totalPaymentAmount = $value + $paymentDetailAmount;


        // Check if the user-provided amount or the total payments made exceed the total fee amount
        if ((int) $totalPaymentAmount > (int) $feeDetailAmount) {
            $remainingAmount = $feeDetailAmount - $paymentDetailAmount;
            $fail('remaining amount is ' . $remainingAmount);
            // $fail('The remaining amount is ' . $remainingAmount . '. The amount must not be greater than the stored amount for the specified client case and fee detail.');
        }
    }

    public function message()
    {
        return 'The amount must not be greater than the stored amount for the specified client case and fee detail.';
    }

}
