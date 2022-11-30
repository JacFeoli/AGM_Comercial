<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Operador.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_operador']) != "") {
                    if ($_POST['busqueda_operador'] != "") {
                            $busqueda_operador = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_operador'] . "%' ";
                    } else {
                            $busqueda_operador = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_operador = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_operador= " WHERE NOMBRE <> ''";
        }
        $query_operador = mysqli_query($connection, "SELECT *
                                                           FROM operadores_2
                                                           $busqueda_operador
                                                          ORDER BY NOMBRE");
        $count_operador = mysqli_num_rows($query_operador);
        $info_pagination = array();
        if ($count_operador > 0) {
            $paginacion_count = getPagination($count_operador);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_operador'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>