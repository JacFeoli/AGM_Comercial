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
        $area_interna_rol_usuario = $_POST['area_interna_rol_usuario'];
        $id_nombre_usuario_rol = $_POST['id_usuario_rol'];
        $id_rol = $_POST['id_rol'];
        if ($editar != "") {
            mysqli_query($connection, "UPDATE roles_usuarios_2 SET ID_ROL = " . $id_rol . ", ID_USUARIO = " . $id_nombre_usuario_rol .
                                                           " WHERE ID_TABLA = " . $editar);
        } else {
            if ($eliminar != "") {
                mysqli_query($connection, "DELETE FROM roles_usuarios_2 WHERE ID_TABLA = " . $eliminar);
            } else {
                mysqli_query($connection, "INSERT INTO roles_usuarios_2 (ID_ROL, ID_USUARIO)
                                                                 VALUES (" . $id_rol . ", " . $id_nombre_usuario_rol . ")");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Roles_Usuarios.php'";
        echo "</script>";
    }
?>