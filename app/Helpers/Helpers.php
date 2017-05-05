<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;

/**
 * Description of newPHPClass
 *
 * @author mohammedzaki
 */
class Helpers {

    static function numberToArabicWords($total) {
        $arb[0] = '';
        $arb[1] = '';
        $arb[2] = '';
        $arb[3] = '';
        $arb[4] = '';
        $arb[5] = '';
        $arb[6] = '';
        
        $total = explode(".", $total);
        $j = strlen($total[0]);
        $je = $j;
        $je--;
        $de = 1;
        for ($i = 1; $i < $j; $i++) {
            $de = $de * 10;
        }
        $t = $total[0];
        for ($i = 0; $i < $j; $i++) {
            $te[$je] = $t / $de;
            $t = $t % $de;
            $de = $de / 10;
            $temp = explode(".", $te[$je]);
            $te[$je] = $temp[0];
            $je--;
        }

        for ($i = 0; $i < $j; $i++) {
            if ($i == 0) {
                if ($j < 3) {
                    switch ($te[$i]) {
                        case "0" : $arb[0] = " ";
                            break;
                        case "1" : $arb[0] = " واحد";
                            break;
                        case "2" : if ($te[1] == "1")
                                $arb[0] = " اثنا ";
                            else
                                $arb[0] = " اثنان";
                            break;
                        case "3" : $arb[0] = " ثلاثة";
                            break;
                        case "4" : $arb[0] = " اربعة";
                            break;
                        case "5" : $arb[0] = " خمسة";
                            break;
                        case "6" : $arb[0] = " ستة";
                            break;
                        case "7" : $arb[0] = " سبعة";
                            break;
                        case "8" : $arb[0] = " ثمانية";
                            break;
                        case "9" : $arb[0] = " تسعة";
                            break;
                    }
                } else {
                    switch ($te[$i]) {
                        case "0" : $arb[0] = " ";
                            break;
                        case "1" : $arb[0] = " وواحد";
                            break;
                        case "2" :
                            if ($te[1] == "1") {
                                $arb[0] = " واثنا ";
                            } else {
                                $arb[0] = " واثنان";
                            }
                            break;
                        case "3" : $arb[0] = " وثلاثة";
                            break;
                        case "4" : $arb[0] = " واربعة";
                            break;
                        case "5" : $arb[0] = " وخمسة";
                            break;
                        case "6" : $arb[0] = " وستة";
                            break;
                        case "7" : $arb[0] = " وسبعة";
                            break;
                        case "8" : $arb[0] = " وثمانية";
                            break;
                        case "9" : $arb[0] = " وتسعة";
                            break;
                    }
                }
            }

            if ($i == 1) {
                if ($j == 2) {
                    if ($te[$i] == 1) {
                        if ($te[0] == "1") {
                            $arb[0] = " ";
                            $arb[1] = " أحد عشر";
                        } elseif ($te[0] == "0") {
                            $arb[1] = " عشرة";
                        } else {
                            $arb[1] = " عشر";
                        }
                    }
                    if ($te[0] == "0") {
                        switch ($te[$i]) {
                            case "0" : $arb[1] = " ";
                                break;
                            case "1" :
                                if ($te[0] == "1") {
                                    $arb[0] = " ";
                                    $arb[1] = " أحد عشر";
                                } elseif ($te[0] == "0") {
                                    $arb[1] = " عشرة";
                                } else {
                                    $arb[1] = "عشر";
                                }
                                break;
                            case "2" : $arb[1] = " عشرون";
                                break;
                            case "3" : $arb[1] = " ثلاثون";
                                break;
                            case "4" : $arb[1] = " اربعون";
                                break;
                            case "5" : $arb[1] = " خمسون";
                                break;
                            case "6" : $arb[1] = " ستون";
                                break;
                            case "7" : $arb[1] = " سبعون";
                                break;
                            case "8" : $arb[1] = " ثمانون";
                                break;
                            case "9" : $arb[1] = " تسعون";
                                break;
                        }
                    }
                } else {
                    switch ($te[$i]) {
                        case "0" : $arb[1] = " ";
                            break;
                        case "1" :
                            if ($te[0] == "1") {
                                $arb[0] = " ";
                                $arb[1] = " وأحد عشر";
                            } elseif ($te[0] == "0") {
                                $arb[1] = " وعشرة";
                            } else {
                                $arb[1] = " عشر";
                            }
                            break;
                        case "2" : $arb[1] = " وعشرون";
                            break;
                        case "3" : $arb[1] = " وثلاثون";
                            break;
                        case "4" : $arb[1] = " واربعون";
                            break;
                        case "5" : $arb[1] = " وخمسون";
                            break;
                        case "6" : $arb[1] = " وستون";
                            break;
                        case "7" : $arb[1] = " وسبعون";
                            break;
                        case "8" : $arb[1] = " وثمانون";
                            break;
                        case "9" : $arb[1] = " وتسعون";
                            break;
                    }
                }
            }

            if ($i == 2) {
                if ($j == 3) {
                    switch ($te[$i]) {
                        case "0" : $arb[2] = " ";
                            break;
                        case "1" : $arb[2] = " مائة";
                            break;
                        case "2" : $arb[2] = " مائتان";
                            break;
                        case "3" : $arb[2] = " ثلاثمائة";
                            break;
                        case "4" : $arb[2] = " اربعمائة";
                            break;
                        case "5" : $arb[2] = " خمسمائة";
                            break;
                        case "6" : $arb[2] = " ستمائة";
                            break;
                        case "7" : $arb[2] = " سبعمائة";
                            break;
                        case "8" : $arb[2] = " ثمانمائة";
                            break;
                        case "9" : $arb[2] = " تسعمائة";
                            break;
                    }
                } else {
                    switch ($te[$i]) {
                        case "0" : $arb[2] = " ";
                            break;
                        case "1" : $arb[2] = " ومائة";
                            break;
                        case "2" : $arb[2] = " ومائتان";
                            break;
                        case "3" : $arb[2] = " وثلاثمائة";
                            break;
                        case "4" : $arb[2] = " واربعمائة";
                            break;
                        case "5" : $arb[2] = " وخمسمائة";
                            break;
                        case "6" : $arb[2] = " وستمائة";
                            break;
                        case "7" : $arb[2] = " وسبعمائة";
                            break;
                        case "8" : $arb[2] = " وثمانمائة";
                            break;
                        case "9" : $arb[2] = " وتسعمائة";
                            break;
                    }
                }
            }

            if ($i == 3) {
                if ($j == 4) {
                    switch ($te[$i]) {
                        case "0" : $arb[$i] = " ";
                            break;
                        case "1" : $arb[$i] = " ألف";
                            break;
                        case "2" : $arb[$i] = " ألفان";
                            break;
                        case "3" : $arb[$i] = " ثلاثة آلاف";
                            break;
                        case "4" : $arb[$i] = " اربعة آلاف";
                            break;
                        case "5" : $arb[$i] = " خمسة آلاف";
                            break;
                        case "6" : $arb[$i] = " ستة آلاف";
                            break;
                        case "7" : $arb[$i] = " سبعة آلاف";
                            break;
                        case "8" : $arb[$i] = " ثمانية آلاف ";
                            break;
                        case "9" : $arb[$i] = " تسعة آلاف ";
                            break;
                    }
                } elseif ($j == 5) {
                    switch ($te[$i]) {
                        case "0" : $arb[$i] = " ";
                            break;
                        case "1" : $arb[$i] = " واحد ";
                            break;
                        case "2" :
                            if ($te[6] == "1") {
                                $arb[$i] = " اثنا ";
                            } else {
                                $arb[$i] = " اثنان";
                            }
                            break;
                        case "3" : $arb[$i] = " ثلاثة ";
                            break;
                        case "4" : $arb[$i] = " اربعة ";
                            break;
                        case "5" : $arb[$i] = " خمسة ";
                            break;
                        case "6" : $arb[$i] = " ستة ";
                            break;
                        case "7" : $arb[$i] = " سبعة ";
                            break;
                        case "8" : $arb[$i] = " ثمانية ";
                            break;
                        case "9" : $arb[$i] = " تسعة ";
                    }
                } else {
                    switch ($te[$i]) {
                        case "0" : $arb[$i] = " ";
                            break;
                        case "1" : $arb[$i] = " وواحد ";
                            break;
                        case "2" :
                            if ($te[4] == "1") {
                                $arb[$i] = " واثنا ";
                            } else {
                                $arb[$i] = " واثنان";
                            }
                            break;
                        case "3" : $arb[$i] = " وثلاثة ";
                            break;
                        case "4" : $arb[$i] = " واربعة ";
                            break;
                        case "5" : $arb[$i] = " وخمسة ";
                            break;
                        case "6" : $arb[$i] = " وستة ";
                            break;
                        case "7" : $arb[$i] = " وسبعة ";
                            break;
                        case "8" : $arb[$i] = " وثمانية ";
                            break;
                        case "9" : $arb[$i] = " وتسعة ";
                    }
                }
            }

            if ($i == 4) {
                /* if ($j == 5) {
                  switch ($te[$i]) {
                  case "0" : $arb[$i] = " ";
                  break;
                  case "1" :
                  if ($te[3] == "1") {
                  $arb[3] = " ";
                  $arb[4] = " أحد عشر الفا";
                  } elseif ($te[3] == "0") {
                  $arb[4] = " عشرة آلاف";
                  } else {
                  $arb[$i] = " عشر الفا";
                  }
                  break;
                  case "2" : $arb[$i] = " عشرون ";
                  break;
                  case "3" : $arb[$i] = " ثلاثون ";
                  break;
                  case "4" : $arb[$i] = " اربعون ";
                  break;
                  case "5" : $arb[$i] = " خمسون ";
                  break;
                  case "6" : $arb[$i] = " ستون ";
                  break;
                  case "7" : $arb[$i] = " سبعون ";
                  break;
                  case "8" : $arb[$i] = " ثمانون ";
                  break;
                  case "9" : $arb[$i] = " تسعون ";
                  break;
                  }
                  } else { */
                switch ($te[$i]) {
                    case "0" : $arb[$i] = " ";
                        break;
                    case "1" :
                        if ($te[3] == "1") {
                            $arb[3] = " ";
                            $arb[4] = " وأحد عشر الفا";
                        } elseif ($te[3] == "0") {
                            $arb[4] = " وعشرة آلاف";
                        } else {
                            $arb[$i] = " عشر الفا";
                        }
                        break;
                    case "2" : $arb[$i] = " وعشرون ";
                        break;
                    case "3" : $arb[$i] = " وثلاثون ";
                        break;
                    case "4" : $arb[$i] = " واربعون ";
                        break;
                    case "5" : $arb[$i] = " وخمسون ";
                        break;
                    case "6" : $arb[$i] = " وستون ";
                        break;
                    case "7" : $arb[$i] = " وسبعون ";
                        break;
                    case "8" : $arb[$i] = " وثمانون ";
                        break;
                    case "9" : $arb[$i] = " وتسعون ";
                        break;
                }
                //}
            }

            if ($i == 5) {
                if ($j == 6) {
                    switch ($te[$i]) {
                        case "0" : $arb[$i] = " ";
                            break;
                        case "1" : $arb[$i] = " مائة ";
                            break;
                        case "2" : $arb[$i] = " مائتان ";
                            break;
                        case "3" : $arb[$i] = " ثلاثمائة ";
                            break;
                        case "4" : $arb[$i] = " اربعمائة ";
                            break;
                        case "5" : $arb[$i] = " خمسمائة ";
                            break;
                        case "6" : $arb[$i] = " ستمائة ";
                            break;
                        case "7" : $arb[$i] = " سبعمائة ";
                            break;
                        case "8" : $arb[$i] = " ثمانمائة ";
                            break;
                        case "9" : $arb[$i] = " تسعمائة ";
                            break;
                    }
                } else {
                    switch ($te[$i]) {
                        case "0" : $arb[$i] = " ";
                            break;
                        case "1" : $arb[$i] = " ومائة ";
                            break;
                        case "2" : $arb[$i] = " ومائتان ";
                            break;
                        case "3" : $arb[$i] = " وثلاثمائة ";
                            break;
                        case "4" : $arb[$i] = " واربعمائة ";
                            break;
                        case "5" : $arb[$i] = " وخمسمائة ";
                            break;
                        case "6" : $arb[$i] = " وستمائة ";
                            break;
                        case "7" : $arb[$i] = " وسبعمائة ";
                            break;
                        case "8" : $arb[$i] = " وثمانمائة ";
                            break;
                        case "9" : $arb[$i] = " وتسعمائة ";
                            break;
                    }
                }
            }

            if ($i == 6) {
                switch ($te[$i]) {
                    case "0" : $arb[$i] = " ";
                        break;
                    case "1" : $arb[$i] = " مليون ";
                        break;
                    case "2" : $arb[$i] = " مليونان ";
                        break;
                    case "3" : $arb[$i] = " ثلاثة ملايين ";
                        break;
                    case "4" : $arb[$i] = " اربعة ملايين ";
                        break;
                    case "5" : $arb[$i] = " خمسة ملايين ";
                        break;
                    case "6" : $arb[$i] = " تة ملايين ";
                        break;
                    case "7" : $arb[$i] = " سبعة ملايين ";
                        break;
                    case "8" : $arb[$i] = " ثمانية ملايين ";
                        break;
                    case "9" : $arb[$i] = " تسعة ملايين ";
                        break;
                }
            }
        }

        if ($j > 4 && $te[4] != "1") {
            $arb[4] = $arb[4] . " الف ";
        }

        $strarb = $arb[6] . $arb[5] . $arb[3] . $arb[4] . $arb[2] . $arb[0] . $arb[1];
        return $strarb . ' جنيها فقط لاغير';
    }

}
