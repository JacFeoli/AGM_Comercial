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
            $nombre_estado_suministro = strtoupper(trim($_POST['nombre_estado_suministro']));
            mysqli_query($connection, "UPDATE estados_suministro_2
                                          SET NOMBRE = '" . $nombre_estado_suministro . "'"
                                    . " WHERE ID_ESTADO_SUMINISTRO = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM estados_suministro_2 WHERE ID_ESTADO_SUMINISTRO = " . $_GET['eliminar']);
            } else {
                $nombre_estado_suministro = strtoupper(trim($_POST['nombre_estado_suministro']));
                mysqli_query($connection, "INSERT INTO estados_suministro_2 (NOMBRE)
                                                VALUES ('" . $nombre_estado_suministro . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Estados_Suministro.php'";
        echo "</script>";
    }
?>