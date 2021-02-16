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

$SQL_SELECT = "Select s.*, COALESCE(m.id_empleado,0) empleado from sub_menu_app s "
        . "left join menus_empleados m on s.id_submenu = m.id_submenu and m.id_empleado = " . $id_empleado . " "
        . "order by s.id_submenu ";
$rs = $con->findAll2($SQL_SELECT);

foreach ($rs as $key => $value) {
    ?>        
    <tr>       
        <td>
            <label>
                <input type="checkbox" 
                       class="js-switch darpermiso" <?php echo ($value['empleado'] > 0) ? "checked" : ""; ?>
                       data-submenu="<?php echo $value['id_submenu']; ?>"
                       data-empelado="<?php echo $id_empleado; ?>"
                       data-url="<?php echo $config['base_url']; ?>"/>
                       <?php echo ($value['empleado'] > 0) ? " Activo" : " Inactivo"; ?>
            </label>
        </td>       
        <td><?php echo $value['descripcion']; ?></td>            
    </tr> 
    <?php
}
?>

