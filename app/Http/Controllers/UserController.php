<?php

namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Http\Helpers\LogActivity;
use App\Http\Requests\StaffRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\ActionDetail;
use App\Models\ClientCase;
use App\Models\Group;
use App\Models\User;
use App\Services\UserService;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;
use DB;
use Session;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
    public function index(Request $request)
    {
        $roles = [
            '0' => 'user-management',
            '1' => 'users'
        ];
        $this->authenticateRole($roles);

        $pageTitle = 'Users';
        $rolesArr = Group::getRolesArray();
        $selectedRoleId = 1; // Example selected id

        // Find selected role by id in roles array
        $selectedRole = collect($rolesArr)->firstWhere('id', $selectedRoleId);
        $selectedRoleId = '';
        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('users');
            return response()->json([
                'html' => view('users.load_user_form', compact('pageTitle', 'rolesArr', 'selectedRoleId'))->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }


        // echo "<pre>"; print_r($selectedRoleId); exit;
        return view('users.index', compact('pageTitle', 'rolesArr', 'selectedRoleId'));
    }
    /*****************************************************************/
    public function usersList(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->userService->getAllUsers();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })
                ->editColumn('logo', function ($row) {

                    $logo = ($row->logo) ? getFile('users', $row->logo) : asset('uploads/no-image.png');
                    return '<img class="rounded-circle" width="50" height="50" src="' . $logo . '" alt="">';
                })

                ->editColumn('name', function ($row) {
                    return $row->full_name;
                })

                ->editColumn('role', function ($row) {
                    return ($row->role) ? $row->role->name : 'Not Assigned';
                })

                ->editColumn('code', function ($row) {
                    $code = htmlspecialchars($row->code, ENT_QUOTES, 'UTF-8');
                    return '<span class="code-toggle">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <span class="code-content">' . $code . '</span>
                            </span>';
                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge light badge-success updateStatus" data-tableID="usersTable" data-URL="' . route('update.user.status') . '" data-ID="' . $row->id . '" data-Status="inactive" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    } else {
                        return '<span class="badge light badge-danger updateStatus" data-tableID="usersTable" data-URL="' . route('update.user.status') . '" data-ID="' . $row->id . '" data-Status="active" style="cursor: pointer; width: 100px;">' . strtoupper($row->status) . '</span>';
                    }

                })


                ->addColumn('action', function ($row) {
                    $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    $logo = ($row->logo) ? getFile('users', $row->logo) : asset('uploads/no-image.png');
                    $btn = '<div class="dropdown custom-dropdown text-end">
                                            <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g></svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                <a class="dropdown-item" target="_blank" href="' . route('profile', $row->username) . '">Profile</a>
                                                <a class="dropdown-item update" data-URL="' . route('user.update', $row->id) . '" data-RowData="' . $rowData . '" data-Logo="' . $logo . '" href="javascript:voiud(0);">Edit</a>
                                                <a class="dropdown-item delete" data-tableID="usersTable" data-URL="' . route('user.delete', $row->id) . '" href="javascript:voiud(0);">Delete</a>
                                            </div>
                                        </div>';
                    return $btn;
                })

                ->rawColumns(['logo', 'code','status', 'action'])
                ->make(true);
        }
    }
    /*****************************************************************/
    public function create()
    {
        $pageTitle = "Create User";
        return view('users.create', compact('pageTitle'));
    }
    /*****************************************************************/
    public function store(StaffRequest $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();

            try {
                $data = $request->all();

                // Validate the request using UserRequest rules
                $validatedData = $request->validated();
                // echo "<pre>"; print_r($validatedData); exit;
                $newData = $this->userService->store($data, $validatedData, $id = '');

                $newData->code = $validatedData['password'];
                $newData->password = Hash::make($validatedData['password']);

                $newData->createdBy = Auth::guard('web')->user()->id;
                $newData->save();
                $user = auth()->user();
                LogActivity::addToLog((string) $user->full_name . ' create a new User [Name: ' . $newData->full_name . ', Email: ' . $newData->email . ']');
                DB::commit();
                return response()->json(['status' => 200, 'message' => 'User created successfully']);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
    /******************************************************************************/
    public function update(StaffRequest $request, $id = null)
    {
        if ($request->ajax()) {
            try {
                // Validate the request using UserRequest rules
                $data = $request->all();
                $validatedData = $request->validated();
                $update = $this->userService->store($data, $validatedData, $id);

                $update->createdBy = Auth::guard('web')->user()->id;
                $update->save();
                $user = auth()->user();
                $changes = $update->getChanges();
                unset($changes['updated_at']);

                // Log only the updated column names
                $updatedColumns = implode(', ', array_keys($changes));

                LogActivity::addToLog($user->full_name . " updated a user's [Name: " . $update->full_name . ", Email: " . $update->email . "] data: " . $updatedColumns);
                Cache::forget('users');
                return response()->json(['status' => 200, 'message' => 'user updated successfully']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
    /******************************************************************************/
    public function updateUsertatus(Request $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $data = $request->all();
                $client = $this->userService->updateStatus($data);
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
                $findUser = $this->userService->findUser($id);
                // @unlink(filePath('users').'/'.$findUser->logo);
                $user = auth()->user();
                LogActivity::addToLog($user->full_name . ' delete a client [Name: ' . $findUser->full_name . ', Email: ' . $findUser->email . ']');

                $findUser->delete();
                return response()->json(['status' => 200, 'message' => 'Data is deleted successfully']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
    }
    /******************************************************************************/
    public function profile($username = null)
    {
        // echo "<pre>"; print_r(value: \Route::currentRouteName()); exit;
        $user = User::where('username',$username)->first();
        if (!$user) {
            Session::flash('flash_message_warning', 'Profile not found');
            return redirect(route('users'));
        }
        $openCasesQuery = ClientCase::query();
        $openCases = $openCasesQuery->where('createdBy',$user->id)->where('status', 'open')->count();

        // Query for close cases
        $closeCasesQuery = ClientCase::query();
        $closeCases = $closeCasesQuery->where('createdBy',$user->id)->where('status', 'close')->count();

        $actions = ActionDetail::where('createdBy',$user->id)->count();

        $pageTitle = "Profile";
        $rolesArr = Group::getRolesArray();
        $selectedRoleId = $user->group_id; // Example selected id

        // Find selected role by id in roles array
        $selectedRole = collect($rolesArr)->firstWhere('id', $selectedRoleId);

        return view('profile', compact('pageTitle', 'user', 'rolesArr', 'selectedRoleId','openCases','closeCases','actions'));
    }
    /******************************************************************************/
    public function updateProfilePicture(Request $request)
    {
        try {
            $data = $request->all();
            $userProfile = User::findOrFail($data['user_id']);
            $filename = '';
            if (isset($data['logo']) && !empty($data['logo'])) {
                $filename = uploadImage($data['logo'], filePath('users'), $userProfile->logo);
                $userProfile->logo = $filename;
            }
            $userProfile->save();
            $user = auth()->user();
            LogActivity::addToLog($user->full_name . ' update profile picture [User: ' . $userProfile->full_name . ']');
            return response()->json(['success' => true, 'message' => 'Profile picture updated', 'image' => $filename]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

    }
    /******************************************************************************/
    public function changePassword(UpdatePasswordRequest $request)
    {
        if ($request->ajax()) {
            try {
                // Determine which user to update
                $data = $request->all();
                $user = $data['user_id'] ? $user = User::findOrFail($data['user_id']) : $user = Auth::guard('web')->user();


                $request->setUser($user); // Set the dynamic user for validation

                $request->validateResolved(); // Ensure validation runs with the updated user

                $user->password = Hash::make($request->input('password'));
                $user->code = $request->input('password');
                $user->save();

                return response()->json(['success' => true, 'message'=>'Password updated successfully.']);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }

        }
    }
    /******************************************************************************/
    public function userActivity(Request $request)
    {
// echo "<pre>"; print_r($request->all()); exit;

        $status = $request->input('status', '');
        $page = $request->input('page', 1);

        $query = \App\Models\LogActivity::query();

        $activities = $query->orderBy('created_at', 'desc')->where('user_id',$request->userID)->paginate(20, ['*'], 'page', $page);

        return response()->json($activities->items());

    }
}
