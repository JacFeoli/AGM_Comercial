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
            $codigo_tarifa = trim($_POST['codigo_tarifa']);
            $nombre_tarifa = strtoupper(trim($_POST['nombre_tarifa']));
            mysqli_query($connection, "UPDATE tarifas_2
                                          SET COD_TARIFA = '" . $codigo_tarifa . "', "
                                          . " NOMBRE = '" . $nombre_tarifa . "'"
                                    . " WHERE ID_TARIFA = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM tarifas_2 WHERE ID_TARIFA = " . $_GET['eliminar']);
            } else {
                $codigo_tarifa = trim($_POST['codigo_tarifa']);
                $nombre_tarifa = strtoupper(trim($_POST['nombre_tarifa']));
                mysqli_query($connection, "INSERT INTO tarifas_2 (COD_TARIFA, NOMBRE)
                                                VALUES ('" . $codigo_tarifa . "'. '" . $nombre_tarifa . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Tarifas.php'";
        echo "</script>";
    }
?>