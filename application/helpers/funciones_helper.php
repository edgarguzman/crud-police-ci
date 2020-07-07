<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('date_format2')) {
    function date_format2($date, $format='EN', $separator='') {
        if ($format == 'ES') {        //español 30-06-2015 -> 2015-06-30 ingles
            list($d,$m,$Y) = explode($separator,$date);
            return $Y.'-'.$m.'-'.$d;
        } elseif ($format == 'EN') {  //ingles 2015-06-30 -> 30-06-2015 español
            list($Y,$m,$d) = explode($separator,$date);
            return $d.'-'.$m.'-'.$Y;
        } elseif ($format == 'DEC') {  //decimal 20150630 -> 2015-06-30 ingles
            $Y = substr($date,0,4);
            $m = substr($date,4,2);
            $d = substr($date,6,2);
            return $Y.'-'.$m.'-'.$d;
        }
        return null;
    }
}

/*
if ( ! function_exists('getEdadActuarial')) {
    function getEdadActuarial($date, $format='EN', $separator='-') {
        if ($format == 'ES') 
            list($d,$m,$a) = explode($separator,$date);
        elseif ($format == 'EN') 
            list($a,$m,$d) = explode($separator,$date);
        if ($d > 0 && $m > 0 && $a > 0) {
            $dd = date('d');
            $mm = date('m');
            $aa = date('Y');
            if ($dd < $d) {
                $dd += 30;
                $mm -= 1;
            }
            if ($mm < $m) {
                $mm += 12;
                $aa -= 1;
            }
            $edad = $aa - $a;
            $mes = $mm - $m;
            if ($mes >= 6)
                $edad += 1;
            return $edad;
        }
        return null;
    }
}
*/
if ( ! function_exists('getEdadActuarial')) {
    function getEdadActuarial($dateNac, $dateInc='', $format='EN', $separator='-') {
        $dateInc = ($dateInc!='') ? $dateInc : date('Y-m-d');
        if ($format == 'ES') {
            list($d,$m,$a) = explode($separator,$dateNac);
            list($dd,$mm,$aa) = explode($separator,$dateInc);
        } elseif ($format == 'EN') {
            list($a,$m,$d) = explode($separator,$dateNac);
            list($aa,$mm,$dd) = explode($separator,$dateInc);
        }
        if ($d > 0 && $m > 0 && $a > 0) {
            if ($dd < $d) {
                $dd += 30;
                $mm -= 1;
            }
            if ($mm < $m) {
                $mm += 12;
                $aa -= 1;
            }
            $edad = $aa - $a;
            $mes = $mm - $m;
            if ($mes >= 6)
                $edad += 1;
            return $edad;
        }
        return null;
    }
}

if ( ! function_exists('getDv')) {
    function getDv($rut) {
        $M = 0; $S = 1;
        for(; $rut; $rut = floor($rut/10))
            $S = ($S + $rut % 10 * (9 - $M++ %6)) % 11;
        return $S ? $S-1 : 'K';
    }
}
