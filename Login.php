<?php
    session_start();
    require_once('Includes/Config.php');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query_select_user = "SELECT *
                                FROM usuarios_2
                               WHERE USUARIO = '" . $username . "'
                                 AND BLOQUEADO = 0";
        $sql_select_user = mysqli_query($connection, $query_select_user);
        $row_user = mysqli_fetch_array($sql_select_user);
        $count_user = mysqli_num_rows($sql_select_user);
        if ($count_user == 1) {
            if (password_verify($password, $row_user['PASSWORD'])) {
                $fullname = $row_user['NOMBRE'];
                $id_user = $row_user['ID_USUARIO'];
                $id_departamento = $row_user['ID_COD_DPTO'];
                $id_municipio = $row_user['ID_COD_MPIO'];
                $ultimo_login = date('Y-m-d H:i:s');
                mysqli_query($connection, "UPDATE usuarios_2
                                              SET ULTIMO_LOGIN = '" . $ultimo_login . "',
                                                  INTENTOS_FALLIDOS = 0
                                            WHERE ID_USUARIO = " . $id_user);
                $query_select_roles = mysqli_query($connection, "SELECT A2.NOMBRE AS NOMBRE, A4.NOMBRE AS TIPO_COMPANIA,
                                                                        A2.ABREVIATURA_ROL AS ABREVIATURA_ROL
                                                                  FROM usuarios_2 A1, roles_2 A2, roles_usuarios_2 A3,
                                                                       tipo_companias_2 A4
                                                                 WHERE A1.ID_USUARIO = A3.ID_USUARIO
                                                                   AND A2.ID_ROL = A3.ID_ROL
                                                                   AND A2.ID_TIPO_COMPANIA = A4.ID_TIPO_COMPANIA
                                                                   AND A1.ID_USUARIO = " . $id_user . "
                                                                 ORDER BY ABREVIATURA_ROL");
                $row_roles = mysqli_fetch_array($query_select_roles);
                $_SESSION['rol'] = $row_roles['ABREVIATURA_ROL'];
                $_SESSION['fullname'] = $fullname;
                $_SESSION['id_user'] = $id_user;
                $_SESSION['id_departamento'] = $id_departamento;
                $_SESSION['id_municipio'] = $id_municipio;
                $_SESSION['timeout'] = time();
                $_SESSION['login'] = 1;
                $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                header("Location:$ruta/Home.php");
            } else {
                mysqli_query($connection, "UPDATE usuarios_2 SET INTENTOS_FALLIDOS = INTENTOS_FALLIDOS + 1
                                           WHERE USUARIO = UPPER('" . $username . "')");
                $message = 'Usuario y/o Contraseña invalidos o Usuario Bloqueado';
            }
        } else {
            mysqli_query($connection, "UPDATE usuarios_2 SET INTENTOS_FALLIDOS = INTENTOS_FALLIDOS + 1
                                        WHERE USUARIO = UPPER('" . $username . "')");
            $message = 'Usuario y/o Contraseña invalidos o Usuario Bloqueado';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>AGM Desarrollos - Login</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <!--<link rel="stylesheet" href="Css/bootstrap.min.css" />-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
    </head>
    <body style="background-image: url('Images/login_img2.jpg'); background-position: center; background-repeat: no-repeat; background-size: cover; position: relative;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="electricaribe-logo">
                        <!--<h1>
                            <img class="img-responsive center-block" src="Images/logo_eca.jpg" />
                            <img class="img-responsive center-block" src="Images/logo-nombre-1.png" />
                        </h1>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-lg-4">
                    <?php
                        if (!empty($message)) { ?>
                            <div style="font-family: 'Cabin'; padding-right: 15px; border-color: #A94442; font-size: 14px;" id="alert-login" class="alert alert-danger alert-dismissible text-center" role="alert">
                                Usuario / Contraseña invalidos o Usuario Bloqueado.
                            </div>
                        <?php
                            //echo "<label class='error-message'>{$message}</label>";
                        }
                    ?>
                    <div style="display: none; font-family: 'Cabin'; padding-right: 15px; border-color: #A94442; font-size: 14px;" id="alert-login-2" class="alert alert-danger alert-dismissible text-center" role="alert">
                        Debe especificar un Usuario y Contraseña.
                    </div>
                    <div style="display: none; font-family: 'Cabin'; padding-right: 15px; border-color: #31708F; font-size: 14px;" id="alert-login-3" class="alert alert-info alert-dismissible text-center" role="alert">
                        Autenticando... Un momento Por Favor
                    </div>
                </div>
                <div class="col-sm-4"></div>
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-lg-4">
                    <form style="background-color: #EEF3F6;" id="login" name="login" method="post" action="Login.php" class="needs-validation login" novalidate>
                        <div class="form-group">
                            <h3>Inicio de Sesión</h3>
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="username">Usuario:</label>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input style="font-weight: bold;" type="text" class="form-control" id="username" name="username" placeholder="Usuario" required />
                                <div class="valid-feedback">Valido.</div>
                                <div class="invalid-feedback">Por favor, llenar este Campo.</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input style="font-weight: bold;" type="password" class="form-control" id="password" name="password" placeholder="CONTRASEÑA" required>
                                <div class="valid-feedback">Valido.</div>
                                <div class="invalid-feedback">Por favor, llenar este Campo.</div>
                            </div>
                        </div>
                        <div style="text-align: center;" class="form-group">
                            <button id="btn_login" type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        </div>
                        <hr />
                        <div style="text-align: center;" class="form-group">
                            <h5>Licencia exclusiva para:</h5>
                            <br />
                            <img style="max-width: 40%;" class="img-responsive center-block" src="Images/AGM Desarrollos 2.png" />
                        </div>
                    </form>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
    <script>
        $(document).ready(function() {
            $("#username").focus();
            $("#btn_login").click(function() {
                var usuario = $("#username").val();
                var password = $("#password").val();
                if (usuario.length == 0) {
                    $("#username").focus();
                    $("#alert-login").css("display", "none");
                    $("#alert-login-2").css("display", "block");
                    /*$("#alert-login-2").fadeTo(3000, 500).slideUp(500, function() {
                            $("#alert-login-2").slideUp(500);
                    });*/
                    return false;
                }
                if (password.length == 0) {
                    $("#password").focus();
                    $("#alert-login").css("display", "none");
                    $("#alert-login-2").css("display", "block");
                    /*$("#alert-login-2").fadeTo(3000, 500).slideUp(500, function() {
                            $("#alert-login-2").slideUp(500);
                    });*/
                    return false;
                }
                $("#alert-login").css("display", "none");
                $("#alert-login-2").css("display", "none");
                $("#alert-login-3").css("display", "block");
            });
        });
    </script>
</html>