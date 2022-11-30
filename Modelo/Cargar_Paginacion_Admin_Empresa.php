<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Empresa.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_empresa']) != "") {
                    if ($_POST['busqueda_empresa'] != "") {
                            $busqueda_empresa = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_empresa'] . "%' ";
                    } else {
                            $busqueda_empresa = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_empresa = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_empresa= " WHERE NOMBRE <> ''";
        }
        $query_empresa = mysqli_query($connection, "SELECT *
                                                           FROM empresas_2
                                                           $busqueda_empresa
                                                          ORDER BY NOMBRE");
        $count_empresa = mysqli_num_rows($query_empresa);
        $info_pagination = array();
        if ($count_empresa > 0) {
            $paginacion_count = getPagination($count_empresa);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_empresa'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>