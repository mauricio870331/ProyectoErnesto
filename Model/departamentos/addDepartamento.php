<?php

session_start();
date_default_timezone_set('America/Bogota');
require_once '../BD.php';
$con = new BD();

if ($_POST["accion"] == "add") {
    $selectVaidDoc = "Select id_empresa from departamento where id_empresa = '" . $_POST['empresa'] . "' and descripcion = '" . $_POST['descripcion'] . "'";
    $rs = $con->query($selectVaidDoc);
    if (count($rs) > 0) {
        $rsduplicate = array("message_code" => "duplicado");
        echo json_encode($rsduplicate);
        $con->desconectar();
        die();
    }
}

$action = "INSERT";
if ($_POST["accion"] == "add") {
    $SQL = "INSERT into departamento (id_empresa,descripcion) "
            . "values ('" . $_POST["empresa"] . "','" . $_POST["descripcion"] . "')";
} else {
    $action = "UPDATE";
    $SQL = "UPDATE departamento set id_empresa = '" . $_POST["empresa"] . "',"
            . "descripcion = '" . $_POST["descripcion"] . "' where id_departamento = " . $_POST["idDpto"];
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


