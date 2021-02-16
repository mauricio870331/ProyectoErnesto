<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $title = "Crear Empresas";
        include './css.php';
        ?>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!-- Menu -->
                <?php include './menu.php'; ?>
                <!-- Heaer -->
                <?php include './header.php'; ?>

                <div id="loader" style="float: right;margin-top: -2%;display: none;z-index: 99999999999;">
                    <img src="<?php echo $config['base_url']; ?>/images/preloader.gif" alt=""/>
                </div>

                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="">                        
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Formulario Crear Empresas <small>por favor diligencie los campos..!</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <br />
                                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nom_empresa">Nombre Empresa <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="nom_empresa"  name="nom_empresa"  class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="documento">Documento <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="documento"  name="documento" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="direccion">Dirección <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="direccion" name="direccion"  class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label for="fotoz" class="control-label col-md-3 col-sm-3 col-xs-12">Isotipo <span class="required">*</span></label>                                       
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input class="form-control col-md-7 col-xs-12" type="file" id="fotoz" value="z"> 
                                                </div>
                                            </div>  

                                            <div class="form-group">
                                                <label for="direccion" class="control-label col-md-3 col-sm-3 col-xs-12">Email <span class="required">*</span></label> 
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input id="email" name="email" class="form-control col-md-7 col-xs-12" type="text">
                                                </div>
                                            </div>   



                                            <div class="ln_solid"></div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                    <input type="text" style="display: none;" id="baseurl" value="<?php echo $config['base_url']; ?>">
                                                    <button id="listarEmpresa" class="btn btn-primary redirect" type="button" data-page="<?php echo $config['base_url'] . "/Views/ListarEmpresas" ?>">Cancelar</button>                                                   
                                                    <button id="crearEmpresa" type="button" class="btn btn-success" data-accion="add">Guardar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /page content -->

                <!-- footer -->
                <?php include './footer.php'; ?>
                <!-- /footer content -->
            </div>
        </div>
        <?php include './js.php'; ?>
        <script>
            $('#dob').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        </script>
    </body>
</html>
