<?php

namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\CountryRequest;
use App\Services\CountryService;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    protected $countryService;
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
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
            '1' => 'country'
        ];
        $this->authenticateRole($roles);
        $pageTitle = "Countries";
        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('country');
            return response()->json([
                'html' => view('master_data.country.load_country_form', compact('pageTitle'))->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }

        return view('master_data.country.index', compact('pageTitle'));
    }
    /******************************************************************************/
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->countryService->getAllCountry();

            return DataTables::of($data)
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge light badge-success updateStatus" data-tableID="countryTable" data-URL="' . route('update.country.status') . '" data-ID="' . $row->id . '" data-Status="inactive" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    } else {
                        return '<span class="badge light badge-danger updateStatus" data-tableID="countryTable" data-URL="' . route('update.country.status') . '" data-ID="' . $row->id . '" data-Status="active" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    }

                })
                ->addColumn('action', function ($row) {
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    return '
                    <a href="javascript:void(0);" data-URL="' . route('update.country', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xs btn-primary update"><i class="fas fa-pen"></i></a>
                    <a href="javascript:void(0);" data-tableID="countryTable" data-URL="' . route('delete.country', $row->id) . '" class="btn btn-xs btn-danger delete"><i class="fas fa-trash"></i></a>

                ';
                })
                ->rawColumns(['status', 'action']) // This is important to render HTML content
                ->make(true);
        }

    }
    /******************************************************************************/
    public function store(CountryRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CountryRequest rules
                $validatedData = $request->validated();
                $saveData = $this->countryService->store($validatedData, $id = '');
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' added a new Country [Country Name: ' . $saveData->name . ', Country Code: ' . $saveData->code . ']');
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
    public function update(CountryRequest $request, $id = null)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using CountryRequest rules
                $validatedData = $request->validated();
                $saveData = $this->countryService->store($validatedData, $id);
                $saveData->createdBy = Auth::guard('web')->user()->id;
                $saveData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' updated a Country [Country Name: ' . $saveData->name . ', Country Code: ' . $saveData->code . ']');
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
                $findData = $this->countryService->updateStatus($data);
                $user = auth()->user();

                LogActivity::addToLog($user->full_name . ' update country [Country Name: ' . $findData->full_name . ', Country Code: ' . $findData->email . ']' . ' status to ' . strtoupper($data['status']));
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
                $findData = $this->countryService->findData($id);
                if ($findData->canBeDeleted()) {
                    $user = auth()->user();
                    LogActivity::addToLog((string) $user->full_name . ' delete a country [Country Name: ' . $findData->name . ', Country Code: ' . $findData->code . ']');

                    $findData->delete();
                    return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
                } else {
                    return response()->json(['status' => 422, 'message' => 'Country cannot be deleted because its cities are in use by courts.']);
                }

            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /******************************************************************************/
    public function searchCountry(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->q;
            $data = $this->countryService->searchInCountry($search);

            return response()->json(['results' => $data]);
        }
    }
}
