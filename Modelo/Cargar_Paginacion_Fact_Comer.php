<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Fact_Esp.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_factura']) != "") {
                if ($_POST['busqueda_factura'] != "") {
                    $busqueda_factura = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_factura'] . "%' ";
                } else {
                    $busqueda_factura = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_factura = " WHERE MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_factura = " WHERE MUN.NOMBRE <> ''";
        }
        $query_factura = mysqli_query($connection, "SELECT *
                                                      FROM facturacion_comercializadores_2 FC,
                                                           departamentos_comercializadores_2 DEPT,
                                                           municipios_comercializadores_2 MUN, comercializadores_2 C
                                                      $busqueda_factura
                                                       AND FC.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND FC.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                       AND DEPT.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                       AND MUN.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE");
        $count_factura = mysqli_num_rows($query_factura);
        $info_pagination = array();
        if ($count_factura > 0) {
            $paginacion_count = getPagination($count_factura);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_factura'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>