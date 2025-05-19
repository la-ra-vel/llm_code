<?php
namespace App\Policies;

use App\Models\User;
use App\Models\GroupPermission;
use App\Models\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the group permission.
     */
    public function delete(User $user, GroupPermission $groupPermission)
    {
        $group = Group::find($groupPermission->group_id);

        if ($group && $group->type === 'super_admin') {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the group permission.
     */
    public function update(User $user, GroupPermission $groupPermission)
    {
        $group = Group::find($groupPermission->group_id);

        if ($group && $group->type === 'super_admin') {
            return false;
        }

        return true;
    }
}
