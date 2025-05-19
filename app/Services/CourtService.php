<?php
namespace App\Services;

use App\Models\ClientCase;
use App\Models\Court;
use Illuminate\Support\Facades\Cache;


class CourtService
{
    public function getAllData()
    {
        // $data = Cache::remember('courts', 60, function () {
        //     return Court::with([
        //         'user' => function ($query) {
        //             $query->select('id', 'fname', 'lname');
        //         },
        //         'city' => function ($query) {
        //             $query->select('id', 'name', 'state_id')->with([
        //                 'state' => function ($query) {
        //                     $query->select('id', 'name', 'country_id')->with([
        //                         'country' => function ($query) {
        //                             $query->select('id', 'name');
        //                         }
        //                     ]);
        //                 }
        //             ]);
        //         }
        //     ])
        //         ->select('id', 'city_id', 'court_categoryID', 'location', 'court_name', 'court_room_no', 'description', 'status', 'createdBy')
        //         ->orderBy('id', 'DESC')
        //         ->get();
        // });

        // return $data;
        $data = Cache::remember('courts', 60, function () {
            return Court::with([
                'city' => function ($query) {
                    $query->select('id', 'name');
                },
                'category' => function ($query) {
                    $query->select('id', 'name');
                },
                'user' => function ($query) {
                    $query->select('id', 'fname', 'lname');
                },
            ])
                ->select('id', 'city_id', 'court_categoryID', 'location', 'court_name', 'court_room_no', 'description', 'status', 'createdBy')
                ->orderBy('id', 'DESC')
                ->get();
        });

        return $data;
    }
    /******************************************************************************/
    public function store($validatedData, $id = '')
    {
        $arrayData = [
            'city_id' => $validatedData['city_id'],
            'court_categoryID' => $validatedData['court_categoryID'],
            'location' => $validatedData['location'],
            'court_name' => $validatedData['court_name'],
            'court_room_no' => $validatedData['court_room_no'],
            'description' => $validatedData['description'],
        ];
        if ($id) {
            $data = Court::find($id);
            $data->update($arrayData);
        } else {
            $data = Court::create($arrayData);
        }
        return $data;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $findData = Court::find($data['ID']);

        if ($findData) {
            $findData->status = $data['status'];
            $findData->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('courts');
            return $findData;
        }

    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = Court::find($id);
        return $findData;
    }
    /******************************************************************************/
    public function searchInCourtCaseID($search)
    {
        $returnData = [];
        $data = ClientCase::where('caseID', 'LIKE', "%" . $search . "%")->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $returnData[] = [
                    "value" => $value->caseID,
                    "id" => $value->caseID,
                    "name" => $value->caseID,
                ];
            }
            return $returnData;
        }
    }
    /******************************************************************************/
}
