<?php
session_start();
include '../Model/BD.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>        
        <?php
        $title = "Lista Departamentos";
        include './css.php';
        ?>
    </head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!-- Menu -->
                <?php include 'menu.php'; ?>
                <!-- Heaer -->
                <?php include 'header.php'; ?>
                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="">
                        <div class="page-title">
                            <div class="title_left">
                                <h3>Buscar Departamentos <i class="fa fa-hand-o-right"></i></h3>
                            </div>

                            <div class="title_right">
                                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Buscar...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
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
                                                    <th>#</th>
                                                    <th>Empresa</th>
                                                    <th>Departamento</th>                                                    
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>                                                
                                                <?php
//                                                $showasesor = true;
//                                                $filter = " es.estado not in ('SUSPENDIDO') ";
                                                include '../Model/departamentos/listDepartamentos.php';
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
                <?php include './footer.php'; ?>
            </div>
        </div>
        <?php include './js.php'; ?>
    </body>
</html>
