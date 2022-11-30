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
                    $busqueda_factura = " WHERE C.NOMBRE LIKE '%" . $_POST['busqueda_factura'] . "%' ";
                } else {
                    $busqueda_factura = " WHERE C.NOMBRE <> ''";
                }
            } else {
                $busqueda_factura = " WHERE C.NOMBRE <> ''";
            }
            if ($_POST['estado_factura_busqueda'] == "") {
                $estado_factura = " AND FE.ESTADO_FACTURA IN (1, 2, 3)";
            } else {
                $estado_factura = " AND FE.ESTADO_FACTURA = " . $_POST['estado_factura_busqueda'];
            }
        } else {
            $busqueda_factura = " WHERE C.NOMBRE <> ''";
            $estado_factura = " AND FE.ESTADO_FACTURA IN (1, 2, 3)";
        }
        $query_factura = mysqli_query($connection, "SELECT *
                                                      FROM facturacion_especiales_2 FE,
                                                           departamentos_visitas_2 DEPT,
                                                           municipios_visitas_2 MUN, contribuyentes_2 C
                                                      $busqueda_factura
                                                      $estado_factura
                                                       AND FE.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND FE.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                       AND FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE
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