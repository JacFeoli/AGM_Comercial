<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Tipo_Cliente.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_tipo_cliente']) != "") {
                    if ($_POST['busqueda_tipo_cliente'] != "") {
                            $busqueda_tipo_cliente = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_tipo_cliente'] . "%' ";
                    } else {
                            $busqueda_tipo_cliente = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_tipo_cliente = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_tipo_cliente= " WHERE NOMBRE <> ''";
        }
        $query_tipo_cliente = mysqli_query($connection, "SELECT *
                                                           FROM tipo_clientes_2
                                                           $busqueda_tipo_cliente
                                                          ORDER BY NOMBRE");
        $count_tipo_cliente = mysqli_num_rows($query_tipo_cliente);
        $info_pagination = array();
        if ($count_tipo_cliente > 0) {
            $paginacion_count = getPagination($count_tipo_cliente);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_tipo_cliente'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>