<?php

session_start();
date_default_timezone_set('America/Santiago');
require_once '../BD.php';
$con = new BD();

if ($_POST["accion"] == "add") {
    $selectValJ = "Select id_horario from horarios where id_empresa = " . $_POST['empresa'] . " and horario = '" . $_POST['horario'] . "'";
    $rs = $con->query($selectValJ);
    if (count($rs) > 0) {
        $rsduplicate = array("message_code" => "duplicado");
        echo json_encode($rsduplicate);
        $con->desconectar();
        die();
    }
}

$action = "INSERT";
if ($_POST["accion"] == "add") {
    $SQL = "INSERT into horarios (id_empresa, horario, entrada, salida, entrada_ini, entrada_fin, salida_ini, salida_fin, atraso, porcentaje_dia ) "
            . "values (" . $_POST["empresa"] . ",'" . $_POST["horario"] . "',"
            . "'" . $_POST["entrada"] . "',"
            . "'" . $_POST["salida"] . "',"
            . "'" . $_POST["entrada_ini"] . "',"
            . "'" . $_POST["entrada_fin"] . "', '" 
            . "'" . $_POST["salida_ini"] . "',"
            . "'" . $_POST["salida_fin"] . "', " 
            .  $_POST["atraso"] . ", '" 
            . $_POST["porcentaje_dia"] . "')";
} else {
    $action = "UPDATE";
    $SQL = "UPDATE horarios set id_empresa = " . $_POST["empresa"] . ","
            . "horario = '" . $_POST["horario"] . "',"
            . "entrada = '" . $_POST["entrada"] . "',"
            . "entrada_ini = '" . $_POST["entrada_ini"] . "',"
            . "entrada_fin = '" . $_POST["entrada_fin"] . "',"
            . "salida_ini = '" . $_POST["salida_ini"] . "',"
            . "salida_fin = '" . $_POST["salida_fin"] . "',"
            . "atraso = " . $_POST["atraso"] . ","
            . "porcentaje_dia = '" . $_POST["porcentaje_dia"] . "',"
            . "salida = '" . $_POST["salida"] . "' where id_horario = " . $_POST["idJornada"];
}

//echo $SQL;
//die;
$rs = $con->exec($SQL, $action);
if ($rs['message_code'] == "error") {
    $con->desconectar();
    echo json_encode($rs);
    die();
}
$con->desconectar();
$rs['accion'] = $_POST["accion"];
echo json_encode($rs);



