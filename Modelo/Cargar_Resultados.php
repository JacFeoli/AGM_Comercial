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
                require_once('../Includes/Paginacion_Resultado_Nic.php');
                $nic = $_POST['nic'];
                $page = $_POST['page'];
                $bd_tabla_facturacion = $_POST['bd_tabla_facturacion'];
                $bd_tabla_recaudo = $_POST['bd_tabla_recaudo'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_NIC * ($page - 1);
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
                                                                        . " WHERE FACT.NIC = " . $nic . " "
                                                                        . " GROUP BY FACT.VALOR_RECIBO, FACT.SEC_REC "
                                                                        . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_NIC);
                $table = "";
                while ($row_facturacion_nic = mysqli_fetch_assoc($query_select_facturacion_nic)) {
                    $table = $table . "<tr>";
                        $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['ANO_FACTURA'] . "</td>";
                        $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['MES_FACTURA'] . "</td>";
                        $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_facturacion_nic['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                        $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_nic['FECHA_FACTURA'] . "</td>";
                        $query_select_info_recaudo = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS VALOR_RECAUDADO "
                                                                              . " FROM $bd_tabla_recaudo "
                                                                              . "WHERE NIC = " . $nic . " "
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
                        $table = $table . "<td style='vertical-align:middle;'><button type='button' data-toggle='modal' id='" . $nic . $row_facturacion_nic['ANO_FACTURA'] . $mes_factura . $row_facturacion_nic['COD_OPER_CONT'] . $row_facturacion_nic['SEC_REC'] . $sec_nis . $row_facturacion_nic['FECHA_FACT_LECT'] . "' data-target='#modalDetalleFactura'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                    $table = $table . "</tr>";
                }
                break;
            case '1':
                require_once('../Includes/Paginacion_Resultado_Propietario.php');
                $propietario = $_POST['propietario'];
                $bd_tabla_catastro = $_POST['bd_tabla_catastro'];
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_PROPIETARIO * ($page - 1);
                }
                $query_select_info_propietario = mysqli_query($connection, "SELECT CAT.NIC AS NIC, CAT.NOMBRE_PROPIETARIO AS PROPIETARIO, "
                                                                                . "CAT.DIRECCION_VIVIENDA AS DIRECCION, "
                                                                                . "CAT.DEUDA_CORRIENTE AS DEUDA_CORRIENTE, "
                                                                                . "CAT.DEUDA_CUOTA AS DEUDA_CUOTA, "
                                                                                . "CAT.ANO_CATASTRO AS ANO, CAT.MES_CATASTRO AS MES, "
                                                                                . "DEP.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO, "
                                                                                . "CORR.NOMBRE AS CORREGIMIENTO, "
                                                                                . "EST.NOMBRE AS ESTADO_SUMINISTRO, TAR.NOMBRE AS TARIFA "
                                                                           . "FROM $bd_tabla_catastro CAT, departamentos_2 DEP, "
                                                                                . "municipios_2 MUN, corregimientos_2 CORR, "
                                                                                . "estados_suministro_2 EST, tarifas_2 TAR "
                                                                          . "WHERE CAT.ID_COD_DPTO = DEP.ID_DEPARTAMENTO "
                                                                            . "AND CAT.ID_COD_MPIO = MUN.ID_MUNICIPIO "
                                                                            . "AND CAT.ID_COD_CORREG = CORR.ID_TABLA "
                                                                            . "AND DEP.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO "
                                                                            . "AND DEP.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                            . "AND MUN.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                            . "AND MUN.ID_MUNICIPIO = CORR.ID_MUNICIPIO "
                                                                            . "AND CAT.ID_TARIFA = TAR.ID_TARIFA "
                                                                            . "AND CAT.ID_ESTADO_SUMINISTRO = EST.ID_ESTADO_SUMINISTRO "
                                                                            . "AND CAT.NOMBRE_PROPIETARIO LIKE '%" . $propietario . "%' "
                                                                          . "ORDER BY CAT.NOMBRE_PROPIETARIO "
                                                                          . "LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_PROPIETARIO);
                $table = "";
                while ($row_facturacion_propietario = mysqli_fetch_assoc($query_select_info_propietario)) {
                    $table = $table . "<tr>";
                        $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_propietario['NIC'] . "</td>";
                        $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_propietario['PROPIETARIO'] . "</td>";
                        $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_propietario['DEPARTAMENTO'] . "</td>";
                        $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_propietario['MUNICIPIO'] . "</td>";
                        $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_propietario['CORREGIMIENTO'] . "</td>";
                    $table = $table . "</tr>";
                }
                break;
            case '2':
                require_once('../Includes/Paginacion_Resultado_Departamento.php');
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                if ($departamento == "") {
                    $query_departamento = "";
                    $query_estratos_departamento = "";
                } else {
                    $query_departamento = " AND MUN.ID_DEPARTAMENTO = " . $departamento . " ";
                    $query_estratos_departamento = " AND ID_COD_DPTO = " . $departamento . " ";
                }
                if ($municipio == "") {
                    $query_municipio = "";
                    $query_estrato_municipio = "";
                } else {
                    $query_municipio = " AND MUN.ID_MUNICIPIO = " . $municipio . " ";
                    $query_estrato_municipio = " AND ID_COD_MPIO = " . $municipio . " ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_DEPARTAMENTO * ($page - 1);
                }
                $id_ano_factura = $_POST['id_ano_factura'];
                if (strlen($_POST['id_mes_factura']) == 1) {
                    $id_mes_factura = "0" . $_POST['id_mes_factura'];
                } else {
                    $id_mes_factura = $_POST['id_mes_factura'];
                }
                $mes_factura = $_POST['mes_factura'];
                $bd_tabla_facturacion = "facturacion_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_recaudo = "recaudo_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_catastro = "catastro_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_refacturacion = "refacturacion_" . $mes_factura . $id_ano_factura . "_2";
                $query_select_info_departamento = mysqli_query($connection, "SELECT MUN.ID_DEPARTAMENTO, MUN.ID_MUNICIPIO, MUN.NOMBRE AS MUNICIPIO, "
                                                                          . "       COUNT(CATA.NIC) AS TOTAL_CLIENTES "
                                                                          . "  FROM municipios_2 MUN, $bd_tabla_catastro CATA "
                                                                          . " WHERE MUN.ID_DEPARTAMENTO = CATA.ID_COD_DPTO "
                                                                          . "   AND MUN.ID_MUNICIPIO = CATA.ID_COD_MPIO "
                                                                          . $query_departamento . " "
                                                                          . $query_municipio . " "
                                                                          . " GROUP BY MUN.NOMBRE "
                                                                          . "HAVING COUNT(1) >= 1 "
                                                                          . " ORDER BY MUN.NOMBRE "
                                                                          . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_DEPARTAMENTO);
                $table = "";
                while ($row_facturacion_departamento = mysqli_fetch_assoc($query_select_info_departamento)) {
                    $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>" . $row_facturacion_departamento['MUNICIPIO'] . " - PERIODO " . $id_ano_factura . $id_mes_factura . "</p>";
                    $table = $table . "<table class='table table-condensed table-hover'>";
                        $table = $table . "<thead>";
                            $table = $table . "<tr>";
                                $table = $table . "<th width=9%></th>";
                                $table = $table . "<th width=6%>R1</th>";
                                $table = $table . "<th width=6%>R2</th>";
                                $table = $table . "<th width=6%>R3</th>";
                                $table = $table . "<th width=6%>R4</th>";
                                $table = $table . "<th width=6%>R5</th>";
                                $table = $table . "<th width=6%>R6</th>";
                                $table = $table . "<th width=6%>COM.</th>";
                                $table = $table . "<th width=6%>OFC.</th>";
                                $table = $table . "<th width=6%>IND.</th>";
                                $table = $table . "<th width=9%>TOTALES</th>";
                            $table = $table . "</tr>";
                        $table = $table . "</thead>";
                        $table = $table . "<tbody>";
                            $table = $table . "<tr>";
                                if ($query_estrato_municipio == "") {
                                    $query_estrato_municipio = " AND ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " ";
                                }
                                $query_select_estrato_1 = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_ESTRATO_1 "
                                                                                  . " FROM $bd_tabla_catastro "
                                                                                  . "WHERE ID_TARIFA = 4 "
                                                                                  . $query_estratos_departamento . " "
                                                                                  . $query_estrato_municipio . " ");
                                $row_estrato_1 = mysqli_fetch_array($query_select_estrato_1);
                                $table = $table . "<td style='vertical-align:middle;'><b>CLIENTES</b></td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_estrato_1['TOTAL_ESTRATO_1'] . "</td>";
                                $query_select_estrato_2 = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_ESTRATO_2 "
                                                                                  . " FROM $bd_tabla_catastro "
                                                                                  . "WHERE ID_TARIFA = 5 "
                                                                                  . $query_estratos_departamento . " "
                                                                                  . $query_estrato_municipio . " ");
                                $row_estrato_2 = mysqli_fetch_array($query_select_estrato_2);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_estrato_2['TOTAL_ESTRATO_2'] . "</td>";
                                $query_select_estrato_3 = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_ESTRATO_3 "
                                                                                  . " FROM $bd_tabla_catastro "
                                                                                  . "WHERE ID_TARIFA = 6 "
                                                                                  . $query_estratos_departamento . " "
                                                                                  . $query_estrato_municipio . " ");
                                $row_estrato_3 = mysqli_fetch_array($query_select_estrato_3);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_estrato_3['TOTAL_ESTRATO_3'] . "</td>";
                                $query_select_estrato_4 = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_ESTRATO_4 "
                                                                                  . " FROM $bd_tabla_catastro "
                                                                                  . "WHERE ID_TARIFA = 10 "
                                                                                  . $query_estratos_departamento . " "
                                                                                  . $query_estrato_municipio . " ");
                                $row_estrato_4 = mysqli_fetch_array($query_select_estrato_4);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_estrato_4['TOTAL_ESTRATO_4'] . "</td>";
                                $query_select_estrato_5 = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_ESTRATO_5 "
                                                                                  . " FROM $bd_tabla_catastro "
                                                                                  . "WHERE ID_TARIFA = 11 "
                                                                                  . $query_estratos_departamento . " "
                                                                                  . $query_estrato_municipio . " ");
                                $row_estrato_5 = mysqli_fetch_array($query_select_estrato_5);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_estrato_5['TOTAL_ESTRATO_5'] . "</td>";
                                $query_select_estrato_6 = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_ESTRATO_6 "
                                                                                  . " FROM $bd_tabla_catastro "
                                                                                  . "WHERE ID_TARIFA = 14 "
                                                                                  . $query_estratos_departamento . " "
                                                                                  . $query_estrato_municipio . " ");
                                $row_estrato_6 = mysqli_fetch_array($query_select_estrato_6);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_estrato_6['TOTAL_ESTRATO_6'] . "</td>";
                                $query_select_comerciales = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_COMERCIALES "
                                                                                     . " FROM $bd_tabla_catastro "
                                                                                     . "WHERE ID_TARIFA IN (1, 7, 12) "
                                                                                     . $query_estratos_departamento . " "
                                                                                     . $query_estrato_municipio . " ");
                                $row_comerciales = mysqli_fetch_array($query_select_comerciales);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_comerciales['TOTAL_COMERCIALES'] . "</td>";
                                $query_select_oficiales = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_OFICIALES "
                                                                                   . " FROM $bd_tabla_catastro "
                                                                                   . "WHERE ID_TARIFA IN (3, 9) "
                                                                                   . $query_estratos_departamento . " "
                                                                                   . $query_estrato_municipio . " ");
                                $row_oficiales = mysqli_fetch_array($query_select_oficiales);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_oficiales['TOTAL_OFICIALES'] . "</td>";
                                $query_select_industriales = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_INDUSTRIALES "
                                                                                      . " FROM $bd_tabla_catastro "
                                                                                      . "WHERE ID_TARIFA IN (2, 8, 13) "
                                                                                      . $query_estratos_departamento . " "
                                                                                      . $query_estrato_municipio . " ");
                                $row_industriales = mysqli_fetch_array($query_select_industriales);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_industriales['TOTAL_INDUSTRIALES'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_facturacion_departamento['TOTAL_CLIENTES'] . "</td>";
                            $table = $table . "</tr>";
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'><b>FACTURADO</b></td>";
                                //FACTURACION ESTRATO 1
                                $query_select_sum_facturado_estrato_1 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO_ESTRATO_1 "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 4 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado_estrato_1 = mysqli_fetch_array($query_select_sum_facturado_estrato_1);
                                //FIN FACTURACION ESTRATO 1
                                //REFACTURACION ESTRATO 1
                                $query_select_sum_refacturado_estrato_1 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO_ESTRATO_1 "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 4 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado_estrato_1 = mysqli_fetch_array($query_select_sum_refacturado_estrato_1);
                                //FIN REFACTURACION ESTRATO 1
                                //TOTAL ESTRATO 1
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_1['TOTAL_FACTURADO_ESTRATO_1'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_1['TOTAL_FACTURADO_ESTRATO_1'] + $row_sum_refacturado_estrato_1['TOTAL_REFACTURADO_ESTRATO_1'], 0, ',', '.') . "</td>";
                                //FIN TOTAL ESTRATO 1
                                //FACTURACION ESTRATO 2
                                $query_select_sum_facturado_estrato_2 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO_ESTRATO_2 "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 5 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado_estrato_2 = mysqli_fetch_array($query_select_sum_facturado_estrato_2);
                                //FIN FACTURACION ESTRATO 2
                                //REFACTURACION ESTRATO 2
                                $query_select_sum_refacturado_estrato_2 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO_ESTRATO_2 "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 5 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado_estrato_2 = mysqli_fetch_array($query_select_sum_refacturado_estrato_2);
                                //FIN REFACTURACION ESTRATO 2
                                //TOTAL ESTRATO 2
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_2['TOTAL_FACTURADO_ESTRATO_2'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_2['TOTAL_FACTURADO_ESTRATO_2'] + $row_sum_refacturado_estrato_2['TOTAL_REFACTURADO_ESTRATO_2'], 0, ',', '.') . "</td>";
                                //FIN TOTAL ESTRATO 2
                                //FACTURACION ESTRATO 3
                                $query_select_sum_facturado_estrato_3 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO_ESTRATO_3 "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 6 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado_estrato_3 = mysqli_fetch_array($query_select_sum_facturado_estrato_3);
                                //FIN FACTURACION ESTRATO 3
                                //REFACTURACION ESTRATO 3
                                $query_select_sum_refacturado_estrato_3 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO_ESTRATO_3 "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 6 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado_estrato_3 = mysqli_fetch_array($query_select_sum_refacturado_estrato_3);
                                //FIN REFACTURACION ESTRATO 3
                                //TOTAL ESTRATO 3
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_3['TOTAL_FACTURADO_ESTRATO_3'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_3['TOTAL_FACTURADO_ESTRATO_3'] + $row_sum_refacturado_estrato_3['TOTAL_REFACTURADO_ESTRATO_3'], 0, ',', '.') . "</td>";
                                //FIN TOTAL ESTRATO 3
                                //FACTURACION ESTRATO 4
                                $query_select_sum_facturado_estrato_4 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO_ESTRATO_4 "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 10 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado_estrato_4 = mysqli_fetch_array($query_select_sum_facturado_estrato_4);
                                //FIN FACTURACION ESTRATO 4
                                //REFACTURACION ESTRATO 4
                                $query_select_sum_refacturado_estrato_4 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO_ESTRATO_4 "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 10 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado_estrato_4 = mysqli_fetch_array($query_select_sum_refacturado_estrato_4);
                                //FIN REFACTURACION ESTRATO 4
                                //TOTAL ESTRATO 4
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_4['TOTAL_FACTURADO_ESTRATO_4'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_4['TOTAL_FACTURADO_ESTRATO_4'] + $row_sum_refacturado_estrato_4['TOTAL_REFACTURADO_ESTRATO_4'], 0, ',', '.') . "</td>";
                                //FIN TOTAL ESTRATO 4
                                //FACTURACION ESTRATO 5
                                $query_select_sum_facturado_estrato_5 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO_ESTRATO_5 "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 11 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado_estrato_5 = mysqli_fetch_array($query_select_sum_facturado_estrato_5);
                                //FIN FACTURACION ESTRATO 5
                                //REFACTURACION ESTRATO 5
                                $query_select_sum_refacturado_estrato_5 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO_ESTRATO_5 "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 11 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado_estrato_5 = mysqli_fetch_array($query_select_sum_refacturado_estrato_5);
                                //FIN REFACTURACION ESTRATO 5
                                //TOTAL ESTRATO 5
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_5['TOTAL_FACTURADO_ESTRATO_5'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_5['TOTAL_FACTURADO_ESTRATO_5'] + $row_sum_refacturado_estrato_5['TOTAL_REFACTURADO_ESTRATO_5'], 0, ',', '.') . "</td>";
                                //FIN TOTAL ESTRATO 5
                                //FACTURACION ESTRATO 6
                                $query_select_sum_facturado_estrato_6 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO_ESTRATO_6 "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 14 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado_estrato_6 = mysqli_fetch_array($query_select_sum_facturado_estrato_6);
                                //FIN FACTURACION ESTRATO 6
                                //REFACTURACION ESTRATO 6
                                $query_select_sum_refacturado_estrato_6 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO_ESTRATO_6 "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 14 "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado_estrato_6 = mysqli_fetch_array($query_select_sum_refacturado_estrato_6);
                                //FIN REFACTURACION ESTRATO 6
                                //TOTAL ESTRATO 6
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_6['TOTAL_FACTURADO_ESTRATO_6'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_estrato_6['TOTAL_FACTURADO_ESTRATO_6'] + $row_sum_refacturado_estrato_6['TOTAL_REFACTURADO_ESTRATO_6'], 0, ',', '.') . "</td>";
                                //FIN TOTAL ESTRATO 6
                                //FACTURACION COMERCIALES
                                $query_select_sum_facturado_comerciales = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO_COMERCIALES "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA IN (1, 7, 12) "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado_comerciales = mysqli_fetch_array($query_select_sum_facturado_comerciales);
                                //FIN FACTURACION COMERCIALES
                                //REFACTURACION COMERCIALES
                                $query_select_sum_refacturado_comerciales = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO_COMERCIALES "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA IN (1, 7, 12) "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado_comerciales = mysqli_fetch_array($query_select_sum_refacturado_comerciales);
                                //FIN REFACTURACION COMERCIALES
                                //TOTAL COMERCIALES
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_comerciales['TOTAL_FACTURADO_COMERCIALES'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_comerciales['TOTAL_FACTURADO_COMERCIALES'] + $row_sum_refacturado_comerciales['TOTAL_REFACTURADO_COMERCIALES'], 0, ',', '.') . "</td>";
                                //FIN TOTAL COMERCIALES
                                //FACTURACION OFICIALES
                                $query_select_sum_facturado_oficiales = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO_OFICIALES "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA IN (3, 9) "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado_oficiales = mysqli_fetch_array($query_select_sum_facturado_oficiales);
                                //FIN FACTURACION OFICIALES
                                //REFACTURACION OFICIALES
                                $query_select_sum_refacturado_oficiales = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO_OFICIALES "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA IN (3, 9) "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado_oficiales = mysqli_fetch_array($query_select_sum_refacturado_oficiales);
                                //FIN REFACTURACION OFICIALES
                                //TOTAL OFICIALES
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_oficiales['TOTAL_FACTURADO_OFICIALES'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_oficiales['TOTAL_FACTURADO_OFICIALES'] + $row_sum_refacturado_oficiales['TOTAL_REFACTURADO_OFICIALES'], 0, ',', '.') . "</td>";
                                //FIN TOTAL OFICIALES
                                //FACTURACION INDUSTRIALES
                                $query_select_sum_facturado_industriales = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO_INDUSTRIALES "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA IN (2, 8, 13) "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado_industriales = mysqli_fetch_array($query_select_sum_facturado_industriales);
                                //FIN FACTURACION INDUSTRIALES
                                //REFACTURACION INDUSTRIALES
                                $query_select_sum_refacturado_industriales = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO_INDUSTRIALES "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA IN (2, 8, 13) "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado_industriales = mysqli_fetch_array($query_select_sum_refacturado_industriales);
                                //FIN REFACTURACION INDUSTRIALES
                                //TOTAL INDUSTRIALES
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_industriales['TOTAL_FACTURADO_INDUSTRIALES'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_industriales['TOTAL_FACTURADO_INDUSTRIALES'] + $row_sum_refacturado_industriales['TOTAL_REFACTURADO_INDUSTRIALES'], 0, ',', '.') . "</td>";
                                //FIN TOTAL INDUSTRIALES
                                //FACTURACION TOTAL
                                $query_select_sum_facturado = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_facturado = mysqli_fetch_array($query_select_sum_facturado);
                                //FIN FACTURACION TOTAL
                                //REFACTURACION TOTAL
                                $query_select_sum_refacturado = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_REFACTURADO "
                                                                                       . " FROM $bd_tabla_refacturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND CONCEPTO <> 'CI306' ");
                                $row_sum_refacturado = mysqli_fetch_array($query_select_sum_refacturado);
                                //FIN REFACTURACION TOTAL
                                //TOTAL TOTAL
                                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado['TOTAL_FACTURADO'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado['TOTAL_FACTURADO'] + $row_sum_refacturado['TOTAL_REFACTURADO'], 0, ',', '.') . "</td>";
                                //FIN TOTAL TOTAL
                            $table = $table . "</tr>";
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'><b>RECAUDADO</b></td>";
                                $query_select_sum_recaudado_estrato_1 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO_ESTRATO_1 "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 4 ");
                                $row_sum_recaudado_estrato_1 = mysqli_fetch_array($query_select_sum_recaudado_estrato_1);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_estrato_1['TOTAL_RECAUDADO_ESTRATO_1'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_estrato_2 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO_ESTRATO_2 "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 5 ");
                                $row_sum_recaudado_estrato_2 = mysqli_fetch_array($query_select_sum_recaudado_estrato_2);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_estrato_2['TOTAL_RECAUDADO_ESTRATO_2'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_estrato_3 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO_ESTRATO_3 "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 6 ");
                                $row_sum_recaudado_estrato_3 = mysqli_fetch_array($query_select_sum_recaudado_estrato_3);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_estrato_3['TOTAL_RECAUDADO_ESTRATO_3'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_estrato_4 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO_ESTRATO_4 "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 10 ");
                                $row_sum_recaudado_estrato_4 = mysqli_fetch_array($query_select_sum_recaudado_estrato_4);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_estrato_4['TOTAL_RECAUDADO_ESTRATO_4'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_estrato_5 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO_ESTRATO_5 "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 11 ");
                                $row_sum_recaudado_estrato_5 = mysqli_fetch_array($query_select_sum_recaudado_estrato_5);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_estrato_5['TOTAL_RECAUDADO_ESTRATO_5'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_estrato_6 = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO_ESTRATO_6 "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = 14 ");
                                $row_sum_recaudado_estrato_6 = mysqli_fetch_array($query_select_sum_recaudado_estrato_6);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_estrato_6['TOTAL_RECAUDADO_ESTRATO_6'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_comerciales = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO_COMERCIALES "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA IN (1, 7, 12) ");
                                $row_sum_recaudado_comerciales = mysqli_fetch_array($query_select_sum_recaudado_comerciales);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_comerciales['TOTAL_RECAUDADO_COMERCIALES'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_oficiales = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO_OFICIALES "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA IN (3, 9) ");
                                $row_sum_recaudado_oficiales = mysqli_fetch_array($query_select_sum_recaudado_oficiales);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_oficiales['TOTAL_RECAUDADO_OFICIALES'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_industriales = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO_INDUSTRIALES "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA IN (2, 8, 13) ");
                                $row_sum_recaudado_industriales = mysqli_fetch_array($query_select_sum_recaudado_industriales);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_industriales['TOTAL_RECAUDADO_INDUSTRIALES'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_RECAUDADO "
                                                                                       . " FROM $bd_tabla_recaudo "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_departamento['ID_MUNICIPIO']);
                                $row_sum_recaudado = mysqli_fetch_array($query_select_sum_recaudado);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado['TOTAL_RECAUDADO'], 0, ',', '.') . "</td>";
                            $table = $table . "</tr>";
                        $table = $table . "</tbody>";
                    $table = $table . "</table>";
                    $table = $table . "<hr />";
                    $query_estrato_municipio = "";
                }
                break;
            case '3':
                require_once('../Includes/Paginacion_Resultado_Tarifa.php');
                $tarifa = $_POST['tarifa'];
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                if ($departamento == "") {
                    $query_departamento = "";
                    $query_estratos_departamento = "";
                } else {
                    $query_departamento = " AND MUN.ID_DEPARTAMENTO = " . $departamento . " ";
                    $query_estratos_departamento = " AND ID_COD_DPTO = " . $departamento . " ";
                }
                if ($municipio == "") {
                    $query_municipio = "";
                    $query_estrato_municipio = "";
                } else {
                    $query_municipio = " AND MUN.ID_MUNICIPIO = " . $municipio . " ";
                    $query_estrato_municipio = " AND ID_COD_MPIO = " . $municipio . " ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_TARIFA * ($page - 1);
                }
                $id_ano_factura = $_POST['id_ano_factura'];
                if (strlen($_POST['id_mes_factura']) == 1) {
                    $id_mes_factura = "0" . $_POST['id_mes_factura'];
                } else {
                    $id_mes_factura = $_POST['id_mes_factura'];
                }
                $mes_factura = $_POST['mes_factura'];
                $bd_tabla_facturacion = "facturacion_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_recaudo = "recaudo_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_catastro = "catastro_" . $mes_factura . $id_ano_factura . "_2";
                $query_select_info_tarifa = mysqli_query($connection, "SELECT MUN.ID_DEPARTAMENTO, MUN.ID_MUNICIPIO, MUN.NOMBRE AS MUNICIPIO, "
                                                                    . "       COUNT(CATA.NIC) AS TOTAL_CLIENTES "
                                                                    . "  FROM municipios_2 MUN, $bd_tabla_catastro CATA "
                                                                    . " WHERE MUN.ID_DEPARTAMENTO = CATA.ID_COD_DPTO "
                                                                    . "   AND MUN.ID_MUNICIPIO = CATA.ID_COD_MPIO "
                                                                    . $query_departamento . " "
                                                                    . $query_municipio . " "
                                                                    . " GROUP BY MUN.NOMBRE "
                                                                    . "HAVING COUNT(1) >= 1 "
                                                                    . " ORDER BY MUN.NOMBRE "
                                                                    . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_TARIFA);
                $table = "";
                $query_select_cod_tarifa = mysqli_query($connection, "SELECT NOMBRE FROM tarifas_2 WHERE ID_TARIFA = " . $tarifa);
                $row_cod_tarifa = mysqli_fetch_array($query_select_cod_tarifa);
                while ($row_facturacion_tarifa = mysqli_fetch_assoc($query_select_info_tarifa)) {
                    $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>" . $row_cod_tarifa['NOMBRE'] . " - " . $row_facturacion_tarifa['MUNICIPIO'] . " - PERIODO " . $id_ano_factura . $id_mes_factura . "</p>";
                    $table = $table . "<table class='table table-condensed table-hover'>";
                        $table = $table . "<thead>";
                            $table = $table . "<tr>";
                                $table = $table . "<th width=9%></th>";
                                $table = $table . "<th width=19%>SITUACIÓN CORRECTA</th>";
                                $table = $table . "<th width=19%>ALTA SIN FACTURAR</th>";
                                $table = $table . "<th width=19%>SIN CONTRATO</th>";
                                $table = $table . "<th width=19%>BAJA FORZADA</th>";
                                $table = $table . "<th width=10%>TOTALES</th>";
                            $table = $table . "</tr>";
                        $table = $table . "</thead>";
                        $table = $table . "<tbody>";
                            $table = $table . "<tr>";
                                if ($query_estrato_municipio == "") {
                                    $query_estrato_municipio = " AND ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " ";
                                }
                                $table = $table . "<td style='vertical-align:middle;'><b>CLIENTES</b></td>";
                                $query_select_sit_corrc = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_SIT_CORRC "
                                                                                  . "  FROM $bd_tabla_catastro "
                                                                                  . " WHERE ID_TARIFA = " . $tarifa . " "
                                                                                  . "   AND ID_ESTADO_SUMINISTRO = 1 "
                                                                                  . $query_estratos_departamento . " "
                                                                                  . $query_estrato_municipio . " ");
                                $row_sit_corrc = mysqli_fetch_array($query_select_sit_corrc);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_sit_corrc['TOTAL_SIT_CORRC'] . "</td>";
                                $query_select_alta_sin_fact = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_ALTA_SIN_FACT "
                                                                                    . "    FROM $bd_tabla_catastro "
                                                                                    . "   WHERE ID_TARIFA = " . $tarifa . " "
                                                                                    . "     AND ID_ESTADO_SUMINISTRO = 2 "
                                                                                    . $query_estratos_departamento . " "
                                                                                    . $query_estrato_municipio . " ");
                                $row_alta_sin_fact = mysqli_fetch_array($query_select_alta_sin_fact);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_alta_sin_fact['TOTAL_ALTA_SIN_FACT'] . "</td>";
                                $query_select_sin_contrato = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_SIN_FACTURAR "
                                                                                    . "   FROM $bd_tabla_catastro "
                                                                                    . "  WHERE ID_TARIFA = " . $tarifa . " "
                                                                                    . "    AND ID_ESTADO_SUMINISTRO = 3 "
                                                                                    . $query_estratos_departamento . " "
                                                                                    . $query_estrato_municipio . " ");
                                $row_sin_contrato = mysqli_fetch_array($query_select_sin_contrato);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_sin_contrato['TOTAL_SIN_FACTURAR'] . "</td>";
                                $query_select_baja_forzada = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_BAJA_FORZADA "
                                                                                    . "   FROM $bd_tabla_catastro "
                                                                                    . "  WHERE ID_TARIFA = " . $tarifa . " "
                                                                                    . "    AND ID_ESTADO_SUMINISTRO = 4 "
                                                                                    . $query_estratos_departamento . " "
                                                                                    . $query_estrato_municipio . " ");
                                $row_baja_forzada = mysqli_fetch_array($query_select_baja_forzada);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_baja_forzada['TOTAL_BAJA_FORZADA'] . "</td>";
                                $query_select_tarifa = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS TOTAL_TARIFA "
                                                                               . "  FROM $bd_tabla_catastro "
                                                                               . " WHERE ID_TARIFA = " . $tarifa . " "
                                                                               . $query_estratos_departamento . " "
                                                                               . $query_estrato_municipio . " ");
                                $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_tarifa['TOTAL_TARIFA'] . "</td>";
                            $table = $table . "</tr>";
                            /*$table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'><b>FACTURADO</b></td>";
                                $query_select_sum_facturado_sit_corrc = mysqli_query($connection, "SELECT SUM(FACT.IMPORTE_TRANS) AS TOTAL_FACTURADO_SIT_CORRC "
                                                                                                . "  FROM $bd_tabla_facturacion FACT, $bd_tabla_catastro CATA "
                                                                                                . " WHERE FACT.ID_COD_MPIO = CATA.ID_COD_MPIO "
                                                                                                . "   AND FACT.ID_TARIFA = CATA.ID_TARIFA "
                                                                                                . "   AND FACT.NIC = CATA.NIC "
                                                                                                . "   AND FACT.ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " "
                                                                                                . "   AND FACT.ID_TARIFA = " . $tarifa . " "
                                                                                                . "   AND CATA.ID_ESTADO_SUMINISTRO = 1 ");
                                $row_sum_facturado_sit_corrc = mysqli_fetch_array($query_select_sum_facturado_sit_corrc);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_sit_corrc['TOTAL_FACTURADO_SIT_CORRC'], 0, ',', '.') . "</td>";
                                $query_select_sum_facturado_alta_sin_fact = mysqli_query($connection, "SELECT SUM(FACT.IMPORTE_TRANS) AS TOTAL_FACTURADO_ALTA_SIN_FACT "
                                                                                                    . "  FROM $bd_tabla_facturacion FACT, $bd_tabla_catastro CATA "
                                                                                                    . " WHERE FACT.ID_COD_MPIO = CATA.ID_COD_MPIO "
                                                                                                    . "   AND FACT.ID_TARIFA = CATA.ID_TARIFA "
                                                                                                    . "   AND FACT.NIC = CATA.NIC "
                                                                                                    . "   AND FACT.ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " "
                                                                                                    . "   AND FACT.ID_TARIFA = " . $tarifa . " "
                                                                                                    . "   AND CATA.ID_ESTADO_SUMINISTRO = 2 ");
                                $row_sum_facturado_alta_sin_fact = mysqli_fetch_array($query_select_sum_facturado_alta_sin_fact);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_alta_sin_fact['TOTAL_FACTURADO_ALTA_SIN_FACT'], 0, ',', '.') . "</td>";
                                $query_select_sum_facturado_sin_contrato = mysqli_query($connection, "SELECT SUM(FACT.IMPORTE_TRANS) AS TOTAL_FACTURADO_SIN_CONTRATO "
                                                                                                   . "  FROM $bd_tabla_facturacion FACT, $bd_tabla_catastro CATA "
                                                                                                   . " WHERE FACT.ID_COD_MPIO = CATA.ID_COD_MPIO "
                                                                                                   . "   AND FACT.ID_TARIFA = CATA.ID_TARIFA "
                                                                                                   . "   AND FACT.NIC = CATA.NIC "
                                                                                                   . "   AND FACT.ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " "
                                                                                                   . "   AND FACT.ID_TARIFA = " . $tarifa . " "
                                                                                                   . "   AND CATA.ID_ESTADO_SUMINISTRO = 3 ");
                                $row_sum_facturado_sin_contrato = mysqli_fetch_array($query_select_sum_facturado_sin_contrato);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_sin_contrato['TOTAL_FACTURADO_SIN_CONTRATO'], 0, ',', '.') . "</td>";
                                $query_select_sum_facturado_baja_forzada = mysqli_query($connection, "SELECT SUM(FACT.IMPORTE_TRANS) AS TOTAL_FACTURADO_BAJA_FORZADA "
                                                                                                   . "  FROM $bd_tabla_facturacion FACT, $bd_tabla_catastro CATA "
                                                                                                   . " WHERE FACT.ID_COD_MPIO = CATA.ID_COD_MPIO "
                                                                                                   . "   AND FACT.ID_TARIFA = CATA.ID_TARIFA "
                                                                                                   . "   AND FACT.NIC = CATA.NIC "
                                                                                                   . "   AND FACT.ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " "
                                                                                                   . "   AND FACT.ID_TARIFA = " . $tarifa . " "
                                                                                                   . "   AND CATA.ID_ESTADO_SUMINISTRO = 4 ");
                                $row_sum_facturado_baja_forzada = mysqli_fetch_array($query_select_sum_facturado_baja_forzada);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado_baja_forzada['TOTAL_FACTURADO_BAJA_FORZADA'], 0, ',', '.') . "</td>";
                                $query_select_sum_facturado = mysqli_query($connection, "SELECT SUM(IMPORTE_TRANS) AS TOTAL_FACTURADO "
                                                                                       . " FROM $bd_tabla_facturacion "
                                                                                       . "WHERE ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " "
                                                                                       . "  AND ID_TARIFA = " . $tarifa);
                                $row_sum_facturado = mysqli_fetch_array($query_select_sum_facturado);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_facturado['TOTAL_FACTURADO'], 0, ',', '.') . "</td>";
                            $table = $table . "</tr>";
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'><b>RECAUDADO</b></td>";
                                $query_select_sum_recaudado_sit_corrc = mysqli_query($connection, "SELECT SUM(FACT.IMPORTE_TRANS) AS TOTAL_RECAUDADO_SIT_CORRC "
                                                                                                . "  FROM $bd_tabla_recaudo FACT, $bd_tabla_catastro CATA "
                                                                                                . " WHERE FACT.ID_COD_MPIO = CATA.ID_COD_MPIO "
                                                                                                . "   AND FACT.ID_TARIFA = CATA.ID_TARIFA "
                                                                                                . "   AND FACT.NIC = CATA.NIC "
                                                                                                . "   AND FACT.ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " "
                                                                                                . "   AND FACT.ID_TARIFA = " . $tarifa . " "
                                                                                                . "   AND CATA.ID_ESTADO_SUMINISTRO = 1 ");
                                $row_sum_recaudado_sit_corrc = mysqli_fetch_array($query_select_sum_recaudado_sit_corrc);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_sit_corrc['TOTAL_RECAUDADO_SIT_CORRC'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_alta_sin_fact = mysqli_query($connection, "SELECT SUM(FACT.IMPORTE_TRANS) AS TOTAL_RECAUDADO_ALTA_SIN_FACT "
                                                                                                    . "  FROM $bd_tabla_recaudo FACT, $bd_tabla_catastro CATA "
                                                                                                    . " WHERE FACT.ID_COD_MPIO = CATA.ID_COD_MPIO "
                                                                                                    . "   AND FACT.ID_TARIFA = CATA.ID_TARIFA "
                                                                                                    . "   AND FACT.NIC = CATA.NIC "
                                                                                                    . "   AND FACT.ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " "
                                                                                                    . "   AND FACT.ID_TARIFA = " . $tarifa . " "
                                                                                                    . "   AND CATA.ID_ESTADO_SUMINISTRO = 2 ");
                                $row_sum_recaudado_alta_sin_fact = mysqli_fetch_array($query_select_sum_recaudado_alta_sin_fact);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_alta_sin_fact['TOTAL_RECAUDADO_ALTA_SIN_FACT'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_sin_contrato = mysqli_query($connection, "SELECT SUM(FACT.IMPORTE_TRANS) AS TOTAL_RECAUDADO_SIN_CONTRATO "
                                                                                                   . "  FROM $bd_tabla_recaudo FACT, $bd_tabla_catastro CATA "
                                                                                                   . " WHERE FACT.ID_COD_MPIO = CATA.ID_COD_MPIO "
                                                                                                   . "   AND FACT.ID_TARIFA = CATA.ID_TARIFA "
                                                                                                   . "   AND FACT.NIC = CATA.NIC "
                                                                                                   . "   AND FACT.ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " "
                                                                                                   . "   AND FACT.ID_TARIFA = " . $tarifa . " "
                                                                                                   . "   AND CATA.ID_ESTADO_SUMINISTRO = 3 ");
                                $row_sum_recaudado_sin_contrato = mysqli_fetch_array($query_select_sum_recaudado_sin_contrato);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_sin_contrato['TOTAL_RECAUDADO_SIN_CONTRATO'], 0, ',', '.') . "</td>";
                                $query_select_sum_recaudado_baja_forzada = mysqli_query($connection, "SELECT SUM(FACT.IMPORTE_TRANS) AS TOTAL_RECAUDADO_BAJA_FORZADA "
                                                                                                   . "  FROM $bd_tabla_recaudo FACT, $bd_tabla_catastro CATA "
                                                                                                   . " WHERE FACT.ID_COD_MPIO = CATA.ID_COD_MPIO "
                                                                                                   . "   AND FACT.ID_TARIFA = CATA.ID_TARIFA "
                                                                                                   . "   AND FACT.NIC = CATA.NIC "
                                                                                                   . "   AND FACT.ID_COD_MPIO = " . $row_facturacion_tarifa['ID_MUNICIPIO'] . " "
                                                                                                   . "   AND FACT.ID_TARIFA = " . $tarifa . " "
                                                                                                   . "   AND CATA.ID_ESTADO_SUMINISTRO = 4 ");
                                $row_sum_recaudado_baja_forzada = mysqli_fetch_array($query_select_sum_recaudado_baja_forzada);
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$ </b>" . number_format($row_sum_recaudado_baja_forzada['TOTAL_RECAUDADO_BAJA_FORZADA'], 0, ',', '.') . "</td>";
                            $table = $table . "</tr>";*/
                        $table = $table . "</tbody>";
                    $table = $table . "</table>";
                    $table = $table . "<hr />";
                    $query_estrato_municipio = "";
                }
                break;
            case '4':
                require_once('../Includes/Paginacion_Resultado_Cambio_Tarifa.php');
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_CAMBIO_TARIFA * ($page - 1);
                }
                $id_ano_cambio_tarifa = $_POST['id_ano_cambio_tarifa'];
                if (strlen($_POST['id_mes_cambio_tarifa']) == 1) {
                    $id_mes_cambio_tarifa = "0" . $_POST['id_mes_cambio_tarifa'];
                } else {
                    $id_mes_cambio_tarifa = $_POST['id_mes_cambio_tarifa'];
                }
                $mes_cambio_tarifa = $_POST['mes_cambio_tarifa'];
                $bd_tabla_novedades = "novedades_" . $mes_cambio_tarifa . $id_ano_cambio_tarifa . "_2";
                $query_select_info_cambio_tarifa = mysqli_query($connection, " SELECT * "
                                                                           . "   FROM $bd_tabla_novedades "
                                                                           . "  WHERE COD_TARIFA_ACTUAL <> COD_TARIFA_ANTERIOR "
                                                                           . "  ORDER BY NIC"
                                                                            . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_CAMBIO_TARIFA);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO " . $id_ano_cambio_tarifa . $id_mes_cambio_tarifa . "</p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=10%>NIC</th>";
                            $table = $table . "<th width=8%>TAR. ACTUAL</th>";
                            $table = $table . "<th width=8%>TAR. ANTER.</th>";
                            $table = $table . "<th width=10%>FECHA CAMBIO</th>";
                            $table = $table . "<th width=24%>TIPO NOVEDAD</th>";
                            $table = $table . "<th width=20%>ESTADO ACTUAL</th>";
                            $table = $table . "<th width=20%>ESTADO ANTERIOR</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                    while ($row_cambio_tarifa = mysqli_fetch_assoc($query_select_info_cambio_tarifa)) {
                        $table = $table . "<tr>";
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_cambio_tarifa['NIC'] . "</td>";
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_cambio_tarifa['COD_TARIFA_ACTUAL'] . "</td>";
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_cambio_tarifa['COD_TARIFA_ANTERIOR'] . "</td>";
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_cambio_tarifa['FECHA_CAMBIO_TARIFA'] . "</td>";
                            $query_select_tipo_novedad = mysqli_query($connection, " SELECT NOMBRE "
                                                                                 . "   FROM tipo_novedades_2 "
                                                                                 . "  WHERE ID_TIPO_NOVEDAD = " . $row_cambio_tarifa['ID_TIPO_NOVEDAD']);
                            $row_tipo_novedad = mysqli_fetch_array($query_select_tipo_novedad);
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_tipo_novedad['NOMBRE'] . "</td>";
                            $query_select_estado_actual_consumo = mysqli_query($connection, " SELECT NOMBRE "
                                                                                          . "   FROM estados_suministro_2 "
                                                                                          . "  WHERE ID_ESTADO_SUMINISTRO = " . $row_cambio_tarifa['ID_ESTADO_ACTUAL']);
                            $row_estado_actual_suministro = mysqli_fetch_array($query_select_estado_actual_consumo);
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_estado_actual_suministro['NOMBRE'] . "</td>";
                            $query_select_estado_anterior_consumo = mysqli_query($connection, " SELECT NOMBRE "
                                                                                          . "   FROM estados_suministro_2 "
                                                                                          . "  WHERE ID_ESTADO_SUMINISTRO = " . $row_cambio_tarifa['ID_ESTADO_ANTERIOR']);
                            $row_estado_anterior_suministro = mysqli_fetch_array($query_select_estado_anterior_consumo);
                            $table = $table . "<td style='vertical-align:middle;'>" . $row_estado_anterior_suministro['NOMBRE'] . "</td>";
                        $table = $table . "</tr>";
                    }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                break;
        }
        echo $table;
    }
?>