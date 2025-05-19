<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Group extends Model
{
    use HasFactory;

    public function group_permissions(): HasMany
    {
        return $this->hasMany(GroupPermission::class, 'group_id')->select('id', 'group_id', 'module_id', 'module_name', 'access')->where('access', 1);
    }
    public function get_all_permissions(): HasMany
    {
        return $this->hasMany(GroupPermission::class, 'group_id')->select('id', 'group_id', 'module_id', 'module_name', 'access');
    }
    // Method to delete related permissions
    public function delete_permissions()
    {
        $this->get_all_permissions()->delete();
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'group_id');
    }

    public static function getRolesArray()
    {
        Auth::guard('web')->user()->user_type === 'super_admin' ?
            $roles = Group::select('id', 'name', 'type')->get() :

            $roles = Group::select('id', 'name', 'type')->where('type', '!=', 'super_admin')->get();

        $rolesArr = [];
        foreach ($roles as $key => $value) {

            $rolesArr[] = [
                'id' => $value->id,
                'name' => $value->name
            ];

        }
        return $rolesArr;
    }

    protected static function boot()
    {
        parent::boot();

        // Cache forget on create, update, delete
        static::created(function () {
            Cache::forget('roles');
        });

        static::updated(function ($role) {

            Cache::forget('roles');
        });

        static::deleted(function () {
            Cache::forget('roles');
        });

        static::deleting(function ($role) {
            if ($role->type == 'super_admin') {
                abort(422, "Super Admin Role cannot be delete.");
            }

            if ($role->user()->count() > 0) {
                if (request()->ajax()) {
                    abort(422, "Role cannot be deleted because they have associated users.");
                } else {
                    throw new \Exception("Role cannot be deleted because they have associated users.");
                }
            }
        });
    }
}
