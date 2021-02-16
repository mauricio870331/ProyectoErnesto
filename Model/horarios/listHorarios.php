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

$SQL_SELECT = "Select h.*, e.nombre_empresa from horarios h "
        . "join empresa e on e.id_empresa = h.id_empresa order by h.id_horario desc limit 500 ";
$rs = $con->findAll2($SQL_SELECT);

foreach ($rs as $key => $value) {
    ?>        
    <tr>  
        <td><?php echo $value['id_horario']; ?></td>
        <td><?php echo $value['nombre_empresa']; ?></td>
        <td>
            <?php echo $value['jornada']; ?>
            <i class="fa <?php echo ($value['jornada'] == "Diurna") ? "fa-sun-o " : "fa-moon-o" ?> lg-icon"></i>   
        </td> 
        <td><?php echo $value['entrada']; ?></td>
        <td><?php echo $value['salida_colacion']; ?></td>
        <td><?php echo $value['entrada_colacion']; ?></td> 
        <td><?php echo $value['salida']; ?></td>        
        <td>       
            <i class="fa fa-edit lg-icon redirect btnEvtTable" data-toggle="tooltip" 
               data-placement="top" title data-original-title="Editar"
               data-page="<?php echo $config['base_url'] . "/Views/EditarHorarios/" . $value['id_horario']; ?>">
            </i>   
        </td>
    </tr> 
    <?php
}
?>

