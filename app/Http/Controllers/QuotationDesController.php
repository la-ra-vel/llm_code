<?php

namespace App\Http\Controllers;

use App\Http\Helpers\LogActivity;
use App\Http\Requests\QuotationDesRequest;
use App\Models\Quotation;
use App\Services\QuotationService;
use Auth;
use Illuminate\Http\Request;
use DB;

class QuotationDesController extends Controller
{
    protected $quotationService;
    public function __construct(QuotationService $quotationService)
    {
        $this->quotationService = $quotationService;
    }
    /********************************************************************/
    public function storeQuotationDescription(QuotationDesRequest $request,$id=null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using QuotationRequest rules
                $validatedData = $request->validated();
                $saveData = $this->quotationService->storeQuotationDesc($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                $quotation = Quotation::select('id','quotation_no')->find($request->quotation_id);
                $quotationDescriptions = $this->quotationService->findQuotationDescriptionsByQuotationID($request->quotation_id);
                LogActivity::addToLog((string) $user->full_name . ' added a Quotation Description [Quotation #: ' . $quotation->quotation_no . ']');
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Data added successfully','data'=>$quotationDescriptions]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
    /********************************************************************/
    public function deleteQuotationDescription(Request $request, $id=null)
    {
        if ($request->ajax()) {
            try {
                $findData = $this->quotationService->findQuotationDescriptions($id);
                $user = auth()->user();
                session()->put('quotation_id',$findData->quotation_id);
                $quotation = Quotation::select('id','quotation_no')->find($findData->quotation_id);

                LogActivity::addToLog((string) $user->full_name . ' Delete a Quotation Description [Quotation #: ' . $quotation->quotation_no . ']');
                $quotationID = session('quotation_id');
                $findData->delete();
                $quotationDescriptions = $this->quotationService->findQuotationDescriptionsByQuotationID($quotationID);
                return response()->json(['success' => true, 'message' => 'Data is deleted successfully','data'=>$quotationDescriptions]);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
}
