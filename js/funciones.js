
var timestamp = null;
function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length)).replace("Views/", "");
}

isToken();
$('#modalHuellas').modal({backdrop: 'static', keyboard: false})
$('#modalHuellas').modal('toggle');
function srnPc() {
    var d = new Date();
    var dateint = d.getTime();
    var letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var total = letters.length;
    var keyTemp = "";
    for (var i = 0; i < 6; i++) {
        keyTemp += letters[parseInt((Math.random() * (total - 1) + 1))];
    }
    keyTemp += dateint;
    return keyTemp;
}

function saveSrnPc() {
    localStorage.setItem("srnPc", srnPc());
    showNotify("Token generado " + localStorage.getItem("srnPc"), "Aviso", "success", 3000);
//    saveToken();
//    localStorage.removeItem("srnPc");
}

function isToken() {
    if (localStorage.getItem("srnPc")) {
        $("#setToken").css("display", "none");
        $("#valToken").html(localStorage.getItem("srnPc"));
        $(".imgFinger").attr("id", localStorage.getItem("srnPc"));
        $(".txtFinger").attr("id", localStorage.getItem("srnPc") + "_texto");
    } else {
        showNotify("No existe un token para equipo, por favor generarlo y configurarlo en el plugin..!", "Aviso", "error", 8000);
    }
}

function activarSensor(showModal) {
    var campos = ['documento', 'nombres', 'apellidos', 'telefono', 'direccion'];
    if (validarCampos(campos) > 0) {
        showNotify("Primero debes llenar los campos", "Error", "info", 3000);
    } else {

        $("#selectFinger").val("");
        if (showModal) {
            $("#asociarhuella").trigger("click");
        }
        $.ajax({
            async: true,
            type: "POST",
            url: base_url + "/Model/FingerUtils/ActivarSensorAdd.php",
            data: "&token=" + localStorage.getItem("srnPc"),
            dataType: "json",
            success: function (data) {
                var json = JSON.parse(data);
                console.log(json);
                if (json["filas"] === 1) {
//                $("#activeSensorLocal").attr("disabled", true);   
                    $("#" + localStorage.getItem("srnPc")).css('background-image', 'url(' + $("#baseurl").val() + '/images/finger.png)');
                }
            }
        });
    }
}

function cargar_push() {
    $.ajax({
        async: true,
        type: "POST",
        url: base_url + "/Model/FingerUtils/httpush.php",
        data: "&timestamp=" + timestamp + "&token=" + localStorage.getItem("srnPc"),
        dataType: "json",
        success: function (data) {
            var json = JSON.parse(JSON.stringify(data));
            timestamp = json["timestamp"];
            imageHuella = json["imgHuella"];
            tipo = json["tipo"];
            id = json["id"];
//            $("#" + id + "_status").text(json["statusPlantilla"]);
            $("#" + id + "_texto").text(json["texto"]);
            if (imageHuella !== null) {
//                $("#" + id).attr("src", "data:image/png;base64," + imageHuella);
                $("#" + id).css('background-image', 'url(data:image/png;base64,' + imageHuella + ')');
            }
            setTimeout("cargar_push()", 1000);
        }
    });
}

