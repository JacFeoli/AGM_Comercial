<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Comercializador.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_comercializador']) != "") {
                    if ($_POST['busqueda_comercializador'] != "") {
                            $busqueda_comercializador = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_comercializador'] . "%' ";
                    } else {
                            $busqueda_comercializador = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_comercializador = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_comercializador = " WHERE NOMBRE <> ''";
        }
        $query_comercializador = mysqli_query($connection, "SELECT *
                                                           FROM comercializadores_2
                                                           $busqueda_comercializador
                                                          ORDER BY NOMBRE");
        $count_comercializador = mysqli_num_rows($query_comercializador);
        $info_pagination = array();
        if ($count_comercializador > 0) {
            $paginacion_count = getPagination($count_comercializador);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_comercializador'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>