jQuery(function () {
    jQuery('body').on('click', '#validar', function () {
        var campos = ['usuario', 'pass'];
        var countErrors = 0;
        for (var item in campos) {
            if ($("#" + campos[item]).val() === "") {
                countErrors++;
                $("#" + campos[item]).css("border", "1px solid #dc3545");
            } else {
                $("#" + campos[item]).css("border", "1px solid #d2d6de");
            }
        }
        if (countErrors > 0) {
            new PNotify({
                title: 'Error',
                text: 'Los campos marcados en rojo son obligatorios!',
                type: 'error',
                styling: 'bootstrap3'
            });
        } else {
            jQuery.ajax({
                type: 'POST',
                url: "Model/login.php",
                data: {usuario: jQuery("#usuario").val(), pass: jQuery("#pass").val()},
                dataType: "JSON",
                success: function (response) {
                    console.log(response)
                    if (response.message === 'success') {
                        setTimeout(redireccionarPagina('Views/ListarEmpleados'), 3000);
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: 'Usuario o contraseña incorrecta!',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                        jQuery("#usuario").val("");
                        jQuery("#pass").val("");
                        jQuery("#usuario").focus();
                    }
                }
            });
        }

    });

    function redireccionarPagina(pagina) {
        window.location = pagina;
    }



});

//if (e.which == 13) {
//            var texto = jQuery(this).val();
//            jQuery(this).val("");
//            var id = jQuery(this).attr('id');
//            console.log(id);
//            var dataId = jQuery(this).data('id');
//            var split = dataId.split(':');
//            jQuery.ajax({
//                type: 'POST',
//                url: "Model/sendMessages.php",
//                data: {mensaje: texto, de: userOnline, para: Number(split[1])},
//                success: function (response) {
//                    if (response !== 'ok') {
//                        alert('ocurrio error al enviar el mensaje');
//                    }
//                }
//            });
//        }


