<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

use App\Models\ServiceCenter;
use Illuminate\Http\Client\Request;
use App\Http\Requests\ServiceCenterStoreRequest;

class ServiceCenterController extends Controller
{
    public function index() {
        $serviceCenters = ServiceCenter::all();

        return view("supporter.serviceCenters.index" , compact("serviceCenters"));
    }

    public function create(User $user) {
        return view("admin.serviceCenters.create", compact("user"));
    }

    public function store(ServiceCenterStoreRequest $request) {
        $startHour = implode(":" ,$request->working_hours["start_hour"]);
        $endtHour = implode(":" ,$request->working_hours["end_hour"]);

        $request->working_hours = $startHour . "-" . $endtHour;

        ServiceCenter::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'fridays_off' => $request->fridaysOff,
            'working_hours' => $request->workingTime
        ]);

        return redirect()->route("home")->with('alert', ['مجموعه شما ثبت شد. خوش آمدید!', 'success']);
    }

    public function edit(User $user) :View {
        $serviceCenter = $user->serviceCenter;
        $hours = explode("-", $serviceCenter->working_hours);
        $working_hours = [
            'start_hour' => $hours[0],
            'end_hour' => $hours[1]
        ];

        foreach($working_hours as $key => $value) {
            $time = explode(":", $value);
            $working_hours[$key] = [
                'hour' => $time[0],
                'minute' => $time[1]
            ];
        }

        $serviceCenter->working_hours = $working_hours;

        return view("admin.serviceCenters.edit", compact("user", 'serviceCenter'));   
    }
}
