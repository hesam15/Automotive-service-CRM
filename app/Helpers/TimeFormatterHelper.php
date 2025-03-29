<?php

namespace App\Helpers;

class TimeFormatterHelper {
    static public function formatHoursForStorage($working_hours) {
        $startHour = implode(":" , $working_hours["start_hour"]);
        $endtHour = implode(":" , $working_hours["end_hour"]);

        return $startHour . "-" . $endtHour;
    }

    static public function formatHoursForDisplay($working_hours) {
        $hours = explode("-", $working_hours);
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

        return $working_hours;
    }
}