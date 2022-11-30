<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Departamento.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_departamento']) != "") {
                    if ($_POST['busqueda_departamento'] != "") {
                            $busqueda_departamento = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_departamento'] . "%' ";
                    } else {
                            $busqueda_departamento = " WHERE NOMBRE <> ''";
                    }
            } else {
                    $busqueda_departamento = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_departamento = " WHERE NOMBRE <> ''";
        }
        $query_departamento = mysqli_query($connection, "SELECT *
                                                           FROM departamentos_2
                                                           $busqueda_departamento
                                                          ORDER BY NOMBRE");
        $count_departamento = mysqli_num_rows($query_departamento);
        $info_pagination = array();
        if ($count_departamento > 0) {
            $paginacion_count = getPagination($count_departamento);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_departamento'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>