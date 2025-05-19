<?php

namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\CourtCategoryRequest;
use App\Http\Requests\CourtRequest;
use App\Models\Court;
use App\Services\CourtService;
use Illuminate\Http\Request;
use DB;
use Auth;
use Yajra\DataTables\DataTables;
use Session;

class CourtController extends Controller
{
    protected $courtService;
    public function __construct(CourtService $courtService)
    {
        $this->courtService = $courtService;
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
    /******************************************************************************/
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = [
            '0' => 'court',
            '1' => 'court_list'
        ];
        $this->authenticateRole($roles);
        $pageTitle = "Courts";
        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('courts');
            return response()->json([
                'html' => view('court.courts.load_court_form', compact('pageTitle'))->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }


        return view('court.courts.index', compact('pageTitle'));
    }

    /******************************************************************************/
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->courtService->getAllData();
            // echo "<pre>"; print_r($data->toArray()); exit;

            return DataTables::of($data)
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })
                ->editColumn('city', function ($row) {
                    return $row->city ? $row->city->name : '';

                })
                ->editColumn('court_category', function ($row) {
                    return $row->category ? $row->category->name : '';

                })
                ->editColumn('addedBy', function ($row) {
                    return $row->user ? $row->user->fname . ' ' . $row->user->lname : '';

                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge light badge-success updateStatus"  data-tableID="courtsTable" data-URL="' . route('update.court.status') . '" data-ID="' . $row->id . '" data-Status="inactive" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    } else {
                        return '<span class="badge light badge-danger updateStatus"  data-tableID="courtsTable" data-URL="' . route('update.court.status') . '" data-ID="' . $row->id . '" data-Status="active" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    }

                })
                ->addColumn('action', function ($row) {
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    return '
                   <a href="javascript:void(0);" data-URL="' . route('courts.update', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xxs btn-primary update"><i class="fas fa-pen"></i></a>
                   <a href="javascript:void(0);" data-tableID="courtsTable" data-URL="' . route('courts.destroy', $row->id) . '" class="btn btn-xxs btn-danger delete"><i class="fas fa-trash"></i></a>

               ';
                })
                ->rawColumns(['status', 'action']) // This is important to render HTML content
                ->make(true);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourtRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CourtRequest rules
                $validatedData = $request->validated();
                // echo "<pre>"; print_r($validatedData); exit;
                $saveData = $this->courtService->store($validatedData, $id = '');
                // echo "<pre>"; print_r($saveData); exit;
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' added a Court ' . $saveData->court_name);
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
    public function update(CourtRequest $request, $id = null)
    {
        // dd($court);
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CourtRequest rules
                $validatedData = $request->validated();
                $saveData = $this->courtService->store($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' update a Court ' . $saveData->court_name);
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Data updated successfully']);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
    /******************************************************************************/
    public function updateStatus(Request $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $data = $request->all();
                $findData = $this->courtService->updateStatus($data);
                $user = auth()->user();
                LogActivity::addToLog($user->full_name . ' update Court (' . strtoupper($findData->court_name) . ') status to ' . strtoupper($data['status']));
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Court Status updated successfully']);

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
                $findData = $this->courtService->findData($id);
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' delete a Court Category ' . $findData->court_name);
                $findData->delete();
                return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /******************************************************************************/
    public function searchCourtCaseID(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->q;
            $data = $this->courtService->searchInCourtCaseID($search);
            return response()->json(['results' => $data]);
        }
    }
}
