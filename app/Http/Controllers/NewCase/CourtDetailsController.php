<?php

namespace App\Http\Controllers\NewCase;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\NewCase\CourtDetailRequest;
use App\Services\NewCase\CourtDetailsService;
use App\Services\NewCase\FeeDetailsService;
use App\Services\NewCase\PaymentDetailsService;
use Auth;
use Illuminate\Http\Request;
use DB;

class CourtDetailsController extends Controller
{
    protected $courtDetailService;
    protected $paymentDetailService;
    protected $feeDetailService;
    public function __construct(
        CourtDetailsService $courtDetailService,
        PaymentDetailsService $paymentDetailService,
        FeeDetailsService $feeDetailService
    ) {
        $this->courtDetailService = $courtDetailService;
        $this->paymentDetailService = $paymentDetailService;
        $this->feeDetailService = $feeDetailService;
    }
    /**********************************************************************/
    public function storeCourtDetails(CourtDetailRequest $request, $id = null)
{
    if ($request->ajax()) {
        DB::beginTransaction();
        try {
            // Validate the request using CityRequest rules
            $validatedData = $request->validated();
            // echo "<pre>"; print_r($validatedData); exit;
            $saveData = $this->courtDetailService->store($validatedData, $id);

            $saveData->createdBy = Auth::guard('web')->user()->id;

            $selectedFeeDetails = $this->feeDetailService->getCaseFeeDetails($saveData->id);
            $selectedFeeDetails = $selectedFeeDetails->unique('fee_description_id')->values();
            $total_fees = $selectedFeeDetails->sum('amount');
            $payment_received = $this->paymentDetailService->getCasePaymentDetails($saveData->id)->sum('amount');
            $pending_payment = $total_fees - $payment_received;

            // Check if the user has opted to skip the pending payment check
            if ($validatedData['case_end_date'] && $pending_payment > 0 && !$request->get('skipPendingPaymentCheck', false)) {
                return response()->json([
                    'court_details' => true,
                    'success' => false,
                    'message' => (string)$pending_payment . ' Payment is pending, do you still want to Close the Case?'
                ]);
            }
            // if ($saveData->status == 'close') {
            //     return response()->json([
            //         'court_details' => true,
            //         'success' => false,
            //         'message' => 'Case is already closed'
            //     ]);
            // }
            if ($request->get('skipPendingPaymentCheck')) {
                $saveData->status = 'close';
            }

            $saveData->save();

            $user = auth()->user();
            LogActivity::addToLog((string) $user->full_name . ' added a new Case [CaseID: ' . $saveData->caseID . ']');
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Data added successfully',
                'court_detail_id' => $saveData->id,
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
    public function updateCourtDetails(CourtDetailRequest $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CityRequest rules
                $validatedData = $request->validated();
                $saveData = $this->courtDetailService->store($validatedData, $id = '');
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $selectedFeeDetails = $this->feeDetailService->getCaseFeeDetails($saveData->id);
                $selectedFeeDetails = $selectedFeeDetails->unique('fee_description_id')->values();
                $total_fees = $selectedFeeDetails->sum('amount');
                $payment_received = $this->paymentDetailService->getCasePaymentDetails($saveData->id)->sum('amount');
                $pending_payment = $total_fees - $payment_received;
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' added a new Case [CaseID: ' . $saveData->caseID . ']');
                DB::commit();
                return response()->json([
                    'success' => true,
                    'status' => 200,
                    'message' => 'Data added successfully',
                    'court_detail_id' => $saveData->id,
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
}
