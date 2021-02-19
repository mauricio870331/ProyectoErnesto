<?php
//echo "<pre>";
//print_r($_SESSION['obj_user']);
//die;

if (isset($_POST['inicio'])) {
    session_start();
    include '../BD.php';
    $SQL_SELECT = "select * from marcaciones where documento = '" . $_SESSION['obj_user'][0]['documento'] . "' and fecha between '" . $_POST['inicio'] . "' and '" . $_POST['fin'] . "'";
} else {
    $SQL_SELECT = "select * from marcaciones where documento = '" . $_SESSION['obj_user'][0]['documento'] . "' and fecha = '" . $hoy . "'";
}
date_default_timezone_set('America/Bogota');
$con = new BD();
$hoy = date("Y-m-d");
$rs = $con->findAll2($SQL_SELECT);
if (isset($_POST['inicio'])) {
    echo json_encode($rs, true);
} else {
    foreach ($rs as $key => $value) {
        ?>        
        <tr>       
            <td><?php echo $value['entrada']; ?></td>       
            <td><?php echo $value['salida_colacion']; ?></td>      
            <td><?php echo $value['entrada_colacion']; ?></td> 
            <td><?php echo $value['salida']; ?></td>  
            <td><?php echo $value['fecha']; ?></td>  
        </tr> 
        <?php
    }
}
?>

