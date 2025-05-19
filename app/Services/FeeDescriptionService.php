<?php
namespace App\Services;

use App\Models\FeeDescription;
use Illuminate\Support\Facades\Cache;


class FeeDescriptionService
{
    public function getAllData()
    {
        $data = Cache::remember('fee_description', 60, function () {
            return FeeDescription::get();
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
            $data = FeeDescription::find($id);
            $data->update($arrayData);
        } else {
            $data = FeeDescription::create($arrayData);
        }
        return $data;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $findData = FeeDescription::find($data['ID']);

        if ($findData) {
            $findData->status = $data['status'];
            $findData->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('fee_description');
            return $findData;
        }

    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = FeeDescription::find($id);
        return $findData;
    }
    /******************************************************************************/
}
