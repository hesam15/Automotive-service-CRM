<?php

namespace App\Http\Controllers;

use App\Helpers\TimeFormatterHelper;
use App\Models\User;
use Illuminate\View\View;

use App\Models\ServiceCenter;
use App\Http\Requests\ServiceCenter\ServiceCenterStoreRequest;
use App\Http\Requests\ServiceCenter\ServiceCenterUpdateRequest;

class ServiceCenterController extends Controller
{
    public function index() {
        $serviceCenters = ServiceCenter::all();

        return view("supporter.serviceCenters.index" , compact("serviceCenters"));
    }

    // public function show(ServiceCenter $serviceCenter) {

    // }

    public function create(User $user) {
        return view("admin.serviceCenters.create", compact("user"));
    }

    public function store(ServiceCenterStoreRequest $request, User $user) {
        $workingHours = TimeFormatterHelper::formatHoursForStorage($request->working_hours);

        $serviceCenter = ServiceCenter::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'fridays_off' => $request->fridays_off,
            'working_hours' => $workingHours,
            'user_id' => $user->id,
            'address' => $request->address,
            'city_id' => 1
        ]);

        if($request->thumbnail_pat) {
            $request->thumbnail_path = fileStorageManager($request->thumbnail_pat, 'thumbnail', ServiceCenter::class, $serviceCenter->id);
        } else {
            $serviceCenter->thumbnail_path = 'service-centers/default-thumbnail.webp';
            $serviceCenter->save();
        }

        return redirect()->route("home")->with('alert', ['مجموعه شما ثبت شد. خوش آمدید!', 'success']);
    }

    public function edit(ServiceCenter $serviceCenter) :View {
        $serviceCenter->working_hours = TimeFormatterHelper::formatHoursForDisplay($serviceCenter->working_hours);

        return view("admin.serviceCenters.edit", compact('serviceCenter'));   
    }

    public function update(ServiceCenterUpdateRequest $request, ServiceCenter $serviceCenter) {
        $workingHours = TimeFormatterHelper::formatHoursForStorage($request->working_hours);

        $serviceCenter->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'fridays_off' => $request->fridays_off,
            'working_hours' => $workingHours
        ]);

        return redirect()->route("home")->with('alert', ['آپدیت مجموعه با موفقیت انجام شد.', 'success']);
    }

    public function destroy(ServiceCenter $serviceCenter) {
        $user = $serviceCenter->user;

        $serviceCenter->delete();

        return redirect()->route("serviceCenter.create", $user->id)->with('alert', ['مجموعه با موفقیت حذف شد.', 'danger']);
    }
}
