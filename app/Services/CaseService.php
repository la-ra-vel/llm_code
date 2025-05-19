<?php
namespace App\Services;

use App\Models\CaseAct;
use App\Models\City;
use App\Models\ClientCase;
use App\Models\FeeDescription;
use App\Models\FeeDetail;
use Illuminate\Support\Facades\Cache;


class CaseService
{

    public function getAllCases()
    {
        $data = Cache::remember('cases', 60, function () {
            return ClientCase::with([
                'client' => function ($query) {
                    // You can specify columns for the related model here if needed
                    $query->select('id', 'fname','lname'); // Example: Only select 'id' and 'name' columns from modal
                },
                'user' => function ($query) {
                    // You can specify columns for the related model here if needed
                    $query->select('id', 'fname','lname'); // Example: Only select 'id' and 'name' columns from modal
                }
            ])
                ->select('id', 'client_id', 'caseID', 'case_legal_matter', 'opponent_name', 'createdBy','status') // Specify the columns you need from the model
                ->orderBy('id', 'DESC')
                ->get();
        });
        return $data;

        // return User::with('role')->orderBy('id', 'DESC')->get();

    }
    public function getFormData()
    {
        $feeDescription = FeeDescription::select('id','name')->where('status','active')->get()->toArray();
        $caseActs = CaseAct::select('id','name')->where('status','active')->get()->toArray();
        $caseActsArr = array_map(fn($value) => ['id' => $value['id'], 'name' => $value['name']], $caseActs);
        $feeDescriptionArr = array_map(fn($value) => ['id' => $value['id'], 'name' => $value['name']], $feeDescription);

        $selectedRoleId = '';
        return [
            'feeDescription' => $feeDescriptionArr,
            'selectedRoleId' => $selectedRoleId,
            'caseActs' => $caseActsArr
        ];
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $findData = ClientCase::find($data['ID']);

        if ($findData) {
            $findData->status = $data['status'];
            if ($data['status']=='open') {
                $findData->case_end_date = null;
            }else{
                $findData->case_end_date = date('Y-m-d');
            }
            $findData->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('cases');
            return $findData;
        }

    }
    /******************************************************************************/
    public function findClientCase($id = null)
    {
        $data = ClientCase::with('document_details')->find($id);
        return $data;
    }

}

