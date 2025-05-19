<?php
namespace App\Services;

use App\Models\State;
use Illuminate\Support\Facades\Cache;


class StateService
{
    public function getAllStates()
    {

        $states = Cache::remember('state', 60, function () {
            return State::with([
                'country' => function ($query) {
                    // You can specify columns for the related model here if needed
                    $query->select('id', 'name'); // Example: Only select 'id' and 'name' columns from roles
                }
            ])
                ->select('id', 'name', 'country_id', 'status') // Specify the columns you need from the User model
                ->orderBy('id', 'DESC')
                ->get();
        });
        return $states;
    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        $userData = [
            'name' => $validatedData['name'],
            'country_id' => $validatedData['country_id'],
        ];
        if ($id) {
            $state = State::find($id);
            $state->update($userData);
        } else {
            $state = State::create($userData);
        }
        return $state;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $findData = State::find($data['ID']);

        if ($findData) {
            $findData->status = $data['status'];
            $findData->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('state');
            return $findData;
        }

    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = State::find($id);
        return $findData;
    }
    /******************************************************************************/
    public function searchInState($search)
    {
        $returnData = [];
        $data = State::where('name', 'LIKE', "%" . $search . "%")->get();
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
