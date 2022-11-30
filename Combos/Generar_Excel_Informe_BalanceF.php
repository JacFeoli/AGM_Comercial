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
        $sw = $_GET['sw'];
        switch ($sw) {
            case '4':
                $id_departamento_historico = $_GET['id_departamento'];
                $id_municipio_historico = $_GET['id_municipio'];
                $query_select_info_municipio = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, MV.NOMBRE AS MUNICIPIO "
                                                                       . "  FROM departamentos_visitas_2 DV, municipios_visitas_2 MV "
                                                                       . " WHERE DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                       . "   AND MV.ID_DEPARTAMENTO = $id_departamento_historico "
                                                                       . "   AND MV.ID_MUNICIPIO = $id_municipio_historico ");
                $row_info_municipio = mysqli_fetch_array($query_select_info_municipio);
                $filename = "Informe Histórico Balance de Flujo. " . $row_info_municipio['DEPARTAMENTO'] . " - " .  $row_info_municipio['MUNICIPIO'] . ".xls";
                $query_select_periodos_historico = mysqli_query($connection, "SELECT DISTINCT(PERIODO_FACTURA) "
                                                                           . "  FROM facturacion_especiales_2 "
                                                                           . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                           . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                           . " UNION "
                                                                           . "SELECT DISTINCT(PERIODO_FACTURA) "
                                                                           . "  FROM facturacion_municipales_2 "
                                                                           . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                           . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                           . " UNION "
                                                                           . "SELECT DISTINCT(PERIODO_FACTURA) "
                                                                           . "  FROM facturacion_comercializadores_2 "
                                                                           . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                           . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                           . " UNION "
                                                                           . "SELECT DISTINCT(CONCAT(SUBSTR(PERIODO, 1, 4), SUBSTR(PERIODO, 6, 2))) AS PERIODO_FACTURA "
                                                                           . "  FROM facturacion_oymri_2021_2 "
                                                                           . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                           . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                           . " ORDER BY PERIODO_FACTURA ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ANO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MES</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACT. COMERCIALIZADORES</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>RECA. COMERCIALIZADORES</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>EFICIENCIA RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARTERA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIAS PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TARIFA KWH - PESOS</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TARIFA - MEDIDOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CONSUMO KWH - MES</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CONSUMO KWH - DIA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CONSUMO - MEDIDOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OTROS</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>COSTO ENERGIA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACT. APORTES MUNC.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>RECA. APORTES MUNC.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACT. CLIENTES ESP.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>RECA. CLIENTES ESP.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACT. OYM</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACT. RI</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                    while ($row_info_periodos_historico = mysqli_fetch_assoc($query_select_periodos_historico)) {
                            $valor_fact_comercializadores = 0;
                            $valor_reca_comercializadores = 0;
                            $valor_reca_aportes = 0;
                            $valor_reca_especiales = 0;
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_periodos_historico['PERIODO_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_info_periodos_historico['PERIODO_FACTURA'], 0, 4) . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_info_periodos_historico['PERIODO_FACTURA'], 4, 2) . "</td>";
                                //FACTURACION COMERCIALIZADORES
                                $query_select_fact_comercializadores = mysqli_query($connection, "SELECT SUM(FC.VALOR_FACTURA) "
                                                                                               . "  FROM facturacion_comercializadores_2 FC "
                                                                                               . " WHERE FC.ID_COD_DPTO = $id_departamento_historico "
                                                                                               . "   AND FC.ID_COD_MPIO = $id_municipio_historico "
                                                                                               . "   AND FC.PERIODO_FACTURA = " . $row_info_periodos_historico['PERIODO_FACTURA'] . " ");
                                if (mysqli_num_rows($query_select_fact_comercializadores) != "") {
                                    $row_fact_comercializadores = mysqli_fetch_array($query_select_fact_comercializadores);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_comercializadores['SUM(FC.VALOR_FACTURA)'], 0, ',', '.') . "</td>";
                                    $valor_fact_comercializadores = $valor_fact_comercializadores + $row_fact_comercializadores['SUM(FC.VALOR_FACTURA)'];
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>$ 0</td>";
                                }
                                //RECAUDO COMERCIALIZADORES
                                $query_select_id_fact_comercializadores = mysqli_query($connection, "SELECT FC.ID_FACTURACION "
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
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($valor_reca_comercializadores, 0, ',', '.') . "</td>";
                                if ($valor_fact_comercializadores != 0) {
                                    $table = $table . "<td style='vertical-align:middle;'>" . number_format(($valor_reca_comercializadores / $valor_fact_comercializadores) * 100, 0) . "%</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>0%</td>";
                                }
                                $table = $table . "<td style='vertical-align:middle;'>0</td>";
                                $d = cal_days_in_month(CAL_GREGORIAN, substr($row_info_periodos_historico['PERIODO_FACTURA'], 4, 2), substr($row_info_periodos_historico['PERIODO_FACTURA'], 0, 4));
                                $table = $table . "<td style='vertical-align:middle;'>" . $d . "</td>";
                                //FACTURACION OTROS AJUSTES - COSTO ENERGIA OPERADORES DE RED
                                $query_select_ajustes_energia = mysqli_query($connection, "SELECT SUM(FO.OTROS_AJUSTES), SUM(FO.VALOR_ENERGIA), SUM(FO.CONSUMO) "
                                                                                        . "  FROM facturacion_operadores_2 FO "
                                                                                        . " WHERE FO.ID_COD_DPTO = $id_departamento_historico "
                                                                                        . "   AND FO.ID_COD_MPIO = $id_municipio_historico "
                                                                                        . "   AND FO.PERIODO_FACTURA = " . $row_info_periodos_historico['PERIODO_FACTURA'] . " ");
                                $row_ajustes_energia = mysqli_fetch_array($query_select_ajustes_energia);
                                if ($row_ajustes_energia['SUM(FO.CONSUMO)'] != 0) {
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format(($row_ajustes_energia['SUM(FO.VALOR_ENERGIA)'] / $row_ajustes_energia['SUM(FO.CONSUMO)']), 0, ',', '.') . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>$ 0</td>";
                                }
                                $table = $table . "<td style='vertical-align:middle;'>0</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . number_format($row_ajustes_energia['SUM(FO.CONSUMO)'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . number_format(($row_ajustes_energia['SUM(FO.CONSUMO)'] / $d), 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>0</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_ajustes_energia['SUM(FO.OTROS_AJUSTES)'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_ajustes_energia['SUM(FO.VALOR_ENERGIA)'], 0, ',', '.') . "</td>";
                                //FACTURACION APORTES MUNICIPALES
                                $query_select_fact_aportes = mysqli_query($connection, "SELECT SUM(FM.VALOR_FACTURA) "
                                                                                     . "  FROM facturacion_municipales_2 FM "
                                                                                     . " WHERE FM.ID_COD_DPTO = $id_departamento_historico "
                                                                                     . "   AND FM.ID_COD_MPIO = $id_municipio_historico "
                                                                                     . "   AND FM.PERIODO_FACTURA = " . $row_info_periodos_historico['PERIODO_FACTURA'] . " ");
                                if (mysqli_num_rows($query_select_fact_aportes) != "") {
                                    $row_fact_aportes = mysqli_fetch_array($query_select_fact_aportes);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_aportes['SUM(FM.VALOR_FACTURA)'], 0, ',', '.') . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>$ 0</td>";
                                }
                                //RECAUDO APORTES MUNICIPALES
                                $query_select_id_fact_aportes = mysqli_query($connection, "SELECT FM.ID_FACTURACION "
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
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($valor_reca_aportes, 0, ',', '.') . "</td>";
                                //FACTURACION CLIENTES ESPECIALES
                                $query_select_fact_especiales = mysqli_query($connection, "SELECT SUM(FE.VALOR_FACTURA) "
                                                                                     . "  FROM facturacion_especiales_2 FE "
                                                                                     . " WHERE FE.ID_COD_DPTO = $id_departamento_historico "
                                                                                     . "   AND FE.ID_COD_MPIO = $id_municipio_historico "
                                                                                     . "   AND FE.PERIODO_FACTURA = " . $row_info_periodos_historico['PERIODO_FACTURA'] . " ");
                                if (mysqli_num_rows($query_select_fact_especiales) != "") {
                                    $row_fact_especiales = mysqli_fetch_array($query_select_fact_especiales);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_especiales['SUM(FE.VALOR_FACTURA)'], 0, ',', '.') . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>$ 0</td>";
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
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($valor_reca_especiales, 0, ',', '.') . "</td>";
                                //FACTURACION OYM
                                $query_select_fact_oym = mysqli_query($connection, "SELECT SUM(FO.VALOR_NETO_LOCAL) "
                                                                                     . "  FROM facturacion_oymri_2021_2 FO "
                                                                                     . " WHERE FO.ID_COD_DPTO = $id_departamento_historico "
                                                                                     . "   AND FO.ID_COD_MPIO = $id_municipio_historico "
                                                                                     . "   AND YEAR(FO.PERIODO) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 0, 4) . " "
                                                                                     . "   AND MONTH(FO.PERIODO) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 4, 2) . " "
                                                                                     . "   AND FO.ID_CONCEPTO = 1 ");
                                if (mysqli_num_rows($query_select_fact_oym) != "") {
                                    $row_fact_oym = mysqli_fetch_array($query_select_fact_oym);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_oym['SUM(FO.VALOR_NETO_LOCAL)'], 0, ',', '.') . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>$ 0</td>";
                                }
                                //FACTURACION RI
                                $query_select_fact_ri = mysqli_query($connection, "SELECT SUM(FO.VALOR_NETO_LOCAL) "
                                                                                     . "  FROM facturacion_oymri_2021_2 FO "
                                                                                     . " WHERE FO.ID_COD_DPTO = $id_departamento_historico "
                                                                                     . "   AND FO.ID_COD_MPIO = $id_municipio_historico "
                                                                                     . "   AND YEAR(FO.PERIODO) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 0, 4) . " "
                                                                                     . "   AND MONTH(FO.PERIODO) = " . substr($row_info_periodos_historico['PERIODO_FACTURA'], 4, 2) . " "
                                                                                     . "   AND FO.ID_CONCEPTO = 2 ");
                                if (mysqli_num_rows($query_select_fact_ri) != "") {
                                    $row_fact_ri = mysqli_fetch_array($query_select_fact_ri);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_ri['SUM(FO.VALOR_NETO_LOCAL)'], 0, ',', '.') . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>$ 0</td>";
                                }
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                header("Content-Type: application/xls");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $table;
                break;
        }
    }
?>