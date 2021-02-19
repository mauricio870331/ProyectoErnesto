<?php
session_start();
date_default_timezone_set('America/Bogota');
$hoy = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $title = "CA | Mi Perfil";
        include 'css.php';
        ?>
    </head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">                
                <!-- Menu -->
                <?php include 'menu.php'; ?>
                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="">
                        <div class="page-title">
                            <div class="title_left">
                                <h3>Mi Perfiíl</h3>
                            </div>
                            <div class="title_right">
                                <div class="col-md-5">
                                    <label  for="dob" class="control-label col-md-3 col-sm-3 col-xs-12">Desde:
                                    </label>                                               
                                    <div class='col-md-9 col-sm-9 col-xs-12'>
                                        <div class="form-group">
                                            <div class='input-group date' id='desde'>
                                                <input id='inicio' type='text' value="<?php echo $hoy; ?>" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label  for="dob" class="control-label col-md-3 col-sm-3 col-xs-12">Hasta:
                                    </label>                                               
                                    <div class='col-md-9 col-sm-9 col-xs-12'>
                                        <div class="form-group">
                                            <div class='input-group date' id='hasta'>
                                                <input id='fin' type='text' value="<?php echo $hoy; ?>" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <i id="filtrarMarcaciones" class="fa fa-search" data-toggle="tooltip" data-title="Buscar Marcaciones" style="font-size: 20px; margin-top: 10%;cursor: pointer"></i>
                                </div>

                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title"> 
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                                            <div class="profile_img">
                                                <div id="crop-avatar">
                                                    <!-- Current avatar -->
                                                    <img class="img-responsive avatar-view" src="<?php echo ($_SESSION["obj_user"][0]["foto"]) ? $config['base_url'] . "/" . $_SESSION["obj_user"][0]["foto"] : $config['base_url'] . "/images/img.jpg"; ?>" alt="Avatar" title="Change the avatar">
                                                </div>
                                            </div>
                                            <h3><?php echo $_SESSION["obj_user"][0]["nombres"] . " " . $_SESSION["obj_user"][0]["apellidos"] ?></h3>

                                            <ul class="list-unstyled user_data">
                                                <li><i class="fa fa-map-marker user-profile-icon"></i> Dirección: <?php echo $_SESSION["obj_user"][0]["direccion"]; ?>
                                                </li>   
                                                <li><i class="fa fa-phone user-profile-icon"></i> Teléfono: <?php echo $_SESSION["obj_user"][0]["telefono"]; ?>
                                                </li>  
                                                <li><i class="fa fa-birthday-cake user-profile-icon"></i> Fecha de Nacimiento: <?php echo $_SESSION["obj_user"][0]["fecha_nacimiento"]; ?>
                                                </li>  
                                                <li><i class="fa fa-briefcase user-profile-icon"></i> Rol: <?php echo $_SESSION["obj_user"][0]["descripcion"]; ?>
                                                </li> 
                                                <li><i class="fa fa-building user-profile-icon"></i> Empresa: <?php echo $_SESSION["obj_user"][0]["nombre_empresa"]; ?>
                                                </li> 


                                            </ul>

                                            <a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i> Editar Perfíl</a>
                                            <br />

                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-12">

                                            <div class="profile_title">
                                                <div class="col-md-3">
                                                    <h2>Mis marcaciones</h2>
                                                </div>   
                                            </div>
                                            <!-- start of user-activity-graph -->
                                            <div>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>                                                           
                                                            <th>Entrada</th>
                                                            <th>Salida Colación</th>
                                                            <th>Entrada Colación</th>
                                                            <th>Salida</th>
                                                            <th>Fecha</th>                                                      
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tblMarcaciones">                                                
                                                        <?php
//                                                $showasesor = true;
//                                                $filter = " es.estado not in ('SUSPENDIDO') ";
                                                        include '../Model/empleados/listMarcaciones.php';
                                                        ?> 
                                                    </tbody>
                                                </table>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /page content -->
                <!-- footer content -->
                <?php include 'footer.php'; ?>
                <!-- /footer content -->
            </div>
        </div>        
        <?php include 'js.php'; ?>  
        <script>
            $('#desde').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#hasta').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        </script>
    </body>
</html>