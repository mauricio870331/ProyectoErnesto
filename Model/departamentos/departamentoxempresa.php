<?php

include_once '../BD.php';
$con = new BD();
$SQL_SELECT = "Select * from departamento where id_empresa = " . $_POST["id_empresa"];
$rs = $con->findAll2($SQL_SELECT);

$SQL_SELECT2 = "Select * from horarios where id_empresa = " . $_POST["id_empresa"];
$rshorarios = $con->findAll2($SQL_SELECT2);

$lista = array();
$lista["dpto"] = $rs;
$lista["horario"] = $rshorarios;

$con->desconectar();

echo json_encode($lista, true);

?>

