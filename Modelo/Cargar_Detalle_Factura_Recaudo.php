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
        $total_facturacion = 0;
        $total_recaudo = 0;
        $info_detalle = array();
        if (isset($_POST['detalle_id']) != 0) {
            $detalle_id = $_POST['detalle_id'];
            $nic = substr($detalle_id, 0, 7);
            $ano_factura = substr($detalle_id, 7, 4);
            $detalle_id_length = strlen($detalle_id);
            switch ($detalle_id_length) {
                /*case 29:
                    $mes_factura = "0" . substr($detalle_id, 11, 1);
                    $cod_oper_cont = substr($detalle_id, 12, 5);
                    $sec_rec = substr($detalle_id, 17, 1);
                    $sec_nis = substr($detalle_id, 18, 1);
                    $fecha_lectura = substr($detalle_id, 19, 10);
                    break;
                case 30:
                    $mes_factura = substr($detalle_id, 11, 2);
                    $cod_oper_cont = substr($detalle_id, 13, 5);
                    $sec_rec = substr($detalle_id, 18, 1);
                    $sec_nis = substr($detalle_id, 19, 2);
                    $fecha_lectura = substr($detalle_id, 21, 10);
                    break;*/
                case 31:
                    $mes_factura = substr($detalle_id, 11, 2);
                    $cod_oper_cont = substr($detalle_id, 13, 5);
                    $sec_rec = substr($detalle_id, 18, 1);
                    $sec_nis = substr($detalle_id, 19, 2);
                    $fecha_lectura = substr($detalle_id, 21, 10);
                    break;
            }
            $query_select_mes_factura = mysqli_query($connection, "SELECT DISTINCT(MES_FACTURA) "
                                                                . "  FROM archivos_cargados_facturacion_2 "
                                                                . " WHERE ANO_FACTURA = " . $ano_factura . " "
                                                                . "   AND ID_MES_FACTURA = " . $mes_factura);
            $row_mes_factura = mysqli_fetch_array($query_select_mes_factura);
            $bd_tabla_catastro = "catastro_" . strtolower($row_mes_factura['MES_FACTURA']) . $ano_factura . "_2";
            $bd_tabla_facturacion = "facturacion_" .  strtolower($row_mes_factura['MES_FACTURA']) . $ano_factura . "_2";
            $bd_tabla_recaudo = " recaudo_" . strtolower($row_mes_factura['MES_FACTURA']) . $ano_factura . "_2 ";
            $query_select_info_catastro = mysqli_query($connection, "SELECT * FROM $bd_tabla_catastro WHERE NIC = " . $nic);
            $row_info_catastro = mysqli_fetch_array($query_select_info_catastro);
            $propietario = "NIC: " . $row_info_catastro['NIC'] . " - Propietario: " . str_replace("Ñ", "N", utf8_decode($row_info_catastro['NOMBRE_PROPIETARIO']));
            $query_select_info_facturacion = mysqli_query($connection, "SELECT * "
                                                                      . " FROM $bd_tabla_facturacion "
                                                                      . "WHERE NIC = " . $nic . " "
                                                                      . "  AND ANO_FACTURA = " . $ano_factura . " "
                                                                      . "  AND MES_FACTURA = " . $mes_factura . " "
                                                                      . "  AND COD_OPER_CONT = '" . $cod_oper_cont . "' "
                                                                      . "  AND SEC_NIS = " . $sec_nis . " "
                                                                      . "  AND SEC_REC = " . $sec_rec . " "
                                                                      . "  AND FECHA_FACT_LECT = '" . $fecha_lectura . "'");
            $table = $table . "<div class='table-responsive'>";
                $table = $table . "<p style='color: #003153;'>FACTURACIÓN - PERIODO: " . $ano_factura . $mes_factura . "</p>";
                $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width='15%'>COD. CONCEPTO</th>";
                            $table = $table . "<th width='70%'>CONCEPTO</th>";
                            $table = $table . "<th width='20%'>VALOR CONCEPTO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_facturacion = mysqli_fetch_assoc($query_select_info_facturacion)) {
                            $table = $table . "<tr>";
                                $table = $table . "<td>" . $row_info_facturacion['CONCEPTO'] . "</td>";
                                $query_select_concepto = mysqli_query($connection, "SELECT NOMBRE FROM tipo_conceptos_2 WHERE COD_CONCEPTO = '" . $row_info_facturacion['CONCEPTO'] . "'");
                                $row_concepto = mysqli_fetch_array($query_select_concepto);
                                $table = $table . "<td>" . $row_concepto['NOMBRE'] . "</td>";
                                $table = $table . "<td>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_info_facturacion['IMPORTE_TRANS'], 2, ',', '.') . "</td>";
                            $table = $table . "</tr>";
                            $total_facturacion = $total_facturacion + $row_info_facturacion['IMPORTE_TRANS'];
                            $fecha_lectura = $row_info_facturacion['FECHA_FACT_LECT'];
                            $sec_rec = $row_info_facturacion['SEC_REC'];
                            $valor_recibo = $row_info_facturacion['VALOR_RECIBO'];
                        }
                        $table = $table . "<tr style='border-top: 2px solid #000000;'>";
                            $table = $table . "<td>" . "<b style='font-size: 13px;'>TOTAL</b>" . "</td>";
                            $table = $table . "<td></td>";
                            $table = $table . "<td>" . "<b style='font-size: 12px;'>$ " . number_format($total_facturacion, 0, ',', '.') . "</b></td>";
                        $table = $table . "</tr>";
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
            $table = $table . "</div>";
            $table = $table . "<br />";
            $query_select_info_recaudo = mysqli_query($connection, "SELECT * "
                                                                  . " FROM $bd_tabla_recaudo "
                                                                  . "WHERE NIC = " . $nic . " "
                                                                  . "  AND ANO_FACTURA = " . $ano_factura . " "
                                                                  . "  AND MES_FACTURA = " . $mes_factura . " "
                                                                  . "  AND VALOR_RECIBO = '" . $valor_recibo . "' "
                                                                  . "  AND SEC_NIS = " . $sec_nis . " "
                                                                  . "  AND SEC_REC = " . $sec_rec);
            $table = $table . "<div class='table-responsive'>";
                $table = $table . "<p style='color: #003153;'>RECAUDO - PERIODO: " . $ano_factura . $mes_factura . "</p>";
                $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width='15%'>COD. CONCEPTO</th>";
                            $table = $table . "<th width='70%'>CONCEPTO</th>";
                            $table = $table . "<th width='20%'>VALOR CONCEPTO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_recaudo = mysqli_fetch_assoc($query_select_info_recaudo)) {
                            $table = $table . "<tr>";
                                $table = $table . "<td>" . $row_info_recaudo['CONCEPTO'] . "</td>";
                                $query_select_concepto = mysqli_query($connection, "SELECT NOMBRE FROM tipo_conceptos_2 WHERE COD_CONCEPTO = '" . $row_info_recaudo['CONCEPTO'] . "'");
                                $row_concepto = mysqli_fetch_array($query_select_concepto);
                                $table = $table . "<td>" . $row_concepto['NOMBRE'] . "</td>";
                                $table = $table . "<td>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_info_recaudo['IMPORTE_TRANS'], 2, ',', '.') . "</td>";
                            $table = $table . "</tr>";
                            $total_recaudo = $total_recaudo + $row_info_recaudo['IMPORTE_TRANS'];
                        }
                        $table = $table . "<tr style='border-top: 2px solid #000000;'>";
                            $table = $table . "<td>" . "<b style='font-size: 13px;'>TOTAL</b>" . "</td>";
                            $table = $table . "<td></td>";
                            $table = $table . "<td>" . "<b style='font-size: 12px;'>$ " . number_format($total_recaudo, 0, ',', '.') . "</b></td>";
                        $table = $table . "</tr>";
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
            $table = $table . "</div>";
            
            $info_detalle[0] = "Detalle Facturación - Recaudo.<br/ >" . $propietario;
            $info_detalle[1] = $table;
            echo json_encode($info_detalle);
            exit();
        } else {
            $info_detalle[0] = "Detalle Factura - Recaudo: Error.";
            $info_detalle[1] = "No existen datos para mostrar. Favor revisar información";
            echo json_encode($info_detalle);
            exit();
        }
    }
?>