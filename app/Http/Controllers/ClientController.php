<?php

namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\ClientRequest;
use App\Services\ClientService;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use Session;

class ClientController extends Controller
{
    protected $clientService;
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
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
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $users = $this->clientService->getAllClients();

            return DataTables::of($users)
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })
                ->editColumn('name', function ($row) {
                    
                    return $row->title . ' ' . $row->fname . ' ' . $row->lname;
                })
                ->editColumn('registered_date', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge light badge-success updateStatus" data-tableID="clientsTable" data-URL="' . route('update.client.status') . '" data-ID="' . $row->id . '" data-Status="inactive" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    } else {
                        return '<span class="badge light badge-danger updateStatus" data-tableID="clientsTable" data-URL="' . route('update.client.status') . '" data-ID="' . $row->id . '" data-Status="active" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    }

                })
                ->addColumn('action', function ($row) {
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    return '
                    <a href="javascript:void(0);" data-URL="' . route('update.client', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xs btn-primary update"><i class="fas fa-pen"></i></a>
                    <a href="javascript:void(0);" data-tableID="clientsTable" data-URL="' . route('delete.client', $row->id) . '" class="btn btn-xs btn-danger delete"><i class="fas fa-trash"></i></a>

                ';
                })
                ->rawColumns(['status', 'action']) // This is important to render HTML content
                ->make(true);
        }

    }
    /******************************************************************************/
    public function index(Request $request)
    {
        $roles = [
            '0' => 'clients'
        ];
        $this->authenticateRole($roles);
        $pageTitle = "Clients";
        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('client');
            return response()->json([
                'html' => view('client.load_client_form', compact('pageTitle'))->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }

        return view('client.create', compact('pageTitle'));
    }
    /******************************************************************************/
    public function store(ClientRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                // Validate the request using ClientRequest rules
                $validatedData = $request->validated();
                $client = $this->clientService->store($validatedData, $id = '');
                $client->createdBy = Auth::guard('web')->user()->id;
                $client->save();
                $user = auth()->user();
                LogActivity::addToLog($user->full_name . ' create a new client [Name: ' . $client->full_name . ', Email: ' . $client->email . ']');
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Client created successfully']);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
    /******************************************************************************/
    public function update(ClientRequest $request, $id = null)
    {
        if ($request->ajax()) {
            try {
                // Validate the request using ClientRequest rules
                $validatedData = $request->validated();
                $client = $this->clientService->store($validatedData, $id);

                $client->createdBy = Auth::guard('web')->user()->id;
                $client->save();
                $user = auth()->user();
                $changes = $client->getChanges();
                unset($changes['updated_at']);

                // Log only the updated column names
                $updatedColumns = implode(', ', array_keys($changes));

                LogActivity::addToLog($user->full_name . " updated a client's [Name: " . $client->full_name . ", Email: " . $client->email . "] data: " . $updatedColumns);

                return response()->json(['status' => 200, 'message' => 'Client updated successfully']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
    /******************************************************************************/
    public function updateClientStatus(Request $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $data = $request->all();
                $client = $this->clientService->updateStatus($data);
                $user = auth()->user();

                LogActivity::addToLog($user->full_name . ' update client [Name: ' . $client->full_name . ', Email: ' . $client->email . ']' . ' status to ' . strtoupper($data['status']));
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'Client Status updated successfully']);

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
                $client = $this->clientService->findClient($id);
                $user = auth()->user();
                LogActivity::addToLog($user->full_name . ' delete a client [Name: ' . $client->full_name . ', Email: ' . $client->email . ']');

                $client->delete();
                return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /******************************************************************************/
    public function searchClients(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->q;
            $data = $this->clientService->searchInClients($search);
            return response()->json(['results' => $data]);
        }
    }
}
