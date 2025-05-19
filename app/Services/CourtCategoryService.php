<?php
namespace App\Services;

use App\Models\Court;
use App\Models\CourtCategory;
use Illuminate\Support\Facades\Cache;


class CourtCategoryService
{
    public function getAllData()
    {

        $data = Cache::remember('court_category', 60, function () {
            return CourtCategory::with([
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
            $data = CourtCategory::find($id);
            $data->update($arrayData);
        } else {
            $data = CourtCategory::create($arrayData);
        }
        return $data;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $findData = CourtCategory::find($data['ID']);

        if ($findData) {
            $findData->status = $data['status'];
            $findData->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('court_category');
            return $findData;
        }

    }
    /******************************************************************************/
    public function findData($id = null)
    {
        $findData = CourtCategory::find($id);
        return $findData;
    }
    /******************************************************************************/
    public function searchInCourtCategory($search)
    {
        $returnData = [];
        $data = CourtCategory::where('name', 'LIKE', "%" . $search . "%")->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $courts = Court::select('id','court_name')->where('court_categoryID',$value->id)->get();
                $returnData[] = [
                    "value" => $value->name,
                    "id" => $value->id,
                    "name" => $value->name,
                    'data' => $courts
                ];
            }
            return $returnData;
        }
    }

    /******************************************************************************/
}
