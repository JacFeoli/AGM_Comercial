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
                $query_select_info_municipio = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, MV.NOMBRE AS MUNICIPIO "
                                                                       . "  FROM departamentos_visitas_2 DV, municipios_visitas_2 MV "
                                                                       . " WHERE DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                       . "   AND MV.ID_DEPARTAMENTO = $id_departamento_historico "
                                                                       . "   AND MV.ID_MUNICIPIO = $id_municipio_historico ");
                $row_info_municipio = mysqli_fetch_array($query_select_info_municipio);
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_MENSUAL * ($page - 1);
                }
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
                                                                       . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_MENSUAL);*/
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
                                                                           . " ORDER BY PERIODO_FACTURA "
                                                                           . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_MENSUAL);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold;'>" . $row_info_municipio['DEPARTAMENTO'] . " - " . $row_info_municipio['MUNICIPIO'] . "&nbsp; <a onClick='generarExcelHistorico(" . $sw . ", " . $id_departamento_historico . ", " . $id_municipio_historico . ")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=5%>PERIODO</th>";
                            $table = $table . "<th width=15%>INGRESOS</th>";
                            $table = $table . "<th width=15%>EGRESOS</th>";
                            $table = $table . "<th width=15%>DIFERENCIA</th>";
                            //$table = $table . "<th width=10%>F. COM.</th>";
                            //$table = $table . "<th width=9%>R. COM.</th>";
                            //$table = $table . "<th width=10%>F. OPER.</th>";
                            //$table = $table . "<th width=9%>R. OPER.</th>";
                            //$table = $table . "<th width=10%>F. APOR.</th>";
                            //$table = $table . "<th width=9%>R. APOR.</th>";
                            //$table = $table . "<th width=10%>F. CLI.</th>";
                            //$table = $table . "<th width=9%>R. CLI.</th>";
                            //$table = $table . "<th width=10%>F. OYM.</th>";
                            //$table = $table . "<th width=9%>R. OYM.</th>";
                            //$table = $table . "<th width=10%>F. RI.</th>";
                            //$table = $table . "<th width=9%>R. RI.</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_periodos_historico = mysqli_fetch_assoc($query_select_periodos_historico)) {
                            $valor_reca_comercializadores = 0;
                            $valor_reca_operadores = 0;
                            $valor_reca_aportes = 0;
                            $valor_reca_especiales = 0;
                            $total_ingresos = 0;
                            $total_egresos = 0;
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_periodos_historico['PERIODO_FACTURA'] . "</td>";
                                //FACTURACION COMERCIALIZADORES
                                $query_select_fact_comercializadores = mysqli_query($connection, "SELECT SUM(FC.VALOR_FACTURA) "
                                                                                               . "  FROM facturacion_comercializadores_2 FC "
                                                                                               . " WHERE FC.ID_COD_DPTO = $id_departamento_historico "
                                                                                               . "   AND FC.ID_COD_MPIO = $id_municipio_historico "
                                                                                               . "   AND SUBSTR(FC.PERIODO_FACTURA, 1, 4) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 0, 4) . " ");
                                if (mysqli_num_rows($query_select_fact_comercializadores) != "") {
                                    $row_fact_comercializadores = mysqli_fetch_array($query_select_fact_comercializadores);
                                    $total_ingresos = $total_ingresos + $row_fact_comercializadores['SUM(FC.VALOR_FACTURA)'];
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_fact_comercializadores['SUM(FC.VALOR_FACTURA)'], 0, ',', '.') . "</td>";
                                } //else {
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> 0</td>";
                                //}
                                //RECAUDO COMERCIALIZADORES
                                /*$query_select_id_fact_comercializadores = mysqli_query($connection, "SELECT FC.ID_FACTURACION "
                                                                                                  . "  FROM facturacion_comercializadores_2 FC "
                                                                                                  . " WHERE FC.ID_COD_DPTO = $id_departamento_historico "
                                                                                                  . "   AND FC.ID_COD_MPIO = $id_municipio_historico "
                                                                                                  . "   AND FC.PERIODO_FACTURA = " . $row_info_periodos_historico['PERIODO_FACTURA'] . " ");
                                while ($row_id_fact_comercializadores = mysqli_fetch_assoc($query_select_id_fact_comercializadores)) {
                                    $query_select_reca_comercializadores = mysqli_query($connection, "SELECT RC.VALOR_RECAUDO "
                                                                                                   . "  FROM recaudo_comercializadores_2 RC "
                                                                                                   . " WHERE RC.ID_FACTURACION = " . $row_id_fact_comercializadores['ID_FACTURACION'] . " ");
                                    $row_reca_comercializadores = mysqli_fetch_array($query_select_reca_comercializadores);
                                    $valor_reca_comercializadores = $valor_reca_comercializadores + $row_reca_comercializadores['VALOR_RECAUDO'];
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($valor_reca_comercializadores, 0, ',', '.') . "</td>";*/
                                //FACTURACION OPERADORES
                                $query_select_fact_operadores = mysqli_query($connection, "SELECT SUM(FO.VALOR_FACTURA) "
                                                                                        . "  FROM facturacion_operadores_2 FO "
                                                                                        . " WHERE FO.ID_COD_DPTO = $id_departamento_historico "
                                                                                        . "   AND FO.ID_COD_MPIO = $id_municipio_historico "
                                                                                        . "   AND SUBSTR(FO.PERIODO_FACTURA, 1, 4) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 0, 4) . " ");
                                if (mysqli_num_rows($query_select_fact_operadores) != "") {
                                    $row_fact_operadores = mysqli_fetch_array($query_select_fact_operadores);
                                    $total_ingresos = $total_ingresos + $row_fact_operadores['SUM(FO.VALOR_FACTURA)'];
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_fact_operadores['SUM(FO.VALOR_FACTURA)'], 0, ',', '.') . "</td>";
                                } //else {
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> 0</td>";
                                //}
                                //RECAUDO OPERADORES
                                /*$query_select_id_fact_operadores = mysqli_query($connection, "SELECT FO.ID_FACTURACION "
                                                                                           . "  FROM facturacion_operadores_2 FO "
                                                                                           . " WHERE FO.ID_COD_DPTO = $id_departamento_historico "
                                                                                           . "   AND FO.ID_COD_MPIO = $id_municipio_historico "
                                                                                           . "   AND FO.PERIODO_FACTURA = " . $row_info_periodos_historico['PERIODO_FACTURA'] . " ");
                                while ($row_id_fact_operadores = mysqli_fetch_assoc($query_select_id_fact_operadores)) {
                                    $query_select_reca_operadores = mysqli_query($connection, "SELECT RO.VALOR_RECAUDO "
                                                                                            . "  FROM recaudo_operadores_2 RO "
                                                                                            . " WHERE RO.ID_FACTURACION = " . $row_id_fact_operadores['ID_FACTURACION'] . " ");
                                    $row_reca_operadores = mysqli_fetch_array($query_select_reca_operadores);
                                    $valor_reca_operadores = $valor_reca_operadores + $row_reca_operadores['VALOR_RECAUDO'];
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($valor_reca_operadores, 0, ',', '.') . "</td>";*/
                                //FACTURACION APORTES MUNICIPALES
                                $query_select_fact_aportes = mysqli_query($connection, "SELECT SUM(FM.VALOR_FACTURA) "
                                                                                     . "  FROM facturacion_municipales_2 FM "
                                                                                     . " WHERE FM.ID_COD_DPTO = $id_departamento_historico "
                                                                                     . "   AND FM.ID_COD_MPIO = $id_municipio_historico "
                                                                                     . "   AND SUBSTR(FM.PERIODO_FACTURA, 1, 4) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 0, 4) . " ");
                                if (mysqli_num_rows($query_select_fact_aportes) != "") {
                                    $row_fact_aportes = mysqli_fetch_array($query_select_fact_aportes);
                                    $total_ingresos = $total_ingresos + $row_fact_aportes['SUM(FM.VALOR_FACTURA)'];
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_fact_aportes['SUM(FM.VALOR_FACTURA)'], 0, ',', '.') . "</td>";
                                } //else {
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> 0</td>";
                                //}
                                //RECAUDO APORTES MUNICIPALES
                                /*$query_select_id_fact_aportes = mysqli_query($connection, "SELECT FM.ID_FACTURACION "
                                                                                        . "  FROM facturacion_municipales_2 FM "
                                                                                        . " WHERE FM.ID_COD_DPTO = $id_departamento_historico "
                                                                                        . "   AND FM.ID_COD_MPIO = $id_municipio_historico "
                                                                                        . "   AND FM.PERIODO_FACTURA = " . $row_info_periodos_historico['PERIODO_FACTURA'] . " ");
                                while ($row_id_fact_aportes = mysqli_fetch_assoc($query_select_id_fact_aportes)) {
                                    $query_select_reca_aportes = mysqli_query($connection, "SELECT RM.VALOR_RECAUDO "
                                                                                         . "  FROM recaudo_municipales_2 RM "
                                                                                         . " WHERE RM.ID_FACTURACION = " . $row_id_fact_aportes['ID_FACTURACION'] . " ");
                                    $row_reca_aportes = mysqli_fetch_array($query_select_reca_aportes);
                                    $valor_reca_aportes = $valor_reca_aportes + $row_reca_aportes['VALOR_RECAUDO'];
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($valor_reca_aportes, 0, ',', '.') . "</td>";*/
                                //FACTURACION CLIENTES ESPECIALES
                                /*$query_select_fact_especiales = mysqli_query($connection, "SELECT SUM(FE.VALOR_FACTURA) "
                                                                                     . "  FROM facturacion_especiales_2 FE "
                                                                                     . " WHERE FE.ID_COD_DPTO = $id_departamento_historico "
                                                                                     . "   AND FE.ID_COD_MPIO = $id_municipio_historico "
                                                                                     . "   AND FE.PERIODO_FACTURA = " . $row_info_periodos_historico['PERIODO_FACTURA'] . " ");
                                if (mysqli_num_rows($query_select_fact_especiales) != "") {
                                    $row_fact_especiales = mysqli_fetch_array($query_select_fact_especiales);
                                    $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_fact_especiales['SUM(FE.VALOR_FACTURA)'], 0, ',', '.') . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> 0</td>";
                                }
                                //RECAUDO CLIENTES ESPECIALES
                                $query_select_id_fact_especiales = mysqli_query($connection, "SELECT FE.ID_FACTURACION "
                                                                                           . "  FROM facturacion_especiales_2 FE "
                                                                                           . " WHERE FE.ID_COD_DPTO = $id_departamento_historico "
                                                                                           . "   AND FE.ID_COD_MPIO = $id_municipio_historico "
                                                                                           . "   AND FE.PERIODO_FACTURA = " . $row_info_periodos_historico['PERIODO_FACTURA'] . " ");
                                while ($row_id_fact_especiales = mysqli_fetch_assoc($query_select_id_fact_especiales)) {
                                    $query_select_reca_especiales = mysqli_query($connection, "SELECT RE.VALOR_RECAUDO "
                                                                                            . "  FROM recaudo_especiales_2 RE "
                                                                                            . " WHERE RE.ID_FACTURACION = " . $row_id_fact_especiales['ID_FACTURACION'] . " ");
                                    $row_reca_especiales = mysqli_fetch_array($query_select_reca_especiales);
                                    $valor_reca_especiales = $valor_reca_especiales + $row_reca_especiales['VALOR_RECAUDO'];
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($valor_reca_especiales, 0, ',', '.') . "</td>";*/
                                //FACTURACION OYM
                                $query_select_fact_oym = mysqli_query($connection, "SELECT SUM(FO.VALOR_NETO_LOCAL) "
                                                                                     . "  FROM facturacion_oymri_2021_2 FO "
                                                                                     . " WHERE FO.ID_COD_DPTO = $id_departamento_historico "
                                                                                     . "   AND FO.ID_COD_MPIO = $id_municipio_historico "
                                                                                     . "   AND YEAR(FO.PERIODO) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 0, 4) . " "
                                                                                     //. "   AND MONTH(FO.PERIODO) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 4, 2) . " "
                                                                                     . "   AND FO.ID_CONCEPTO = 1 ");
                                if (mysqli_num_rows($query_select_fact_oym) != "") {
                                    $row_fact_oym = mysqli_fetch_array($query_select_fact_oym);
                                    $total_egresos = $total_egresos + $row_fact_oym['SUM(FO.VALOR_NETO_LOCAL)'];
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_fact_oym['SUM(FO.VALOR_NETO_LOCAL)'], 0, ',', '.') . "</td>";
                                } //else {
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> 0</td>";
                                //}
                                //FACTURACION RI
                                $query_select_fact_ri = mysqli_query($connection, "SELECT SUM(FO.VALOR_NETO_LOCAL) "
                                                                                     . "  FROM facturacion_oymri_2021_2 FO "
                                                                                     . " WHERE FO.ID_COD_DPTO = $id_departamento_historico "
                                                                                     . "   AND FO.ID_COD_MPIO = $id_municipio_historico "
                                                                                     . "   AND YEAR(FO.PERIODO) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 0, 4) . " "
                                                                                     //. "   AND MONTH(FO.PERIODO) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 4, 2) . " "
                                                                                     . "   AND FO.ID_CONCEPTO = 2 ");
                                if (mysqli_num_rows($query_select_fact_ri) != "") {
                                    $row_fact_ri = mysqli_fetch_array($query_select_fact_ri);
                                    $total_egresos = $total_egresos + $row_fact_ri['SUM(FO.VALOR_NETO_LOCAL)'];
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_fact_ri['SUM(FO.VALOR_NETO_LOCAL)'], 0, ',', '.') . "</td>";
                                } //else {
                                    //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> 0</td>";
                                //}
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($total_ingresos, 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($total_egresos, 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($total_ingresos - $total_egresos, 0, ',', '.') . "</td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                break;
        }
        echo $table;
    }
?>