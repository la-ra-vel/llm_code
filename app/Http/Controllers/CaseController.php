<?php

namespace App\Http\Controllers;

use App\Http\Helpers\LogActivity;
use App\Models\CaseAct;
use App\Models\Client;
use App\Models\ClientCase;
use App\Models\Court;
use App\Services\CaseService;
use App\Services\NewCase\FeeDetailsService;
use App\Services\NewCase\PaymentDetailsService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Session;
use DB;

class CaseController extends Controller
{
    protected $caseService;
    protected $paymentDetailService;
    protected $feeDetailService;
    public function __construct(
        CaseService $caseService,
        PaymentDetailsService $paymentDetailService,
        FeeDetailsService $feeDetailService
    ) {
        $this->caseService = $caseService;
        $this->paymentDetailService = $paymentDetailService;
        $this->feeDetailService = $feeDetailService;
    }
    /********************************************************************/
    public function authenticateRole($roles = null)
    {
        $permissionRole = [];
        foreach ($roles as $key => $value) {

            $permissionCheck = checkRolePermission($value);

            $permissionRole[] = [
                'role' => $value,
                'access' => $permissionCheck->access
            ];
        }

        if (@$permissionRole[0]['access'] == 0 && @$permissionRole[1]['access'] == 0) {
            Session::flash('flash_message_warning', 'You have no permission');
            return redirect(route('dashboard'))->send();
        }
    }
    /********************************************************************/
    public function index()
    {
        $roles = [
            '0' => 'cases'
        ];
        $this->authenticateRole($roles);
        $pageTitle = "Case List";
        return view('case.index', compact('pageTitle'));
    }
    /******************************************************************************/
    public function caseList(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->caseService->getAllCases();
            // echo "<pre>"; print_r($data->toArray()); exit;
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })
                ->editColumn('client', function ($row) {

                    return $row->client ? $row->client->full_name : '';
                })

                ->editColumn('opponentName', function ($row) {

                    return $row->opponent_name ? $row->opponent_name : '';
                })

                ->editColumn('legal_matter', function ($row) {

                    return $row->case_legal_matter ? $row->case_legal_matter : '';
                })

                ->editColumn('createdBy', function ($row) {

                    return $row->user ? $row->user->full_name : '';
                })


                ->editColumn('status', function ($row) {
                    if ($row->status == 'open') {
                        return '<span class="badge light badge-success updateStatus" data-tableID="caseTable" data-URL="' . route('update.case.status') . '" data-ID="' . $row->id . '" data-Status="close" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    } else {
                        return '<span class="badge light badge-danger updateStatus" data-tableID="caseTable" data-URL="' . route('update.case.status') . '" data-ID="' . $row->id . '" data-Status="open" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    }

                })

                ->addColumn('action', function ($row) {
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    return '
                    <a class="btn btn-xs btn-primary" target="_blank"  href="' . route('edit.case', $row->id) . '"><i class="fas fa-pen"></i></a>
                    ';
                })




                ->rawColumns(['logo', 'status', 'action'])
                ->make(true);
        }
    }

    /******************************************************************************/
    public function create()
    {
        $roles = [
            '0' => 'cases'
        ];
        $this->authenticateRole($roles);
        $pageTitle = "Create Case";
        $data = $this->caseService->getFormData();
        $caseID = ClientCase::getNextCaseNo();
        $editCase = [];
        $caseActIds = [];
        $clients = [];
        $startDate = '';
        $endDate = '';
        $selectedFeeDetails = [];
        $total_fees = 0;
        $payment_received = 0;
        $pending_payment = 0;

        return view(
            'case.create',
            compact(
                'pageTitle',
                'data',
                'caseID',
                'editCase',
                'caseActIds',
                'clients',
                'startDate',
                'endDate',
                'selectedFeeDetails',
                'total_fees',
                'payment_received',
                'pending_payment'
            )
        );
    }
    /******************************************************************************/
    public function edit($id = null)
    {
        try {

            $editCase = ClientCase::with([
                'client:id,fname,lname,mobile',
                'court_category:id,name'
            ])->find($id);

            if (!$editCase) {
                Session::flash('flash_message_warning', 'Case not found');
                return redirect(route('case.index'));
            }

            $startDate = $editCase->getCaseStartDate();
            $endDate = $editCase->getCaseEndDate();

            $caseActIds = $editCase->case_acts;

            // Retrieve the caseActs by the decoded IDs
            $caseActsArr = [];
            if ($caseActIds) {
                $caseActs = CaseAct::whereIn('id', $caseActIds)->get();
                $caseActsArr = ClientCase::getCaseActsArray($caseActs);
            }


            $courtAddress = Court::select('id', 'court_name')->where('court_categoryID', $editCase->court_category->id)->get();

            $selectedFeeDetails = $this->feeDetailService->getCaseFeeDetails($editCase->id);

            $selectedFeeDetails = $selectedFeeDetails->unique('fee_description_id')->values();
            $total_fees = $selectedFeeDetails->sum('amount');
            $payment_received = $this->paymentDetailService->getCasePaymentDetails($editCase->id)->sum('amount');
            $pending_payment = $total_fees - $payment_received;

            $pageTitle = (string) "Edit Case " . $editCase->caseID;
            $data = $this->caseService->getFormData();
            return view(
                'case.create',
                compact(
                    'pageTitle',
                    'data',
                    'editCase',
                    'caseActsArr',
                    'caseActIds',
                    'courtAddress',
                    'startDate',
                    'endDate',
                    'selectedFeeDetails',
                    'total_fees',
                    'payment_received',
                    'pending_payment'
                )
            );
        } catch (\Throwable $th) {
            abort(500, $th->getMessage());
        }
    }
    /******************************************************************************/
    public function updateCaseStatus(Request $request)
{
    if ($request->ajax()) {
        DB::beginTransaction();
        try {
            $data = $request->all();

            // Check if the case is being reopened
            if ($data['status'] === 'open') {
                // Directly update the case status without any checks
                $saveData = $this->caseService->updateStatus($data);
                $user = auth()->user();
                LogActivity::addToLog($user->full_name . ' reopened Case [CaseID: ' . $saveData->caseID . ']');
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Case reopened successfully']);
            }

            $selectedFeeDetails = $this->feeDetailService->getCaseFeeDetails($data['ID']);
            $selectedFeeDetails = $selectedFeeDetails->unique('fee_description_id')->values();
            $total_fees = $selectedFeeDetails->sum('amount');
            $payment_received = $this->paymentDetailService->getCasePaymentDetails($data['ID'])->sum('amount');
            $pending_payment = $total_fees - $payment_received;

            // Check for pending payments if not reopening the case
            if ($pending_payment > 0 && empty($data['skipPending'])) {
                return response()->json([
                    'court_details' => true,
                    'success' => false,
                    'message' => (string) $pending_payment . ' Payment is pending, do you still want to close the case?'
                ]);
            }

            // Proceed to update the status
            $saveData = $this->caseService->updateStatus($data);
            $user = auth()->user();
            LogActivity::addToLog($user->full_name . ' updated Case Status [CaseID: ' . $saveData->caseID . ']' . ' status to ' . strtoupper($data['status']));
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Case Status updated successfully']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    return response()->json(['error' => 'Invalid request'], 400);
}

    /******************************************************************************/
    public function delete(Request $request, $id = null)
    {
        DB::beginTransaction();
        if ($request->ajax()) {
            try {
                $clientCase = $this->caseService->findClientCase($id);
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' delete a client case [CaseID: ' . $clientCase->caseID . ' ]');

                $clientCase->delete();
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /******************************************************************************/
    public function checkCasePendingAmount(Request $request)
    {
        if ($request->ajax()) {
            echo "<pre>";
            print_r($request->all());
            exit;
        }
    }



}
