<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Tipo_Visita.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_tipo_visita']) != "") {
                    if ($_POST['busqueda_tipo_visita'] != "") {
                            $busqueda_tipo_visita = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_tipo_visita'] . "%' ";
                    } else {
                            $busqueda_tipo_visita = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_tipo_visita = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_tipo_visita = " WHERE NOMBRE <> ''";
        }
        $query_tipo_visita = mysqli_query($connection, "SELECT *
                                                           FROM tipo_visitas_2
                                                           $busqueda_tipo_visita
                                                          ORDER BY NOMBRE");
        $count_tipo_visita = mysqli_num_rows($query_tipo_visita);
        $info_pagination = array();
        if ($count_tipo_visita > 0) {
            $paginacion_count = getPagination($count_tipo_visita);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_tipo_visita'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>