<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Tarifa.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_tarifa']) != "") {
                    if ($_POST['busqueda_tarifa'] != "") {
                            $busqueda_tarifa = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_tarifa'] . "%' ";
                    } else {
                            $busqueda_tarifa = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_tarifa = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_tarifa= " WHERE NOMBRE <> ''";
        }
        $query_tarifa = mysqli_query($connection, "SELECT *
                                                     FROM tarifas_2
                                                     $busqueda_tarifa
                                                    ORDER BY NOMBRE");
        $count_tarifa = mysqli_num_rows($query_tarifa);
        $info_pagination = array();
        if ($count_tarifa > 0) {
            $paginacion_count = getPagination($count_tarifa);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_tarifa'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>