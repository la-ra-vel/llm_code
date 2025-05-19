<?php

namespace App\Rules;

use App\Models\PaymentDetail;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueFeeDetailForCasePayment implements ValidationRule
{
    protected $clientCasePid;
    protected $feeDescriptionId;
    protected $exceptId;

    public function __construct($clientCasePid, $feeDescriptionId, $exceptId = null)
    {
        $this->clientCasePid = $clientCasePid;
        $this->feeDescriptionId = $feeDescriptionId;
        $this->exceptId = $exceptId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // Check if the fee detail already exists for the given client_case_pid and fee_description_id
        $query = PaymentDetail::where('client_case_pid', $this->clientCasePid)
            ->where('fee_description_id', $this->feeDescriptionId);

        if ($this->exceptId) {
            $query->where('id', '!=', $this->exceptId);
        }

        $exists = $query->exists();

        if ($exists) {
            $fail('record is already exist, you can edit it.');
        }
    }

    public function message()
    {
        return 'A fee detail with the same description already exists for this client case.';
    }
}
