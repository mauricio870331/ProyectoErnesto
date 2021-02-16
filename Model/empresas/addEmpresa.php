<?php

session_start();
date_default_timezone_set('America/Bogota');
require_once '../BD.php';
$con = new BD();

if ($_POST["accion"] == "add") {
    $selectVaidDoc = "Select id_empresa from empresa where documento = '" . $_POST['documento'] . "'";
    $rs = $con->query($selectVaidDoc);
    if (count($rs) > 0) {
        $rsduplicate = array("message_code" => "duplicado");
        echo json_encode($rsduplicate);
        $con->desconectar();
        die();
    }
}
$ruta = "../../EmpresaImagenes";
$isotipo = "";

if (!file_exists($ruta)) {
    mkdir($ruta, 0700);
}

if (isset($_FILES['isotipo']) && !empty($_FILES['isotipo'])) {
    $archivo = $_FILES['isotipo']['tmp_name'];
    $nombre_archivo = $_FILES['isotipo']['name'];
    $tamanio = $_FILES["isotipo"]["size"];
    $ext = pathinfo($nombre_archivo);
    $extension = $ext['extension'];
    $isotipo = $ruta . "/" . $_POST["documento"] . "." . $extension;
    $subir = move_uploaded_file($archivo, $isotipo);
}

$action = "INSERT";
if ($_POST["accion"] == "add") {
    $SQL = "INSERT into empresa (nombre_empresa,documento,direccion,isotipo,email,fecha_creacion) "
            . "values ('" . $_POST["nom_empresa"] . "','" . $_POST["documento"] . "',"
            . "'" . $_POST["direccion"] . "',"
            . "'" . str_replace("../", "", $isotipo) . "', '" . $_POST["email"] . "', NOW())";
} else {
    $action = "UPDATE";
    if ($isotipo != "") {
        $foto = ",isotipo = '" . str_replace("../", "", $isotipo) . "'";
    }
    $SQL = "UPDATE empresa set nombre_empresa = '" . $_POST["nom_empresa"] . "',"
            . "documento = '" . $_POST["documento"] . "',"
            . "direccion = '" . $_POST["direccion"] . "',"
            . "email = '" . $_POST["email"] . "' " . $foto . " where id_empresa = " . $_POST["idEmpresa"];
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


