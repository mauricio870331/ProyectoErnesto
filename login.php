<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Control de Asistencia</title>
        <link rel="shortcut icon" type="image/png" href="images/Fingerprint.png">
        <!-- Bootstrap -->
        <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- NProgress -->
        <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
        <!-- Animate.css -->
        <link href="vendors/animate.css/animate.min.css" rel="stylesheet">
        <!-- Custom Theme Style -->
        <link href="css/custom.min.css" rel="stylesheet">
        <!-- PNotify -->
        <link href="vendors/pnotify/dist/pnotify.css" rel="stylesheet">
        <link href="vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
        <link href="vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
    </head>
    <body class="login">
        <div>
            <a class="hiddenanchor" id="signup"></a>
            <a class="hiddenanchor" id="signin"></a>

            <div class="login_wrapper">
                <div class="animate form login_form">
                    <section class="login_content">
                        <form>
                            <h1>Inicio de Sesión</h1>
                            <div>
                                <input id="usuario" type="text" class="form-control" placeholder="Username" required="" />
                            </div>
                            <div>
                                <input id="pass" type="password" class="form-control" placeholder="Password" required="" />
                            </div>
                            <div>
                                <a class="btn btn-default submit" href="Marcaciones">Marcaciones</a>
                                <a id="validar" class="btn btn-default submit" href="javascript:void(0)">Ingresar</a>

                                <a class="reset_pass" href="#">Lost your password?</a>
                            </div>

                            <div class="clearfix"></div>

                            <div class="separator">  
                                <div class="clearfix"></div>
                                <br />
                                <div>
                                    <h1><img src="images/Fingerprint.png" alt=""/> Control de Asistencia</h1>                  
                                    <p>Proyecto realizado con la plantilla Gentella Master by Mauricio Herrera</p>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>        
        <script src="vendors/jquery/dist/jquery.min.js"></script>
        <script src="js/funcionesLogin.js"></script>
        <!-- PNotify -->
        <script src="vendors/pnotify/dist/pnotify.js"></script>
        <script src="vendors/pnotify/dist/pnotify.buttons.js"></script>
        <script src="vendors/pnotify/dist/pnotify.nonblock.js"></script>
    </body>
</html>
