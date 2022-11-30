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
        $id_municipio_libreta = $_POST['id_municipio_libreta_eliminar'];
        $query_select_municipio_libreta = mysqli_query($connection, "SELECT COUNT(*)
                                                                       FROM bitacora_libretas_2
                                                                      WHERE ID_MUNICIPIO_LIBRETA = " . $id_municipio_libreta);
        $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
        $num_municipio_libreta = $row_municipio_libreta['COUNT(*)'];
        echo $num_municipio_libreta;
    }
?>