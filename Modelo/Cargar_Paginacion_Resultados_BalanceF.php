<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        $sw = $_POST['sw'];
        switch ($sw) {
            case '4':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $id_departamento_historico = $_POST['id_departamento_historico'];
                $id_municipio_historico = $_POST['id_municipio_historico'];
                /*$query_select_info_historico = mysqli_query($connection, "SELECT FC.ID_COD_DPTO, DV.NOMBRE AS DEPARTAMENTO, "
                                                                       . "       FC.ID_COD_MPIO, MV.NOMBRE AS MUNICIPIO, "
                                                                       . "       FC.PERIODO_FACTURA, SUM(FC.VALOR_FACTURA) "
                                                                       . "  FROM facturacion_comercializadores_2 FC, "
                                                                       . "       departamentos_visitas_2 DV, "
                                                                       . "       municipios_visitas_2 MV "
                                                                       . " WHERE FC.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                       . "   AND FC.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                       . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                       . "   AND FC.ID_COD_DPTO = $id_departamento_historico "
                                                                       . "   AND FC.ID_COD_MPIO = $id_municipio_historico "
                                                                       . " GROUP BY FC.PERIODO_FACTURA "
                                                                       . "HAVING COUNT(FC.PERIODO_FACTURA) >= 1 "
                                                                       . " ORDER BY FC.PERIODO_FACTURA ");*/
                $query_select_periodos_historico = mysqli_query($connection, "SELECT DISTINCT(SUBSTR(PERIODO_FACTURA, 1, 4)) AS PERIODO_FACTURA "
                                                                           . "  FROM facturacion_especiales_2 "
                                                                           . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                           . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                           . " UNION "
                                                                           . "SELECT DISTINCT(SUBSTR(PERIODO_FACTURA, 1, 4)) AS PERIODO_FACTURA "
                                                                           . "  FROM facturacion_municipales_2 "
                                                                           . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                           . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                           . " UNION "
                                                                           . "SELECT DISTINCT(SUBSTR(PERIODO_FACTURA, 1, 4)) AS PERIODO_FACTURA "
                                                                           . "  FROM facturacion_comercializadores_2 "
                                                                           . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                           . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                           . " ORDER BY PERIODO_FACTURA");
                $count_resultado_periodos_historico = mysqli_num_rows($query_select_periodos_historico);
                $info_pagination = array();
                if ($count_resultado_periodos_historico > 0) {
                    $paginacion_count = getPagination($count_resultado_periodos_historico);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
        }
        echo json_encode($info_pagination);
        exit();
    }
?>