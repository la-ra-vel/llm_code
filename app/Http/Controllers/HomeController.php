<?php

namespace App\Http\Controllers;

use App\Helpers\AssetHelper;
use App\Models\ClientCase;
use App\Models\User;
use App\Services\DashboardService;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    protected $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    /******************************************************************************/
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $assets = AssetHelper::getAssets('dashboard');
            // echo "<pre>"; print_r($assets); exit;
            return response()->json([
                'html' => view('dashboard_searchbar')->render(),
                'scripts' => $assets['scripts'],
                'styles' => $assets['styles']
            ]);
        }
        $permissionCheck = checkRolePermission('dashboard');
        $pageTitle = "Dashboard";
        return view('dashboard', compact('pageTitle', 'permissionCheck'));
    }
    /******************************************************************************/
    public function courtCustomSearch(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->dashboardService->getCourtSearchData($request);
            // echo "<pre>"; print_r($data->toArray()); exit;
            return DataTables::of($data)
                ->addColumn('counter', function () {
                    static $counter = 1;
                    return $counter++;
                })
                ->addColumn('caseID', function ($row) {
                    return '<a href="' . route('edit.case', $row->id) . '" target="_blank" style="text-decoration: underline;">' . $row->caseID . '</a>';
                })

                ->addColumn('client_name', function ($row) {
                    return $row->client ? $row->client->full_name : '';
                })
                ->addColumn('client_mobile', function ($row) {
                    return $row->client ? $row->client->mobile : '';
                })
                ->addColumn('courtCategory', function ($row) {
                    return $row->court_category->name ? $row->court_category->name : '';
                })
                ->addColumn('courtAddress', function ($row) {
                    return $row->case_court_address ? $row->case_court_address : '';
                })

                ->rawColumns(['caseID'])
                ->make(true);
        }
    }
    /******************************************************************************/
    public function dashboardWidgetData(Request $request)
    {
        $openCasesQuery = ClientCase::query();
        $openCases = $openCasesQuery->where('status', 'open')->count();
        $openCasesIds = $openCasesQuery->pluck('id')->toArray();


        // Query for close cases
        $closeCasesQuery = ClientCase::query();
        $closeCases = $closeCasesQuery->where('status', 'close')->count();
        $closeCasesIds = $closeCasesQuery->pluck('id')->toArray();

        // Query for upcoming actions
        $upcomingActionsQuery = $this->dashboardService->actionDetails();

        $upcomingActions = $upcomingActionsQuery->where('hearing_date', '>', now())->count();
        $upcomingActionsIds = $upcomingActionsQuery->pluck('client_case_pid')->toArray();
        $upcomingActionsIds = array_unique($upcomingActionsIds);
        $upcomingActionsIds = implode(',', $upcomingActionsIds);

        // Query for today's actions
        $todayActionsQuery = $this->dashboardService->actionDetails();
        $todayActions = $todayActionsQuery->whereDate('hearing_date', '=', now()->toDateString())->count();
        $todayActionsQueryIds = $todayActionsQuery->pluck('client_case_pid')->toArray();
        $todayActionsQueryIds = array_unique($todayActionsQueryIds);
        $todayActionsQueryIds = implode(',', $todayActionsQueryIds);

        // Query for upcoming one week actions
        $upcomingOneWeekActionsQuery = $this->dashboardService->actionDetails();
        $upcomingOneWeekActions = $upcomingOneWeekActionsQuery->whereBetween('hearing_date', [now(), now()->addWeek()])->count();
        $upcomingOneWeekActionsIds = $upcomingOneWeekActionsQuery->pluck('client_case_pid')->toArray();
        $upcomingOneWeekActionsIds = array_unique($upcomingOneWeekActionsIds);
        $upcomingOneWeekActionsIds = implode(',', $upcomingOneWeekActionsIds);

        // Query for upcoming one month actions
        $upcomingOneMonthActionsQuery = $this->dashboardService->actionDetails();
        $upcomingOneMonthActions = $upcomingOneMonthActionsQuery->whereBetween('hearing_date', [now(), now()->addMonth()])->count();
        $upcomingOneMonthActionsIds = $upcomingOneMonthActionsQuery->pluck('client_case_pid')->toArray();
        $upcomingOneMonthActionsIds = array_unique($upcomingOneMonthActionsIds);
        $upcomingOneMonthActionsIds = implode(',', $upcomingOneMonthActionsIds);
        // echo "<pre>"; print_r($upcomingOneMonthActionsIds); exit;

        return response()->json([
            'success' => true,
            'openCases' => $openCases,
            'openCasesIds' => $openCasesIds,
            'closeCases' => $closeCases,
            'closeCasesIds' => $closeCasesIds,
            'upcomingActions' => $upcomingActions,
            'upcomingActionsIds' => $upcomingActionsIds,
            'todayActions' => $todayActions,
            'todayActionsQueryIds' => $todayActionsQueryIds,
            'upcomingOneWeekActions' => $upcomingOneWeekActions,
            'upcomingOneWeekActionsIds' => $upcomingOneWeekActionsIds,
            'upcomingOneMonthActions' => $upcomingOneMonthActions,
            'upcomingOneMonthActionsIds' => $upcomingOneMonthActionsIds

        ]);
    }
    public function updateThemeMode(Request $request)
    {
        if ($request->ajax()) {
            User::find(Auth::guard('web')->user()->id)->update(['theme_mode' => $request->mode]);
            return response()->json(['success' => true]);
        }
    }
    public function forgotPassword()
    {
        $pageTitle = "Forgot Password";
        return view('auth.forgot_pass', compact('pageTitle'));
    }
    public function emailVerify(Request $request)
    {
        if ($request->ajax()) {
            try {
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    // $code = generateUniqueCode();
                    $message = sendMail('PASSWORD_RESET', ['data' => ['code' => $user->code]], $user, null);
                    // $user->update(['pass_reset_code' => $code]);
                    return response()->json(['success' => true, 'message' => $message]);
                } else {
                    $message = 'email not found';
                    return response()->json(['success' => false, 'message' => $message]);
                }

            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }

        }
    }

}
