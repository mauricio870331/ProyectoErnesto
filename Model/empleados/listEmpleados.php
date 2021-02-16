<?php
//echo "<pre>";
//print_r($_SESSION['obj_user']);
//die;
$con = new BD();
//$where = "where asesor = " . $_SESSION['obj_user'][0]['id'] . " " . $filter;
//if ($_SESSION['obj_user'][0]['descripcion'] == 'ADMINISTRADOR') {
//    $where = " where " . $filter;
//}
//
//if (isset($_POST['field']) && $_POST['field'] != "") {
//
//    $pos = strpos($where, "asesor");
//    if ($pos === false) {
//        $where .= " AND (u.nombres like '%" . $_POST['field'] . "%' or u.apellidos like '%" . $_POST['field'] . "%' or u.documento like '%" . $_POST['field'] . "%')";
//    } else {
//        $where .= " AND (u.nombres like '%" . $_POST['field'] . "%' or u.apellidos like '%" . $_POST['field'] . "%' or u.documento like '%" . $_POST['field'] . "%') " . $filter;
//    }
//}

$SQL_SELECT = "Select * from empleados order by id_empleado desc limit 500 ";
$rs = $con->findAll2($SQL_SELECT);

foreach ($rs as $key => $value) {
    ?>        
    <tr>       
        <td><?php echo $value['id_empleado']; ?></td>
        <td><?php echo $value['documento']; ?></td> 
        <td><?php echo $value['nombres'] . " " . $value['apellidos']; ?></td>
        <td><?php echo $value['telefono']; ?></td>
        <td><?php echo $value['direccion']; ?></td>        
        <td>  
            <?php if ($_SESSION["obj_user"][0]["id_rol"] != 4) { ?>
                <i class="fa fa-edit lg-icon redirect btnEvtTable" data-toggle="tooltip" 
                   data-placement="top" title data-original-title="Editar"
                   data-page="<?php echo $config['base_url'] . "/Views/EditarEmpleados/" . $value['id_empleado']; ?>" >
                </i>   
                <i class="fa fa-key lg-icon redirect btnEvtTable" data-toggle="tooltip" 
                   data-placement="top" title data-original-title="Permisos"
                   data-page="<?php echo $config['base_url'] . "/Views/PermisosEmpleados/" . $value['id_empleado']; ?>" >
                </i>
            <?php } ?>
        </td>
    </tr> 
    <?php
}
?>

