<?php
session_start();
require './Utils.php';
date_default_timezone_set('America/Bogota');
if (isset($_POST)) {
    require_once 'BD.php';
    $con = new BD();
    $usuario = $_POST['usuario'];
    $pass = $_POST['pass'];
    $query = "SELECT *, concat(nombres,' ',apellidos) nombre_completo, r.descripcion, em.nombre_empresa FROM empleados e "
            . "join roles r on e.id_rol = r.id_rol "
            . "join empresa em on em.id_empresa = e.id_empresa "
            . "WHERE e.documento = ?  and e.password = ?";

//    echo $query;die;
    $resultado = $con->login($query, array($usuario, Utils::hash($pass)));

    $response = array("message" => "error");
    if (count($resultado) > 0) {
        $_SESSION['obj_user'] = $resultado;
        $response = array("message" => "success");
    }
    echo json_encode($response);
}
?>