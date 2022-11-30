<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        $fecha_cambio = date('Y-m-d H:i:s');
        if (isset($_GET['editar'])) {
            $editar = $_GET['editar'];
            $id_area_interna = $_GET['id_area_interna'];
        } else {
            $editar = "";
        }
        if (isset($_GET['eliminar'])) {
            $eliminar = $_GET['eliminar'];
            $id_area_interna = $_GET['id_area_interna'];
        } else {
            $eliminar = "";
        }
        $tipo_identificacion_usuario = trim($_POST['tipo_identificacion_usuario']);
        $identificacion_usuario = trim($_POST['identificacion_usuario']);
        $nombre_usuario = strtoupper(trim($_POST['nombre_usuario']));
        $area_interna_usuario = trim($_POST['area_interna_usuario']);
        $usuario_bloqueado = trim($_POST['usuario_bloqueado']);
        $user_usuario = strtoupper(trim($_POST['user_usuario']));
        $password1_usuario = strtoupper(trim($_POST['password1_usuario']));
        $correo_electronico_usuario = strtolower(trim($_POST['correo_electronico_usuario']));
        $departamento = $_POST['id_departamento'];
        $municipio = $_POST['id_municipio'];
        if ($departamento == '') {
            $departamento = 0;
        }
        if ($municipio == '') {
            $municipio = 0;
        }
        if ($editar != "") {
            $password_hash = password_hash($password1_usuario, PASSWORD_DEFAULT);
            mysqli_query($connection, "UPDATE usuarios_tipo_companias_2 SET ID_TIPO_COMPANIA = " . $area_interna_usuario . " WHERE ID_USUARIO = " . $editar . " AND ID_TIPO_COMPANIA = " . $id_area_interna);
            mysqli_query($connection, "UPDATE usuarios_2 SET NOMBRE = '" . $nombre_usuario . "', ID_TIPO_IDENTIFICACION = " . $tipo_identificacion_usuario . ",
                                                             IDENTIFICACION = " . $identificacion_usuario . ", ID_COD_DPTO = " . $departamento . ",
                                                             ID_COD_MPIO = " . $municipio . ", USUARIO = '" . $user_usuario . "',
                                                             PASSWORD = '" . $password_hash . "',
                                                             CORREO_ELECTRONICO = '" . $correo_electronico_usuario . "', BLOQUEADO = " . $usuario_bloqueado . ",
                                                             ULTIMO_CAMBIO = '" . $fecha_cambio . "'
                                                       WHERE ID_USUARIO = " . $editar);
        } else {
            if ($eliminar != "") {
                mysqli_query($connection, "DELETE FROM usuarios_tipo_companias_2 WHERE ID_USUARIO = " . $eliminar . " AND ID_TIPO_COMPANIA = " . $id_area_interna);
                mysqli_query($connection, "DELETE FROM usuarios_2 WHERE ID_USUARIO = " . $eliminar);
            } else {
                $password_hash = password_hash($password1_usuario, PASSWORD_DEFAULT);
                mysqli_query($connection, "INSERT INTO usuarios_2 (NOMBRE, ID_TIPO_IDENTIFICACION, IDENTIFICACION, ID_COD_DPTO, ID_COD_MPIO, USUARIO, PASSWORD, CORREO_ELECTRONICO,
                                                                   BLOQUEADO, ULTIMO_CAMBIO)
                                                           VALUES ('" . $nombre_usuario . "', " . $tipo_identificacion_usuario . ", " . $identificacion_usuario .
                                                                   ", " . $departamento . ", " . $municipio .
                                                                   ", '" . $user_usuario . "', '" . $password_hash .
                                                                   "', '" . $correo_electronico_usuario . "', " . $usuario_bloqueado . ", '" . $fecha_cambio . "')");
                $query_max_id_usuario = mysqli_query($connection, "SELECT MAX(ID_USUARIO) AS MAX_ID_USUARIO FROM usuarios_2");
                $row_max_id_usuario = mysqli_fetch_array($query_max_id_usuario);
                $max_id_usuario = $row_max_id_usuario['MAX_ID_USUARIO'];
                mysqli_query($connection, "INSERT INTO usuarios_tipo_companias_2 (ID_USUARIO, ID_TIPO_COMPANIA) VALUES (" . $max_id_usuario . ", " . $area_interna_usuario . ")");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Usuarios.php'";
        echo "</script>";
    }
?>