//////------------------------------------------------------------------------------
//Crear Empleados
$("#crearEmpleado").click(function () {
    var campos = ['empresa', 'departamento', 'rol', 'jornada', 'documento', 'nombres', 'apellidos', 'telefono', 'direccion'];
    if (validarCampos(campos) > 0) {
        showNotify("Los campos marcados con (*) son requeridos..!", "Error", "error", 3000);
    } else {
        var data = new FormData();
        var inputFile = document.getElementById("foto");
        var file = inputFile.files[0];
        for (var item in campos) {
            data.append(campos[item], $("#" + campos[item]).val());
        }
        if (file !== undefined) {
            data.append("foto", file);
        }
        data.append("fecha_nacimiento", $("#fecha_nacimiento").val());
        data.append("token", localStorage.getItem("srnPc"));
        data.append("accion", $(this).data("accion"));
        if ($(this).data("accion") === "edit") {
            data.append("idUser", $("#idUser").val());
        }
//        data.append("log_trans", n);
        $.ajax({
            async: true,
            type: "POST",
            url: base_url + "/Model/empleados/addEmpleado.php",
            data: data,
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response)
            {
                if (response.message_code === "success") {
                    if (response.accion === "add") {
                        setTimeout(redireccionarPagina(base_url + '/Views/CrearEmpleados/empleadoCreado'), 1000);
                    } else {
                        setTimeout(redireccionarPagina(base_url + '/Views/ListarEmpleados/empleadoactualizado'), 1000);
                    }
                } else if (response.message_code === "duplicado") {
                    showNotify("Ya existe un empleado con ese numero de documento..!", "Error", "error", 3000);
                } else {
                    showNotify("Ocurrio un error al crear el Lead, por favor vea el log de errores..!", "Error", "error", 3000);
                }
            }
        });
    }
});
//addFinger
$("#nextFinger").click(function () {
    var campos = ['selectFinger'];
    if (validarCampos(campos) > 0) {
        showNotify("Seleccione el dedo", "Error", "error", 3000);
        return;
    }
    var data = new FormData();
    data.append("dedo", $("#selectFinger").val());
    data.append("documento", $("#documento").val());
    data.append("token", localStorage.getItem("srnPc"));
// remover el dedo que ya ha sido guardado para el usuario
//y validar si la huella para ese usuario ya existe
    $.ajax({
        async: true,
        type: "POST",
        url: base_url + "/Model/FingerUtils/addNextFinger.php",
        data: data,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        success: function (response)
        {
            if (response.message_code === "success") {
                $("#selectFinger").val("");
                activarSensor(false);
            } else if (response.message_code === "duplicado") {
                showNotify("ya ingresaste el dedo: " + $("#selectFinger").val() + " selecciona otro", "Aviso", "warning", 3000);
            } else if (response.message_code === "error") {
                showNotify("Ya existe un empleado con ese numero de documento..!", "Error", "error", 3000);
                activarSensor(false);
            } else {
                showNotify("Ocurrio un error al crear el Lead, por favor vea el log de errores..!", "Error", "error", 3000);
            }
        }
    });
});
$(".closeModalFinger").click(function () {
////    $('#modalHuellas').modal('toggle');
    $('#triggerButton').trigger("click");
    var res = $("#fingerOptions").val().split(",");
    var data = new FormData();
    data.append("dedo", res[2]);
    data.append("documento", $("#documento").val());
    data.append("token", localStorage.getItem("srnPc"));
    data.append("option", $(this).data("opc"));
    data.append("accion", res[1]);
    data.append("idHuella", res[3]);
    if (res[0] !== "") {
        data.append("idHuellaUpd", res[0]);
    }
    $.ajax({
        async: true,
        type: "POST",
        url: base_url + "/Model/FingerUtils/updateFingerTemp.php",
        data: data,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        success: function (response)
        {
            if (response.message_code === "success") {
                if (response.option === "1") {
                    $("#" + response.idHuella).attr("src", $("#baseurl").val() + "/images/fingerprint_24px.png");
                    $("#" + response.idHuella).addClass("nofire");
                }
            } else {
                showNotify("Ocurrio un error al crear el Lead, por favor vea el log de errores..!", "Error", "error", 3000);
            }
        }
    });
});
$(".redirect").click(function () {
    redireccionarPagina($(this).data("page"));
});
$(".finger-check").click(function () {
    if (!$(this).hasClass("nofire")) {
        var campos = ['empresa', 'departamento', 'rol', 'jornada', 'documento', 'nombres', 'apellidos', 'telefono', 'direccion'];
        if (validarCampos(campos) > 0) {
            showNotify("Primero debes llenar los campos", "Error", "info", 3000);
            return;
        }
        activarSensor(true);
        var data = $(this).data("id") + "," + $(this).data("accion") + "," + $(this).data("dedo") + "," + $(this).data("huella");
        console.log(data)
        jQuery("#fingerOptions").val(data);
//        $("#selectFinger").val($(this).data("dedo"));
        $('#modalHuellas').modal('toggle');
    }
});
//cargarDpto desde empresa
$("#empresa").on("change", function () {
    var id_empresa = $(this).val();
    if (id_empresa !== "") {
        var data = new FormData();
        data.append("id_empresa", id_empresa);
        $.ajax({
            async: true,
            type: "POST",
            url: base_url + "/Model/departamentos/departamentoxempresa.php",
            data: data,
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            success: function (response)
            {
                var opcionesDpto = "<option value=''>Seleccione</option>";
                var opcioneshorario = "<option value=''>Seleccione</option>";
                for (var item in response.dpto) {
                    opcionesDpto += '<option value="' + response.dpto[item].id_departamento + '">' + response.dpto[item].descripcion + '</option>';
                }
                for (var item in response.horario) {
                    opcioneshorario += '<option value="' + response.horario[item].id_horario + '">' + response.horario[item].jornada + '</option>';
                }
                $("#departamento").html(opcionesDpto);
                $("#jornada").html(opcioneshorario);
            }
        });
    } else {
        $("#departamento").html("<option value=''>Seleccione</option>");
        $("#jornada").html("<option value=''>Seleccione</option>");
    }
});
$(".darpermiso").on("click", function () {
    var id_submenu = $(this).data("submenu");
    var id_empleado = $(this).data("empelado");
    var data = new FormData();
    data.append("id_submenu", id_submenu);
    data.append("id_empleado", id_empleado);
    data.append("baseurl", $(this).data("url"));
    $.ajax({
        async: true,
        type: "POST",
        url: base_url + "/Model/empleados/grantepermision.php",
        data: data,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        success: function (response)
        {
            if (response.message_code === "success") {
                setTimeout(redireccionarPagina(response.baseurl + '/Views/PermisosEmpleados/' + response.id_empleado), 1000);
            } else {
                showNotify("Ocurrio un error agregando el permiso..!", "Error", "error", 3000);
            }
        }
    });
});
//Crear Empleados
$("#crearEmpresa").click(function () {
    var campos = ['nom_empresa', 'documento', 'direccion', 'email'];
    if (validarCampos(campos) > 0) {
        showNotify("Los campos marcados con (*) son requeridos..!", "Error", "error", 3000);
    } else {
        var data = new FormData();
        var inputFile = document.getElementById("fotoz");
        var file = inputFile.files[0];
        for (var item in campos) {
            data.append(campos[item], $("#" + campos[item]).val());
        }
        if (file !== undefined) {
            data.append("isotipo", file);
        }
        data.append("accion", $(this).data("accion"));
        if ($(this).data("accion") === "edit") {
            data.append("idEmpresa", $("#token").val());
        }
        $.ajax({
            async: true,
            type: "POST",
            url: base_url + "/Model/empresas/addEmpresa.php",
            data: data,
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response)
            {
                if (response.message_code === "success") {
                    if (response.accion === "add") {
                        setTimeout(redireccionarPagina(base_url + '/Views/CrearEmpresas/empresaCreada'), 1000);
                    } else {
                        setTimeout(redireccionarPagina(base_url + '/Views/ListarEmpresas/empresaEditada'), 1000);
                    }
                } else if (response.message_code === "duplicado") {
                    showNotify("Ya existe una empresa con ese numero de documento..!", "Error", "error", 3000);
                } else {
                    showNotify("Ocurrio un error al crear el Lead, por favor vea el log de errores..!", "Error", "error", 3000);
//                    $("#error_ambulance").css("display", "block");
//                    $("#title_error").text("Codigo de Error: " + response.code_mysql);
//                    $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
//                    setInterval(logAnimation, 1000);
//                    $('html, body').stop().animate({
//                        scrollTop: jQuery("#upsection").offset().top
//                    }, 700);
                }
            }
        });
    }
});
//Crear Dpartamentos
$("#crearDepto").click(function () {
    var campos = ['descripcion', 'empresa'];
    if (validarCampos(campos) > 0) {
        showNotify("Los campos marcados con (*) son requeridos..!", "Error", "error", 3000);
    } else {
        var data = new FormData();
        for (var item in campos) {
            data.append(campos[item], $("#" + campos[item]).val());
        }
        data.append("accion", $(this).data("accion"));
        if ($(this).data("accion") === "edit") {
            data.append("idDpto", $("#token").val());
        }
//        data.append("log_trans", n);
        $.ajax({
            async: true,
            type: "POST",
            url: base_url + "/Model/departamentos/addDepartamento.php",
            data: data,
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response)
            {
                if (response.message_code === "success") {
                    if (response.accion === "add") {
                        setTimeout(redireccionarPagina(base_url + '/Views/CrearDepartamentos/departamentoCreado'), 1000);
                    } else {
                        setTimeout(redireccionarPagina(base_url + '/Views/ListarDepartamentos/departamentoActualizado'), 1000);
                    }
                } else if (response.message_code === "duplicado") {
                    showNotify("Ya existe un departamento con esa misma informacion..!", "Error", "error", 3000);
                } else {
                    showNotify("Ocurrio un error al crear el Lead, por favor vea el log de errores..!", "Error", "error", 3000);
//                    $("#error_ambulance").css("display", "block");
//                    $("#title_error").text("Codigo de Error: " + response.code_mysql);
//                    $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
//                    setInterval(logAnimation, 1000);
//                    $('html, body').stop().animate({
//                        scrollTop: jQuery("#upsection").offset().top
//                    }, 700);
                }
            }
        });
    }
});
//Crear Horarios
$("#crearHorario").click(function () {
    var campos = ['empresa', 'horario', 'entrada', 'salida', 'entrada_ini', 'entrada_fin', 'salida_ini', 'salida_fin', 'atraso', 'porcentaje_dia'];
    if (validarCampos(campos) > 0) {
        showNotify("Los campos marcados con (*) son requeridos..!", "Error", "error", 3000);
    } else {
        var data = new FormData();
        for (var item in campos) {
            data.append(campos[item], $("#" + campos[item]).val());
        }
        data.append("accion", $(this).data("accion"));
        if ($(this).data("accion") === "edit") {
            data.append("idJornada", $("#token").val());
        }
        $.ajax({
            async: true,
            type: "POST",
            url: base_url + "/Model/horarios/addHorario.php",
            data: data,
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response)
            {
                if (response.message_code === "success") {
                    if (response.accion === "add") {
                        setTimeout(redireccionarPagina(base_url + '/Views/CrearHorarios/horarioCreado'), 1000);
                    } else {
                        setTimeout(redireccionarPagina(base_url + '/Views/ListarHorarios/horarioActualizado'), 1000);
                    }
                } else if (response.message_code === "duplicado") {
                    showNotify("Ya existe un horario para esa misma jornada..!", "Error", "error", 3000);
                } else {
                    showNotify("Ocurrio un error al crear el Lead, por favor vea el log de errores..!", "Error", "error", 3000);
                }
            }
        });
    }
});
//Crear Permisos
$("#addUpdatePermiso").click(function () {
    var campos = ['id_jefe_area', 'fecha_permiso', 'inicio_permiso', 'termino_permiso', 'motivo'];
    if (validarCampos(campos) > 0) {
        showNotify("Los campos marcados con (*) son requeridos..!", "Error", "error", 3000);
    } else {
        var data = new FormData();
        for (var item in campos) {
            data.append(campos[item], $("#" + campos[item]).val());
        }
        data.append("accion", $(this).data("accion"));
        if ($(this).data("accion") === "edit") {
            data.append("id_permiso", $("#token").val());
        }
        $.ajax({
            async: true,
            type: "POST",
            url: base_url + "/Model/permisos/addPermiso.php",
            data: data,
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response)
            {
                if (response.message_code === "success") {
                    if (response.accion === "add") {
                        setTimeout(redireccionarPagina(base_url + '/Views/CrearPermisos/permisoCreado'), 1000);
                    } else {
                        setTimeout(redireccionarPagina(base_url + '/Views/ListarPermisos/permisoActualizado'), 1000);
                    }
                } else if (response.message_code === "duplicado") {
                    showNotify("Ya existe un horario para esa misma jornada..!", "Error", "error", 3000);
                } else {
                    showNotify("Ocurrio un error al crear el Lead, por favor vea el log de errores..!", "Error", "error", 3000);
                }
            }
        });
    }
});
$("#filtrarMarcaciones").on("click", function () {

    var data = new FormData();
    data.append("inicio", $("#inicio").val());
    data.append("fin", $("#fin").val());
    $.ajax({
        async: true,
        type: "POST",
        url: base_url + "/Model/empleados/listMarcaciones.php",
        data: data,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        success: function (response)
        {
            var tr = "";
            if (response.length > 0) {
                for (var i = 0; i < response.length; i++) {
                    tr += "<tr>";
                    tr += "<td>" + response[i].entrada + "</td>";
                    tr += "<td>" + response[i].salida_colacion + "</td>";
                    tr += "<td>" + response[i].entrada_colacion + "</td>";
                    tr += "<td>" + response[i].salida + "</td>";
                    tr += "<td>" + response[i].fecha + "</td>";
                    tr += "</tr>" + response[i].fecha + "</td>";
                }
            } else {
                tr = "<tr><td colspan='6' style='text-align: center'>No hay resultados</td></tr>";
            }
            $("#tblMarcaciones").html(tr);
        }
    });

});

