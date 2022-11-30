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
        $table = "";
        $info_historial = array();
        if (isset($_POST['detalle_id']) != 0) {
            switch ($_POST['hist']) {
                case 'periodo':
                    $sw = 0;
                    $detalle_id = $_POST['detalle_id'];
                    $periodo_factura = $_POST['periodo_factura'];
                    $query_select_max_periodo = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA), ID_MES_FACTURA, MES_FACTURA "
                                                                        . "  FROM archivos_cargados_facturacion_2 "
                                                                        . " WHERE ANO_FACTURA = " . substr($periodo_factura, 0, 4)
                                                                        . "   AND ID_MES_FACTURA = " . substr($periodo_factura, 4, 2)
                                                                        . " ORDER BY ANO_FACTURA DESC, ID_MES_FACTURA DESC");
                    while ($row_max_periodo = mysqli_fetch_assoc($query_select_max_periodo)) {
                        $bd_tabla_facturacion = "facturacion_" .  strtolower($row_max_periodo['MES_FACTURA']) . $row_max_periodo['ANO_FACTURA'] . "_2";
                        $bd_tabla_recaudo = "recaudo_" . strtolower($row_max_periodo['MES_FACTURA']) . $row_max_periodo['ANO_FACTURA'] . "_2";
                        $bd_tabla_cartera = "cartera_fallida_" . $row_max_periodo['ANO_FACTURA'] . "_2";
                        if ($sw == 0) {
                            $bd_tabla_catastro = "catastro_" . strtolower($row_max_periodo['MES_FACTURA']) . $row_max_periodo['ANO_FACTURA'] . "_2";
                            $query_select_info_catastro = mysqli_query($connection, "SELECT * FROM $bd_tabla_catastro WHERE NIC = " . $detalle_id);
                            if (mysqli_num_rows($query_select_info_catastro) != 0) {
                                $row_info_catastro = mysqli_fetch_array($query_select_info_catastro);
                                $propietario = "NIC: " . $row_info_catastro['NIC'] . " - Propietario: " . str_replace("Ñ", "N", utf8_decode($row_info_catastro['NOMBRE_PROPIETARIO']));
                                $sw = 1;
                            } else {
                                $propietario = "NIC: NO ENCONTRADO EN EL PERIODO SELECCIONADO.";
                            }
                        }
                        $query_select_facturacion_nic = mysqli_query($connection, "SELECT FACT.VALOR_RECIBO AS VALOR_FACTURA, FACT.FECHA_TRANS AS FECHA_FACTURA, "
                                                                                        . "RECA.VALOR_RECIBO AS VALOR_RECAUDO, RECA.FECHA_TRANS AS FECHA_RECAUDO, "
                                                                                        . "FACT.ANO_FACTURA, FACT.MES_FACTURA, FACT.COD_OPER_CONT, FACT.SEC_REC, "
                                                                                        . "FACT.SEC_NIS, FACT.FECHA_FACT_LECT AS FECHA_FACT_LECT "
                                                                                 . "  FROM $bd_tabla_facturacion FACT "
                                                                                 . "  LEFT JOIN $bd_tabla_recaudo RECA "
                                                                                 . "    ON FACT.NIC = RECA.NIC "
                                                                                 . "   AND FACT.VALOR_RECIBO = RECA.VALOR_RECIBO "
                                                                                 . "   AND FACT.SEC_REC = RECA.SEC_REC "
                                                                                 . " WHERE FACT.NIC = " . $detalle_id . " "
                                                                                 . " GROUP BY FACT.VALOR_RECIBO, FACT.SEC_REC ");
                        while ($row_facturacion_nic = mysqli_fetch_assoc($query_select_facturacion_nic)) {
                            $table = $table . "<tr>";
                                if (strlen($row_facturacion_nic['MES_FACTURA']) == 1) {
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['ANO_FACTURA'] . "0" . $row_facturacion_nic['MES_FACTURA'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['ANO_FACTURA'] . $row_facturacion_nic['MES_FACTURA'] . "</td>";
                                }
                                $query_select_sv = mysqli_query($connection, "SELECT SIMBOLO_VARIABLE "
                                                                           . "  FROM $bd_tabla_cartera "
                                                                           . " WHERE NIC = " . $detalle_id . " "
                                                                           . "   AND FECHA_FACT = '" . $row_facturacion_nic['FECHA_FACT_LECT'] . "'");
                                $row_sv = mysqli_fetch_array($query_select_sv);
                                if ($row_sv['SIMBOLO_VARIABLE'] != "") {
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_sv['SIMBOLO_VARIABLE'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>-</td>";
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_facturacion_nic['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['FECHA_FACTURA'] . "</td>";
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS VALOR_RECAUDADO "
                                                                                      . " FROM $bd_tabla_recaudo "
                                                                                      . "WHERE NIC = " . $detalle_id . " "
                                                                                      . "  AND ANO_FACTURA = " . $row_facturacion_nic['ANO_FACTURA'] . " "
                                                                                      . "  AND MES_FACTURA = " . $row_facturacion_nic['MES_FACTURA'] . " "
                                                                                      . "  AND VALOR_RECIBO = '" . $row_facturacion_nic['VALOR_RECAUDO'] . "' "
                                                                                      . "  AND SEC_NIS = " . $row_facturacion_nic['SEC_NIS'] . " "
                                                                                      . "  AND SEC_REC = " . $row_facturacion_nic['SEC_REC']);
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_info_recaudo['VALOR_RECAUDADO'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['FECHA_RECAUDO'] . "</td>";
                                if (($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO']) <= 0) {
                                    $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color:#00A328;' class='label label-success'><b style='font-size: 12px;'>$ </b><b>" . number_format($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO'], 0, ',', '.') . "</b></span></td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color:#CC3300;' class='label label-success'><b style='font-size: 12px;'>$ </b><b>" . number_format($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO'], 0, ',', '.') . "</b></span></td>";
                                }
                                if (strlen($row_facturacion_nic['MES_FACTURA']) == 1) {
                                    $mes_factura = "0" . $row_facturacion_nic['MES_FACTURA'];
                                } else {
                                    $mes_factura = $row_facturacion_nic['MES_FACTURA'];
                                }
                                if (strlen($row_facturacion_nic['SEC_NIS']) == 1) {
                                    $sec_nis = "0" . $row_facturacion_nic['SEC_NIS'];
                                } else {
                                    $sec_nis = $row_facturacion_nic['SEC_NIS'];
                                }
                                $table = $table . "<td style='vertical-align:middle;'><button type='button' data-toggle='modal' id='" . $detalle_id . $row_facturacion_nic['ANO_FACTURA'] . $mes_factura . $row_facturacion_nic['COD_OPER_CONT'] . $row_facturacion_nic['SEC_REC'] . $sec_nis . $row_facturacion_nic['FECHA_FACT_LECT'] . "' data-target='#modalDetalleHistorialFactura'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                            $table = $table . "</tr>";
                        }
                    }
                    /*$table = $table . "<div class='table-responsive'>";
                        //$table = $table . "<p style='color: #003153;'>HISTORIAL FACTURACIÓN - RECAUDO</p>";
                        $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                            $table = $table . "<thead>";
                                $table = $table . "<tr>";
                                    $table = $table . "<th width=10%>AÑO FACT.</th>";
                                    $table = $table . "<th width=10%>MES FACT.</th>";
                                    $table = $table . "<th width=10%>VALOR FACTURA</th>";
                                    $table = $table . "<th width=10%>FECHA FACTURA</th>";
                                    $table = $table . "<th width=10%>VALOR RECAUDO</th>";
                                    $table = $table . "<th width=10%>FECHA RECAUDO</th>";
                                    $table = $table . "<th width=10%>DEUDA MES</th>";
                                    $table = $table . "<th width=10%>DETALLE</th>";
                                $table = $table . "</tr>";
                            $table = $table . "</thead>";
                            $table = $table . "<tbody>";
                                while ($row_max_periodo = mysqli_fetch_assoc($query_select_max_periodo)) {
                                    $bd_tabla_facturacion = "facturacion_" .  strtolower($row_max_periodo['MES_FACTURA']) . $row_max_periodo['ANO_FACTURA'] . "_2";
                                    $bd_tabla_recaudo = "recaudo_" . strtolower($row_max_periodo['MES_FACTURA']) . $row_max_periodo['ANO_FACTURA'] . "_2";
                                    if ($sw == 0) {
                                        $bd_tabla_catastro = "catastro_" . strtolower($row_max_periodo['MES_FACTURA']) . $row_max_periodo['ANO_FACTURA'] . "_2";
                                        $query_select_info_catastro = mysqli_query($connection, "SELECT * FROM $bd_tabla_catastro WHERE NIC = " . $detalle_id);
                                        if (mysqli_num_rows($query_select_info_catastro) != 0) {
                                            $row_info_catastro = mysqli_fetch_array($query_select_info_catastro);
                                            $propietario = "NIC: " . $row_info_catastro['NIC'] . " - Propietario: " . str_replace("Ñ", "N", utf8_decode($row_info_catastro['NOMBRE_PROPIETARIO']));
                                            $sw = 1;
                                        }
                                    }
                                    $query_select_facturacion_nic = mysqli_query($connection, "SELECT FACT.VALOR_RECIBO AS VALOR_FACTURA, FACT.FECHA_TRANS AS FECHA_FACTURA, "
                                                                                                   . "RECA.VALOR_RECIBO AS VALOR_RECAUDO, RECA.FECHA_TRANS AS FECHA_RECAUDO, "
                                                                                                   . "FACT.ANO_FACTURA, FACT.MES_FACTURA, FACT.COD_OPER_CONT, FACT.SEC_REC, "
                                                                                                   . "FACT.SEC_NIS, FACT.FECHA_FACT_LECT "
                                                                                            . "  FROM $bd_tabla_facturacion FACT "
                                                                                            . "  LEFT JOIN $bd_tabla_recaudo RECA "
                                                                                            . "    ON FACT.NIC = RECA.NIC "
                                                                                            . "   AND FACT.VALOR_RECIBO = RECA.VALOR_RECIBO "
                                                                                            . "   AND FACT.SEC_REC = RECA.SEC_REC "
                                                                                            . " WHERE FACT.NIC = " . $detalle_id . " "
                                                                                            . " GROUP BY FACT.VALOR_RECIBO, FACT.SEC_REC ");
                                    while ($row_facturacion_nic = mysqli_fetch_assoc($query_select_facturacion_nic)) {
                                        $table = $table . "<tr>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['ANO_FACTURA'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['MES_FACTURA'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_facturacion_nic['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['FECHA_FACTURA'] . "</td>";
                                            $query_select_info_recaudo = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS VALOR_RECAUDADO "
                                                                                                  . " FROM $bd_tabla_recaudo "
                                                                                                  . "WHERE NIC = " . $detalle_id . " "
                                                                                                  . "  AND ANO_FACTURA = " . $row_facturacion_nic['ANO_FACTURA'] . " "
                                                                                                  . "  AND MES_FACTURA = " . $row_facturacion_nic['MES_FACTURA'] . " "
                                                                                                  . "  AND VALOR_RECIBO = '" . $row_facturacion_nic['VALOR_RECAUDO'] . "' "
                                                                                                  . "  AND SEC_NIS = " . $row_facturacion_nic['SEC_NIS'] . " "
                                                                                                  . "  AND SEC_REC = " . $row_facturacion_nic['SEC_REC']);
                                            $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                            $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_info_recaudo['VALOR_RECAUDADO'], 0, ',', '.') . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['FECHA_RECAUDO'] . "</td>";
                                            if (($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO']) <= 0) {
                                                $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color:#00A328;' class='label label-success'><b style='font-size: 12px;'>$ </b><b>" . number_format($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO'], 0, ',', '.') . "</b></span></td>";
                                            } else {
                                                $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color:#CC3300;' class='label label-success'><b style='font-size: 12px;'>$ </b><b>" . number_format($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO'], 0, ',', '.') . "</b></span></td>";
                                            }
                                            if (strlen($row_facturacion_nic['MES_FACTURA']) == 1) {
                                                $mes_factura = "0" . $row_facturacion_nic['MES_FACTURA'];
                                            } else {
                                                $mes_factura = $row_facturacion_nic['MES_FACTURA'];
                                            }
                                            if (strlen($row_facturacion_nic['SEC_NIS']) == 1) {
                                                $sec_nis = "0" . $row_facturacion_nic['SEC_NIS'];
                                            } else {
                                                $sec_nis = $row_facturacion_nic['SEC_NIS'];
                                            }
                                            $table = $table . "<td style='vertical-align:middle;'><button type='button' data-toggle='modal' id='" . $detalle_id . $row_facturacion_nic['ANO_FACTURA'] . $mes_factura . $row_facturacion_nic['COD_OPER_CONT'] . $row_facturacion_nic['SEC_REC'] . $sec_nis . $row_facturacion_nic['FECHA_FACT_LECT'] . "' data-target='#modalDetalleHistorialFactura'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                                        $table = $table . "</tr>";
                                    }
                                }
                            $table = $table . "</tbody>";
                        $table = $table . "</table>";
                    $table = $table . "</div>";*/
                    break;
                case 'ano':
                    $sw = 0;
                    $detalle_id = $_POST['detalle_id'];
                    $ano_factura = $_POST['ano_factura'];
                    $deuda_total = 0;
                    $query_select_max_ano = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA), ID_MES_FACTURA, MES_FACTURA "
                                                                        . "  FROM archivos_cargados_facturacion_2 "
                                                                        . " WHERE ANO_FACTURA = " . $ano_factura
                                                                        . " ORDER BY ANO_FACTURA DESC");
                    while ($row_max_ano = mysqli_fetch_assoc($query_select_max_ano)) {
                        $bd_tabla_facturacion = "facturacion_" .  strtolower($row_max_ano['MES_FACTURA']) . $row_max_ano['ANO_FACTURA'] . "_2";
                        $bd_tabla_recaudo = "recaudo_" . strtolower($row_max_ano['MES_FACTURA']) . $row_max_ano['ANO_FACTURA'] . "_2";
                        $bd_tabla_cartera = "cartera_fallida_" . $row_max_ano['ANO_FACTURA'] . "_2";
                        if ($sw == 0) {
                            $bd_tabla_catastro = "catastro_" . strtolower($row_max_ano['MES_FACTURA']) . $row_max_ano['ANO_FACTURA'] . "_2";
                            $query_select_info_catastro = mysqli_query($connection, "SELECT * FROM $bd_tabla_catastro WHERE NIC = " . $detalle_id);
                            if (mysqli_num_rows($query_select_info_catastro) != 0) {
                                $row_info_catastro = mysqli_fetch_array($query_select_info_catastro);
                                $propietario = "NIC: " . $row_info_catastro['NIC'] . " - Propietario: " . str_replace("Ñ", "N", utf8_decode($row_info_catastro['NOMBRE_PROPIETARIO']));
                                $sw = 1;
                            } else {
                                $propietario = "NIC: NO ENCONTRADO EN EL AÑO SELECCIONADO.";
                            }
                        }
                        $query_select_facturacion_nic = mysqli_query($connection, "SELECT FACT.VALOR_RECIBO AS VALOR_FACTURA, FACT.FECHA_TRANS AS FECHA_FACTURA, "
                                                                                        . "RECA.VALOR_RECIBO AS VALOR_RECAUDO, RECA.FECHA_TRANS AS FECHA_RECAUDO, "
                                                                                        . "FACT.ANO_FACTURA, FACT.MES_FACTURA, FACT.COD_OPER_CONT, FACT.SEC_REC, "
                                                                                        . "FACT.SEC_NIS, FACT.FECHA_FACT_LECT AS FECHA_FACT_LECT "
                                                                                 . "  FROM $bd_tabla_facturacion FACT "
                                                                                 . "  LEFT JOIN $bd_tabla_recaudo RECA "
                                                                                 . "    ON FACT.NIC = RECA.NIC "
                                                                                 . "   AND FACT.VALOR_RECIBO = RECA.VALOR_RECIBO "
                                                                                 . "   AND FACT.SEC_REC = RECA.SEC_REC "
                                                                                 . " WHERE FACT.NIC = " . $detalle_id . " "
                                                                                 . " GROUP BY FACT.VALOR_RECIBO, FACT.SEC_REC ");
                        while ($row_facturacion_nic = mysqli_fetch_assoc($query_select_facturacion_nic)) {
                            $table = $table . "<tr>";
                                if (strlen($row_facturacion_nic['MES_FACTURA']) == 1) {
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['ANO_FACTURA'] . "0" . $row_facturacion_nic['MES_FACTURA'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['ANO_FACTURA'] . $row_facturacion_nic['MES_FACTURA'] . "</td>";
                                }
                                $query_select_sv = mysqli_query($connection, "SELECT SIMBOLO_VARIABLE "
                                                                           . "  FROM $bd_tabla_cartera "
                                                                           . " WHERE NIC = " . $detalle_id . " "
                                                                           . "   AND FECHA_FACT = '" . $row_facturacion_nic['FECHA_FACT_LECT'] . "'");
                                $row_sv = mysqli_fetch_array($query_select_sv);
                                if ($row_sv['SIMBOLO_VARIABLE'] != "") {
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_sv['SIMBOLO_VARIABLE'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'>-</td>";
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_facturacion_nic['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['FECHA_FACTURA'] . "</td>";
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS VALOR_RECAUDADO "
                                                                                      . " FROM $bd_tabla_recaudo "
                                                                                      . "WHERE NIC = " . $detalle_id . " "
                                                                                      . "  AND ANO_FACTURA = " . $row_facturacion_nic['ANO_FACTURA'] . " "
                                                                                      . "  AND MES_FACTURA = " . $row_facturacion_nic['MES_FACTURA'] . " "
                                                                                      . "  AND VALOR_RECIBO = '" . $row_facturacion_nic['VALOR_RECAUDO'] . "' "
                                                                                      . "  AND SEC_NIS = " . $row_facturacion_nic['SEC_NIS'] . " "
                                                                                      . "  AND SEC_REC = " . $row_facturacion_nic['SEC_REC']);
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_info_recaudo['VALOR_RECAUDADO'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['FECHA_RECAUDO'] . "</td>";
                                if (($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO']) <= 0) {
                                    $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color:#00A328;' class='label label-success'><b style='font-size: 12px;'>$ </b><b>" . number_format($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO'], 0, ',', '.') . "</b></span></td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color:#CC3300;' class='label label-success'><b style='font-size: 12px;'>$ </b><b>" . number_format($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO'], 0, ',', '.') . "</b></span></td>";
                                }
                                $deuda_total = $deuda_total + ($row_facturacion_nic['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDADO']);
                                if (strlen($row_facturacion_nic['MES_FACTURA']) == 1) {
                                    $mes_factura = "0" . $row_facturacion_nic['MES_FACTURA'];
                                } else {
                                    $mes_factura = $row_facturacion_nic['MES_FACTURA'];
                                }
                                if (strlen($row_facturacion_nic['SEC_NIS']) == 1) {
                                    $sec_nis = "0" . $row_facturacion_nic['SEC_NIS'];
                                } else {
                                    $sec_nis = $row_facturacion_nic['SEC_NIS'];
                                }
                                $table = $table . "<td style='vertical-align:middle;'><button type='button' data-toggle='modal' id='" . $detalle_id . $row_facturacion_nic['ANO_FACTURA'] . $mes_factura . $row_facturacion_nic['COD_OPER_CONT'] . $row_facturacion_nic['SEC_REC'] . $sec_nis . $row_facturacion_nic['FECHA_FACT_LECT'] . "' data-target='#modalDetalleHistorialFactura'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                            $table = $table . "</tr>";
                        }
                    }
                    $table = $table . "<tr>";
                        $table = $table . "<td><b>TOTAL DEUDA</b></td>";
                        $table = $table . "<td colspan='5'></td>";
                        $table = $table . "<td><span style='font-size: 11px; background-color:#CC3300;' class='label label-success'><b style='font-size: 12px;'>$ </b><b>" . number_format($deuda_total, 0, ',', '.') . "</b></span></td>";
                        $table = $table . "<td colspan='5'></td>";
                    $table = $table . "</tr>";
                    break;
            }
            $info_historial[0] = "Historial Facturación - Recaudo.<br/ >" . $propietario;
            $info_historial[1] = $table;
            echo json_encode($info_historial);
            exit();
        } else {
            $info_historial[1] = "No existen datos para mostrar. Favor revisar información";
            echo json_encode($info_historial);
            exit();
        }
    }
?>