<?php

namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\CaseActsRequest;
use App\Services\CaseActService;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Yajra\DataTables\DataTables;

class CaseActsController extends Controller
{
    protected $caseActService;
    public function __construct(CaseActService $caseActService)
    {
        $this->caseActService = $caseActService;
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
    public function index(Request $request)
    {

        $roles = [
            '0' => 'master_data',
            '1' => 'case_acts'
        ];
        $this->authenticateRole($roles);
        $pageTitle = "Case Acts";
        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('case_acts');
            return response()->json([
                'html' => view('master_data.case_acts.load_case_acts_form', compact('pageTitle'))->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }

        return view('master_data.case_acts.index', compact('pageTitle'));
    }
    /******************************************************************************/
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->caseActService->getAllData();

            return DataTables::of($data)
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })
                ->editColumn('addedBy', function ($row) {
                   return $row->user ? $row->user->fname .  ' ' . $row->user->lname : '';

                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge light badge-success updateStatus" data-tableID="casActsTable" data-URL="' . route('update.case.act.status') . '" data-ID="' . $row->id . '" data-Status="inactive" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    } else {
                        return '<span class="badge light badge-danger updateStatus" data-tableID="casActsTable" data-URL="' . route('update.case.act.status') . '" data-ID="' . $row->id . '" data-Status="active" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    }

                })
                ->addColumn('action', function ($row) {
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    return '
                    <a href="javascript:void(0);" data-URL="' . route('update.case.act', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xs btn-primary update"><i class="fas fa-pen"></i></a>
                    <a href="javascript:void(0);" data-tableID="casActsTable" data-URL="' . route('delete.case.act', $row->id) . '" class="btn btn-xs btn-danger delete"><i class="fas fa-trash"></i></a>

                ';
                })
                ->rawColumns(['status', 'action']) // This is important to render HTML content
                ->make(true);
        }

    }
    /******************************************************************************/
    public function store(CaseActsRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CaseActsRequest rules
                $validatedData = $request->validated();
                // echo "<pre>"; print_r($validatedData); exit;
                $saveData = $this->caseActService->store($validatedData, $id = '');
                // echo "<pre>"; print_r($saveData); exit;
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' added a Case Act ' . $saveData->name);
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Data added successfully']);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
    /******************************************************************************/
    public function update(CaseActsRequest $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CaseActsRequest rules
                $validatedData = $request->validated();
                $saveData = $this->caseActService->store($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' update a Case Act ' . $saveData->name);
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
                $findData = $this->caseActService->updateStatus($data);
                $user = auth()->user();
                LogActivity::addToLog($user->full_name . ' update Case Act (' . strtoupper($findData->name) . ') status to ' . strtoupper($data['status']));
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Country Status updated successfully']);

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
        if ($request->ajax()) {
            try {
                $findData = $this->caseActService->findData($id);
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' delete a Case Act ' . $findData->name);
                $findData->delete();
                return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /******************************************************************************/
}
