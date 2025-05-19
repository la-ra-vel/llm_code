<?php

namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\QuotationRequest;
use App\Models\GeneralSetting;
use App\Models\Quotation;
use App\Services\QuotationService;
use Auth;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\DataTables;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;

class QuotationController extends Controller
{
    protected $quotationService;
    public function __construct(QuotationService $quotationService)
    {
        $this->quotationService = $quotationService;
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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $roles = [
            '0' => 'quotations'
        ];
        $this->authenticateRole($roles);
        $quotation_no = Quotation::getNextQuotationNo();
        // dd($quotation_no);
        $pageTitle = "Quotations";
        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('quotations');
            return response()->json([
                'html' => view('quotations.load_quotation_form', compact('pageTitle', 'quotation_no'))->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }

        return view('quotations.index', compact('pageTitle', 'quotation_no'));
    }
    /******************************************************************************/
    public function list(Request $request)
    {
        // echo "<pre>"; print_r($request); exit;
        if ($request->ajax()) {

            Cache::forget('quotations');
            $data = $this->quotationService->getAllData();
            // echo "<pre>"; print_r($data->toArray()); exit;
            return DataTables::of($data)
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })

                ->addColumn('date_time', function ($row) {
                    // echo "<pre>"; print_r($row); exit;
                    return date('d-m-Y', strtotime($row->date)) . ' | ' . date('h:i A', strtotime($row->time));
                })


                ->editColumn('quotation', function ($row) {

                    return '<a href="javascript:void(0);" data-URL="' . route('quotation.generate') . '" data-QuotationNo="' . $row->quotation_no . '" data-ID="' . $row->id . '" class="downloadQuotation" style="text-decoration: none; cursor: pointer; color:blue;">QUOTATION</a>';


                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 'approved') {
                        return '<span class="badge light badge-success updateStatus" data-tableID="quotationsTable" data-URL="' . route('update.quotation.status') . '" data-ID="' . $row->id . '" data-Status="pending" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    } else {
                        return '<span class="badge light badge-danger updateStatus" data-tableID="quotationsTable" data-URL="' . route('update.quotation.status') . '" data-ID="' . $row->id . '" data-Status="approved" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    }

                })

                ->addColumn('action', function ($row) {
                    $quotationDescription = $row['quotation_description'];

                    // Remove quotation_description from the original array
                    unset($row['quotation_description']);

                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    $rowDataQuotationDescription = htmlspecialchars(json_encode($quotationDescription), ENT_QUOTES, 'UTF-8');

                    $btn = '<div class="dropdown custom-dropdown text-end">
                                            <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g></svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                <a class="dropdown-item quotationDescription" data-QuotationNo="' . $row->quotation_no . '" data-RowData="' . $rowDataQuotationDescription . '" data-QuotationID="' . $row->id . '" data-URL="' . route('store.quotation.description') . '" href="javascript:void(0);">Description</a>
                                                <a class="dropdown-item update" data-URL="' . route('quotations.update', $row->id) . '" data-RowData="' . $rowData . '" href="javascript:void(0);">Edit</a>
                                                <a class="dropdown-item delete" data-tableID="quotationsTable" data-URL="' . route('quotations.destroy', $row->id) . '" href="javascript:void(0);">Delete</a>
                                            </div>
                                        </div>';
                    return $btn;
                })


                ->rawColumns(['quotation', 'status','action']) // This is important to render HTML content
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuotationRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using QuotationRequest rules
                $validatedData = $request->validated();
                $saveData = $this->quotationService->store($validatedData, $id = '');
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' added a Quotation [Quotation #: ' . $saveData->quotation_no . ']');
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Data added successfully']);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuotationRequest $request, string $id)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using QuotationRequest rules
                $validatedData = $request->validated();
                $saveData = $this->quotationService->store($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' updated a Quotation [Quotation #: ' . $saveData->quotation_no . ']');
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Data updated successfully']);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if ($request->ajax()) {
            try {
                $findData = $this->quotationService->findData($id);
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' Delete a Quotation [Quotation #: ' . $findData->quotation_no . ']');

                $findData->delete();
                return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /******************************************************************************/
    public function quotationGenerate(Request $request)
    {
        $data = $request->all(); // Get the data from the request
        $quotation_id = $data['id'];
        $quotationWithDescriptions = $this->quotationService->findData($quotation_id);
        // echo "<pre>"; print_r($quotationWithDescriptions->toArray()); exit;
        // Generate the PDF
        $general = GeneralSetting::first();

        $logoPath = public_path('uploads/logo/' . ($general->logo ?: $general->default_image));

        $logo = imageToBase64($logoPath); // Convert local file to base64


        $pdf = PDF::loadView('pdf.quotation', compact('general','logo', 'quotationWithDescriptions'));


        return $pdf->download('quotation.pdf');
    }
    /******************************************************************************/
    public function updateQuotationStatus(Request $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $data = $request->all();

                $findData = $this->quotationService->updateStatus($data);
                // echo "<pre>"; print_r($findData); exit;
                $user = auth()->user();

                LogActivity::addToLog($user->full_name . ' update Quotation [Quotation # : ' . $findData->quotation_no . ']' . ' status to ' . strtoupper($data['status']));
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Quotation Status updated successfully']);

            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }
}
