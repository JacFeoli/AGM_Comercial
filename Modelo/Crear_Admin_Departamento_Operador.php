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
            $id_operador = trim($_POST['id_operador']);
            $id_departamento = trim($_POST['id_departamento']);
            $nombre_departamento = strtoupper(trim($_POST['nombre_departamento']));
            mysqli_query($connection, "UPDATE departamentos_operadores_2
                                          SET ID_OPERADOR = '" . $id_operador . "', "
                                    . "       ID_DEPARTAMENTO = '" . $id_departamento . "', "
                                    . "       NOMBRE = '" . $nombre_departamento . "' "
                                    . " WHERE ID_TABLA = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM departamentos_operadores_2 WHERE ID_TABLA = " . $_GET['eliminar']);
            } else {
                $id_operador = trim($_POST['id_operador']);
                $id_departamento = trim($_POST['id_departamento']);
                $nombre_departamento = strtoupper(trim($_POST['nombre_departamento']));
                mysqli_query($connection, "INSERT INTO departamentos_operadores_2 (ID_OPERADOR, ID_DEPARTAMENTO, NOMBRE)
                                                VALUES ('" . $id_operador . "', '" . $id_departamento . "', '" . $nombre_departamento . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Departamentos_Operadores.php'";
        echo "</script>";
    }
?>