<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Municipio.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_municipio']) != "") {
                    if ($_POST['busqueda_municipio'] != "") {
                            $busqueda_municipio = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_municipio'] . "%' ";
                    } else {
                            $busqueda_municipio = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_municipio = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_municipio = " WHERE NOMBRE <> ''";
        }
        $query_municipio = mysqli_query($connection, "SELECT *
                                                        FROM municipios_comercializadores_2
                                                        $busqueda_municipio
                                                       ORDER BY NOMBRE");
        $count_municipio = mysqli_num_rows($query_municipio);
        $info_pagination = array();
        if ($count_municipio > 0) {
            $paginacion_count = getPagination($count_municipio);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_municipio'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>