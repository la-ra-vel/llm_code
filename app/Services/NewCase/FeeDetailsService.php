<?php

namespace App\Services\NewCase;

use App\Models\FeeDetail;
use Illuminate\Support\Facades\Cache;

class FeeDetailsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getCaseFeeDetails($id)
    {

        $data = FeeDetail::with([
            'user' => function ($query) {
                // You can specify columns for the related model here if needed
                $query->select('id', 'fname', 'lname'); // Example: Only select 'id' and 'name' columns from relation table
            },
            'fee_description' => function ($query) {
                // You can specify columns for the related model here if needed
                $query->select('id', 'name'); // Example: Only select 'id' and 'name' columns from relation table
            }
        ])
            ->select('id', 'client_case_pid', 'fee_description_id', 'amount', 'remarks','createdBy') // Specify the columns you need from the model
            ->where('client_case_pid', $id)
            ->orderBy('id', 'DESC')
            ->get();

        return $data;
    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        // echo "<pre>"; print_r($validatedData); exit;
        if ($id) {
            $data = FeeDetail::find($id);
            $data->update($validatedData);
        } else {
            $data = FeeDetail::create($validatedData);
        }
        return $data;
    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = FeeDetail::find($id);
        return $findData;
    }
}
