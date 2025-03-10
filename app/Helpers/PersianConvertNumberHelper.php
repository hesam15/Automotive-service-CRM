<?php

namespace App\Helpers;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class PersianConvertNumberHelper
{

    public $value;
    private static $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    private static $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    public function __construct($value){
        $this->value = $value;
    }

    public function convertPersianToEnglish() {      
        $this->value = str_replace(self::$persian, self::$english, $this->value);

        return $this;
    }

    public function convertEnglishToPersian() {
        if(!$this->value) {
            return "";
        }
        $this->value = str_replace(self::$english, self::$persian, $this->value);

        return $this;
    }

    public function convertDateToPersinan() {
        if (!$this->value) {
            return "";
        }
        
        $this->value = Jalalian::fromCarbon(Carbon::parse($this->value))->format('Y/m/d');

        return $this;
    }

    public function convertDateToEnglish() {
        if (!$this->value) {
            return "";
        }

        $this->value = Jalalian::fromFormat('Y/m/d', $this->value)->toCarbon()->format('Y-m-d');

        return $this;
    }

    public function getValue() {
        return $this->value;
    }
}
