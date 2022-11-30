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
        if (isset($_GET['editar'])) {
            $nombre_tipo_visita = strtoupper(trim($_POST['nombre_tipo_visita']));
            mysqli_query($connection, "UPDATE tipo_visitas_2
                                          SET NOMBRE = '" . $nombre_tipo_visita . "'"
                                    . " WHERE ID_TIPO_VISITA = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $_GET['eliminar']);
            } else {
                $nombre_tipo_visita = strtoupper(trim($_POST['nombre_tipo_visita']));
                mysqli_query($connection, "INSERT INTO tipo_visitas_2 (NOMBRE)
                                                VALUES ('" . $nombre_tipo_visita . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Tipo_Visitas.php'";
        echo "</script>";
    }
?>