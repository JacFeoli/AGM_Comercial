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
            $nombre_empresa = strtoupper(trim($_POST['nombre_empresa']));
            mysqli_query($connection, "UPDATE empresas_2
                                          SET NOMBRE = '" . $nombre_empresa . "'"
                                    . " WHERE ID_EMPRESA = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM empresas_2 WHERE ID_EMPRESA = " . $_GET['eliminar']);
            } else {
                $nombre_empresa = strtoupper(trim($_POST['nombre_empresa']));
                mysqli_query($connection, "INSERT INTO empresas_2 (NOMBRE)
                                                VALUES ('" . $nombre_empresa . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Empresas.php'";
        echo "</script>";
    }
?>