<?php
if (!include_once('../Model/BD.php')) {
    include_once '../Model/BD.php';
}
$con = new BD();
$query = "select m.nombre as menu, m.icono, sm.sub_menu, sm.descripcion from menu_app m "
        . "inner join sub_menu_app sm on m.id_menu = sm.id_menu "
        . "inner join menus_empleados me on me.id_submenu = sm.id_submenu "
        . "where me.id_empleado = " . $_SESSION["obj_user"][0]["id_empleado"] . " order by m.nombre";
$rsultset = $con->findAll2($query);
$arrayMenu = array();
for ($index = 0; $index < count($rsultset); $index++) {
    if (!array_key_exists($rsultset[$index]["menu"], $arrayMenu)) {
        $arrayMenu[$rsultset[$index]["menu"]] = array($rsultset[$index]["icono"]);
    }
}
foreach ($arrayMenu as $key => $value) {
    $arraytemp = array();
    for ($i = 0; $i < count($rsultset); $i++) {
        if ($key == $rsultset[$i]["menu"]) {
            $arraytemp[] = array($rsultset[$i]["sub_menu"], $rsultset[$i]["descripcion"]);
        }
    }
    $arrayMenu[$key][1] = $arraytemp;
}

//echo "<pre>";
//print_r($_SESSION["obj_user"][0]);
//die;
?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="index.html" class="site_title"><i class="fa fa-hand-o-up"></i> <span>Ctrl - Asistencia</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img width="125" height="57" src="<?php echo ($_SESSION["obj_user"][0]["foto"]) ? $config['base_url'] . "/" . $_SESSION["obj_user"][0]["foto"] : $config['base_url'] . "/images/img.jpg"; ?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Bienvenido,</span>
                <h2><?php echo $_SESSION["obj_user"][0]["nombres"] . " " . $_SESSION["obj_user"][0]["apellidos"] ?></h2>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <?php if ($_SESSION["obj_user"][0]["id_rol"] != 4) { ?>
                        <li><a><i class="fa fa-gear"></i> Configuración <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li id="setToken"><a href="javascript:void(0)" onclick="saveSrnPc()">Generar Token</a></li>    
                                <li data-toggle="modal" data-target=".modaltoken" ><a href="javascript:void(0)" onclick="isToken()">Ver Token</a></li>  
                            </ul>
                        </li>
                    <?php } ?>

                    <?php foreach ($arrayMenu as $key => $value) { ?>
                        <li><a><i class="fa <?php echo $value[0]; ?>"></i> <?php echo $key; ?> <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php for ($j = 0; $j < count($value[1]); $j++) { ?>
                                    <li><a href="<?php echo $config['base_url'] . "/" . $value[1][$j][0]; ?>"><?php echo $value[1][$j][1]; ?></a></li> 
                                <?php } ?>                               
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </div>     
        </div>
        <!-- /sidebar menu -->     
    </div>
</div>
<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img  src="<?php echo ($_SESSION["obj_user"][0]["foto"]) ? $config['base_url'] . "/" . $_SESSION["obj_user"][0]["foto"] : $config['base_url'] . "/images/img.jpg"; ?>" alt=""><?php echo $_SESSION["obj_user"][0]["nombres"] . " " . $_SESSION["obj_user"][0]["apellidos"] ?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="<?php echo $config['base_url'] ?>/Views/Perfil"> Perfíl</a></li> 
                        <li><a href="<?php echo $config['base_url'] ?>/Model/Logout.php"><i class="fa fa-sign-out pull-right"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>               
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->


<div class="modal fade bs-example-modal-sm modaltoken" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Token</h4>
            </div>
            <div class="modal-body" id="valToken" style="text-align: center;">
            </div>
            <div class="modal-footer">             
                <button type="button"  data-dismiss="modal" class="btn btn-primary">Cerrar</button>
            </div>

        </div>
    </div>
</div>

