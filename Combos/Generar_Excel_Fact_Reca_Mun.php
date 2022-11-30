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
            case '0':
                $query_select_nombre_departamento = mysqli_query($connection, "SELECT NOMBRE "
                                                                            . "  FROM departamentos_visitas_2 "
                                                                            . " WHERE ID_DEPARTAMENTO = " . $_GET['departamento']);
                $row_nombre_departamento = mysqli_fetch_array($query_select_nombre_departamento);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE "
                                                                         . "  FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $_GET['departamento'] . " "
                                                                         . "   AND ID_MUNICIPIO = " . $_GET['municipio']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                if (strlen($_GET['id_mes']) == 1) {
                    $filename = "Reporte Aportes Municipales " . ucfirst($row_nombre_departamento['NOMBRE']) . " - " . ucfirst($row_nombre_municipio['NOMBRE']) . " - Periodo " . $_GET['id_ano'] . "0" . $_GET['id_mes'] . ".xls";
                } else {
                    $filename = "Reporte Aportes Municipales " . ucfirst($row_nombre_departamento['NOMBRE']) . " - " . ucfirst($row_nombre_municipio['NOMBRE']) . " - Periodo " . $_GET['id_ano'] . $_GET['id_mes'] . ".xls";
                }
                $query_select_info_municipio = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                          . "    MV.NOMBRE AS MUNICIPIO, "
                                                                          . "    USU.NOMBRE AS USUARIO, "
                                                                          . "    FM.CONSECUTIVO_FACT AS FACTURA, "
                                                                          . "    FM.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                          . "    FM.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                          . "    FM.FECHA_ENTREGA AS FECHA_ENTREGA, "
                                                                          . "    FM.FECHA_VENCIMIENTO AS FECHA_VENCIMIENTO, "
                                                                          . "    FM.PERIODO_FACTURA AS PERIODO, "
                                                                          . "    FM.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                          . "    FM.ID_FACTURACION AS ID_FACTURACION, "
                                                                          . "    FM.NO_CC_VENCIDAS AS CC_VENCIDAS, "
                                                                          . "    FM.OBSERVACIONES AS OBSERVACIONES "
                                                                          . "  FROM facturacion_municipales_2 FM, "
                                                                          . "       departamentos_visitas_2 DV, "
                                                                          . "       municipios_visitas_2 MV, "
                                                                          . "       usuarios_2 USU "
                                                                          . " WHERE FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                          . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                          . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                          . "   AND FM.ID_USUARIO = USU.ID_USUARIO "
                                                                          . "   AND YEAR(FM.FECHA_FACTURA) = " . $_GET['id_ano'] . ""
                                                                          . "   AND MONTH(FM.FECHA_FACTURA) = " . $_GET['id_mes'] . ""
                                                                          . "   AND MV.ID_DEPARTAMENTO = " . $_GET['departamento'] . " "
                                                                          . "   AND MV.ID_MUNICIPIO = " . $_GET['municipio'] . " "
                                                                          . " ORDER BY DV.NOMBRE, MV.NOMBRE, FM.FECHA_FACTURA DESC ");
                /*$flag = false;
                while ($row_info_municipio = mysqli_fetch_assoc($query_select_info_municipio)) {
                    if (!$flag) {
                        echo implode("\t", array_keys($row_info_municipio)) . "\r\n";
                        $flag = true;
                    }
                    echo implode("\t", array_values($row_info_municipio)) . "\r\n";
                }*/
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>C. COBRO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA ENTREGA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA VENCIMIENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>C.C. VENCIDAS</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. RECAUDO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_municipio = mysqli_fetch_assoc($query_select_info_municipio)) {
                            $estado = "";
                            $recaudo = 0;
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_municipio['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FECHA_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FECHA_ENTREGA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FECHA_VENCIMIENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['PERIODO'] . "</td>";
                                switch ($row_info_municipio['ESTADO_FACTURA']) {
                                    case "1":
                                        $table = $table . "<td style='vertical-align:middle;'>ENTREGADO</td>";
                                        break;
                                    case "2":
                                        $table = $table . "<td style='vertical-align:middle;'>PENDIENTE ENVIO</td>";
                                        break;
                                    case "3":
                                        $table = $table . "<td style='vertical-align:middle;'>RECLAMADA</td>";
                                        break;
                                    case "6":
                                        $table = $table . "<td style='vertical-align:middle;'>PAGADO ACUERDO</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['OBSERVACIONES'] . "</td>";
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                                     . " WHERE ID_FACTURACION = '" . $row_info_municipio['ID_FACTURACION'] . "'");
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    while ($row_info_recaudo = mysqli_fetch_assoc($query_select_info_recaudo)) {
                                        $recaudo = $recaudo + $row_info_recaudo['VALOR_RECAUDO'];
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($recaudo, 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "ENTREGADO";
                                            break;
                                        case "2":
                                            $estado = "PENDIENTE ENVIO";
                                            break;
                                        case "3":
                                            $estado = "RECLAMADA";
                                            break;
                                        case "4":
                                            $estado = "PAGADA";
                                            break;
                                        case "5":
                                            $estado = "PAGO PARCIAL";
                                            break;
                                        case "6":
                                            $estado = "PAGADO ACUERDO";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['OBSERVACIONES'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $estado = "";
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                }
                                /*if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "ENTREGADO";
                                            break;
                                        case "2":
                                            $estado = "PENDIENTE ENVIO";
                                            break;
                                        case "3":
                                            $estado = "RECLAMADA";
                                            break;
                                        case "4":
                                            $estado = "PAGADO";
                                            break;
                                        case "5":
                                            $estado = "PAGO PARCIAL";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['OBSERVACIONES'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $estado = "";
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                }*/
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                header("Content-Type: application/xls");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $table;
                break;
            case '4':
                if (strlen($_GET['id_mes']) == 1) {
                    $filename = "Reporte Aportes Municipales - Periodo " . $_GET['id_ano'] . "0" . $_GET['id_mes'] . ".xls";
                } else {
                    $filename = "Reporte Aportes Municipales - Periodo " . $_GET['id_ano'] . $_GET['id_mes'] . ".xls";
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FM.CONSECUTIVO_FACT AS FACTURA, "
                                                                        . "    FM.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FM.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FM.FECHA_ENTREGA AS FECHA_ENTREGA, "
                                                                        . "    FM.FECHA_VENCIMIENTO AS FECHA_VENCIMIENTO, "
                                                                        . "    FM.PERIODO_FACTURA AS PERIODO, "
                                                                        . "    FM.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                        . "    FM.ID_FACTURACION AS ID_FACTURACION, "
                                                                        . "    FM.OBSERVACIONES AS OBSERVACIONES, "
                                                                        . "    FM.NO_CC_VENCIDAS AS CC_VENCIDAS "
                                                                        . "  FROM facturacion_municipales_2 FM, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV, "
                                                                        . "       usuarios_2 USU "
                                                                        . " WHERE FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        . "   AND FM.ID_USUARIO = USU.ID_USUARIO "
                                                                        //. "   AND YEAR(FM.FECHA_FACTURA) = " . $_GET['id_ano'] . ""
                                                                        //. "   AND MONTH(FM.FECHA_FACTURA) = " . $_GET['id_mes'] . ""
                                                                        . "   AND FM.PERIODO_FACTURA = " . $_GET['id_ano'] . $_GET['id_mes']
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FM.FECHA_FACTURA DESC ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>C. COBRO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA ENTREGA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA VENCIMIENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>C.C. VENCIDAS</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. RECAUDO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                    while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                            $estado = "";
                            $recaudo = 0;
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FECHA_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FECHA_ENTREGA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FECHA_VENCIMIENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['PERIODO'] . "</td>";
                                switch ($row_info_mensual['ESTADO_FACTURA']) {
                                    case "1":
                                        $table = $table . "<td style='vertical-align:middle;'>ENTREGADO</td>";
                                        break;
                                    case "2":
                                        $table = $table . "<td style='vertical-align:middle;'>PENDIENTE ENVIO</td>";
                                        break;
                                    case "3":
                                        $table = $table . "<td style='vertical-align:middle;'>RECLAMADA</td>";
                                        break;
                                    case "6":
                                        $table = $table . "<td style='vertical-align:middle;'>PAGADO ACUERDO</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['OBSERVACIONES'] . "</td>";
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                                     . " WHERE ID_FACTURACION = '" . $row_info_mensual['ID_FACTURACION'] . "'");
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    while ($row_info_recaudo = mysqli_fetch_assoc($query_select_info_recaudo)) {
                                        $recaudo = $recaudo + $row_info_recaudo['VALOR_RECAUDO'];
                                    }
                                    $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($recaudo, 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "ENTREGADO";
                                            break;
                                        case "2":
                                            $estado = "PENDIENTE ENVIO";
                                            break;
                                        case "3":
                                            $estado = "RECLAMADA";
                                            break;
                                        case "4":
                                            $estado = "PAGADA";
                                            break;
                                        case "5":
                                            $estado = "PAGO PARCIAL";
                                            break;
                                        case "6":
                                            $estado = "PAGADO ACUERDO";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['OBSERVACIONES'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $estado = "";
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                }
                                /*$row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "ENTREGADO";
                                            break;
                                        case "2":
                                            $estado = "PENDIENTE ENVIO";
                                            break;
                                        case "3":
                                            $estado = "RECLAMADA";
                                            break;
                                        case "4":
                                            $estado = "PAGADO";
                                            break;
                                        case "5":
                                            $estado = "PAGO PARCIAL";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['OBSERVACIONES'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $estado = "";
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                }*/
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                header("Content-Type: application/xls");
                header("Content-Disposition: attachment; filename=\"$filename\"");
                echo $table;
                break;
            case '5':
                $filename = "Reporte Aportes Municipales - Rango " . $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin'] . ".xls";
                $query_select_info_rango = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        //. "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FM.CONSECUTIVO_FACT AS FACTURA, "
                                                                        . "    FM.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FM.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FM.FECHA_ENTREGA AS FECHA_ENTREGA, "
                                                                        . "    FM.FECHA_VENCIMIENTO AS FECHA_VENCIMIENTO, "
                                                                        . "    FM.PERIODO_FACTURA AS PERIODO, "
                                                                        . "    FM.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                        . "    FM.ID_FACTURACION AS ID_FACTURACION, "
                                                                        . "    FM.OBSERVACIONES AS OBSERVACIONES, "
                                                                        . "    FM.NO_CC_VENCIDAS AS CC_VENCIDAS "
                                                                        . "  FROM facturacion_municipales_2 FM, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV "
                                                                        //. "       usuarios_2 USU "
                                                                        . " WHERE FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        //. "   AND FM.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND FM.FECHA_FACTURA BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "' "
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FM.FECHA_FACTURA DESC ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>C. COBRO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA ENTREGA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA VENCIMIENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. C.C.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>C.C. VENCIDAS</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. RECAUDO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                            $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                                 . " WHERE ID_FACTURACION = '" . $row_info_rango['ID_FACTURACION'] . "'");
                            $sw = 0;
                            $estado = "";
                            $recaudo = 0;
                            if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                while ($row_info_recaudo = mysqli_fetch_assoc($query_select_info_recaudo)) {
                                    if ($sw == 0) {
                                        $table = $table . "<tr>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['DEPARTAMENTO'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['MUNICIPIO'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FACTURA'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_FACTURA'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_ENTREGA'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_VENCIMIENTO'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['PERIODO'] . "</td>";
                                            switch ($row_info_rango['ESTADO_FACTURA']) {
                                                case "1":
                                                    $table = $table . "<td style='vertical-align:middle;'>ENTREGADO</td>";
                                                    break;
                                                case "2":
                                                    $table = $table . "<td style='vertical-align:middle;'>PENDIENTE ENVIO</td>";
                                                    break;
                                                case "3":
                                                    $table = $table . "<td style='vertical-align:middle;'>RECLAMADA</td>";
                                                    break;
                                                case "6":
                                                    $table = $table . "<td style='vertical-align:middle;'>PAGADO ACUERDO</td>";
                                                    break;
                                            }
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['OBSERVACIONES'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['CC_VENCIDAS'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                            switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                                case "1":
                                                    $estado = "ENTREGADO";
                                                    break;
                                                case "2":
                                                    $estado = "PENDIENTE ENVIO";
                                                    break;
                                                case "3":
                                                    $estado = "RECLAMADA";
                                                    break;
                                                case "4":
                                                    $estado = "PAGADA";
                                                    break;
                                                case "5":
                                                    $estado = "PAGO PARCIAL";
                                                    break;
                                                case "6":
                                                    $estado = "PAGADO ACUERDO";
                                                    break;
                                            }
                                            $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['OBSERVACIONES'] . "</td>";
                                            $sw = 1;
                                        $table = $table . "</tr>";
                                    } else {
                                        $table = $table . "<tr>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['DEPARTAMENTO'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['MUNICIPIO'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FACTURA'] . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'></td>";
                                            $table = $table . "<td style='vertical-align:middle;'></td>";
                                            $table = $table . "<td style='vertical-align:middle;'></td>";
                                            $table = $table . "<td style='vertical-align:middle;'></td>";
                                            $table = $table . "<td style='vertical-align:middle;'></td>";
                                            switch ($row_info_rango['ESTADO_FACTURA']) {
                                                case "1":
                                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                                    break;
                                                case "2":
                                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                                    break;
                                                case "3":
                                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                                    break;
                                                case "6":
                                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                                    break;
                                            }
                                            $table = $table . "<td style='vertical-align:middle;'></td>";
                                            $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                            $table = $table . "<td style='vertical-align:middle;'></td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                            switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                                case "1":
                                                    $estado = "ENTREGADO";
                                                    break;
                                                case "2":
                                                    $estado = "PENDIENTE ENVIO";
                                                    break;
                                                case "3":
                                                    $estado = "RECLAMADA";
                                                    break;
                                                case "4":
                                                    $estado = "PAGADA";
                                                    break;
                                                case "5":
                                                    $estado = "PAGO PARCIAL";
                                                    break;
                                                case "6":
                                                    $estado = "PAGADO ACUERDO";
                                                    break;
                                            }
                                            $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                            $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['OBSERVACIONES'] . "</td>";
                                            $sw = 1;
                                        $table = $table . "</tr>";
                                    }
                                }
                            } else {
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['DEPARTAMENTO'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['MUNICIPIO'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FACTURA'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_FACTURA'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_ENTREGA'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_VENCIMIENTO'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['PERIODO'] . "</td>";
                                    switch ($row_info_rango['ESTADO_FACTURA']) {
                                        case "1":
                                            $table = $table . "<td style='vertical-align:middle;'>ENTREGADO</td>";
                                            break;
                                        case "2":
                                            $table = $table . "<td style='vertical-align:middle;'>PENDIENTE ENVIO</td>";
                                            break;
                                        case "3":
                                            $table = $table . "<td style='vertical-align:middle;'>RECLAMADA</td>";
                                            break;
                                        case "6":
                                            $table = $table . "<td style='vertical-align:middle;'>PAGADO ACUERDO</td>";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['OBSERVACIONES'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $sw = 1;
                                $table = $table . "</tr>";
                            }
                            /*$table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_ENTREGA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_VENCIMIENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['PERIODO'] . "</td>";
                                switch ($row_info_rango['ESTADO_FACTURA']) {
                                    case "1":
                                        $table = $table . "<td style='vertical-align:middle;'>ENTREGADO</td>";
                                        break;
                                    case "2":
                                        $table = $table . "<td style='vertical-align:middle;'>PENDIENTE ENVIO</td>";
                                        break;
                                    case "3":
                                        $table = $table . "<td style='vertical-align:middle;'>RECLAMADA</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['OBSERVACIONES'] . "</td>";
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                                     . " WHERE ID_FACTURACION = '" . $row_info_rango['ID_FACTURACION'] . "'");
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    while ($row_info_recaudo = mysqli_fetch_assoc($query_select_info_recaudo)) {
                                        $recaudo = $recaudo + $row_info_recaudo['VALOR_RECAUDO'];
                                    }
                                    //$row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($recaudo, 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['CC_VENCIDAS'] . "</td>";
                                    $query_select_info_recaudo_2 = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                                         . " WHERE ID_FACTURACION = '" . $row_info_rango['ID_FACTURACION'] . "'");
                                    $row_info_recaudo_2 = mysqli_fetch_array($query_select_info_recaudo_2);
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo_2['FECHA_PAGO_BITACORA'] . "</td>";
                                    switch ($row_info_recaudo_2['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "ENTREGADO";
                                            break;
                                        case "2":
                                            $estado = "PENDIENTE ENVIO";
                                            break;
                                        case "3":
                                            $estado = "RECLAMADA";
                                            break;
                                        case "4":
                                            $estado = "PAGADO";
                                            break;
                                        case "5":
                                            $estado = "PAGO PARCIAL";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo_2['OBSERVACIONES'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $estado = "";
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                }
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "ENTREGADO";
                                            break;
                                        case "2":
                                            $estado = "PENDIENTE ENVIO";
                                            break;
                                        case "3":
                                            $estado = "RECLAMADA";
                                            break;
                                        case "4":
                                            $estado = "PAGADO";
                                            break;
                                        case "5":
                                            $estado = "PAGO PARCIAL";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['OBSERVACIONES'] . "</td>";
                                } else {
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['CC_VENCIDAS'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $estado = "";
                                    $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                }
                            $table = $table . "</tr>";*/
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