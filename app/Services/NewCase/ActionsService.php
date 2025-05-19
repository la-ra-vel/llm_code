<?php

namespace App\Services\NewCase;

use App\Models\ActionDetail;

class ActionsService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getCaseActions($id)
    {
        $data = ActionDetail::with([
            'user' => function ($query) {
                // You can specify columns for the related model here if needed
                $query->select('id', 'fname', 'lname'); // Example: Only select 'id' and 'name' columns from relation table
            }
        ])
            ->select('id', 'hearing_date', 'note', 'createdBy') // Specify the columns you need from the model
            ->where('client_case_pid', $id)
            ->orderBy('id', 'DESC')
            ->get();

        return $data;

    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        if ($id) {
            $data = ActionDetail::find($id);
            $data->update($validatedData);
        } else {
            $data = ActionDetail::create($validatedData);
        }
        return $data;
    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = ActionDetail::find($id);
        return $findData;
    }
}
