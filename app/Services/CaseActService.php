<?php

namespace App\Services;

use App\Models\CaseAct;
use Illuminate\Support\Facades\Cache;

class CaseActService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllData()
    {

        $data = Cache::remember('case_acts', 60, function () {
            return CaseAct::with([
                'user' => function ($query) {
                    // You can specify columns for the related model here if needed
                    $query->select('id', 'fname','lname'); // Example: Only select 'id' and 'name' columns from table
                }
            ])
                ->select('id', 'name', 'status', 'createdBy') // Specify the columns you need from the model
                ->orderBy('id', 'DESC')
                ->get();
        });
        return $data;
    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        $arrayData = [
            'name' => $validatedData['name'],
        ];
        if ($id) {
            $data = CaseAct::find($id);
            $data->update($arrayData);
        } else {
            $data = CaseAct::create($arrayData);
        }
        return $data;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $findData = CaseAct::find($data['ID']);

        if ($findData) {
            $findData->status = $data['status'];
            $findData->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('case_acts');
            return $findData;
        }

    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = CaseAct::find($id);
        return $findData;
    }
    /******************************************************************************/
}
