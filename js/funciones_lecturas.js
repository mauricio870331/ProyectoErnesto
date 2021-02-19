function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length)).replace("Views/", "");
}


isToken();

var timestamp = null;

function isToken() {
    if (!localStorage.getItem("srnPc")) {
        showNotify("No existe un token para equipo, por favor generarlo y configurarlo en el plugin..!", "Aviso", "error", 8000);
    } else {
        $(".imgFinger").attr("id", localStorage.getItem("srnPc"));
        $(".txtFinger").attr("id", localStorage.getItem("srnPc") + "_texto");
        activarSensorRead();
    }
}

function activarSensorRead() {
    $.ajax({
        async: true,
        type: "POST",
        url: getAbsolutePath() + "Model/FingerUtils/ActivarSensorReader.php",
        data: "&token=" + localStorage.getItem("srnPc"),
        dataType: "json",
        success: function (data) {
            var json = JSON.parse(data);
            if (json.filas === 1) {
                console.log("todo ok")
                showNotify("Sensor activado en el cliente", "Información", "success", 3000);
            }
        }
    });
}

function cargar_push() {

//    console.log(baseUrl + "/Model/FingerUtils/httpush.php");

    $.ajax({
        async: true,
        type: "POST",
        url: base_url + "/Model/FingerUtils/httpush.php",
        data: "&timestamp=" + timestamp + "&token=" + localStorage.getItem("srnPc") + "&baseUrl=" + base_url,
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
                $("#" + id).css('background-image', 'url(data:image/png;base64,' + imageHuella + ')');
                $("#infoUser").html("Identificacion: " + json["documento"] + "<br/>Nombre: " + json["nombre"] + "");
                if (json["documento"] !== "----") {
                    addMarcacion(json["documento"]);
                } else {
                    showNotify("Usuario no Identificado", "Aviso..!", "info", 3000);
                }
            }
            setTimeout("cargar_push()", 1000);
        }
    });
}

function addMarcacion(documento) {
    $.ajax({
        async: true,
        type: "POST",
        url: "Model/empleados/addMarcacion.php",
        data: "&documento=" + documento,
        dataType: "json",
        success: function (data) {
//            var json = JSON.parse(data);
            console.log(data.message_code);
            if (data.message_code === "success") {
                showNotify("Marcación Registrada", "Información", "success", 3000);
            } else if (data.message_code === "fuera_rango") {
                showNotify("No puedes registrar marcación en esta hora", "Marcación Incorrecta..!", "error", 5000);
            } else {
                showNotify(data.msn, "Marcación Incorrecta..!", "error", 5000);
            }
        }
    });
}


function showNotify(text, title, type, delay) {
    new PNotify({
        title: title,
        text: text,
        type: type,
        delay: delay,
        styling: 'bootstrap3'
    });
}