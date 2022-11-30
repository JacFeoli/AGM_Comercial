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
        $departamento = $_POST['departamento'];
        $municipio = $_POST['municipio'];
        $query_select_municipio = mysqli_query($connection, "SELECT COUNT(*)
                                                               FROM municipios_libreta_2
                                                              WHERE ID_DEPARTAMENTO = " . $departamento . "
                                                                AND ID_MUNICIPIO = " . $municipio);
        $row_municipio = mysqli_fetch_array($query_select_municipio);
        $num_municipio = $row_municipio['COUNT(*)'];
        echo $num_municipio;
    }
?>