function showNotify(text, title, type, delay) {
    new PNotify({
        title: title,
        text: text,
        type: type,
        delay: delay,
        styling: 'bootstrap3'
    });
}

function validarCampos(campos) {
    var countErrors = 0;
    for (var item in campos) {
        if ($("#" + campos[item]).val() === "") {
            countErrors++;
            $("#" + campos[item]).css("border", "1px solid #dc3545");
        } else {
            $("#" + campos[item]).css("border", "1px solid #d2d6de");
        }
    }
    return countErrors;
}



function redireccionarPagina(pagina) {
    window.location = pagina;
}


//------------------------------------------------------------------------------
//Modal delteLead
$(".deleteLead").click(function () {
    if ($(this).data("estado") === "CLIENTE") {
        showAlert("No puedes suspender un cliente", "error");
        return;
    }
    $("#lead" + $(this).data("id")).trigger("click");
    $("#deleteLead").attr("data-id", $(this).data("id"));
    $("#deleteLead").attr("data-opc", $(this).data("option"));
});
$("#deleteLead").click(function () {
    var data = new FormData();
    var d = new Date();
    var n = d.getTime();
    data.append("log_trans", n);
    data.append("id", $(this).data("id"));
    data.append("opcion", $(this).data("opc"));
    $.ajax({
        type: 'POST',
        url: "../Model/Leads/DeleteLead.php",
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#loader').show();
        },
        complete: function () {
            $('#loader').hide();
        },
        success: function (response) {
            if (response.message_code === "success") {
                setTimeout(redireccionarPagina('ListarLeads.php'), 5000);
            } else {
                showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                $("#error_ambulance").css("display", "block");
                $("#title_error").text("Codigo de Error: " + response.code_mysql);
                $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                setInterval(logAnimation, 1000);
                $('html, body').stop().animate({
                    scrollTop: jQuery("#upsection").offset().top
                }, 700);
            }
        }
    });
});
//fin delete lead

