<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Extensions;

use Carbon\Carbon;

class DateTime extends Carbon
{

    public function __construct($time = null, $tz = null)
    {
        parent::__construct($time, $tz);
    }

    public function startDayFormat()
    {
        return $this->format("Y-m-d 00:00:00");
    }

    public function endDayFormat()
    {
        return $this->format("Y-m-d 23:59:59");
    }

    function getMonthName()
    {
        $this->setLocale('ar');
        return $this->formatLocalized('%A %d %B %Y');
    }

    public static function todayDateformat()
    {
        return static::today()->format('Y-m-d');
    }

    public static function parseToDateFormat($time)
    {
        return static::parse($time)->format('Y-m-d');
    }

}
