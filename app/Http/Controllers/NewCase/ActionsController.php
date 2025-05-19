<?php

namespace App\Http\Controllers\NewCase;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\NewCase\ActionDetailRequest;
use App\Models\ClientCase;
use App\Services\NewCase\ActionsService;
use App\Services\NewCase\FeeDetailsService;
use App\Services\NewCase\PaymentDetailsService;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;

class ActionsController extends Controller
{
    protected $actionService;
    protected $paymentDetailService;
    protected $feeDetailService;
    public function __construct(
        ActionsService $actionService,
        PaymentDetailsService $paymentDetailService,
        FeeDetailsService $feeDetailService
    ) {
        $this->actionService = $actionService;
        $this->paymentDetailService = $paymentDetailService;
        $this->feeDetailService = $feeDetailService;
    }
    public function actionDetails(Request $request, $id = null)
    {
        if ($request->ajax()) {
            if ($request->ajax()) {
                $data = $this->actionService->getCaseActions($id);
                return DataTables::of($data)
                    ->addIndexColumn()

                    ->addColumn('counter', function () {
                        static $counter = 1;
                        return $counter++;
                    })

                    ->addColumn('action_details', function ($row) {
                        return $row->fee_description ? $row->fee_description->name : '';
                    })

                    ->addColumn('createdBy', function ($row) {
                        return $row->user ? $row->user->full_name : '';
                    })

                    ->addColumn('action', function ($row) {
                        $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                        return '
                        <a href="javascript:void(0);" data-URL="' . route('store.court.actions.details', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xs btn-primary updateActionDetails"><i class="fas fa-pen"></i></a>
                        <a href="javascript:void(0);" data-tableID="action_detailsTable" data-URL="' . route('delete.court.action.details', $row->id) . '" class="btn btn-xs btn-danger deleteActionDetails"><i class="fas fa-trash"></i></a>

                    ';
                    })

                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
    }
    /**********************************************************************/
    public function storeCourtActionDetails(ActionDetailRequest $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CityRequest rules
                $validatedData = $request->validated();
                $saveData = $this->actionService->store($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $selectedFeeDetails = $this->feeDetailService->getCaseFeeDetails($saveData->client_case_pid);
                $selectedFeeDetails = $selectedFeeDetails->unique('fee_description_id')->values();
                $total_fees = $selectedFeeDetails->sum('amount');
                $payment_received = $this->paymentDetailService->getCasePaymentDetails($saveData->client_case_pid)->sum('amount');
                $pending_payment = $total_fees - $payment_received;
                $user = auth()->user();
                $case = ClientCase::select('id','caseID')->findOrFail($validatedData['client_case_pid']);
                LogActivity::addToLog((string) $user->full_name . ' add/update a Case Action Details against [CaseID: ' . @$case->caseID . ']');
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
    public function deleteCourtActionDetails(Request $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $findData = $this->actionService->findData($id);
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' delete Action Detail from Case');

                $findData->delete();
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'calculations' => false,
                    'message' => 'Data is deleted successfully'
                ]);

            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
}
