<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Tipo_Contrato.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_tipo_contrato']) != "") {
                    if ($_POST['busqueda_tipo_contrato'] != "") {
                            $busqueda_tipo_contrato = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_tipo_contrato'] . "%' ";
                    } else {
                            $busqueda_tipo_contrato = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_tipo_contrato = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_tipo_contrato = " WHERE NOMBRE <> ''";
        }
        $query_tipo_contrato = mysqli_query($connection, "SELECT *
                                                           FROM tipo_contratos_2
                                                           $busqueda_tipo_contrato
                                                          ORDER BY NOMBRE");
        $count_tipo_contrato = mysqli_num_rows($query_tipo_contrato);
        $info_pagination = array();
        if ($count_tipo_contrato > 0) {
            $paginacion_count = getPagination($count_tipo_contrato);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_tipo_contrato'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>