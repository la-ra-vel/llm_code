<?php

namespace App\Http\Controllers\NewCase;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\NewCase\FeeDetailRequest;
use App\Models\ClientCase;
use App\Models\FeeDetail;
use App\Services\NewCase\FeeDetailsService;
use App\Services\NewCase\PaymentDetailsService;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;

class FeeDetailsController extends Controller
{
    protected $feeDetailService;
    protected $paymentDetailService;
    public function __construct(FeeDetailsService $feeDetailService, PaymentDetailsService $paymentDetailService)
    {
        $this->feeDetailService = $feeDetailService;
        $this->paymentDetailService = $paymentDetailService;
    }
    public function feeDetails(Request $request, $id = null)
    {
        if ($request->ajax()) {
            if ($request->ajax()) {
                $data = $this->feeDetailService->getCaseFeeDetails($id);
                return DataTables::of($data)
                    ->addIndexColumn()

                    ->addColumn('counter', function () {
                        static $counter = 1;
                        return $counter++;
                    })

                    ->addColumn('fee_description', function ($row) {
                        return $row->fee_description ? $row->fee_description->name : '';
                    })

                    ->addColumn('createdBy', function ($row) {
                        return $row->user ? $row->user->full_name : '';
                    })

                    ->addColumn('action', function ($row) {
                        $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                        return '
                        <a href="javascript:void(0);" data-URL="' . route('store.court.fee.details', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xs btn-primary updateFeeDetails"><i class="fas fa-pen"></i></a>
                        <a href="javascript:void(0);" data-tableID="fee_detailsTable" data-URL="' . route('delete.court.fee.details', $row->id) . '" class="btn btn-xs btn-danger deleteFeeDetails"><i class="fas fa-trash"></i></a>

                    ';
                    })

                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
    }
    /**********************************************************************/
    public function storeCourtFeeDetails(FeeDetailRequest $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CityRequest rules
                $validatedData = $request->validated();
                $saveData = $this->feeDetailService->store($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                $selectedFeeDetails = $this->feeDetailService->getCaseFeeDetails($saveData->client_case_pid);
                $selectedFeeDetails = $selectedFeeDetails->unique('fee_description_id')->values();
                $total_fees = $selectedFeeDetails->sum('amount');
                $payment_received = $this->paymentDetailService->getCasePaymentDetails($saveData->client_case_pid)->sum('amount');
                $pending_payment = $total_fees - $payment_received;
                $case = ClientCase::select('id','caseID')->findOrFail($validatedData['client_case_pid']);
                LogActivity::addToLog((string) $user->full_name . ' add/update a Case Fee Details against [CaseID: ' . @$case->caseID . ']');
                DB::commit();
                return response()->json([
                    'success' => true,
                    'status' => 200,
                    'message' => 'Data added successfully',
                    'court_detail_id' => $validatedData['client_case_pid'],
                    'saveDataID' => $saveData->id,
                    'selectedFeeDetails' => $selectedFeeDetails,
                    'total_fees' => $total_fees,
                    'payment_received' => $payment_received,
                    'pending_payment' => $pending_payment
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /**********************************************************************/
    public function deleteCourtFeeDetails(Request $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $findData = $this->feeDetailService->findData($id);
                $user = auth()->user();
                session()->put('client_case_pid',$findData->client_case_pid);
                $findData->delete();
                LogActivity::addToLog((string) $user->full_name . ' delete Fee Detail from Case');
                $client_case_pid = session('client_case_pid');
                $selectedFeeDetails = $this->feeDetailService->getCaseFeeDetails($client_case_pid);

                $total_fees = $selectedFeeDetails->sum('amount');
                $payment_received = $this->paymentDetailService->getCasePaymentDetails($client_case_pid)->sum('amount');
                $pending_payment = $total_fees - $payment_received;

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'calculations' => true,
                    'message' => 'Data is deleted successfully',
                    'total_fees' => $total_fees,
                    'payment_received' => $payment_received,
                    'pending_payment' => $pending_payment
                ]);

            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
}
