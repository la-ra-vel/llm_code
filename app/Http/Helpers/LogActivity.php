<?php


namespace App\Http\Helpers;

use Request;
use App\Models\LogActivity as LogActivityModel;


class LogActivity
{
    public static function addToLog($subject)
    {

        $log = [];
        $log['subject'] = $subject;
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = auth()->guard('web')->check() ? auth()->guard('web')->user()->id : 0;
        $log['date'] = date('Y-m-d',strtotime(getCurrentDateTime()));
        $log['time'] = date('h:i A',strtotime(getCurrentDateTime()));
        LogActivityModel::create($log);
    }

}
