<?php

session_start();
date_default_timezone_set('America/Bogota');
require_once '../BD.php';
require '../Utils.php';
$con = new BD();

if ($_POST["accion"] == "add") {
    $selectVaidDoc = "Select id_empleado from empleados where documento = '" . $_POST['documento'] . "'";
    $rs = $con->query($selectVaidDoc);
    if (count($rs) > 0) {
        $rsduplicate = array("message_code" => "duplicado");
        echo json_encode($rsduplicate);
        $con->desconectar();
        die();
    }
}

$ruta = "../../EmpleadosImagenes";
$foto = "";

if (!file_exists($ruta)) {
    mkdir($ruta, 0700);
}

if (isset($_FILES['foto']) && !empty($_FILES['foto'])) {
    $archivo = $_FILES['foto']['tmp_name'];
    $nombre_archivo = $_FILES['foto']['name'];
    $tamanio = $_FILES["foto"]["size"];
    $ext = pathinfo($nombre_archivo);
    $extension = $ext['extension'];
    $foto = $ruta . "/" . $_POST["documento"] . "." . $extension;
    //Redimensionar
    $subir = move_uploaded_file($archivo, $foto);
}

//'documento', 'nombres', 'apellidos', 'telefono', 'direccion', 'fecha_nacimiento'
$field_dob = "";
$fielddob_val = "";

$action = "INSERT";
if ($_POST["accion"] == "add") {
    if ($_POST["fecha_nacimiento"] != "") {
        $field_dob = ",fecha_nacimiento";
        $fielddob_val = ",'" . $_POST["fecha_nacimiento"] . "'";
    }
    $SQL = "INSERT into empleados (id_empresa,documento,nombres,apellidos,telefono,direccion,fecha_creacion,foto,"
            . "id_departamento,id_rol,password,id_horario" . $field_dob . ") "
            . "values (" . $_POST["empresa"] . ","
            . ""
            . "'" . $_POST["documento"] . "','" . $_POST["nombres"] . "',"
            . "'" . $_POST["apellidos"] . "',"
            . "'" . $_POST["telefono"] . "',"
            . "'" . $_POST["direccion"] . "', NOW(), "
            . "'" . str_replace("../", "", $foto) . "',"
            . "" . $_POST["departamento"] . ","
            . "" . $_POST["rol"] . ","
            . "'" . Utils::hash("1234") . "',"
            . "" . $_POST["jornada"] . ""
            . "" . $fielddob_val . ")";
} else {
    $action = "UPDATE";
    if ($_POST["fecha_nacimiento"] != "") {
        $field_dob = ",fecha_nacimiento = '" . $_POST["fecha_nacimiento"] . "'";
    }
    if ($foto != "") {
        $foto = "foto = '" . str_replace("../", "", $foto) . "',";
    }
    $SQL = "UPDATE empleados set documento = '" . $_POST["documento"] . "',"
            . "nombres = '" . $_POST["nombres"] . "',"
            . "apellidos = '" . $_POST["apellidos"] . "',"
            . "telefono = '" . $_POST["telefono"] . "',"
            . "direccion = '" . $_POST["direccion"] . "'," . $foto
            . "id_empresa = " . $_POST["empresa"] . ","
            . "id_departamento = " . $_POST["departamento"] . ","
            . "id_rol = " . $_POST["rol"] . ","
            . "id_horario = " . $_POST["jornada"] . ""
            . "" . $field_dob . " WHERE id_empleado = " . $_POST["idUser"];
}
//echo $SQL;
//die;
$rs = $con->exec($SQL, $action);
if ($_POST["accion"] == "add") {
    $selectVaidDoc = "Select id_empleado from empleados where documento = '" . $_POST['documento'] . "'";
    $rss = $con->query($selectVaidDoc);
    switch ($_POST["rol"]) {
        case 4:
            $querypermisos = "select id_submenu from sub_menu_app where id_submenu in (11,12)";
            break;
        default :
            $querypermisos = "select id_submenu from sub_menu_app";
            break;
    }
    $rst = $con->query($querypermisos);
    for ($j = 0; $j < count($rst); $j++) {
        $INSERT = "INSERT into menus_empleados (id_submenu,id_empleado) "
                . "values(" . $rst[$j]["id_submenu"] . ", " . $rss[0]["id_empleado"] . ")";
        $con->exec($INSERT, "INSERT");
    }
}

if ($rs['message_code'] == "error") {
    $con->desconectar();
    echo json_encode($rs);
    die();
}

$DELETE = "delete from huellas_temp where pc_serial = '" . $_POST['token'] . "'";
$con->exec($DELETE, "DELETE");
$con->desconectar();
$rs['accion'] = $_POST["accion"];
//$rs['token'] = $_POST["idUser"];
echo json_encode($rs);


