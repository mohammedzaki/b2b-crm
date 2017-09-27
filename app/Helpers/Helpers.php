<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;

require('I18N_Arabic/Arabic.php');

use \I18N_Arabic;
use Carbon\Carbon;

/**
 * Description of newPHPClass
 *
 * @author mohammedzaki
 */
class Helpers {
    
    static function numberToArabicWords($total) {
        $Arabic = new I18N_Arabic('Numbers');
        $text = $Arabic->money2str($total, 'EGP', 'ar');
        return $text;
    }
    
    static function hoursMinutsToString($totalDuration) {
        $hours = floor($totalDuration / 3600);
        $minutes = floor(($totalDuration / 60) % 60);
        $seconds = $totalDuration % 60;
        return "$hours:$minutes:$seconds";
    }
    
    static function diffInHoursMinutsToSeconds(Carbon $startDate = null, Carbon $endDate = null) {
        if ($startDate == null || $endDate == null) {
            return 0;
        }
        return $endDate->diffInSeconds($startDate);
    }
}
