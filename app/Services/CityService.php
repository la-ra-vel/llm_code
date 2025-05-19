<?php
namespace App\Services;

use App\Models\City;
use Illuminate\Support\Facades\Cache;


class CityService
{
    public function getAllCities()
    {
        $cities = Cache::remember('city', 60, function () {
            return City::with([
                'state' => function ($query) {
                    $query->select('id', 'name', 'country_id');
                },
                'state.country' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->select('id', 'name', 'state_id','status') // Specify the columns you need from the City model
            ->orderBy('id', 'DESC')
            ->get();
        });

        return $cities;
    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        $userData = [
            'name' => $validatedData['name'],
            'state_id' => $validatedData['state_id'],
        ];
        if ($id) {
            $city = City::find($id);
            $city->update($userData);
        } else {
            $city = City::create($userData);
        }
        return $city;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $findData = City::find($data['ID']);

        if ($findData) {
            $findData->status = $data['status'];
            $findData->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('city');
            return $findData;
        }

    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = City::find($id);
        return $findData;
    }
    /******************************************************************************/
    public function searchInCity($search)
    {
        $returnData = [];
        $data = City::where('name', 'LIKE', "%" . $search . "%")->where('status','active')->get();
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
