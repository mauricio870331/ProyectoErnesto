<?php

include_once '../BD.php';
$con = new bd();
$id = strtotime("now");
$delete = "delete from huellas_temp where pc_serial = '" . $_POST['token'] . "'";
$con->exec($delete);
$insert = "insert into huellas_temp (id,pc_serial, texto, opc) "
        . "values (" . $id . ",'" . $_POST['token'] . "', 'El sensor de huella dactilar esta activado','leer')";
$row = $con->exec($insert);
$filas = $row["row_count"];
$con->desconectar();
echo json_encode("{\"filas\":$filas}");
