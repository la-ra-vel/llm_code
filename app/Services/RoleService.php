<?php
namespace App\Services;

use App\Models\Group;
use App\Models\GroupPermission;
use Illuminate\Support\Facades\Cache;
use DB;


class RoleService
{
    public function getAllRoles()
    {
        $roles = Cache::remember('roles', 60, function () {
            return Group::select('id', 'name')->with('group_permissions')->get();
        });
        return $roles;
    }
    /******************************************************************************/
    public function store($inputData, $group)
    {
        $group->delete_permissions();

        $module_id = $inputData['txtModID'];
        $permission_modulename = $inputData['txtModname'];
        $permission_modulepage = $inputData['txtModpage'];
        $permission_access = $inputData['txtaccess'];

        $permission = [];
        foreach ($permission_access as $key => $val) {
            $permission[$val] = isset($permission_modulepage[$val]) ? 1 : 0;
        }
        // $checkPermission = ['txtaccess' => $permission];
        foreach ($permission_modulepage as $Pkey => $PID) {

            $insertData = new GroupPermission;
            $insertData->group_id = $group->id;
            $insertData->module_id = $module_id[$Pkey];
            $insertData->module_name = $permission_modulename[$Pkey];
            $insertData->module_page = $permission_modulepage[$Pkey];
            $insertData->access = isset($permission[$Pkey]) ? 1 : 0;
            $insertData->save();
        }
        Cache::forget('roles');
        return $group;
    }

    /******************************************************************************/
    public function findRole($id = null)
    {
        $group = Group::find($id);
        return $group;

    }
}
