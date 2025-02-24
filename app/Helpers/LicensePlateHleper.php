<?php
namespace App\Helpers;

class LicensePlateHleper{
    public static function generate($licensePlate) {
        return $licensePlate->plate_two . '-' . $licensePlate->plate_letter . '-' . $licensePlate->plate_three . '-' . $licensePlate->plate_iran;
    }
}