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
                $id_ano_mensual = $_POST['id_ano_mensual'];
                $id_mes_mensual = $_POST['id_mes_mensual'];
                $query_select_info_mensual = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FC.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FC.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FC.PERIODO_FACTURA AS PERIODO "
                                                                        . "  FROM facturacion_comercializadores_2 FC, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV, "
                                                                        . "       usuarios_2 USU "
                                                                        . " WHERE FC.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FC.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        . "   AND FC.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND YEAR(FC.FECHA_FACTURA) = " . $id_ano_mensual . ""
                                                                        . "   AND MONTH(FC.FECHA_FACTURA) = " . $id_mes_mensual . ""
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FC.FECHA_FACTURA DESC ");
                $count_resultado_mensual = mysqli_num_rows($query_select_info_mensual);
                $info_pagination = array();
                if ($count_resultado_mensual > 0) {
                    $paginacion_count = getPagination($count_resultado_mensual);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '5':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $fecha_inicio = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_fin'];
                $query_select_info_rango = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FC.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FC.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FC.PERIODO_FACTURA AS PERIODO "
                                                                        . "  FROM facturacion_comercializadores_2 FC, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV, "
                                                                        . "       usuarios_2 USU "
                                                                        . " WHERE FC.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FC.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        . "   AND FC.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND FC.FECHA_FACTURA BETWEEN '" . $fecha_inicio . "' AND '$fecha_fin' "
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FC.FECHA_FACTURA DESC ");
                $count_resultado_rango = mysqli_num_rows($query_select_info_rango);
                $info_pagination = array();
                if ($count_resultado_rango > 0) {
                    $paginacion_count = getPagination($count_resultado_rango);
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