<?php

namespace App\Http\Controllers\NewCase;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\NewCase\DocumentRequest;
use App\Models\ClientCase;
use App\Services\NewCase\ActionsService;
use App\Services\NewCase\DocumentService;
use App\Services\NewCase\FeeDetailsService;
use App\Services\NewCase\PaymentDetailsService;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;

class DocumentController extends Controller
{
    protected $documentService;
    protected $paymentDetailService;
    protected $feeDetailService;
    public function __construct(
        DocumentService $documentService,
        PaymentDetailsService $paymentDetailService,
        FeeDetailsService $feeDetailService
    ) {
        $this->documentService = $documentService;
        $this->paymentDetailService = $paymentDetailService;
        $this->feeDetailService = $feeDetailService;
    }
    public function documentDetails(Request $request, $id = null)
    {
        if ($request->ajax()) {
            if ($request->ajax()) {
                $data = $this->documentService->getCaseDocuments($id);
                // echo "<pre>"; print_r($data->toArray()); exit;
                return DataTables::of($data)
                    ->addIndexColumn()

                    ->addColumn('counter', function () {
                        static $counter = 1;
                        return $counter++;
                    })

                    ->addColumn('file', function ($row) {
                        // return $row->fee_description ? $row->fee_description->name : '';
                        return '<a href="'. asset('uploads/case_documents/'.$row->file) .'" class="openDocument" style="cursor: pointer;">' . $row->file . '</a>';
                    })

                    ->addColumn('createdBy', function ($row) {
                        return $row->user ? $row->user->full_name : '';
                    })

                    ->addColumn('action', function ($row) {
                        $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                        return '
                        <a href="javascript:void(0);" data-URL="' . route('store.court.document.details', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xs btn-primary updateDocumentDetails"><i class="fas fa-pen"></i></a>
                        <a href="javascript:void(0);" data-tableID="document_detailsTable" data-URL="' . route('delete.court.document.details', $row->id) . '" class="btn btn-xs btn-danger deleteDocumentDetails"><i class="fas fa-trash"></i></a>

                    ';
                    })

                    ->rawColumns(['file', 'action'])
                    ->make(true);
            }
        }
    }
    /**********************************************************************/
    public function storeCourtDocumentDetails(DocumentRequest $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CityRequest rules
                $validatedData = $request->validated();
                // echo "<pre>"; print_r($validatedData); exit;
                $saveData = $this->documentService->store($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $selectedFeeDetails = $this->feeDetailService->getCaseFeeDetails($saveData->client_case_pid);
                $selectedFeeDetails = $selectedFeeDetails->unique('fee_description_id')->values();
                $total_fees = $selectedFeeDetails->sum('amount');
                $payment_received = $this->paymentDetailService->getCasePaymentDetails($saveData->client_case_pid)->sum('amount');
                $pending_payment = $total_fees - $payment_received;
                $user = auth()->user();
                $case = ClientCase::select('id', 'caseID')->findOrFail($validatedData['client_case_pid']);
                LogActivity::addToLog((string) $user->full_name . ' add/update a Case Document against [CaseID: ' . @$case->caseID . ']');
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
    public function deleteCourtDocumentDetails(Request $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $findData = $this->documentService->findData($id);
                unLinkFile('case_documents',$findData->file);
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' delete Document Detail from Case');

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
