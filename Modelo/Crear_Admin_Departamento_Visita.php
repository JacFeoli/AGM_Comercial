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
            $id_departamento = trim($_POST['id_departamento']);
            $nombre_departamento = strtoupper(trim($_POST['nombre_departamento']));
            mysqli_query($connection, "UPDATE departamentos_visitas_2
                                          SET NOMBRE = '" . $nombre_departamento . "'"
                                    . " WHERE ID_TABLA = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM departamentos_visitas_2 WHERE ID_TABLA = " . $_GET['eliminar']);
            } else {
                $id_departamento = trim($_POST['id_departamento']);
                $nombre_departamento = strtoupper(trim($_POST['nombre_departamento']));
                mysqli_query($connection, "INSERT INTO departamentos_visitas_2 (ID_DEPARTAMENTO, NOMBRE)
                                                VALUES ('" . $id_departamento . "', '" . $nombre_departamento . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Departamentos_Visitas.php'";
        echo "</script>";
    }
?>