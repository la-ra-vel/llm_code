<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Services\CityService;
use Illuminate\Http\Request;
use App\Helpers\AssetHelper;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\StateRequest;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use Session;

class CityController extends Controller
{
    protected $cityService;
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
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
            '1' => 'city'
        ];
        $this->authenticateRole($roles);
        $pageTitle = "Cities";
        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('city');
            return response()->json([
                'html' => view('master_data.city.load_city_form', compact('pageTitle'))->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }

        return view('master_data.city.index', compact('pageTitle'));
    }
    /******************************************************************************/
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->cityService->getAllCities();
            // echo "<pre>"; print_r($data->toArray()); exit;

            return DataTables::of($data)
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })
                ->addColumn('country', function ($row) {
                    return $row->state->country ? $row->state->country->name : '';
                })

                ->addColumn('state', function ($row) {
                    return $row->state ? $row->state->name : '';
                })


                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge light badge-success updateStatus" data-tableID="cityTable" data-URL="' . route('update.city.status') . '" data-ID="' . $row->id . '" data-Status="inactive" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    } else {
                        return '<span class="badge light badge-danger updateStatus" data-tableID="cityTable" data-URL="' . route('update.city.status') . '" data-ID="' . $row->id . '" data-Status="active" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    }

                })
                ->addColumn('action', function ($row) {
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    return '
                    <a href="javascript:void(0);" data-URL="' . route('update.city', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xs btn-primary update"><i class="fas fa-pen"></i></a>
                    <a href="javascript:void(0);" data-tableID="cityTable" data-URL="' . route('delete.city', $row->id) . '" class="btn btn-xs btn-danger delete"><i class="fas fa-trash"></i></a>

                ';
                })
                ->rawColumns(['status', 'action']) // This is important to render HTML content
                ->make(true);
        }

    }
    /******************************************************************************/
    public function store(CityRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CityRequest rules
                $validatedData = $request->validated();
                $saveData = $this->cityService->store($validatedData, $id = '');
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' added a new City [City Name: ' . $saveData->name . ']');
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
    public function update(CityRequest $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CityRequest rules
                $validatedData = $request->validated();
                $saveData = $this->cityService->store($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' updated a City [City Name: ' . $saveData->name . ']');
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
                $findData = $this->cityService->updateStatus($data);
                $user = auth()->user();

                LogActivity::addToLog($user->full_name . ' update City [City Name: ' . $findData->name . ']' . ' status to ' . strtoupper($data['status']));
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'City Status updated successfully']);

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
                $findData = $this->cityService->findData($id);
                $user = auth()->user();
                // Check if the city has courts
                foreach ($findData->courts as $court) {
                    // Check if any court is associated with any client case
                    if ($court->client_cases()->exists()) {
                        return response()->json(['status' => 422, 'message' => 'City cannot be deleted as there are related client cases.']);

                    }
                }

                LogActivity::addToLog((string) $user->full_name . ' delete a City [City Name: ' . $findData->name . ']');

                $findData->delete();
                return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /******************************************************************************/
    public function searchCity(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->q;
            $data = $this->cityService->searchInCity($search);
            return response()->json(['results' => $data]);
        }
    }
    /******************************************************************************/
}
