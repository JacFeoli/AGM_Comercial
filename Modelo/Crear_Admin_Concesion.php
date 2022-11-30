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
            $nombre_concesion = strtoupper(trim($_POST['nombre_concesion']));
            mysqli_query($connection, "UPDATE concesiones_2
                                          SET NOMBRE = '" . $nombre_concesion . "'"
                                    . " WHERE ID_CONCESION = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM concesiones_2 WHERE ID_CONCESION = " . $_GET['eliminar']);
            } else {
                $nombre_concesion = strtoupper(trim($_POST['nombre_concesion']));
                mysqli_query($connection, "INSERT INTO concesiones_2 (NOMBRE)
                                                VALUES ('" . $nombre_concesion . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Concesiones.php'";
        echo "</script>";
    }
?>