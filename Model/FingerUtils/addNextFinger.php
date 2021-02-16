<?php

session_start();
date_default_timezone_set('America/Bogota');
require_once '../BD.php';
$con = new BD();
$id = strtotime("now");
//print_r($_POST);
//die;

$selectVaidDoc = "Select * from huellas where documento = '" . $_POST['documento'] . "' and nombre_dedo ='" . $_POST['dedo'] . "'";
$rs = $con->findAll2($selectVaidDoc);
if (count($rs) > 0) {
    $con->desconectar();
    $rs = array("message_code" => "duplicado");
    echo json_encode($rs);
    die;
}

$selectVaidDoc = "Select imgHuella, huella from huellas_temp where pc_serial = '" . $_POST['token'] . "'";
$rs = $con->findAll2($selectVaidDoc);

if (count($rs) > 0) {
    $INSERT = "INSERT INTO huellas (id, documento,nombre_dedo,huella,imgHuella) "
            . "values (" . $id . ",'" . $_POST['documento'] . "', '" . $_POST['dedo'] . "', '" . $rs[0]['huella'] . "', '" . $rs[0]['imgHuella'] . "')";
    $rs = $con->exec($INSERT);
} else {
    $rs = array("message_code" => "error");
}

$con->desconectar();
echo json_encode($rs);


