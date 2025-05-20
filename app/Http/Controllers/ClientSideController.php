<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCenter;
use Hekmatinasser\Jalali\Jalali;

class ClientSideController extends Controller
{
    public function index() {
        $serviceCenters = ServiceCenter::all();

        $now = Jalali::now();
        $nowHour = intval($now->format('H:m'));
        $today = $now->dayOfWeek;

        foreach($serviceCenters as $serviceCenter) {
            $serviceCenterTimes = explode('-' ,$serviceCenter->working_hours);

            if(intval($serviceCenterTimes[0]) > $nowHour || intval($serviceCenterTimes[1]) < $nowHour) {
                $serviceCenter->is_open = false;
                $serviceCenter->save();
            } elseif($serviceCenter->fridays_off && $today == 6) {
                $serviceCenter->is_open = false;
                $serviceCenter->save();
            } else {
                $serviceCenter->is_open = true;
                $serviceCenter->save();
            }
        }

        return view('customer.index', ['serviceCenters' => $serviceCenters]);
    }
}
