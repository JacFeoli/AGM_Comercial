<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Contribuyente.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_contribuyente']) != "") {
                    if ($_POST['busqueda_contribuyente'] != "") {
                            $busqueda_contribuyente = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_contribuyente'] . "%' ";
                    } else {
                            $busqueda_contribuyente = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_contribuyente = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_contribuyente= " WHERE NOMBRE <> ''";
        }
        $query_contribuyente = mysqli_query($connection, "SELECT *
                                                           FROM contribuyentes_2
                                                           $busqueda_contribuyente
                                                          ORDER BY NOMBRE");
        $count_contribuyente = mysqli_num_rows($query_contribuyente);
        $info_pagination = array();
        if ($count_contribuyente > 0) {
            $paginacion_count = getPagination($count_contribuyente);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_contribuyente'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>