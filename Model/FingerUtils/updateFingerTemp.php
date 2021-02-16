<?php

require_once '../BD.php';
$con = new BD();
$id = strtotime("now");

$selectVaidDoc = "Select * from huellas where documento = '" . $_POST['documento'] . "' and nombre_dedo ='" . $_POST['dedo'] . "'";
$rs = $con->findAll2($selectVaidDoc);

if ($_POST['option'] == 1 && $_POST['accion'] == "add") {

    if (count($rs) == 0) {
        $selectVaidDoc = "Select imgHuella, huella from huellas_temp where pc_serial = '" . $_POST['token'] . "'";
        $rs = $con->findAll2($selectVaidDoc);
        if (count($rs) > 0) {
            $INSERT = "INSERT INTO huellas (id,documento,nombre_dedo,huella,imgHuella) "
                    . "values (" . $id . ",'" . $_POST['documento'] . "', '" . $_POST['dedo'] . "', '" . $rs[0]['huella'] . "', '" . $rs[0]['imgHuella'] . "')";
            $rs = $con->exec($INSERT);
            if ($rs['message_code'] == "error") {
                $con->desconectar();
                echo json_encode($rs);
                die();
            }
        }
    }
}

if ($_POST['option'] == 1 && $_POST['accion'] == "edit") {
    $selectVaidDoc = "Select imgHuella, huella from huellas_temp where pc_serial = '" . $_POST['token'] . "'";
    $rs = $con->findAll2($selectVaidDoc);
    if (count($rs) > 0) {
        $UPDATE = "UPDATE huellas set nombre_dedo = '" . $_POST['dedo'] . "', huella = '" . $rs[0]['huella'] . "',"
                . "imgHuella = '" . $rs[0]['imgHuella'] . "' where id = " . $_POST["idHuellaUpd"];        
        $rs = $con->exec($UPDATE);
        if ($rs['message_code'] == "error") {
            $con->desconectar();
            echo json_encode($rs);
            die();
        }
    }
}

$Update = "update huellas_temp set opc = 'cerrar' where pc_serial = '" . $_POST['token'] . "'";
//$Update = "delete from huellas_temp where pc_serial = '" . $_POST['token'] . "'";
$rs = $con->exec($Update);
$rs['idHuella'] = $_POST["idHuella"];
$rs['accion'] = $_POST['accion'];
$rs['option'] = $_POST['option'];
$con->desconectar();
echo json_encode($rs);

