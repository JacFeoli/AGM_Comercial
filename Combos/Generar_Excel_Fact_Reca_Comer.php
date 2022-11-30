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
                if (strlen($_GET['id_mes']) == 1) {
                    $filename = "Reporte Comercializadores - Periodo " . $_GET['id_ano'] . "0" . $_GET['id_mes'] . ".xls";
                } else {
                    $filename = "Reporte Comercializadores - Periodo " . $_GET['id_ano'] . $_GET['id_mes'] . ".xls";
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FC.ID_COMERCIALIZADOR AS ID_COMERCIALIZADOR, "
                                                                        . "    FC.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FC.PERIODO_FACTURA AS PERIODO, "
                                                                        . "    FC.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FC.AJUSTE_FACT AS AJUSTE_FACT, "
                                                                        . "    FC.VALOR_RECAUDO AS VALOR_RECAUDO, "
                                                                        . "    FC.AJUSTE_RECA AS AJUSTE_RECA, "
                                                                        . "    FC.VALOR_ENERGIA AS VALOR_ENERGIA, "
                                                                        . "    FC.CUOTA_ENERGIA AS CUOTA_ENERGIA, "
                                                                        . "    FC.OTROS_AJUSTES AS OTROS_AJUSTES, "
                                                                        . "    FC.VALOR_FAVOR AS VALOR_FAVOR, "
                                                                        . "    FC.CONSUMO AS CONSUMO, "
                                                                        . "    FC.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                        . "    FC.ID_FACTURACION AS ID_FACTURACION "
                                                                        . "  FROM facturacion_comercializadores_2 FC, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV, "
                                                                        . "       usuarios_2 USU "
                                                                        . " WHERE FC.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FC.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        . "   AND FC.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND YEAR(FC.FECHA_FACTURA) = " . $_GET['id_ano'] . ""
                                                                        . "   AND MONTH(FC.FECHA_FACTURA) = " . $_GET['id_mes'] . ""
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FC.FECHA_FACTURA DESC ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>COMERCIALIZADOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>AJUSTES FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR RECA.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>AJUSTES RECA.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR ENER.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CUOTA ENER.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OTROS AJUSTES</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR FAVOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CONSUMO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. BITACORA</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                    while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                            $estado = "";
                            $recaudo = 0;
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['MUNICIPIO'] . "</td>";
                                $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = '" . $row_info_mensual['ID_COMERCIALIZADOR'] . "'");
                                $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FECHA_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['PERIODO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['AJUSTE_FACT'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['AJUSTE_RECA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_ENERGIA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['CUOTA_ENERGIA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['OTROS_AJUSTES'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_FAVOR'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . number_format($row_info_mensual['CONSUMO'], 0, ',', '.') . "</td>";
                                switch ($row_info_mensual['ESTADO_FACTURA']) {
                                    case "1":
                                        $table = $table . "<td style='vertical-align:middle;'>PAGADA</td>";
                                        break;
                                    case "2":
                                        $table = $table . "<td style='vertical-align:middle;'>PENDIENTE ENVIO</td>";
                                        break;
                                }
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 "
                                                                                     . " WHERE ID_FACTURACION = '" . $row_info_mensual['ID_FACTURACION'] . "'");
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "PAGADA";
                                            break;
                                        case "2":
                                            $estado = "PENDIENTE ENVIO";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                } else {
                                    $estado = "";
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                }
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                header("Content-Type: application/xls");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $table;
                break;
            case '5':
                $filename = "Reporte Comercializadores - Rango " . $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin'] . ".xls";
                $query_select_info_rango = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FC.ID_COMERCIALIZADOR AS ID_COMERCIALIZADOR, "
                                                                        . "    FC.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FC.PERIODO_FACTURA AS PERIODO, "
                                                                        . "    FC.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FC.AJUSTE_FACT AS AJUSTE_FACT, "
                                                                        . "    FC.VALOR_RECAUDO AS VALOR_RECAUDO, "
                                                                        . "    FC.AJUSTE_RECA AS AJUSTE_RECA, "
                                                                        . "    FC.VALOR_ENERGIA AS VALOR_ENERGIA, "
                                                                        . "    FC.CUOTA_ENERGIA AS CUOTA_ENERGIA, "
                                                                        . "    FC.OTROS_AJUSTES AS OTROS_AJUSTES, "
                                                                        . "    FC.VALOR_FAVOR AS VALOR_FAVOR, "
                                                                        . "    FC.CONSUMO AS CONSUMO, "
                                                                        . "    FC.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                        . "    FC.ID_FACTURACION AS ID_FACTURACION "
                                                                        . "  FROM facturacion_comercializadores_2 FC, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV, "
                                                                        . "       usuarios_2 USU "
                                                                        . " WHERE FC.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FC.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        . "   AND FC.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND FC.FECHA_FACTURA BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "' "
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FC.FECHA_FACTURA DESC ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>COMERCIALIZADOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>AJUSTES FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR RECA.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>AJUSTES RECA.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR ENER.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CUOTA ENER.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OTROS AJUSTES</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR FAVOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CONSUMO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. BITACORA</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                            $estado = "";
                            $recaudo = 0;
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['MUNICIPIO'] . "</td>";
                                $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = '" . $row_info_rango['ID_COMERCIALIZADOR'] . "'");
                                $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['PERIODO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['AJUSTE_FACT'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['AJUSTE_RECA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_ENERGIA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['CUOTA_ENERGIA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['OTROS_AJUSTES'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_FAVOR'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . number_format($row_info_rango['CONSUMO'], 0, ',', '.') . "</td>";
                                switch ($row_info_rango['ESTADO_FACTURA']) {
                                    case "1":
                                        $table = $table . "<td style='vertical-align:middle;'>PAGADA</td>";
                                        break;
                                    case "2":
                                        $table = $table . "<td style='vertical-align:middle;'>PENDIENTE ENVIO</td>";
                                        break;
                                }
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 "
                                                                                     . " WHERE ID_FACTURACION = '" . $row_info_rango['ID_FACTURACION'] . "'");
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "PAGADA";
                                            break;
                                        case "2":
                                            $estado = "PENDIENTE ENVIO";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                } else {
                                    $estado = "";
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
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