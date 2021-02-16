<?php

class Utils {

    const SALT = '0421';

    public static function hash($password) {
        return hash('sha512', self::SALT . $password);
    }

    public static function verify($password, $hash) {
        return ($hash == self::hash($password));
    }

    public static function DMY_TO_YMD($str) {
        $parts = explode("/", $str);
        $new = $parts[2] . "-" . $parts[0] . "-" . $parts[1];
        return $new;
    }

    public static function YMD_TO_DMY($str) {
        $parts = explode("-", $str);
        $new = $parts[1] . "/" . $parts[2] . "/" . $parts[0];
        return $new;
    }

    public static function SUM_ONE_MONTH_TO_DATE($month, $day) {
        $fecha = date('Y-m-' . $day);
        $nuevafecha = strtotime('+' . $month . ' month', strtotime($fecha));
        $returnFecha = date('Y-m-' . $day, $nuevafecha);
        return $returnFecha;
    }

    public static function ConvertMontIntToStr($mon, $opc = 1) {
        $mes = "";
        switch ($mon) {
            case "01":$mes = "Enero";
                break;
            case "02":$mes = "Febrero";
                break;
            case "03":$mes = "Marzo";
                break;
            case "04":$mes = "Abril";
                break;
            case "05":$mes = "Mayo";
                break;
            case "06":$mes = "Junio";
                break;
            case "07":$mes = "Julio";
                break;
            case "08":$mes = "Agosto";
                break;
            case "09":$mes = "Septiembre";
                break;
            case "10":$mes = "Octubre";
                break;
            case "11":$mes = "Noviembre";
                break;
            case "12":$mes = "Diciembre";
                break;
            default:$mes = "Sin conversion";
                break;
        }
        return $mes;
    }

    public static function GET_PERSON($id, $con) {
        $SQL_QUERY = "select * from personas where id = " . $id;
        $rs = $con->findAll2($SQL_QUERY);
        return $rs;
    }

    public static function ConvertTimeStrToInt($hora, $min, $fomat) {
        /*
          echo Utils::ConvertTimeStrToInt($time_vence[0], substr($time_vence[1], 0,2), substr($time_vence[1], -2))."<br>";
          echo Utils::ConvertTimeStrToInt($time_actual[0], substr($time_actual[1], 0,2), substr($time_actual[1], -2))."<br>";
         */
        $timestamp = "";
        switch ($fomat) {
            case "AM":
                $timestamp = strtotime($hora . ":" . $min . ":00");
                break;
            case "PM":
                $cont = 0;
                for ($index = 12; $index < 25; $index++) {
                    if ($hora == $cont) {
                        $timestamp = strtotime($index . ":" . $min . ":00");
                        break;
                    }
                    $cont++;
                }

                break;
        }
        return $timestamp;
    }

    public static function ConvertDateStrToInt($fecha) {
//$time_vence = explode(":", $rs[0]["hora_vence"]);
//$time_actual = explode(":", $rs[0]["Hora_corta"]);        
        $timestamp = strtotime($fecha);
        return $timestamp;
    }

}

//echo Utils::hash("123456");
?>

