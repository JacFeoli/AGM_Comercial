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
        $id_tabla = $_POST['id_tabla'];
        $fecha_visita = $_POST['fecha_visita'];
        $tipo_visita = $_POST['tipo_visita'];
        $observaciones = strtoupper($_POST['observaciones']);
        $fecha_creacion = date('Y-m-d H:i:s');
        $fecha_actualizacion = '0000-00-00';
        $id_usuario = $_SESSION['id_user'];
        mysqli_query($connection, "INSERT INTO bitacora_libretas_2 (ID_MUNICIPIO_LIBRETA, ID_TIPO_VISITA, OBSERVACIONES, FECHA_VISITA, FECHA_CREACION, FECHA_ACTUALIZACION, ID_USUARIO)
                                                            VALUES ('" . $id_tabla .
                                                                   "', '" . $tipo_visita .
                                                                   "', '" . $observaciones .
                                                                   "', '" . $fecha_visita .
                                                                   "', '" . $fecha_creacion .
                                                                   "', '" . $fecha_actualizacion .
                                                                   "', '" . $id_usuario . "')");
        echo "<script>";
            echo "document.location.href = '../Bitacora_Acuerdos.php'";
        echo "</script>";
    }
?>