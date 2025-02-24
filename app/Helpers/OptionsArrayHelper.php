<?php
namespace App\Helpers;

class OptionsArrayHelper{
    public static function generateOptionsArray($subOptions, $subValues){
        $optionsArray = array_combine(
            $subOptions,
            array_map(function ($value) {
                return array_map('trim', explode('،', $value));
            }, $subValues)
        );

        return $optionsArray;
    }
}