<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoadTabsController extends Controller
{
    public function courtDetails(Request $request)
    {
            return view('case.tabs.court_details');
    }
    public function feeDetails(Request $request)
    {
            return view('case.tabs.fee_details');
    }
    public function noteDetails(Request $request)
    {
            return view('case.tabs.note_details');
    }
    public function paymentDetails(Request $request)
    {
            return view('case.tabs.payment_details');
    }
    public function documentDetails(Request $request)
    {
            return view('case.tabs.documents');
    }
}
