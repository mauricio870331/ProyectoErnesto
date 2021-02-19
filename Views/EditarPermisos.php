<?php
session_start();
include '../Model/BD.php';
$con = new BD();
$rs = $con->findAll("permisos", "where id_permiso = " . $_GET["token"], true);
$rs2 = $con->findAll("empleados", "where id_rol = 1");
$con->desconectar();
$title = "Editar Permisos";
$titleForm = "Formulario Editar Permisos";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'css.php'; ?>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!-- Menu -->
                <?php include 'menu.php'; ?>
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
                                        <h2><?php echo $titleForm; ?> <small>por favor diligencie los campos..!</small></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <br />
                                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                                            <div class="form-group">
                                                <label  for="dob" class="control-label col-md-3 col-sm-3 col-xs-12">Fecha De Permiso <span class="required">*</span>                                           </label>                                               
                                                <div class='col-md-6 col-sm-6 col-xs-12' style="margin-bottom: -8px;">
                                                    <div class='input-group date' id='fechaPer'>
                                                        <input id='fecha_permiso' type='text' value="<?php echo $rs->fecha_permiso; ?>" class="form-control" />
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>  

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Jefe de Area <span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">

                                                    <select id="id_jefe_area" name="id_jefe_area" class="form-control col-md-7 col-xs-12">
                                                        <option value="">Seleccione</option>
                                                        <?php
                                                        for ($i = 0; $i < count($rs2); $i++) {
                                                            ?>
                                                            <option <?php echo ($rs2[$i]->id_empleado == $rs->id_jefe_area) ? 'selected' : '' ?> value="<?php echo $rs2[$i]->id_empleado; ?>"><?php echo $rs2[$i]->nombres . " " . $rs2[$i]->apellidos; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jornada">Hora Inicio <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="inicio_permiso" value="<?php echo $rs->inicio_permiso; ?>" class="form-control" data-inputmask="'mask': '99:99:99'">                                                    
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jornada">Hora Fin <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="termino_permiso" value="<?php echo $rs->termino_permiso; ?>" class="form-control" data-inputmask="'mask': '99:99:99'">                                                    
                                                </div>
                                            </div>                                      

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="salida">Motivo <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">                                    
                                                    <textarea id="motivo" class="form-control col-md-7 col-xs-12" rows="3" placeholder="Describa aqui el motivo de su permiso"><?php echo $rs->motivo; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="ln_solid"></div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                    <input type="text" style="display: none;" id="baseurl" value="<?php echo $config['base_url']; ?>">
                                                    <input id="token" value="<?php echo $rs->id_permiso; ?>" type="text" style="display: none;" />
                                                    <button id="cancelPermiso" class="btn btn-primary redirect" type="button" data-page="<?php echo $config['base_url'] . "/Views/ListarPermisos" ?>">Cancelar</button>                                                   
                                                    <button id="addUpdatePermiso" type="button" class="btn btn-success" data-accion="edit">Guardar</button>
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
                <?php include 'footer.php'; ?>
                <!-- /footer content -->
            </div>
        </div>
        <?php include 'js.php'; ?>
        <script>
            $('#fechaPer').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        </script>
    </body>
</html>
