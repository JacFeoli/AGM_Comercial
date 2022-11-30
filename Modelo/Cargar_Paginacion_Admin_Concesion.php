<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Concesion.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_concesion']) != "") {
                    if ($_POST['busqueda_concesion'] != "") {
                            $busqueda_concesion = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_concesion'] . "%' ";
                    } else {
                            $busqueda_concesion = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_concesion = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_concesion= " WHERE NOMBRE <> ''";
        }
        $query_concesion = mysqli_query($connection, "SELECT *
                                                           FROM concesiones_2
                                                           $busqueda_concesion
                                                          ORDER BY NOMBRE");
        $count_concesion = mysqli_num_rows($query_concesion);
        $info_pagination = array();
        if ($count_concesion > 0) {
            $paginacion_count = getPagination($count_concesion);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_concesion'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>