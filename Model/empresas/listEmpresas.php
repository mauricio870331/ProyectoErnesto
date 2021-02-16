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


$SQL_SELECT = "Select * from empresa order by id_empresa desc limit 500 ";
$rs = $con->findAll2($SQL_SELECT);

foreach ($rs as $key => $value) {
    ?>        
    <tr>       
        <td><?php echo $value['id_empresa']; ?></td>
        <td><?php echo $value['documento']; ?></td> 
        <td><?php echo $value['nombre_empresa']; ?></td>
        <td><?php echo $value['direccion']; ?></td>
        <td><img width="60" src="<?php echo $config['base_url'] . "/" . $value['isotipo']; ?>" alt=""/></td>     
        <td><?php echo $value['email']; ?></td>     
        <td>       
            <i class="fa fa-edit lg-icon redirect" data-toggle="tooltip" 
               data-placement="top" title data-original-title="Editar"
               data-page="<?php echo $config['base_url'] . "/Views/EditarEmpresas/" . $value['id_empresa']; ?>">
            </i>   
        </td>
    </tr> 
    <?php
}
?>

