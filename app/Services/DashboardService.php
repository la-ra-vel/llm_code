<?php
namespace App\Services;

use App\Models\ActionDetail;
use App\Models\ClientCase;
use App\Models\FeeDescription;
use App\Models\FeeDetail;
use Illuminate\Support\Facades\Cache;
use App\Services\ClientService;


class DashboardService
{

    protected $clientService;
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
    public function getCourtSearchData($request)
    {
        $data = $request->all();
        $courtCategory = $request->input('court_category');
        $courtAddress = $request->input('court_address');
        // $caseID = $request->input('case_id');
        $custom_search = $request->input('custom_search');
        $default_cases = $request->input('default_cases');

        $query = ClientCase::with(['client', 'court_category']);

        if (isset($default_cases) && !empty($default_cases) && $default_cases == 'open') {
            $query->where('status', 'open');
        }

        // if ($caseID) {
        //     $query->where('caseID', $caseID);
        // }

        if ($custom_search) {
            $search = $custom_search;
            $searchData = $this->clientService->searchInClients($search);
            $ids = array_column($searchData, 'id');
            $query->whereIn('client_id', $ids)->orWhere('case_legal_matter', $custom_search);


        }

        if (ctype_digit($custom_search)) {
            $query->where('caseID', $custom_search);
        }

        if ($courtCategory) {
            $query->where('court_catID', $courtCategory);
        }

        if ($courtAddress) {
            $query->where('case_court_address', 'like', '%' . $courtAddress . '%');
        }
        if (isset($data['open_cases_link']) && !empty($data['open_cases_link'])) {
            $openIds = explode(',', $data['open_cases_link']);
            $query->whereIn('id', $openIds);
        }

        if (isset($data['close_cases_link']) && !empty($data['close_cases_link'])) {
            $closeIds = explode(',', $data['close_cases_link']);
            $query->whereIn('id', $closeIds);
        }

        if (isset($data['upcoming_actions_link']) && !empty($data['upcoming_actions_link'])) {
            $upcomingIds = explode(',', $data['upcoming_actions_link']);
            $query->whereIn('id', $upcomingIds);
        }

        if (isset($data['pending_today_link']) && !empty($data['pending_today_link'])) {
            $todayIds = explode(',', $data['pending_today_link']);
            $query->whereIn('id', $todayIds);
        }

        if (isset($data['upcoming_one_week_link']) && !empty($data['upcoming_one_week_link'])) {
            $oneWeekIds = explode(',', $data['upcoming_one_week_link']);
            $query->whereIn('id', $oneWeekIds);
        }

        if (isset($data['upcoming_one_month_link']) && !empty($data['upcoming_one_month_link'])) {
            $oneMonthIds = explode(',', $data['upcoming_one_month_link']);
            $query->whereIn('id', $oneMonthIds);
        }

        $cases = $query->get();
        return $cases;

    }

    public function actionDetails()
    {
        $data = ActionDetail::query();
        return $data;
    }


}

