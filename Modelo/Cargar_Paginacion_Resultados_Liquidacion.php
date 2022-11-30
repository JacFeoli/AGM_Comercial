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
                $sw2 = 0;
                $or = "";
                $where = "";
                $myAnos = explode(', ', $_POST['id_ano_municipio']);
                $myMes = explode(', ', $_POST['id_mes_municipio']);
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
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (FE.ID_COD_MPIO = MV.ID_MUNICIPIO AND YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . $query_departamento . " " . $query_municipio . ") ";
                        $sw2 = 1;
                    } else {
                        $or = $or . " OR (FE.ID_COD_MPIO = MV.ID_MUNICIPIO AND YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . $query_departamento . " " . $query_municipio . ") ";
                    }
                }
                $query_select_info_municipio = mysqli_query($connection, "SELECT * "
                                                                       . "  FROM facturacion_especiales_2 FE, municipios_visitas_2 MV "
                                                                       . $where
                                                                       . $or
                                                                       . " ORDER BY FE.FECHA_FACTURA DESC ");
                $count_resultado_municipio = mysqli_num_rows($query_select_info_municipio);
                $info_pagination = array();
                if ($count_resultado_municipio > 0) {
                    $paginacion_count = getPagination($count_resultado_municipio);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '1':
                require_once('../Includes/Paginacion_Resultado_Usuario_Bitacora.php');
                $sw2 = 0;
                $or = "";
                $where = "";
                $myAnos = explode(', ', $_POST['id_ano_contribuyente']);
                $myMes = explode(', ', $_POST['id_mes_contribuyente']);
                $contribuyente = $_POST['contribuyente'];
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . " AND FE.ID_CONTRIBUYENTE = " . $contribuyente . ") ";
                        $sw2 = 1;
                    } else {
                        $or = $or . " OR (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . " AND FE.ID_CONTRIBUYENTE = " . $contribuyente . ") ";
                    }
                }
                $query_select_info_contribuyente = mysqli_query($connection, "SELECT * "
                                                                           . "  FROM facturacion_especiales_2 FE "
                                                                           . $where
                                                                           . $or
                                                                           . " ORDER BY FE.FECHA_FACTURA DESC ");
                $count_resultado_contribuyente = mysqli_num_rows($query_select_info_contribuyente);
                $info_pagination = array();
                if ($count_resultado_contribuyente > 0) {
                    $paginacion_count = getPagination($count_resultado_contribuyente);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '2':
                
                break;
            case '3':
                
                break;
            case '4':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $sw2 = 0;
                $or = "";
                $where = "";
                $myAnos = explode(', ', $_POST['id_ano_mensual']);
                $myMes = explode(', ', $_POST['id_mes_mensual']);
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . ") ";
                        $sw2 = 1;
                    } else {
                        $or = $or . " OR (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . ") ";
                    }
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT * "
                                                                     . "  FROM facturacion_especiales_2 FE "
                                                                     . $where
                                                                     . $or
                                                                     . " ORDER BY FE.FECHA_FACTURA DESC ");
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
                                                                        . "    CONT.NOMBRE AS CONTRIBUYENTE, "
                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FE.CONSECUTIVO_FACT AS FACTURA, "
                                                                        . "    FE.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FE.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FE.PERIODO_FACTURA AS PERIODO "
                                                                        . "  FROM facturacion_especiales_2 FE, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV, "
                                                                        . "       contribuyentes_2 CONT, "
                                                                        . "       usuarios_2 USU "
                                                                        . " WHERE FE.ID_CONTRIBUYENTE = CONT.ID_CONTRIBUYENTE "
                                                                        . "   AND FE.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FE.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        . "   AND FE.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND FE.FECHA_FACTURA BETWEEN '" . $fecha_inicio . "' AND '$fecha_fin' "
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FE.FECHA_FACTURA DESC ");
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