<?php
session_start();
include '../Model/BD.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>        
        <?php
        $title = "Lista Permisos";
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
                                <h3>Permisos de Aplicacion <i class="fa fa-lock"></i></h3>
                            </div>                            
                        </div>

                        <div class="clearfix"></div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><?php echo $title; ?></h2>                                        
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <table id="datatable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Estado</th>
                                                    <th>Menu</th>                                                    

                                                </tr>
                                            </thead>
                                            <tbody>                                                
                                                <?php
//                                                $showasesor = true;
                                                $id_empleado = $_GET["token"];
                                                include '../Model/empleados/listPermisosApp.php';
                                                ?> 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /page content -->
                <!-- footer -->
                <?php include 'footer.php'; ?>
            </div>
        </div>
        <?php include 'js.php'; ?>
        <script>
            $('#datatable').dataTable({"ordering": false});
        </script>
    </body>
</html>
