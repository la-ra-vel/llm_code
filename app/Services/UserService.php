<?php
namespace App\Services;

use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;


class UserService
{
    public function getAllUsers()
    {
        $authUser = Auth::user(); // Get the authenticated user

        $users = Cache::remember('users', 60, function () use ($authUser) {
            return User::with([
                'role' => function ($query) {
                    // Specify columns for the related role model here if needed
                    $query->select('id', 'name');
                }
            ])
                ->select('id', 'username', 'fname', 'lname', 'code', 'email', 'mobile', 'address', 'logo', 'firm_name', 'group_id', 'status') // Specify the columns you need from the User model
                ->when($authUser->user_type !== 'super admin', function ($query) use ($authUser) {
                    // If the user is not a super admin, limit the results to the current user and users they created
                    $query->where('id', $authUser->id)
                        ->orWhere('createdby', $authUser->id);
                })
                ->orderBy('id', 'DESC')
                ->get();
        });

        return $users;

        // return User::with('role')->orderBy('id', 'DESC')->get();

    }
    /******************************************************************************/
    public function store($data, $validatedData, $id = '')
    {

        if ($id) {
            $user = User::find($id);

            // Check if user_type is super_admin
            if ($user->user_type === User::USER_TYPE) { // check user type
                // Remove group_id from validatedData
                unset($validatedData['group_id']);
                unset($validatedData['username']);
            }

            // Check if password is present in validatedData
            if (isset($validatedData['password']) && !empty($validatedData['password'])) {
                $user->password = bcrypt($validatedData['password']);
            } else {
                // Remove the password field from the update data to prevent it from being updated to null
                unset($validatedData['password']);
            }

            $user->update($validatedData);
        } else {
            $user = User::create($validatedData);
            $user->username = $validatedData['username'] ?? $user->username;
        }

        if (isset($data['logo']) && !empty($data['logo'])) {
            $filename = uploadImage($data['logo'], filePath('users'), $user->logo);
            $user->logo = $filename;
        }

        return $user;
    }
    /******************************************************************************/
    public function updateStatus($data)
    {
        $user = User::find($data['ID']);

        if ($user) {
            $user->status = $data['status'];
            $user->save();  // This will trigger the 'updated' event and call the observer
            Cache::forget('users');
            return $user;
        }

    }
    /******************************************************************************/
    public function findUser($id = null)
    {
        $user = User::find($id);
        return $user;

    }
}
