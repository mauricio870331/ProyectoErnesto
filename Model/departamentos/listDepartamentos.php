<?php
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

$SQL_SELECT = "Select d.*, e.nombre_empresa empresa from departamento d "
        . "join empresa e on d.id_empresa = e.id_empresa order by d.id_departamento desc limit 500 ";
$rs = $con->findAll2($SQL_SELECT);

foreach ($rs as $key => $value) {
    ?>        
    <tr>       
        <td><?php echo $value['id_departamento']; ?></td>
        <td><?php echo $value['empresa']; ?></td> 
        <td><?php echo $value['descripcion']; ?></td>   
        <td>       
            <i class="fa fa-edit lg-icon redirect" data-toggle="tooltip" 
               data-placement="top" title data-original-title="Editar"
               data-page="<?php echo $config['base_url'] . "/Views/EditarDepartamentos/" . $value['id_departamento']; ?>">
            </i>   
        </td>
    </tr> 
    <?php
}
?>

