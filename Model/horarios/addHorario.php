<?php

session_start();
date_default_timezone_set('America/Bogota');
require_once '../BD.php';
$con = new BD();

if ($_POST["accion"] == "add") {
    $selectValJ = "Select id_horario from horarios where id_empresa = " . $_POST['empresa'] . " and jornada = '" . $_POST['jornada'] . "'";
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
    $SQL = "INSERT into horarios (id_empresa,jornada,entrada,salida_colacion, entrada_colacion, salida) "
            . "values (" . $_POST["empresa"] . ",'" . $_POST["jornada"] . "',"
            . "'" . $_POST["entrada"] . "',"
            . "'" . $_POST["salida_c"] . "',"
            . "'" . $_POST["entrada_c"] . "', '" . $_POST["salida"] . "')";
} else {
    $action = "UPDATE";
    $SQL = "UPDATE horarios set id_empresa = " . $_POST["empresa"] . ","
            . "jornada = '" . $_POST["jornada"] . "',"
            . "entrada = '" . $_POST["entrada"] . "',"
            . "salida_colacion = '" . $_POST["salida_c"] . "',"
            . "entrada_colacion = '" . $_POST["entrada_c"] . "',"
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

