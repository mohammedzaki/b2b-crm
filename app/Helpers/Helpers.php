<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;

require('I18N_Arabic/Arabic.php');

use \I18N_Arabic;

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
    
}
