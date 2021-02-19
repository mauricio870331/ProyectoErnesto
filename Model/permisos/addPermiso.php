<?php

session_start();
date_default_timezone_set('America/Bogota');
require_once '../BD.php';
$con = new BD();
$id_empleado = $_SESSION["obj_user"][0]["id_empleado"];


$selectValJ = "Select id_empresa from empleados where id_empleado = " . $id_empleado;
$rs = $con->query($selectValJ);

//echo "<pre>";
//print_r($rs[0]["id_empresa"]);
//die;

$action = "INSERT";
if ($_POST["accion"] == "add") {
    $SQL = "INSERT into permisos (id_empresa,id_empleado,id_jefe_area,fecha_permiso,inicio_permiso, termino_permiso, motivo) "
            . "values (" . $rs[0]["id_empresa"] . "," . $id_empleado . ","
            . "" . $_POST["id_jefe_area"] . ","
            . "'" . $_POST["fecha_permiso"] . "',"
            . "'" . $_POST["inicio_permiso"] . "', "
            . "'" . $_POST["termino_permiso"] . "',"
            . "'" . $_POST["motivo"] . "')";
} else {
    $action = "UPDATE";
    $SQL = "UPDATE permisos set id_jefe_area = " . $_POST["id_jefe_area"] . ","
            . "fecha_permiso = '" . $_POST["fecha_permiso"] . "',"
            . "inicio_permiso = '" . $_POST["inicio_permiso"] . "',"
            . "termino_permiso = '" . $_POST["termino_permiso"] . "',"
            . "motivo = '" . $_POST["motivo"] . "' where id_permiso = " . $_POST["id_permiso"];
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


