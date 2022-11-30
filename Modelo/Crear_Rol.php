<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        // *** ESTA CONEXIÓN ES PARA LOCAL (PC) *** \\.
        //header("Location:/Juridica/Login_2.php");
        // *** ESTA CONEXIÓN ES PARA EL SERVIDOR *** \\.
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
        $nombre_rol = strtoupper(trim($_POST['nombre_rol']));
        $abreviatura_rol = strtoupper(trim($_POST['abreviatura_rol']));
        $area_interna_rol = trim($_POST['area_interna_rol']);
        if ($editar != "") {
            mysqli_query($connection, "UPDATE roles_2 SET NOMBRE = '" . $nombre_rol . "', ABREVIATURA_ROL = '" . $abreviatura_rol .
                                                     "', ID_TIPO_COMPANIA = " . $area_interna_rol . " WHERE ID_ROL = " . $editar);
        } else {
            if ($eliminar != "") {
                mysqli_query($connection, "DELETE FROM roles_2 WHERE ID_ROL = " . $eliminar);
            } else {
                mysqli_query($connection, "INSERT INTO roles_2 (ABREVIATURA_ROL, NOMBRE, ID_TIPO_COMPANIA)
                                                        VALUES ('" . $abreviatura_rol . "', '" . $nombre_rol . "', " . $area_interna_rol . ")");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Roles.php'";
        echo "</script>";
    }
?>