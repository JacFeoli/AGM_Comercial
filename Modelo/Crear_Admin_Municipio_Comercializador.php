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
            $id_comercializador = trim($_POST['id_comercializador']);
            $id_departamento = trim($_POST['id_departamento']);
            $id_municipio = trim($_POST['id_municipio']);
            $nombre_municipio = strtoupper(trim($_POST['nombre_municipio']));
            mysqli_query($connection, "UPDATE municipios_comercializadores_2
                                          SET ID_COMERCIALIZADOR = '" . $id_comercializador . "',
                                              ID_DEPARTAMENTO = '" . $id_departamento . "',
                                              ID_MUNICIPIO = '" . $id_municipio . "',
                                              NOMBRE = '" . $nombre_municipio . "' "
                                    . " WHERE ID_TABLA = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM municipios_comercializadores_2 WHERE ID_TABLA = " . $_GET['eliminar']);
            } else {
                $id_comercializador = trim($_POST['id_comercializador']);
                $id_departamento = trim($_POST['id_departamento']);
                $id_municipio = trim($_POST['id_municipio']);
                $nombre_municipio = strtoupper(trim($_POST['nombre_municipio']));
                mysqli_query($connection, "INSERT INTO municipios_comercializadores_2 (ID_COMERCIALIZADOR, ID_DEPARTAMENTO, ID_MUNICIPIO, NOMBRE)
                                                VALUES ('" . $id_comercializador . "', '" . $id_departamento . "', '" . $id_municipio . "', '" . $nombre_municipio . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Municipios_Comercializadores.php'";
        echo "</script>";
    }
?>