//------------------------------------------------------------------------------
//Modal setClient
$(".setClient").click(function () {
    if ($(this).data("ss") !== "") {
        $("#client" + $(this).data("id")).trigger("click");
        $("#setClient").attr("data-id", $(this).data("id"));
        $("#setClient").attr("data-opc", $(this).data("option"));
    } else {
        showAlert("No puedes convertir en cliente a un lead sin sesguro social..!", "error");
    }
});
$("#setClient").click(function () {
    var data = new FormData();
    var d = new Date();
    var n = d.getTime();
    data.append("log_trans", n);
    data.append("id", $(this).data("id"));
    data.append("opcion", $(this).data("opc"));
    $.ajax({
        type: 'POST',
        url: "../Model/Leads/SetClient.php",
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#loader').show();
        },
        complete: function () {
            $('#loader').hide();
        },
        success: function (response) {
            if (response.message_code === "success") {
                setTimeout(redireccionarPagina('ListarLeads.php'), 5000);
            } else {
                showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                $("#error_ambulance").css("display", "block");
                $("#title_error").text("Codigo de Error: " + response.code_mysql);
                $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                setInterval(logAnimation, 1000);
                $('html, body').stop().animate({
                    scrollTop: jQuery("#upsection").offset().top
                }, 700);
            }

        }
    });
});
//Fin setClient

//------------------------------------------------------------------------------
$(".moreinfoLead").click(function () {
    redireccionarPagina('Profile.php?token=' + $(this).data("id"));
});

$(".moreinfoLead2").click(function () {
    window.open('Profile.php?token=' + $(this).data("id"), '_blank');
});

