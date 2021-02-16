<?php

date_default_timezone_set('America/Bogota');

require_once '../BD.php';
$con = new BD();
$hora_actual = date("H:i:s");
$fecha_actual = date("Y-m-d");

//echo $fecha_actual;die;
$selectJornada = "Select id_jornada from empleados where documento = '" . $_POST['documento'] . "'";
$rsjornada = $con->query($selectJornada);

$selectMarc = "Select id from marcaciones where documento = '" . $_POST['documento'] . "' and fecha = '" . $fecha_actual . "'";
$rsfecha = $con->query($selectMarc);


$queryHorario = "SELECT *, TIME_FORMAT(TIMEDIFF(entrada,'" . $hora_actual . "'), '%H:%i') resto_entrada, "
        . "TIME_FORMAT(TIMEDIFF(salida_colacion,'" . $hora_actual . "'), '%H:%i') resto_salidac,"
        . "TIME_FORMAT(TIMEDIFF(entrada_colacion,'" . $hora_actual . "'), '%H:%i') resto_entradac,"
        . "TIME_FORMAT(TIMEDIFF(salida,'" . $hora_actual . "'), '%H:%i') resto_salida   FROM horarios WHERE id = " . $rsjornada[0]["id_jornada"];
$rs = $con->query($queryHorario);

//echo "<pre>";
//print_r($rs);
//die;

$hora_entrada = explode(":", $rs[0]['resto_entrada'])[0];
$minuto_entrada = (int) explode(":", $rs[0]['resto_entrada'])[1];

$hora_salidac = explode(":", $rs[0]['resto_salidac'])[0];
$minuto_salidac = (int) explode(":", $rs[0]['resto_salidac'])[1];

$hora_entradac = explode(":", $rs[0]['resto_entradac'])[0];
$minuto_entradac = (int) explode(":", $rs[0]['resto_entradac'])[1];

$hora_salida = explode(":", $rs[0]['resto_salida'])[0];
$minuto_salida = (int) explode(":", $rs[0]['resto_salida'])[1];

$select_marca = "";

//echo $hora_enrada ." --> ".$minuto_enrada;
//Determina si la marcacion es entrada
if ($hora_entrada == "-00" || $hora_entrada == "00") {
    if ($minuto_entrada <= 15) {
        $select_marca = "entrada";
    } else {
        $select_marca = "fr;entrada";
    }
}

//Determina si la marcacion es salida_colacion
if ($hora_salidac == "-00" || $hora_salidac == "00") {
    if ($minuto_salidac <= 15) {
        $select_marca = "salida_colacion";
    } else {
        $select_marca = "fr;salida_colacion";
    }
}

//Determina si la marcacion es entrada_colacion
if ($hora_entradac == "-00" || $hora_entradac == "00") {
    if ($minuto_entradac <= 15) {
        $select_marca = "entrada_colacion";
    } else {
        $select_marca = "fr;entrada_colacion";
    }
}

//Determina si la marcacion es salida
if ($hora_salida == "-00" || $hora_salida == "00" || $hora_salida == "01" || $hora_salida == "02") {
    if ($minuto_salida <= 15) {
        $select_marca = "salida";
    } else {
        $select_marca = "fr;salida";
    }
}

$QUERY = "";
$action = "INSERT";

if ($select_marca == "entrada" || $select_marca == "fr;entrada") {
    $QUERY = "insert into marcaciones (documento,entrada, fecha) values ('" . $_POST['documento'] . "','" . $hora_actual . "','" . $fecha_actual . "')";
}

if ($select_marca == "salida_colacion" || $select_marca == "fr;salida_colacion") {
    if (count($rsfecha) > 0) {
        $action = "UPDATE";
        $QUERY = "update marcaciones set salida_colacion = '" . $hora_actual . "' where id = " . $rsfecha[0]["id"];
    } else {
        $action = "INSERT";
        $QUERY = "insert into marcaciones (documento,salida_colacion, fecha) values ('" . $_POST['documento'] . "','" . $hora_actual . "','" . $fecha_actual . "')";
    }
}

if ($select_marca == "entrada_colacion" || $select_marca == "fr;entrada_colacion") {
    if (count($rsfecha) > 0) {
        $action = "UPDATE";
        $QUERY = "update marcaciones set entrada_colacion = '" . $hora_actual . "' where id = " . $rsfecha[0]["id"];
    } else {
        $action = "INSERT";
        $QUERY = "insert into marcaciones (documento,entrada_colacion, fecha) values ('" . $_POST['documento'] . "','" . $hora_actual . "','" . $fecha_actual . "')";
    }
}

if ($select_marca == "salida" || $select_marca == "fr;salida") {
    if (count($rsfecha) > 0) {
        $action = "UPDATE";
        $QUERY = "update marcaciones set salida = '" . $hora_actual . "' where id = " . $rsfecha[0]["id"];
    } else {
        $action = "INSERT";
        $QUERY = "insert into marcaciones (documento,salida, fecha) values ('" . $_POST['documento'] . "','" . $hora_actual . "','" . $fecha_actual . "')";
    }
}
//echo $QUERY;
//die;

if ($QUERY != "") {
    $respose = $con->exec($QUERY, $action);
} else {
    $respose = array("message_code" => "fuera_rango");
}

$con->desconectar();
echo json_encode($respose);
