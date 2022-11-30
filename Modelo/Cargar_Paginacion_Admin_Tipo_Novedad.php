<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Tipo_Novedad.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_tipo_novedad']) != "") {
                    if ($_POST['busqueda_tipo_novedad'] != "") {
                        $busqueda_tipo_novedad = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_tipo_novedad'] . "%' ";
                    } else {
                        $busqueda_tipo_novedad = " WHERE NOMBRE <> ''";
                    }
            } else {
                $busqueda_tipo_novedad = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_tipo_novedad= " WHERE NOMBRE <> ''";
        }
        $query_tipo_novedad = mysqli_query($connection, "SELECT *
                                                           FROM tipo_novedades_2
                                                           $busqueda_tipo_novedad
                                                          ORDER BY NOMBRE");
        $count_tipo_novedad = mysqli_num_rows($query_tipo_novedad);
        $info_pagination = array();
        if ($count_tipo_novedad > 0) {
            $paginacion_count = getPagination($count_tipo_novedad);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_tipo_novedad'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>