//Regresar al perfil
$("#backfromProfile").click(function () {
    if ($(this).data("user") === "CLIENTE") {
        redireccionarPagina('ListarClientes.php');
    } else {
        redireccionarPagina('ListarLeads.php');
    }
});
//Modal Documentos
$(".newDocument").click(function () {
    $("#newDoc" + $(this).data("id")).trigger("click");
    $("#addDocument").attr("data-id", $(this).data("id"));
    $("#addDocument").attr("data-option", $(this).data("option"));
    if ($(this).data("option") === "adjunto") {
        $("#data-doc").css("display", "none");
    } else {
        $("#data-doc").css("display", "block");
    }

});
//------------------------------------------------------------------------------
//AddDocuments
$(".hideInputFile").change(function () {
    if ($(this).val() !== "") {
        $("#descripcion").val($(this).val());
        $(".exampleInputFileGroup").css("display", "none");
        $(".autoSend").css("display", "block");
    } else {
        $("#descripcion").val("");
        $(".exampleInputFileGroup").css("display", "block");
        $(".autoSend").css("display", "none");
    }
});
$("#addDocument").click(function () {

    var opciones = ["Bienvenida Greenlight", "Contrato Greenlight",
        "Acuerdo de Pagos Greenlight", "Solicitud de Reportes"];
    for (var item in opciones) {
        if ($("#descripcion").val() === opciones[item]) {
            var textTd = $("#tbl_producto tbody tr td").text();
            var textTd2 = $("#tbl_producto tbody tr td").length;
            if (textTd === "No data available in table" || textTd2 === 0) {
                showAlert("No hay productos creados aún...!", "warn");
                return;
            }
        }
    }

    var campos = {};
    if ($('.exampleInputFileGroup').is(':hidden')) {
        campos = ['descripcion'];
    } else {
        campos = ['descripcion', 'exampleInputFile'];
    }
    var countErrors = 0;
    for (var item in campos) {
        if ($("#" + campos[item]).val() === ""
                || $("#" + campos[item]).val() === "Seleccione") {
            countErrors++;
            $("#" + campos[item]).css("border", "1px solid red");
        } else {
            $("#" + campos[item]).css("border", "1px solid #d2d6de");
        }
    }
    if (countErrors > 0) {
        showAlert("Los campos marcados en rojo son obligatorios", "error");
    } else {
        var data = new FormData();
        var d = new Date();
        var n = d.getTime();
        data.append("log_trans", n);
        var inputFile = document.getElementById("exampleInputFile");
        var file = inputFile.files[0];
        if (file !== undefined) {
            data.append("exampleInputFile", file);
        }
        data.append("descripcion", $("#descripcion").val());
        data.append("person_id", $(this).data("id"));
        data.append("opcion", $(this).data("option"));
        data.append("nombre_cliente", $("#nombre_cliente").val());
        if ($('#send').prop('checked')) {
            data.append("enviar", "true");
        } else {
            data.append("enviar", "false");
        }
        $.ajax({
            type: 'POST',
            url: "../Model/Leads/AddDocuments.php",
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response) {
                if (response.message_code === "success") {
                    if (response.opcion === "documento") {
                        setTimeout(redireccionarPagina('Profile.php?token=' + response.token + '&mensaje=documento'), 5000);
                    } else {
                        setTimeout(redireccionarPagina('Profile.php?token=' + response.token + '&mensaje=adjunto'), 5000);
                    }
                } else {
                    showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                    $("#error_ambulance").css("display", "block");
                    $("#title_error").text("Codigo de Error: " + response.code_mysql);
                    $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                    setInterval(logAnimation, 1000);
                    $('html, body').stop().animate({
                        scrollTop: jQuery("#upsection").offset().top
                    }, 700);
                }
            }
        });
    }
});
//------------------------------------------------------------------------------
//Modal delteDoc
$(".deleteDoc").click(function () {
    $("#docId" + $(this).data("id")).trigger("click");
    $("#deleteDoc").attr("data-id", $(this).data("id"));
    $("#deleteDoc").attr("data-token", $(this).data("token"));
    $("#deleteDoc").attr("data-option", $(this).data("opcion"));
});
$("#deleteDoc").click(function () {
    var data = new FormData();
    var d = new Date();
    var n = d.getTime();
    data.append("log_trans", n);
    data.append("id", $(this).data("id"));
    data.append("opcion", $(this).data("option"));
    data.append("person_id", $(this).data("token"));
    var token = $(this).data("token");
    $.ajax({
        type: 'POST',
        url: "../Model/Leads/DeleteDoc.php",
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#loader').show();
        },
        complete: function () {
            $('#loader').hide();
        },
        success: function (response) {
            if (response.message_code === "success") {
                if (response.option === "documento") {
                    setTimeout(redireccionarPagina('Profile.php?token=' + token + '&mensaje=deletedoc'), 5000);
                } else {
                    setTimeout(redireccionarPagina('Profile.php?token=' + token + '&mensaje=deleteadjunto'), 5000);
                }
            } else {
                showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                $("#error_ambulance").css("display", "block");
                $("#title_error").text("Codigo de Error: " + response.code_mysql);
                $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                setInterval(logAnimation, 1000);
                $('html, body').stop().animate({
                    scrollTop: jQuery("#upsection").offset().top
                }, 700);
            }
        }
    });
});
//Fin delteDoc
//------------------------------------------------------------------------------

//Modal ConfirmDisputas
$(".newDocDisputa").click(function () {
    var textTd = $("#tbl_disputas tbody tr td").text();
    var textTd2 = $("#tbl_disputas tbody tr td").length;
    var nColumnas = $("#tbl_disputas tbody tr:last td").length;
    console.log(nColumnas);
    if (textTd === "No data available in table" || textTd2 === 0) {
        showAlert("No hay disputas generadas para este cliente...!", "warn");
        return;
    }
    $("#newDocDisp" + $(this).data("id")).trigger("click");
    $("#addDocDisp").attr("data-id", $(this).data("id"));
});
//Add Disputa
$("#addDocDisp").click(function () {
    var data = new FormData();
    var d = new Date();
    var n = d.getTime();
    data.append("log_trans", n);
    data.append("person_id", $(this).data("id"));
    $.ajax({
        type: 'POST',
        url: "../Model/Leads/AddDocDisputa.php",
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#loader').show();
        },
        complete: function () {
            $('#loader').hide();
        },
        success: function (response) {
            if (response.message_code === "success") {
                setTimeout(redireccionarPagina('Profile.php?token=' + response.token + '&mensaje=documento'), 5000);
            } else if (response.message_code === "no_disputa") {
                setTimeout(redireccionarPagina('Profile.php?token=' + response.token + '&mensaje=nd'), 5000);
            } else {
                showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                $("#error_ambulance").css("display", "block");
                $("#title_error").text("Codigo de Error: " + response.code_mysql);
                $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                setInterval(logAnimation, 1000);
                $('html, body').stop().animate({
                    scrollTop: jQuery("#upsection").offset().top
                }, 700);
            }
        }
    });
});
//----------------------------------------------------------------------------
//Modal Respuesta disputa
$(".rptaDisputa").click(function () {
    $("#rptaDisputa" + $(this).data("id")).trigger("click");
    $("#addRptaDisputa").attr("data-id", $(this).data("id"));
});

