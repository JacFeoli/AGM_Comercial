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
            case '1':
                if (strlen($_GET['id_mes']) == 1) {
                    $filename = "Reporte Bitacora Ingresos - Periodo " . $_GET['id_ano'] . "0" . $_GET['id_mes'] . ".xls";
                } else {
                    $filename = "Reporte Bitacora Ingresos - Periodo " . $_GET['id_ano'] . $_GET['id_mes'] . ".xls";
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT * "
                                                                     . "  FROM municipios_operadores_2 MO "
                                                                     . " ORDER BY MO.NOMBRE ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FUENTE DE RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CLIENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR INGRESO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIA RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MES RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ANO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIA INGRESO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MES INGRESO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ANO INGRESO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                            $query_select_fact_oper = mysqli_query($connection, "SELECT * "
                                                                              . "  FROM facturacion_operadores_2 "
                                                                              . " WHERE ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "' "
                                                                              . "   AND ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "' "
                                                                              . "   AND YEAR(FECHA_FACTURA) = " . $_GET['id_ano'] . " "
                                                                              . "   AND MONTH(FECHA_FACTURA) = " . $_GET['id_mes'] . " "
                                                                              . "   AND ID_OPERADOR = '" . $row_info_mensual['ID_OPERADOR'] . "'");
                            while ($row_fact_oper = mysqli_fetch_assoc($query_select_fact_oper)) {
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRE'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>OPERADOR DE RED</td>";
                                    $query_select_operador = mysqli_query($connection, "SELECT * FROM operadores_2 WHERE ID_OPERADOR = '" . $row_fact_oper['ID_OPERADOR'] . "'");
                                    $row_operador = mysqli_fetch_array($query_select_operador);
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_operador['NOMBRE'] . "</td>";
                                    $query_select_reca_oper = mysqli_query($connection, "SELECT * FROM recaudo_operadores_2 WHERE ID_FACTURACION = '" . $row_fact_oper['ID_FACTURACION'] . "' ");
                                    $row_reca_oper = mysqli_fetch_array($query_select_reca_oper);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_reca_oper['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_oper['FECHA_RECAUDO'], 8, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_oper['FECHA_RECAUDO'], 5, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_oper['FECHA_RECAUDO'], 0, 4) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_oper['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_oper['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_oper['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                $table = $table . "</tr>";
                            }
                            $query_select_fact_munc = mysqli_query($connection, "SELECT * "
                                                                              . "  FROM facturacion_municipales_2 "
                                                                              . " WHERE ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "' "
                                                                              . "   AND ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "' "
                                                                              . "   AND YEAR(FECHA_FACTURA) = " . $_GET['id_ano'] . " "
                                                                              . "   AND MONTH(FECHA_FACTURA) = " . $_GET['id_mes'] . "");
                            while ($row_fact_munc = mysqli_fetch_assoc($query_select_fact_munc)) {
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRE'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>APORTES MUNICIPALES</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                                    $query_select_reca_munc = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 WHERE ID_FACTURACION = '" . $row_fact_munc['ID_FACTURACION'] . "'");
                                    $row_reca_munc = mysqli_fetch_array($query_select_reca_munc);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_reca_munc['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_munc['FECHA_PAGO_MUNICIPIO'], 8, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_munc['FECHA_PAGO_MUNICIPIO'], 5, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_munc['FECHA_PAGO_MUNICIPIO'], 0, 4) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_munc['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_munc['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_munc['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                $table = $table . "</tr>";
                            }
                            $query_select_fact_esp = mysqli_query($connection, "SELECT * FROM facturacion_especiales_2 FE, contribuyentes_2 C "
                                                                             . " WHERE FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE "
                                                                             . "   AND FE.ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "' "
                                                                             . "   AND FE.ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "' "
                                                                             . "   AND YEAR(FE.FECHA_FACTURA) = " . $_GET['id_ano'] . " "
                                                                             . "   AND MONTH(FE.FECHA_FACTURA) = " . $_GET['id_mes'] . " ");
                            while ($row_fact_esp = mysqli_fetch_assoc($query_select_fact_esp)) {
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRE'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>CLIENTE ESPECIAL</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_esp['NOMBRE'] . "</td>";
                                    $query_select_reca_esp = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 WHERE ID_FACTURACION = '" . $row_fact_esp['ID_FACTURACION'] . "'");
                                    $row_reca_esp = mysqli_fetch_array($query_select_reca_esp);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_reca_esp['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_esp['FECHA_PAGO_SOPORTE'], 8, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_esp['FECHA_PAGO_SOPORTE'], 5, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_esp['FECHA_PAGO_SOPORTE'], 0, 4) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_esp['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_esp['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_esp['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                $table = $table . "</tr>";
                            }
                            $query_select_fact_comer = mysqli_query($connection, "SELECT * FROM facturacion_comercializadores_2 FC, comercializadores_2 C "
                                                                               . " WHERE FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR "
                                                                               . "   AND FC.ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "' "
                                                                               . "   AND FC.ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "' "
                                                                               . "   AND YEAR(FC.FECHA_FACTURA) = " . $_GET['id_ano'] . " "
                                                                               . "   AND MONTH(FC.FECHA_FACTURA) = " . $_GET['id_mes'] . " ");
                            while ($row_fact_comer = mysqli_fetch_assoc($query_select_fact_comer)) {
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRE'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>COMERCIALIZADOR</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_comer['NOMBRE'] . "</td>";
                                    $query_select_reca_comer = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 WHERE ID_FACTURACION = '" . $row_fact_comer['ID_FACTURACION'] . "'");
                                    $row_reca_comer = mysqli_fetch_array($query_select_reca_comer);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_reca_comer['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_comer['FECHA_RECAUDO'], 8, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_comer['FECHA_RECAUDO'], 5, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_comer['FECHA_RECAUDO'], 0, 4) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_comer['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_comer['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_reca_comer['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                $table = $table . "</tr>";
                            }
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                header("Content-Type: application/xls");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $table;
                break;
            case '4':
                if (strlen($_GET['id_mes']) == 1) {
                    $filename = "Reporte Bitacora Ingresos - Periodo " . $_GET['id_ano'] . "0" . $_GET['id_mes'] . ".xls";
                } else {
                    $filename = "Reporte Bitacora Ingresos - Periodo " . $_GET['id_ano'] . $_GET['id_mes'] . ".xls";
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT * "
                                                                     . "  FROM municipios_operadores_2 MO "
                                                                     . " ORDER BY MO.NOMBRE ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FUENTE DE RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CLIENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR INGRESO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIA RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MES RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ANO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIA INGRESO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MES INGRESO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ANO INGRESO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                            $sw_nombre = 0;
                            $sw_alcaldia = 0;
                            $sw_contribuyente = 0;
                            $sw_comercializadores = 0;
                            $contador_contribuyente = 1;
                            $contador_comercializadores = 1;
                            $total_contribuyente = 0;
                            $total_comercializadores = 0;
                            $estado = "";
                            $recaudo = 0;
                            $total_ingresos = 0;
                            $query_select_info_alcaldia = mysqli_query($connection, "SELECT * "
                                                                                  . "  FROM alcaldias_2 "
                                                                                  . " WHERE ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "' "
                                                                                  . "   AND ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "'");
                            $row_info_alcaldia = mysqli_fetch_array($query_select_info_alcaldia);
                            if ($row_info_alcaldia['VALOR_CONCEPTO'] != 0) {
                                $sw_alcaldia = 1;
                                $total_ingresos = $total_ingresos + 1;
                            }
                            $query_select_info_contribuyentes = mysqli_query($connection, "SELECT * "
                                                                                        . "  FROM contribuyentes_2 "
                                                                                        . " WHERE ID_DEPARTAMENTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "' "
                                                                                        . "   AND ID_MUNICIPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "'");
                            if (mysqli_num_rows($query_select_info_contribuyentes) != 0) {
                                while ($row_info_contribuyente = mysqli_fetch_assoc($query_select_info_contribuyentes)) {
                                    $sw_contribuyente = 1;
                                    $total_ingresos = $total_ingresos + 1;
                                    $total_contribuyente = $total_contribuyente + 1;
                                }
                            }
                            $query_select_info_comercializadores = mysqli_query($connection, "SELECT * "
                                                                                           . "  FROM municipios_comercializadores_2 "
                                                                                           . " WHERE ID_DEPARTAMENTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "' "
                                                                                           . "   AND ID_MUNICIPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "'");
                            if (mysqli_num_rows($query_select_info_comercializadores) != 0) {
                                while ($row_info_comercializadores = mysqli_fetch_assoc($query_select_info_comercializadores)) {
                                    $sw_comercializadores = 1;
                                    $total_ingresos = $total_ingresos + 1;
                                    $total_comercializadores = $total_comercializadores + 1;
                                }
                            }
                            if ($total_ingresos == 0) {
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRE'] . "</td>";
                                    $query_select_fact_oper = mysqli_query($connection, "SELECT RO.FECHA_RECAUDO, RO.VALOR_RECAUDO, RO.FECHA_PAGO_BITACORA, O.NOMBRE, RO.ID_FACTURACION 
                                                                                           FROM recaudo_operadores_2 RO, facturacion_operadores_2 FO, operadores_2 O
                                                                                          WHERE FO.ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "'
                                                                                            AND FO.ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "'
                                                                                            AND YEAR(RO.FECHA_PAGO_BITACORA) = " . $_GET['id_ano'] . "
                                                                                            AND MONTH(RO.FECHA_PAGO_BITACORA) = " . $_GET['id_mes'] . "
                                                                                            AND RO.ID_FACTURACION = FO.ID_FACTURACION
                                                                                            AND FO.ID_OPERADOR = O.ID_OPERADOR");
                                    $row_fact_oper = mysqli_fetch_array($query_select_fact_oper);
                                    if ($row_fact_oper['ID_FACTURACION'] != "") {
                                        $table = $table . "<td style='vertical-align:middle;'>OPERADOR DE RED</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_oper['NOMBRE'] . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_oper['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 8, 2) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 5, 2) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 0, 4) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                    }
                                $table = $table . "</tr>";
                            } else {
                                for ($i=1;$i<=$total_ingresos;$i++) {
                                    $gran_total = 0;
                                    if ($sw_nombre == 0) {
                                        $query_select_fact_oper = mysqli_query($connection, "SELECT RO.FECHA_RECAUDO, RO.VALOR_RECAUDO, RO.FECHA_PAGO_BITACORA, O.NOMBRE, RO.ID_FACTURACION 
                                                                                              FROM recaudo_operadores_2 RO, facturacion_operadores_2 FO, operadores_2 O
                                                                                             WHERE FO.ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "'
                                                                                               AND FO.ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "'
                                                                                               AND YEAR(RO.FECHA_PAGO_BITACORA) = " . $_GET['id_ano'] . "
                                                                                               AND MONTH(RO.FECHA_PAGO_BITACORA) = " . $_GET['id_mes'] . "
                                                                                               AND RO.ID_FACTURACION = FO.ID_FACTURACION
                                                                                               AND FO.ID_OPERADOR = O.ID_OPERADOR
                                                                                               AND O.ID_OPERADOR = '" . $row_info_mensual['ID_OPERADOR'] . "'");
                                        while ($row_fact_oper = mysqli_fetch_assoc($query_select_fact_oper)) {
                                            $table = $table . "<tr>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>OPERADOR DE RED</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_oper['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_oper['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 0, 4) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                                $gran_total = $gran_total + $row_fact_oper['VALOR_RECAUDO'];
                                            $table = $table . "</tr>";
                                        }
                                        $sw_nombre = 1;
                                    }
                                    if ($sw_alcaldia == 1) {
                                        $table = $table . "<tr>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRE'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>APORTES MUNICIPALES</td>";
                                            $query_select_fact_munc = mysqli_query($connection, "SELECT RM.VALOR_RECAUDO, RM.FECHA_PAGO_BITACORA, RM.FECHA_PAGO_MUNICIPIO
                                                                                                   FROM recaudo_municipales_2 RM, facturacion_municipales_2 FM
                                                                                                  WHERE FM.ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "'
                                                                                                    AND FM.ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "'
                                                                                                    AND YEAR(RM.FECHA_PAGO_BITACORA) = " . $_GET['id_ano'] . "
                                                                                                    AND MONTH(RM.FECHA_PAGO_BITACORA) = " . $_GET['id_mes'] . "
                                                                                                    AND RM.ID_FACTURACION = FM.ID_FACTURACION");
                                            $row_fact_munc = mysqli_fetch_array($query_select_fact_munc);
                                            $table = $table . "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_munc['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_MUNICIPIO'], 8, 2) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_MUNICIPIO'], 5, 2) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_MUNICIPIO'], 0, 4) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                            $gran_total = $gran_total + $row_fact_munc['VALOR_RECAUDO'];
                                            $sw_alcaldia = 2;
                                        $table = $table . "</tr>";
                                        continue;
                                    }
                                    if ($sw_contribuyente == 1) {
                                        if ($contador_contribuyente <= $total_contribuyente) {
                                            $limit_contribuyente = $contador_contribuyente - 1;
                                            $table = $table . "<tr>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>FACTURACION MUNICIPIO</td>";
                                                $query_select_fact_esp = mysqli_query($connection, "SELECT C.NOMBRE, RE.VALOR_RECAUDO, RE.FECHA_PAGO_SOPORTE, RE.FECHA_PAGO_BITACORA
                                                                                                      FROM recaudo_especiales_2 RE, facturacion_especiales_2 FE, contribuyentes_2 C
                                                                                                     WHERE FE.ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "'
                                                                                                       AND FE.ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "'
                                                                                                       AND YEAR(RE.FECHA_PAGO_BITACORA) = " . $_GET['id_ano'] . "
                                                                                                       AND MONTH(RE.FECHA_PAGO_BITACORA) = " . $_GET['id_mes'] . "
                                                                                                       AND RE.ID_FACTURACION = FE.ID_FACTURACION
                                                                                                       AND FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE
                                                                                                     LIMIT $limit_contribuyente, 1");
                                                $row_fact_esp = mysqli_fetch_array($query_select_fact_esp);
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_esp['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_esp['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_SOPORTE'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_SOPORTE'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_SOPORTE'], 0, 4) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                                $contador_contribuyente = $contador_contribuyente + 1;
                                                $gran_total = $gran_total + $row_fact_esp['VALOR_RECAUDO'];
                                            $table = $table . "</tr>";
                                            continue;
                                        } else {
                                            $sw_contribuyente = 2;
                                        }
                                    }
                                    if ($sw_comercializadores == 1) {
                                        if ($contador_comercializadores <= $total_comercializadores) {
                                            $limit_comercializadores = $contador_comercializadores - 1;
                                            $table = $table . "<tr>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>COMERCIALIZADOR</td>";
                                                $query_select_fact_comer = mysqli_query($connection, "SELECT C.NOMBRE, RC.VALOR_RECAUDO, RC.FECHA_RECAUDO, RC.FECHA_PAGO_BITACORA
                                                                                                        FROM recaudo_comercializadores_2 RC, facturacion_comercializadores_2 FC, comercializadores_2 C
                                                                                                       WHERE FC.ID_COD_DPTO = '" . $row_info_mensual['ID_DEPARTAMENTO'] . "'
                                                                                                         AND FC.ID_COD_MPIO = '" . $row_info_mensual['ID_MUNICIPIO'] . "'
                                                                                                         AND YEAR(RC.FECHA_PAGO_BITACORA) = " . $_GET['id_ano'] . "
                                                                                                         AND MONTH(RC.FECHA_PAGO_BITACORA) = " . $_GET['id_mes'] . "
                                                                                                         AND RC.ID_FACTURACION = FC.ID_FACTURACION
                                                                                                         AND FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                                                                       LIMIT $limit_comercializadores, 1");
                                                $row_fact_comer = mysqli_fetch_array($query_select_fact_comer);
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_comer['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_comer['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_RECAUDO'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_RECAUDO'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_RECAUDO'], 0, 4) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                                $contador_comercializadores = $contador_comercializadores + 1;
                                                $gran_total = $gran_total + $row_fact_comer['VALOR_RECAUDO'];
                                            $table = $table . "</tr>";
                                            continue;
                                        } else {
                                            $sw_comercializadores = 2;
                                        }
                                    }
                                }
                            }
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                header("Content-Type: application/xls");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $table;
                break;
            case '5':
                $filename = "Reporte Bitacora Ingresos - Rango " . $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin'] . ".xls";
                $query_select_info_rango = mysqli_query($connection, "SELECT * "
                                                                   . "  FROM municipios_operadores_2 MO "
                                                                   . " ORDER BY MO.NOMBRE ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FUENTE DE RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CLIENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR INGRESO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIA RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MES RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ANO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIA INGRESO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MES INGRESO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ANO INGRESO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                            $sw_nombre = 0;
                            $sw_alcaldia = 0;
                            $sw_contribuyente = 0;
                            $sw_comercializadores = 0;
                            $contador_contribuyente = 1;
                            $contador_comercializadores = 1;
                            $total_contribuyente = 0;
                            $total_comercializadores = 0;
                            $estado = "";
                            $recaudo = 0;
                            $total_ingresos = 0;
                            $query_select_info_alcaldia = mysqli_query($connection, "SELECT * "
                                                                                  . "  FROM alcaldias_2 "
                                                                                  . " WHERE ID_COD_DPTO = '" . $row_info_rango['ID_DEPARTAMENTO'] . "' "
                                                                                  . "   AND ID_COD_MPIO = '" . $row_info_rango['ID_MUNICIPIO'] . "'");
                            $row_info_alcaldia = mysqli_fetch_array($query_select_info_alcaldia);
                            if ($row_info_alcaldia['VALOR_CONCEPTO'] != 0) {
                                $sw_alcaldia = 1;
                                $total_ingresos = $total_ingresos + 1;
                            }
                            $query_select_info_contribuyentes = mysqli_query($connection, "SELECT * "
                                                                                        . "  FROM contribuyentes_2 "
                                                                                        . " WHERE ID_DEPARTAMENTO = '" . $row_info_rango['ID_DEPARTAMENTO'] . "' "
                                                                                        . "   AND ID_MUNICIPIO = '" . $row_info_rango['ID_MUNICIPIO'] . "'");
                            if (mysqli_num_rows($query_select_info_contribuyentes) != 0) {
                                while ($row_info_contribuyente = mysqli_fetch_assoc($query_select_info_contribuyentes)) {
                                    $sw_contribuyente = 1;
                                    $total_ingresos = $total_ingresos + 1;
                                    $total_contribuyente = $total_contribuyente + 1;
                                }
                            }
                            $query_select_info_comercializadores = mysqli_query($connection, "SELECT * "
                                                                                           . "  FROM municipios_comercializadores_2 "
                                                                                           . " WHERE ID_DEPARTAMENTO = '" . $row_info_rango['ID_DEPARTAMENTO'] . "' "
                                                                                           . "   AND ID_MUNICIPIO = '" . $row_info_rango['ID_MUNICIPIO'] . "'");
                            if (mysqli_num_rows($query_select_info_comercializadores) != 0) {
                                while ($row_info_comercializadores = mysqli_fetch_assoc($query_select_info_comercializadores)) {
                                    $sw_comercializadores = 1;
                                    $total_ingresos = $total_ingresos + 1;
                                    $total_comercializadores = $total_comercializadores + 1;
                                }
                            }
                            if ($total_ingresos == 0) {
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['NOMBRE'] . "</td>";
                                    $query_select_fact_oper = mysqli_query($connection, "SELECT RO.FECHA_RECAUDO, RO.VALOR_RECAUDO, RO.FECHA_PAGO_BITACORA, O.NOMBRE, RO.ID_FACTURACION 
                                                                                           FROM recaudo_operadores_2 RO, facturacion_operadores_2 FO, operadores_2 O
                                                                                          WHERE FO.ID_COD_DPTO = '" . $row_info_rango['ID_DEPARTAMENTO'] . "'
                                                                                            AND FO.ID_COD_MPIO = '" . $row_info_rango['ID_MUNICIPIO'] . "'
                                                                                            AND RO.FECHA_PAGO_BITACORA BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "'
                                                                                            AND RO.ID_FACTURACION = FO.ID_FACTURACION
                                                                                            AND FO.ID_OPERADOR = O.ID_OPERADOR");
                                    $row_fact_oper = mysqli_fetch_array($query_select_fact_oper);
                                    if ($row_fact_oper['ID_FACTURACION'] != "") {
                                        $table = $table . "<td style='vertical-align:middle;'>OPERADOR DE RED</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_oper['NOMBRE'] . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_oper['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 8, 2) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 5, 2) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 0, 4) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                        $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                    }
                                $table = $table . "</tr>";
                            } else {
                                for ($i=1;$i<=$total_ingresos;$i++) {
                                    $gran_total = 0;
                                    if ($sw_nombre == 0) {
                                        $table = $table . "<tr>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['NOMBRE'] . "</td>";
                                            $query_select_fact_oper = mysqli_query($connection, "SELECT RO.FECHA_RECAUDO, RO.VALOR_RECAUDO, RO.FECHA_PAGO_BITACORA, O.NOMBRE, RO.ID_FACTURACION 
                                                                                                   FROM recaudo_operadores_2 RO, facturacion_operadores_2 FO, operadores_2 O
                                                                                                  WHERE FO.ID_COD_DPTO = '" . $row_info_rango['ID_DEPARTAMENTO'] . "'
                                                                                                    AND FO.ID_COD_MPIO = '" . $row_info_rango['ID_MUNICIPIO'] . "'
                                                                                                    AND RO.FECHA_PAGO_BITACORA BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "'
                                                                                                    AND RO.ID_FACTURACION = FO.ID_FACTURACION
                                                                                                    AND FO.ID_OPERADOR = O.ID_OPERADOR");
                                            $row_fact_oper = mysqli_fetch_array($query_select_fact_oper);
                                            if ($row_fact_oper['ID_FACTURACION'] != "") {
                                                $table = $table . "<td style='vertical-align:middle;'>OPERADOR DE RED</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_oper['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_oper['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_RECAUDO'], 0, 4) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_oper['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                                $gran_total = $gran_total + $row_fact_oper['VALOR_RECAUDO'];
                                            }
                                        $table = $table . "</tr>";
                                        $sw_nombre = 1;
                                    }
                                    if ($sw_alcaldia == 1) {
                                        $table = $table . "<tr>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['NOMBRE'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>APORTES MUNICIPALES</td>";
                                            $query_select_fact_munc = mysqli_query($connection, "SELECT RM.VALOR_RECAUDO, RM.FECHA_PAGO_BITACORA, RM.FECHA_PAGO_MUNICIPIO
                                                                                                   FROM recaudo_municipales_2 RM, facturacion_municipales_2 FM
                                                                                                  WHERE FM.ID_COD_DPTO = '" . $row_info_rango['ID_DEPARTAMENTO'] . "'
                                                                                                    AND FM.ID_COD_MPIO = '" . $row_info_rango['ID_MUNICIPIO'] . "'
                                                                                                    AND RM.FECHA_PAGO_BITACORA BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "'
                                                                                                    AND RM.ID_FACTURACION = FM.ID_FACTURACION");
                                            $row_fact_munc = mysqli_fetch_array($query_select_fact_munc);
                                            $table = $table . "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_munc['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_MUNICIPIO'], 8, 2) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_MUNICIPIO'], 5, 2) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_MUNICIPIO'], 0, 4) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_munc['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                            $gran_total = $gran_total + $row_fact_munc['VALOR_RECAUDO'];
                                            $sw_alcaldia = 2;
                                        $table = $table . "</tr>";
                                        continue;
                                    }
                                    if ($sw_contribuyente == 1) {
                                        if ($contador_contribuyente <= $total_contribuyente) {
                                            $limit_contribuyente = $contador_contribuyente - 1;
                                            $table = $table . "<tr>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>FACTURACION MUNICIPIO</td>";
                                                $query_select_fact_esp = mysqli_query($connection, "SELECT C.NOMBRE, RE.VALOR_RECAUDO, RE.FECHA_PAGO_SOPORTE, RE.FECHA_PAGO_BITACORA
                                                                                                      FROM recaudo_especiales_2 RE, facturacion_especiales_2 FE, contribuyentes_2 C
                                                                                                     WHERE FE.ID_COD_DPTO = '" . $row_info_rango['ID_DEPARTAMENTO'] . "'
                                                                                                       AND FE.ID_COD_MPIO = '" . $row_info_rango['ID_MUNICIPIO'] . "'
                                                                                                       AND RE.FECHA_PAGO_BITACORA BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "'
                                                                                                       AND RE.ID_FACTURACION = FE.ID_FACTURACION
                                                                                                       AND FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE
                                                                                                     LIMIT $limit_contribuyente, 1");
                                                $row_fact_esp = mysqli_fetch_array($query_select_fact_esp);
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_esp['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_esp['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_SOPORTE'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_SOPORTE'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_SOPORTE'], 0, 4) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_esp['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                                $contador_contribuyente = $contador_contribuyente + 1;
                                                $gran_total = $gran_total + $row_fact_esp['VALOR_RECAUDO'];
                                            $table = $table . "</tr>";
                                            continue;
                                        } else {
                                            $sw_contribuyente = 2;
                                        }
                                    }
                                    if ($sw_comercializadores == 1) {
                                        if ($contador_comercializadores <= $total_comercializadores) {
                                            $limit_comercializadores = $contador_comercializadores - 1;
                                            $table = $table . "<tr>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>COMERCIALIZADOR</td>";
                                                $query_select_fact_comer = mysqli_query($connection, "SELECT C.NOMBRE, RC.VALOR_RECAUDO, RC.FECHA_RECAUDO, RC.FECHA_PAGO_BITACORA
                                                                                                        FROM recaudo_comercializadores_2 RC, facturacion_comercializadores_2 FC, comercializadores_2 C
                                                                                                       WHERE FC.ID_COD_DPTO = '" . $row_info_rango['ID_DEPARTAMENTO'] . "'
                                                                                                         AND FC.ID_COD_MPIO = '" . $row_info_rango['ID_MUNICIPIO'] . "'
                                                                                                         AND RC.FECHA_PAGO_BITACORA BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "'
                                                                                                         AND RC.ID_FACTURACION = FC.ID_FACTURACION
                                                                                                         AND FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                                                                       LIMIT $limit_comercializadores, 1");
                                                $row_fact_comer = mysqli_fetch_array($query_select_fact_comer);
                                                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_comer['NOMBRE'] . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_comer['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_RECAUDO'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_RECAUDO'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_RECAUDO'], 0, 4) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_fact_comer['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
                                                $contador_comercializadores = $contador_comercializadores + 1;
                                                $gran_total = $gran_total + $row_fact_comer['VALOR_RECAUDO'];
                                            $table = $table . "</tr>";
                                            continue;
                                        } else {
                                            $sw_comercializadores = 2;
                                        }
                                    }
                                }
                            }
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