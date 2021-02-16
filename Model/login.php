<?php

require './Utils.php';

session_start();
date_default_timezone_set('America/Bogota');
if (isset($_POST)) {
    require_once 'BD.php';
    $con = new BD();
    $usuario = $_POST['usuario'];
    $pass = $_POST['pass'];
    $resultado = $con->login("SELECT *, concat(nombres,' ',apellidos) nombre_completo, r.descripcion FROM empleados e "
            . "join roles r on e.id_rol = r.id_rol "
            . "WHERE documento = ?  and password = ?", array($usuario, Utils::hash($pass)));

    $response = array("message" => "error");
    if (count($resultado) > 0) {
        $_SESSION['obj_user'] = $resultado;
        $response = array("message" => "success");
    }

    echo json_encode($response);
}
?>