//
//$("#select_bureau").change(function () {
//    if ($(this).val() != "") {
//        $.ajax({
//            type: 'POST',
//            url: "../Model/Leads/GetRazonesXDisputa.php",
//            data: {"idDisputa": $("#addRptaDisputa").data("id"), "bureau": $(this).val()},
//            dataType: 'html',
//            success: function (response) {
////                console.log(response);
//                jQuery("#contentRazonesD").html(response);
//                jQuery("#contentRazonesD").css("display", "block");
//            }
//        });
//    } else {
//        jQuery("#contentRazonesD").html("");
//        jQuery("#contentRazonesD").css("display", "none");
//    }
//
//});


//Add Disputa
$("#addRptaDisputa").click(function () {
    var campos = ['select_bureau', 'select_respuesta'];
    var countErrors = 0;
    //consultar aqui si el bureau ya tiene respuesta con un ajax

    /* if (!$(".chkrazon").length) {
     showAlert("Este bureau ya actualizo su respuesta", "warn");
     return;
     }*/

    for (var item in campos) {
        if ($("#" + campos[item]).val() === "") {
            countErrors++;
            $("#" + campos[item]).css("border", "1px solid red");
        } else {
            $("#" + campos[item]).css("border", "1px solid #d2d6de");
        }
    }
    if (countErrors > 0) {
        showAlert("Los campos marcados en rojo son obligatorios", "error");
    } else {
        var data = new FormData();
        var d = new Date();
        var n = d.getTime();
        data.append("log_trans", n);
        data.append("bureau", $("#select_bureau").val());
        data.append("respuesta", $("#select_respuesta").val());
        data.append("id_disputa", $(this).data("id"));
        data.append("comentarios", $("#txtComments").val());
        // data.append("razones", JSON.stringify($('[name="razones_val[]"]').serializeArray()));
        var token = $(this).data("token");
        $.ajax({
            type: 'POST',
            url: "../Model/Leads/AddAnswerDisputa.php",
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response) {
                if (response.message_code === "success") {
                    setTimeout(redireccionarPagina('Profile.php?token=' + token + '&mensaje=rptadisputaOk'), 5000);
                } else {
                    showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                    $("#error_ambulance").css("display", "block");
                    $("#title_error").text("Codigo de Error: " + response.code_mysql);
                    $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                    setInterval(logAnimation, 1000);
                    $('html, body').stop().animate({
                        scrollTop: jQuery("#upsection").offset().top
                    }, 700);
                }
            }
        });
    }
});
//-------------------Fin add respuesta ----------------------------------------


//Modal Notas
$(".newNota").click(function () {
//    if ($(this).data("ss") !== "") {
    $("#newNota" + $(this).data("id")).trigger("click");
    $("#addNota").attr("data-id", $(this).data("id"));
    $("#addNota").attr("data-option", $(this).data("option"));
    if ($(this).data("option") === "update") {
        $("#titulo").val($(this).data("titulo"));
        $("#txtDescripcion").val($(this).data("desc"));
    }
//    } else {
//        showAlert("No puedes agregar notas a un lead o cliente sin seguro social", "error");
//    }
});
//AddNota
$("#addNota").click(function () {
    var campos = ['titulo_n', 'txtDescripcion'];
    var countErrors = 0;
    for (var item in campos) {
        if ($("#" + campos[item]).val() === "") {
            countErrors++;
            $("#" + campos[item]).css("border", "1px solid red");
        } else {
            $("#" + campos[item]).css("border", "1px solid #d2d6de");
        }
    }
    if (countErrors > 0) {
        showAlert("Los campos marcados en rojo son obligatorios", "error");
    } else {
        var data = new FormData();
        var d = new Date();
        var n = d.getTime();
        data.append("log_trans", n);
        data.append("titulo", $("#titulo_n").val());
        data.append("descripcion", $("#txtDescripcion").val());
        data.append("person_id", $(this).data("token"));
        data.append("id_nota", $(this).data("id"));
        data.append("accion", $(this).data("option"));
        var token = $(this).data("token");
        $.ajax({
            type: 'POST',
            url: "../Model/Leads/AddNotes.php",
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response) {
                if (response.message_code === "success") {
                    setTimeout(redireccionarPagina('Profile.php?token=' + token + '&mensaje=notaok'), 5000);
                } else {
                    showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                    $("#error_ambulance").css("display", "block");
                    $("#title_error").text("Codigo de Error: " + response.code_mysql);
                    $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                    setInterval(logAnimation, 1000);
                    $('html, body').stop().animate({
                        scrollTop: jQuery("#upsection").offset().top
                    }, 700);
                }
            }
        });
    }
});
///DeleteNota
$(".deleteNota").click(function () {
    $("#notaId" + $(this).data("id")).trigger("click");
    $("#deleteNota").attr("data-id", $(this).data("id"));
    $("#deleteNota").attr("data-token", $(this).data("token"));
});
$("#deleteNota").click(function () {
    var data = new FormData();
    var d = new Date();
    var n = d.getTime();
    data.append("log_trans", n);
    data.append("id", $(this).data("id"));
    var token = $(this).data("token");
    $.ajax({
        type: 'POST',
        url: "../Model/Leads/DeleteNota.php",
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#loader').show();
        },
        complete: function () {
            $('#loader').hide();
        },
        success: function (response) {
            if (response.message_code === "success") {
                setTimeout(redireccionarPagina('Profile.php?token=' + token + '&mensaje=deletenota'), 5000);
            } else {
                showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                $("#error_ambulance").css("display", "block");
                $("#title_error").text("Codigo de Error: " + response.code_mysql);
                $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                setInterval(logAnimation, 1000);
                $('html, body').stop().animate({
                    scrollTop: jQuery("#upsection").offset().top
                }, 700);
            }
        }
    });
});
//fin notas

