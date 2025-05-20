<?php

use Hekmatinasser\Jalali\Jalali;

if(!function_exists('checkCloseStatus')) {
    function checkCloseStatus($serviceCenter) {
        $now = Jalali::now();
        $nowHour = intval($now->format('H:m'));
        $today = $now->dayOfWeek;

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
}