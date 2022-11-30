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
            case '0':
                require_once('../Includes/Paginacion_Resultado_Municipio.php');
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                if ($departamento == "") {
                    $query_departamento = " ";
                } else {
                    $query_departamento = " AND MV.ID_DEPARTAMENTO = " . $departamento . " ";
                }
                if ($municipio == "") {
                    $query_municipio = "";
                } else {
                    $query_municipio = " AND MV.ID_MUNICIPIO = " . $municipio . " ";
                }
                $id_ano_municipio = $_POST['id_ano_municipio'];
                $id_mes_municipio = $_POST['id_mes_municipio'];
                $query_select_info_municipio = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                            . "    MV.NOMBRE AS MUNICIPIO, "
                                                                            //. "    USU.NOMBRE AS USUARIO, "
                                                                            . "    FM.CONSECUTIVO_FACT AS FACTURA, "
                                                                            . "    FM.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                            . "    FM.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                            . "    FM.PERIODO_FACTURA AS PERIODO "
                                                                            . "  FROM facturacion_municipales_2 FM, "
                                                                            . "       departamentos_visitas_2 DV, "
                                                                            . "       municipios_visitas_2 MV "
                                                                            //. "       usuarios_2 USU "
                                                                            . " WHERE FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                            . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                            . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                            //. "   AND FM.ID_USUARIO = USU.ID_USUARIO "
                                                                            . "   AND YEAR(FM.FECHA_FACTURA) = " . $id_ano_municipio . ""
                                                                            . "   AND MONTH(FM.FECHA_FACTURA) = " . $id_mes_municipio . ""
                                                                            . $query_departamento . " "
                                                                            . $query_municipio . " "
                                                                            . " ORDER BY DV.NOMBRE, MV.NOMBRE, FM.FECHA_FACTURA DESC ");
                $count_resultado_municipio = mysqli_num_rows($query_select_info_municipio);
                $info_pagination = array();
                if ($count_resultado_municipio > 0) {
                    $paginacion_count = getPagination($count_resultado_municipio);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '4':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $id_ano_mensual = $_POST['id_ano_mensual'];
                $id_mes_mensual = $_POST['id_mes_mensual'];
                $query_select_info_mensual = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        //. "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FM.CONSECUTIVO_FACT AS FACTURA, "
                                                                        . "    FM.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FM.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FM.PERIODO_FACTURA AS PERIODO "
                                                                        . "  FROM facturacion_municipales_2 FM, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV "
                                                                        //. "       usuarios_2 USU "
                                                                        . " WHERE FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        //. "   AND FM.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND YEAR(FM.FECHA_FACTURA) = " . $id_ano_mensual . ""
                                                                        . "   AND MONTH(FM.FECHA_FACTURA) = " . $id_mes_mensual . ""
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FM.FECHA_FACTURA DESC ");
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
                                                                        //. "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FM.CONSECUTIVO_FACT AS FACTURA, "
                                                                        . "    FM.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FM.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FM.PERIODO_FACTURA AS PERIODO "
                                                                        . "  FROM facturacion_municipales_2 FM, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV "
                                                                        //. "       usuarios_2 USU "
                                                                        . " WHERE FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        //. "   AND FM.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND FM.FECHA_FACTURA BETWEEN '" . $fecha_inicio . "' AND '$fecha_fin' "
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FM.FECHA_FACTURA DESC ");
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