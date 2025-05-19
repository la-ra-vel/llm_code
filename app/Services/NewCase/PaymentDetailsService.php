<?php

namespace App\Services\NewCase;

use App\Models\PaymentDetail;

class PaymentDetailsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getCasePaymentDetails($id)
    {
        $data = PaymentDetail::with([
            'user' => function ($query) {
                // You can specify columns for the related model here if needed
                $query->select('id', 'fname', 'lname'); // Example: Only select 'id' and 'name' columns from relation table
            },
            'fee_description' => function ($query) {
                // You can specify columns for the related model here if needed
                $query->select('id', 'name', 'name'); // Example: Only select 'id' and 'name' columns from relation table
            }
        ])
            ->select('id', 'payment_date', 'amount', 'fee_description_id', 'payment_mode','remarks', 'createdBy') // Specify the columns you need from the model
            ->where('client_case_pid', $id)
            ->orderBy('id', 'DESC')
            ->get();

        return $data;

    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        if ($id) {
            $data = PaymentDetail::find($id);
            $data->update($validatedData);
        } else {
            $data = PaymentDetail::create($validatedData);
        }
        return $data;
    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = PaymentDetail::find($id);
        return $findData;
    }
}
