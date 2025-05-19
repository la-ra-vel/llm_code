<?php

namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\StateRequest;
use App\Services\StateService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use Session;

class StateController extends Controller
{
    protected $stateService;
    public function __construct(StateService $stateService)
    {
        $this->stateService = $stateService;
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
            '1' => 'state'
        ];
        $this->authenticateRole($roles);
        $pageTitle = "States";
        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('state');
            return response()->json([
                'html' => view('master_data.state.load_state_form', compact('pageTitle'))->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }

        return view('master_data.state.index', compact('pageTitle'));
    }
    /******************************************************************************/
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->stateService->getAllStates();

            return DataTables::of($data)
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })
                ->addColumn('country', function ($row) {
                    return $row->country ? $row->country->name : '';
                })


                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge light badge-success updateStatus" data-tableID="stateTable" data-URL="' . route('update.state.status') . '" data-ID="' . $row->id . '" data-Status="inactive" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    } else {
                        return '<span class="badge light badge-danger updateStatus" data-tableID="stateTable" data-URL="' . route('update.state.status') . '" data-ID="' . $row->id . '" data-Status="active" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    }

                })
                ->addColumn('action', function ($row) {
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    return '
                    <a href="javascript:void(0);" data-URL="' . route('update.state', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xs btn-primary update"><i class="fas fa-pen"></i></a>
                    <a href="javascript:void(0);" data-tableID="stateTable" data-URL="' . route('delete.state', $row->id) . '" class="btn btn-xs btn-danger delete"><i class="fas fa-trash"></i></a>

                ';
                })
                ->rawColumns(['status', 'action']) // This is important to render HTML content
                ->make(true);
        }

    }
    /******************************************************************************/
    public function store(StateRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using StateRequest rules
                $validatedData = $request->validated();
                $saveData = $this->stateService->store($validatedData, $id = '');
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' added a new State [State Name: ' . $saveData->name . ']');
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
    public function update(StateRequest $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using StateRequest rules
                $validatedData = $request->validated();
                $saveData = $this->stateService->store($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' updated a state [State Name: ' . $saveData->name . ']');
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
                $findData = $this->stateService->updateStatus($data);
                $user = auth()->user();

                LogActivity::addToLog($user->full_name . ' update state [State Name: ' . $findData->name . ']' . ' status to ' . strtoupper($data['status']));
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'State Status updated successfully']);

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
                $findData = $this->stateService->findData($id);

                if ($findData->canBeDeleted()) {
                    $user = auth()->user();
                    LogActivity::addToLog((string) $user->full_name . ' delete a state [State Name: ' . $findData->name . ']');

                    $findData->delete();
                    return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
                } else {
                    return response()->json(['status' => 422, 'message' => 'State cannot be deleted because it has cities.']);
                }


            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /******************************************************************************/
    public function searchState(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->q;
            $data = $this->stateService->searchInState($search);
            return response()->json(['results' => $data]);
        }
    }
    /******************************************************************************/
}
