<?php
session_start();
include '../Model/BD.php';
$dedos = array();
$dedos[] = "Pulgar Derecho";
$dedos[] = "Pulgar Izquierdo";
$dedos[] = "Indice Derecho";
$dedos[] = "Indice Izquierdo";
$dedos[] = "Corazón Derecho";
$dedos[] = "Corazón Izquierdo";
$dedos[] = "Anular Derecho";
$dedos[] = "Anular Izquierdo";
$dedos[] = "Meñique Derecho";
$dedos[] = "Meñique Izquierdo";

$con = new BD();
$query = "select e.*, d.descripcion departamento, h.id_horario, h.jornada from empleados e "
        . "join  departamento d on e.id_departamento = d.id_departamento "
        . "join horarios h on h.id_horario = e.id_horario where id_empleado = " . $_GET["token"];
$rs = $con->findAll2($query, true);
$rsHuellas = $con->findAll("huellas", "where documento = '" . $rs[0]->documento . "'");
$rsemrpesa = $con->findAll("empresa");
$rsroles = $con->findAll("roles");


for ($k = 0; $k < count($rsHuellas); $k++) {
    for ($index = 0; $index < count($dedos); $index++) {
        if ($rsHuellas[$k]->nombre_dedo == $dedos[$index]) {
            $dedos[$index] = $rsHuellas[$k];
        }
    }
}
//echo "<pre>";
//print_r($dedos);die;