//Modal Recordatorios
$(".newRecordatorio").click(function () {
    var date = new Date();
    var year = date.getFullYear();
    var month = (1 + date.getMonth()).toString().padStart(2, '0');
    var day = date.getDate().toString().padStart(2, '0');
    $("#newRemember" + $(this).data("id")).trigger("click");
    $("#addRecordatorio").attr("data-id", $(this).data("id"));
    $("#addRecordatorio").attr("data-option", $(this).data("option"));
    $("#txtDesc").val("");
    $("#_to").val("");
    $("#vence").val(year + "-" + month + "-" + day);
    if ($(this).data("option") === "update") {
        $("#vence").val($(this).data("vence"));
        $("#hora_vence").val($(this).data("horav"));
        $("#txtDesc").val($(this).data("desc"));
        $("#_to option[value=" + $(this).data("to") + "]").attr("selected", true);
        //$("#_to").val($(this).data("to"));
    }
});
//Add recordatorio
$("#addRecordatorio").click(function () {
    var campos = ['txtDesc', 'vence', 'hora_vence', '_to'];
    var countErrors = 0;
    for (var item in campos) {
        if ($("#" + campos[item]).val() === "") {
            countErrors++;
            $("#" + campos[item]).css("border", "1px solid red");
        } else {
            $("#" + campos[item]).css("border", "1px solid #d2d6de");
        }
    }
    if (countErrors > 0) {
        showAlert("Los campos marcados en rojo son obligatorios", "error");
    } else {
        var data = new FormData();
        var d = new Date();
        var n = d.getTime();
        data.append("log_trans", n);
        data.append("vence", $("#vence").val());
        data.append("hora_vence", $("#hora_vence").val());
        if ($("#_tipo").length === 0) {
            data.append("descripcion", "Recordatorio de Seguimiento: " + $("#txtDesc").val());
        } else {
            data.append("descripcion", "Recordatorio de " + $("#_tipo").val() + ": " + $("#txtDesc").val());
        }
        data.append("id_recordatorio", $(this).data("id"));
        data.append("person_id", $(this).data("token"));
        data.append("accion", $(this).data("option"));
        data.append("_from", $("#_from").val());
        data.append("_to", $("#_to").val());
        var token = $(this).data("token");
        var view = $(this).data("view");
        $.ajax({
            type: 'POST',
            url: "../Model/Leads/AddRecordatorio.php",
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response) {
                if (response.message_code === "success") {
                    if (view === "Profile") {
                        setTimeout(redireccionarPagina('Profile.php?token=' + token + '&mensaje=rememberOk'), 5000);
                    } else {
                        setTimeout(redireccionarPagina('ListarRecordatorios.php?mensaje=rememberOk'), 5000);
                    }
                } else {
                    showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                    $("#error_ambulance").css("display", "block");
                    $("#title_error").text("Codigo de Error: " + response.code_mysql);
                    $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                    setInterval(logAnimation, 1000);
                    $('html, body').stop().animate({
                        scrollTop: jQuery("#upsection").offset().top
                    }, 700);
                }
            }
        });
    }
});
///DeleteRecordatorio
$(".deleteRecordatorio").click(function () {
    $("#recId" + $(this).data("id")).trigger("click");
    $("#deleteRec").attr("data-id", $(this).data("id"));
    $("#deleteRec").attr("data-token", $(this).data("token"));
});
$("#deleteRec").click(function () {
    var data = new FormData();
    var d = new Date();
    var n = d.getTime();
    data.append("log_trans", n);
    data.append("id", $(this).data("id"));
    var token = $(this).data("token");
    var view = $(this).data("view");
    $.ajax({
        type: 'POST',
        url: "../Model/Leads/DeleteRecordatorio.php",
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#loader').show();
        },
        complete: function () {
            $('#loader').hide();
        },
        success: function (response) {
            if (response.message_code === "success") {
                if (view === "Profile") {
                    setTimeout(redireccionarPagina('Profile.php?token=' + token + '&mensaje=deleteRemember'), 5000);
                } else {
                    setTimeout(redireccionarPagina('ListarRecordatorios.php?mensaje=deleteRemember'), 5000);
                }
            } else {
                showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                $("#error_ambulance").css("display", "block");
                $("#title_error").text("Codigo de Error: " + response.code_mysql);
                $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                setInterval(logAnimation, 1000);
                $('html, body').stop().animate({
                    scrollTop: jQuery("#upsection").offset().top
                }, 700);
            }
        }
    });
});
//Modal Disputas
$(".newDisputa").click(function () {
    $("#newDisputa" + $(this).data("id")).trigger("click");
    $("#addDisputa").attr("data-id", $(this).data("id"));
    $("#addDisputa").attr("data-option", $(this).data("option"));
    $("#tituloFrmDisputa").text("Formulario Nueva Disputa");
    $("#addDisputa").attr("data-disputa", "");
    if ($(this).data("option") === "update") {
        $("#tituloFrmDisputa").text("Formulario Actualizar Disputa");
        $("#addDisputa").attr("data-disputa", $(this).data("disputa"));
    }
});

