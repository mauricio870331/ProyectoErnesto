
function getParameterByName(param) {
    param = "" + window.location;
    var msj = param.split("/");
    return  msj[msj.length - 1];
}

switch (getParameterByName()) {
    case "empleadoCreado":
        showNotify("Empleado creado con éxito..!", "Aviso..!", "success", 2000);
        break;
    case "empleadoactualizado":
        showNotify("Empleado actualizado con éxito..!", "Aviso..!", "success", 2000);
        break;
    case "empresaCreada":
        showNotify("Empresa creado con éxito..!", "Aviso..!", "success", 2000);
        break;
    case "empresaEditada":
        showNotify("Empresa editada con éxito..!", "Aviso..!", "success", 2000);
        break;
    case "departamentoCreado":
        showNotify("Departamento creado con éxito..!", "Aviso..!", "success", 2000);
        break;
    case "departamentoActualizado":
        showNotify("Departamento actualizado con éxito..!", "Aviso..!", "success", 2000);
        break;
    case "horarioCreado":
        showNotify("Horario creado con éxito..!", "Aviso..!", "success", 2000);
        break;
    case "horarioActualizado":
        showNotify("Horario actualizado con éxito..!", "Aviso..!", "success", 2000);
        break;
    case "permisoCreado":
        showNotify("Permiso creado con éxito..!", "Aviso..!", "success", 2000);
        break;
    case "permisoActualizado":
        showNotify("Permiso actualizado con éxito..!", "Aviso..!", "success", 2000);
        break;

    default:

        break;
}

