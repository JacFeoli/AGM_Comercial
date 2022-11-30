<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Estado_Suministro.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_estado_suministro']) != "") {
                    if ($_POST['busqueda_estado_suministro'] != "") {
                        $busqueda_estado_suministro = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_estado_suministro'] . "%' ";
                    } else {
                        $busqueda_estado_suministro = " WHERE NOMBRE <> ''";
                    }
            } else {
                $busqueda_estado_suministro = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_estado_suministro= " WHERE NOMBRE <> ''";
        }
        $query_estado_suministro = mysqli_query($connection, "SELECT *
                                                           FROM estados_suministro_2
                                                           $busqueda_estado_suministro
                                                          ORDER BY NOMBRE");
        $count_estado_suministro = mysqli_num_rows($query_estado_suministro);
        $info_pagination = array();
        if ($count_estado_suministro > 0) {
            $paginacion_count = getPagination($count_estado_suministro);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_estado_suministro'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>