var arayRazonesDisputa = [];
var num_razon = 0;
$(".addRazon").click(function () {

    var campos = ['razon', 'razon_val'];
    var countErrors = 0;
    for (var item in campos) {
        if ($("#" + campos[item]).val() === ""
                || $("#" + campos[item]).val() === "Seleccione") {
            countErrors++;
            $("#" + campos[item]).css("border", "1px solid red");
        } else {
            $("#" + campos[item]).css("border", "1px solid #d2d6de");
        }
    }

    if (countErrors > 0) {
        showAlert("Los campos marcados en rojo son obligatorios", "error");
    } else {
        var datos_razon = [];
        num_razon++;
        datos_razon[0] = num_razon;
        datos_razon[1] = $("#razon option:selected").text();
        datos_razon[2] = $("#razon_val").val();
        arayRazonesDisputa.push(datos_razon);
        var htmlRazones = "<tr id='ncuota" + num_razon + "'>"
                + "<td>" + num_razon + "</td>"
                + "<td>" + $("#razon option:selected").text() + "</td>"
                + "<td>" + $("#razon_val").val() + "</td>"
                + "<td><i class='fa fa-fw fa-eraser' onclick='removeRazon(" + num_razon + ")' style='color: red;cursor: pointer;font-size: 15px;'"
                + " data-toggle='tooltip' title='Remover'></i>"
                + "</tr>";
        jQuery("#content_razones").append(htmlRazones);
        $("#razon_val").val("");
    }
});
//Remove Razon
function removeRazon(id_razon) {
    var i = -1;
    for (var item in arayRazonesDisputa) {
        if (arayRazonesDisputa[item][0] === id_razon) {
            i = item;
            break;
        }
    }
    if (i !== -1) {
        arayRazonesDisputa.splice(i, 1);
    }
    for (var item in arayRazonesDisputa) {
        arayRazonesDisputa[item][0] = (Number(item) + Number(1));
    }
    jQuery("#content_razones").html("");
    var htmlRazones = "";
    for (var item in arayRazonesDisputa) {
        var htmlRazones = "<tr id='ncuota" + arayRazonesDisputa[item][0] + "'>"
                + "<td>" + arayRazonesDisputa[item][0] + "</td>"
                + "<td>" + arayRazonesDisputa[item][1] + "</td>"
                + "<td>" + arayRazonesDisputa[item][2] + "</td>"
                + "<td><i class='fa fa-fw fa-eraser' onclick='removeRazon(" + arayRazonesDisputa[item][0] + ")' style='color: red;cursor: pointer;font-size: 15px;'"
                + " data-toggle='tooltip' title='Remover'></i>"
                + "</tr>";
        jQuery("#content_razones").append(htmlRazones);
    }
    $("#razon_val").val("");
}
//fin remove razon

//Add Disputas
$("#addDisputa").click(function () {
    if (arayRazonesDisputa.length === 0) {
        showAlert("Los campos marcados en rojo son obligatorios", "error");
    } else {
        var data = new FormData();
        var d = new Date();
        var n = d.getTime();
        data.append("log_trans", n);
        data.append("razones", JSON.stringify(arayRazonesDisputa));
        data.append("person_id", $(this).data("id"));
        data.append("option", $(this).data("option"));
        if ($(this).data("option") === "update") {
            data.append("id_disputa", $(this).data("disputa"));
        }
        $.ajax({
            type: 'POST',
            url: "../Model/Leads/AddDisputa.php",
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#loader').show();
            },
            complete: function () {
                $('#loader').hide();
            },
            success: function (response) {
                if (response.message_code === "success" && response.option === "add") {
                    setTimeout(redireccionarPagina('Profile.php?token=' + response.token + '&mensaje=disputaOk'), 5000);
                } else if (response.message_code === "success" && response.option === "update") {
                    setTimeout(redireccionarPagina('Profile.php?token=' + response.token + '&mensaje=disputaUpdate'), 5000);
                } else {
                    showAlert("Ocurrio un error al actualizar la informaciòn, por favor vea el log de errores..!", "error");
                    $("#error_ambulance").css("display", "block");
                    $("#title_error").text("Codigo de Error: " + response.code_mysql);
                    $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + n);
                    setInterval(logAnimation, 1000);
                    $('html, body').stop().animate({
                        scrollTop: jQuery("#upsection").offset().top
                    }, 700);
                }
            }
        });
    }
});
//----------------------------------------


//DeleteDisputa
$(".deleteDisputa").click(function () {
    $("#deleteDisputa" + $(this).data("id")).trigger("click");
    $("#deleteDisputa").attr("data-id", $(this).data("id"));
    $("#deleteDisputa").attr("data-token", $(this).data("token"));
});

$("#deleteDisputa").click(function () {
    var d = new Date();
    var n = d.getTime();
    var data = new FormData();
    data.append("id_disputa", $(this).data("id"));
    data.append("person_id", $(this).data("token"));
    data.append("option", "delete");
    data.append("log_trans", n);
    $.ajax({
        type: 'POST',
        url: "../Model/Leads/AddDisputa.php",
        data: data,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#loader').show();
        },
        complete: function () {
            $('#loader').hide();
        },
        success: function (response) {
            if (response.message_code === "success") {
                setTimeout(redireccionarPagina('Profile.php?token=' + response.token + '&mensaje=deleteDisputa'), 5000);
            } else {
                showAlert("Ocurrio un error al eliminar la cuota, por favor vea el log de errores..!", "error");
                $("#error_ambulance").css("display", "block");
                $("#title_error").text("Codigo de Error: " + response.code_mysql);
                $("#content_error").text("Detalle: " + response.msn + " | Comuniquese con el administrador del sistema e indique el siguiente código: " + $("#log_trans").val());
                setInterval(logAnimation, 1000);
                $('html, body').stop().animate({
                    scrollTop: jQuery("#upsection").offset().top
                }, 700);
            }
        }
    });
});




function validarEmail(valor) {
    emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
    if (emailRegex.test(valor)) {
        return true;
    } else {
        return  false;
    }
}



