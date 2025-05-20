<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCenter;
use Hekmatinasser\Jalali\Jalali;

class ClientSideController extends Controller
{
    public function index() {
        $serviceCenters = ServiceCenter::all();

        foreach($serviceCenters as $serviceCenter) {
            checkCloseStatus($serviceCenter);
        }

        return view('customer.index', ['serviceCenters' => $serviceCenters]);
    }

    public function show(ServiceCenter $serviceCenter) {

    }
}
