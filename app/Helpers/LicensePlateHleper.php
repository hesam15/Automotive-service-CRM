<?php
namespace App\Helpers;

class LicensePlateHleper{
    public static function generate($licensePlate) {
        return $licensePlate = implode('-', $licensePlate);
    }

    public static function show($licensePlate){
        return $licensePlate = explode('-', $licensePlate);
    } 
}