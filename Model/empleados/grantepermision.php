<?php

session_start();
date_default_timezone_set('America/Bogota');
require_once '../BD.php';
$con = new BD();

$selectVaidDoc = "Select * from menus_empleados where id_submenu = " . $_POST['id_submenu'] . " and id_empleado =" . $_POST['id_empleado'];
$rs = $con->query($selectVaidDoc);

if (count($rs) == 0) {
    $action = "INSERT";
    $SQL = "INSERT into menus_empleados (id_submenu ,id_empleado) "
            . "values (" . $_POST["id_submenu"] . ", " . $_POST["id_empleado"] . ")";
} else {
    $action = "DELETE";
    $SQL = "DELETE from menus_empleados where id_submenu = " . $_POST['id_submenu'] . " and id_empleado =" . $_POST['id_empleado'];
}

$rs = $con->exec($SQL, $action);
if ($rs['message_code'] == "error") {
    $con->desconectar();
    echo json_encode($rs);
    die();
}
$con->desconectar();
$rs["id_empleado"] = $_POST['id_empleado'];
$rs["baseurl"] = $_POST['baseurl'];
echo json_encode($rs);


