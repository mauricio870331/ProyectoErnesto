<?php
$id_empleado = $_SESSION["obj_user"][0]["id_empleado"];
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
$SQL_SELECT = "Select p.*, concat(e.nombres, ' ', e.apellidos) jefe_area from permisos p "
        . "join empleados e on e.id_empleado = p.id_jefe_area "
        . "where p.id_empleado = " . $id_empleado . " order by p.id_permiso desc limit 500 ";
$rs = $con->findAll2($SQL_SELECT);

foreach ($rs as $key => $value) {
    ?>        
    <tr>  
        <td><?php echo $value['id_permiso']; ?></td>
        <td><?php echo $value['jefe_area']; ?></td>
        <td><?php echo $value['fecha_permiso']; ?></td> 
        <td><?php echo $value['inicio_permiso']; ?></td>
        <td><?php echo $value['termino_permiso']; ?></td>
        <td><?php echo $value['motivo']; ?></td>           
        <td>       
            <i class="fa fa-edit lg-icon redirect btnEvtTable" data-toggle="tooltip" 
               data-placement="top" title data-original-title="Editar"
               data-page="<?php echo $config['base_url'] . "/Views/EditarPermisos/" . $value['id_permiso']; ?>">
            </i>   
        </td>
    </tr> 
    <?php
}
?>

