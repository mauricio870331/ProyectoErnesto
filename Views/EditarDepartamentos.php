<?php
session_start();
include '../Model/BD.php';
$con = new BD();
$rs = $con->findAll("departamento", "where id_departamento = " . $_GET["token"], true);
$rs2 = $con->findAll("empresa");
$con->desconectar();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $title = "Editar Departamentos";
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
                                        <h2>Formulario Editar Departamentos <small>por favor diligencie los campos..!</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <br />
                                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Empresa <span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <select disabled="disabled" id="empresa" name="empresa" class="form-control col-md-7 col-xs-12">
                                                        <option value="">Seleccione</option>
                                                        <?php
                                                        for ($i = 0; $i < count($rs2); $i++) {
                                                            ?>
                                                            <option  <?php echo ($rs2[$i]->id_empresa == $rs->id_empresa) ? 'selected' : '' ?> value="<?php echo $rs2[$i]->id_empresa; ?>"><?php echo $rs2[$i]->nombre_empresa; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion">Departamento <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input id="descripcion" name="descripcion" type="text"
                                                           value="<?php echo $rs->descripcion; ?>" name="descripcion"  class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>


                                            <div class="ln_solid"></div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                    <input type="text" style="display: none;" id="baseurl" value="<?php echo $config['base_url']; ?>">
                                                    <input id="token" value="<?php echo $rs->id_departamento; ?>" type="text" style="display: none;" />
                                                    <button id="redirect" class="btn btn-primary redirect" type="button" data-page="<?php echo $config['base_url'] . "/Views/ListarDepartamentos" ?>">Cancelar</button>                                                   
                                                    <button id="crearDepto" type="button" class="btn btn-success" 
                                                            data-accion="edit" >Guardar</button>
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
