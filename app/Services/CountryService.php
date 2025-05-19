<?php
namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\Cache;


class CountryService
{
    public function getAllCountry()
    {
        $users = Cache::remember('country', 60, function () {
            return Country::get();
        });
        return $users;
    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        $userData = [
            'name' => $validatedData['name'],
            'code' => $validatedData['code'],
        ];
        if ($id) {
            $country = Country::find($id);
            $country->update($userData);
        } else {
            $country = Country::create($userData);
        }
        return $country;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $findData = Country::find($data['ID']);

        if ($findData) {
            $findData->status = $data['status'];
            $findData->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('country');
            return $findData;
        }

    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = Country::find($id);
        return $findData;
    }
    /******************************************************************************/
    public function searchInCountry($search)
    {
        $returnData = [];
        $data = Country::where('name', 'LIKE', "%" . $search . "%")->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $returnData[] = [
                    "value" => $value->name,
                    "id" => $value->id,
                    "name" => $value->name,
                ];
            }
            return $returnData;
        }
    }
}
