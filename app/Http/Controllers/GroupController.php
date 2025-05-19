<?php

namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Models\Group;
use App\Models\GroupModule;
use App\Models\GroupPermission;
use App\Http\Helpers\LogActivity;
use App\Services\RoleService;
use Auth;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;
use Session;

class GroupController extends Controller
{
    public $roleService;
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
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

        if ($permissionRole[0]['access'] == 0 && $permissionRole[1]['access'] == 0) {
            Session::flash('flash_message_warning', 'You have no permission');
            return redirect(route('dashboard'))->send();
        }
    }
    /*****************************************************************/
    public function create(Request $request)
    {

        $pageTitle = "Create Role";


        return view('roles.create', compact('pageTitle'));
    }
    /*****************************************************************/
    public function index(Request $request)
    {
        $roles = [
            '0' => 'user-management',
            '1' => 'roles'
        ];
        $this->authenticateRole($roles);
        $pageTitle = 'Roles List';
        $rolesArr = GroupModule::getModulesArray();
        $selectedRoleId = 1; // Example selected id

        // Find selected role by id in roles array
        $selectedRole = collect($rolesArr)->firstWhere('id', $selectedRoleId);
        $selectedRoleId = '';
        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('roles');
            return response()->json([
                'html' => view('roles.load_role_form', compact('pageTitle', 'rolesArr', 'selectedRoleId'))->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }
        return view('roles.index', compact('pageTitle', 'rolesArr', 'selectedRoleId'));
    }
    /*****************************************************************/
    public function rolesList(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->roleService->getAllRoles();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })

                ->addColumn('action', function ($row) {
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    return '
                    <a href="javascript:void(0);" data-URL="' . route('role.update', $row->id) . '" data-RowData="' . $rowData . '" class="btn btn-xs btn-primary update"><i class="fas fa-pen"></i></a>
                    <a href="javascript:void(0);" data-tableID="rolesTable" data-URL="' . route('role.delete', $row->id) . '" class="btn btn-xs btn-danger delete"><i class="fas fa-trash"></i></a>

                ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }
    }
    /*****************************************************************/
    public function store(Request $request)
    {
        $inputData = $request->all();
        DB::beginTransaction();
        try {

            $group = new Group;
            $group->name = $inputData['role_name'];

            $group->save();
            $storeData = $this->roleService->store($inputData, $group);
            $storeData->createdBy = Auth::guard('web')->user()->id;
            $storeData->save();
            $permission_modulename = implode(',', $inputData['txtModname']);
            $user = auth()->user();
            LogActivity::addToLog((string) $user->full_name . ' added a new role ' . strtoupper($group->name) . ' with permissions [ ' . $permission_modulename . ' ]');
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Data addedd successfully']);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }

    }
    /******************************************************************************/
    public function update(Request $request, $id = null)
    {
        $inputData = $request->all();
        DB::beginTransaction();
        try {
            if ($id) {
                $group = Group::find($id);
                if (!$group) {
                    return response()->json(['status' => 404, 'message' => 'Group not found.'], 404);
                }
            } else {
                $group = new Group;
            }

            $group->name = $inputData['role_name'];

            $group->save();

            if ($id && $group->type === 'super_admin') {
                // If the group is super_admin, do not update permissions
                $groupPermission = GroupPermission::where('group_id', $id)->first();
                if (Gate::denies('update', $groupPermission)) {
                    DB::commit();
                    return response()->json(['status' => 200, 'message' => 'Role name updated successfully, but permissions were not updated for Super Admin group.']);
                }
            }

            $permission_modulename = implode(',', $inputData['txtModname']);
            $storeData = $this->roleService->store($inputData, $group);
            $storeData->createdBy = Auth::guard('web')->user()->id;
            $storeData->save();
            $user = auth()->user();
            LogActivity::addToLog((string) $user->full_name . ' update a role ' . strtoupper($group->name) . ' with permissions [ ' . $permission_modulename . ' ]');
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Data updated successfully']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    /******************************************************************************/
    public function delete(Request $request, $id = null)
    {
        if ($request->ajax()) {
            try {

                $data = $this->roleService->findRole($id);
                if (!$data) {
                    return response()->json(['status' => 404, 'message' => 'Group not found.'], 404);
                }
                if ($data->type === 'super_admin') {
                    // If the group is super_admin, do not update permissions
                    $groupPermission = GroupPermission::where('group_id', $id)->first();
                    if (Gate::denies('delete', $groupPermission)) {
                        DB::commit();
                        return response()->json(['status' => 422, 'message' => 'Super Admin Role cannot be delete.']);
                    }
                }
                $data->delete_permissions();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' delete a role ' . strtoupper($data->name));

                $data->delete();
                return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
}