$con->desconectar();
//echo "<pre>";
//print_r($rs);
//die;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $title = "Editar Empleados";
        include 'css.php';
        ?>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!-- Menu -->
                <?php include 'menu.php'; ?>
                <div id="loader" style="float: right;margin-top: -2%;display: none;z-index: 99999999999;">
                    <img src="../../images/preloader.gif" alt=""/>
                </div>
                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="">                        
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Formulario Editar Empleados</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <br />
                                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Empresa <span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">

                                                    <select id="empresa" name="empresa" class="form-control col-md-7 col-xs-12">
                                                        <option value="">Seleccione</option>
                                                        <?php
                                                        for ($i = 0; $i < count($rsemrpesa); $i++) {
                                                            ?>
                                                            <option <?php echo ($rsemrpesa[$i]->id_empresa == $rs[0]->id_empresa) ? 'selected' : '' ?> value="<?php echo $rsemrpesa[$i]->id_empresa; ?>"><?php echo $rsemrpesa[$i]->nombre_empresa; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Departamento <span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">                                                   
                                                    <select id="departamento" name="departamento" class="form-control col-md-7 col-xs-12">
                                                        <option value="">Seleccione</option>  
                                                        <option selected value="<?php echo $rs[0]->id_departamento ?>"><?php echo $rs[0]->departamento ?></option>   
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="documento">Documento<span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="documento"  name="documento" 
                                                           value="<?php echo $rs[0]->documento; ?>" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nombres">Nombres <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="nombres" 
                                                           value="<?php echo $rs[0]->nombres; ?>" name="nombres" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="apellidos">Apellidos <span class="required">*</span>
                                                </label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="text" id="apellidos" 
                                                           value="<?php echo $rs[0]->apellidos; ?>" name="apellidos"  class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="telefono" class="control-label col-md-3 col-sm-3 col-xs-12">Telefono <span class="required">*</span></label> 
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input id="telefono" name="telefono" 
                                                           value="<?php echo $rs[0]->telefono; ?>" class="form-control col-md-7 col-xs-12" type="text">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="direccion" class="control-label col-md-3 col-sm-3 col-xs-12">Dirección <span class="required">*</span></label> 
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input id="direccion" name="direccion" 
                                                           value="<?php echo $rs[0]->direccion; ?>" class="form-control col-md-7 col-xs-12" type="text">
                                                </div>
                                            </div>    


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Rol <span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">                                                 
                                                    <select id="rol" name="rol" class="form-control col-md-7 col-xs-12">
                                                        <option value="">Seleccione</option>
                                                        <?php
                                                        for ($i = 0; $i < count($rsroles); $i++) {
                                                            ?>
                                                            <option <?php echo ($rsroles[$i]->id_rol == $rs[0]->id_rol) ? 'selected' : '' ?>  value="<?php echo $rsroles[$i]->id_rol; ?>"><?php echo $rsroles[$i]->descripcion; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Jornada <span class="required">*</span></label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">                                                    
                                                    <select id="jornada" name="jornada" class="form-control col-md-7 col-xs-12">
                                                        <option value="">Seleccione</option>  
                                                        <option selected value="<?php echo $rs[0]->id_horario ?>"><?php echo $rs[0]->jornada ?></option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="fotoactual" class="control-label col-md-3 col-sm-3 col-xs-12">Foto Actual</label>                                       
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <img width="60" src="<?php echo $config['base_url'] . "/" . $rs[0]->foto; ?>" alt=""/>
                                                </div>
                                            </div>                                             

                                            <div class="form-group">
                                                <label for="fotoz" class="control-label col-md-3 col-sm-3 col-xs-12">Foto </label>                                       
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input class="form-control col-md-7 col-xs-12" type="file" id="foto" value="z"> 
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label  for="dob" class="control-label col-md-3 col-sm-3 col-xs-12">Fecha de Nacimiento
                                                </label>                                               
                                                <div class='col-md-6 col-sm-6 col-xs-12'>
                                                    <div class="form-group">
                                                        <div class='input-group date' id='dob'>
                                                            <input id='fecha_nacimiento' type='text' value="<?php echo $rs[0]->fecha_nacimiento; ?>" class="form-control" />
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  

                                            <div class="form-group" style="margin-top: -10px;">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Asociar Huellas </label> 
                                                <div class="col-md-6 col-sm-6 col-xs-12">  
                                                    <?php
                                                    for ($i = 0; $i < count($dedos); $i++) {
                                                        if (is_object($dedos[$i])) {
                                                            ?>
                                                            <img id="huella<?php echo $i ?>" 
                                                                 class="finger-check" 
                                                                 data-toggle="tooltip" 
                                                                 data-placement="top" title 
                                                                 data-original-title="<?php echo $dedos[$i]->nombre_dedo; ?>"
                                                                 src="<?php echo $config['base_url']; ?>/images/fingerprint_24px.png"
                                                                 data-id="<?php echo $dedos[$i]->id; ?>"
                                                                 data-accion="edit"
                                                                 data-huella="huella<?php echo $i ?>"
                                                                 data-dedo="<?php echo $dedos[$i]->nombre_dedo; ?>"
                                                                 alt=""/>
                                                                 <?php
                                                             } else {
                                                                 ?>
                                                            <img id="huella<?php echo $i ?>" 
                                                                 class="finger-check"
                                                                 data-accion="add"
                                                                 data-toggle="tooltip" 
                                                                 data-placement="top" title 
                                                                 data-dedo="<?php echo $dedos[$i]; ?>"
                                                                 data-huella="huella<?php echo $i ?>"
                                                                 data-original-title="<?php echo $dedos[$i]; ?>"
                                                                 data-id="" src="<?php echo $config['base_url']; ?>/images/finger_disabled.png" alt=""/>
                                                                 <?php
                                                             }
                                                         }
                                                         ?>
                                                </div>
                                            </div>
                                            <div class="ln_solid"></div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                    <input type="text" style="display: none;" id="baseurl" value="<?php echo $config['base_url']; ?>">
                                                    <input id="idUser" value="<?php echo $rs[0]->id_empleado; ?>" type="text" style="display: none;" />
                                                    <button id="listaEmpleados" data-page="<?php echo $config['base_url'] . "/Views/ListarEmpleados" ?>"
                                                            class="btn btn-primary redirect" type="button">Cancelar</button>                                                    
                                                    <button id="crearEmpleado" type="button" class="btn btn-success" data-accion="edit">Guardar</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Huellas -->
                    <div class="modal fade bs-example-modal-sm" id="modalHuellas" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel2">Asociar Huellas</h4>
                                </div>
                                <div class="modal-body" >                                                                      
                                    <div class="img imgFinger"></div>
                                    <div class="txtFinger ct3"></div>
                                </div>
                                <div class="modal-footer">
                                    <input id="fingerOptions" type="text" value="" style="display: none">
                                    <button  id="triggerButton" data-dismiss="modal" style="display: none"/>
                                    <button  data-opc="2" type="button" class="btn btn-danger closeModalFinger">Cerrar</button>
                                    <button id="updateFinger" data-opc="1" type="button" class="btn btn-success closeModalFinger" >Guardar</button>                                   
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
            $('#dob').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            cargar_push();
        </script>
    </body>
</html>
