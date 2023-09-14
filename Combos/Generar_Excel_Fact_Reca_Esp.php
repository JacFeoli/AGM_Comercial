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
                    $filename = "Reporte Cliente Especiales " . ucfirst($row_nombre_departamento['NOMBRE']) . " - " . ucfirst($row_nombre_municipio['NOMBRE']) . " - Periodo " . $_GET['id_ano'] . "0" . $_GET['id_mes'] . ".xls";
                } else {
                    $filename = "Reporte Cliente Especiales " . ucfirst($row_nombre_departamento['NOMBRE']) . " - " . ucfirst($row_nombre_municipio['NOMBRE']) . " - Periodo " . $_GET['id_ano'] . $_GET['id_mes'] . ".xls";
                }
                $query_select_info_municipio = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                          . "    MV.NOMBRE AS MUNICIPIO, "
                                                                          . "    CONT.NOMBRE AS CONTRIBUYENTE, "
                                                                          . "    CONT.NIT_CONTRIBUYENTE AS NIT, "
                                                                          . "    FE.ID_TIPO_CLIENTE AS TIPO_CLIENTE, "
                                                                          . "    USU.NOMBRE AS USUARIO, "
                                                                          . "    FE.CONSECUTIVO_FACT AS FACTURA, "
                                                                          . "    FE.TARIFA AS TARIFA, "
                                                                          . "    FE.VALOR_TARIFA AS VALOR_TARIFA, "
                                                                          . "    FE.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                          . "    FE.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                          . "    FE.FECHA_ENTREGA AS FECHA_ENTREGA, "
                                                                          . "    FE.FECHA_VENCIMIENTO AS FECHA_VENCIMIENTO, "
                                                                          . "    FE.PERIODO_FACTURA AS PERIODO, "
                                                                          . "    FE.ID_TIPO_FACTURACION AS TIPO_FACTURACION, "
                                                                          . "    FE.ID_COMERCIALIZADOR AS COMERCIALIZADOR, "
                                                                          . "    FE.ID_FACTURADO_POR AS FACTURADO_POR, "
                                                                          . "    FE.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                          . "    FE.ID_FACTURACION AS ID_FACTURACION, "
                                                                          . "    FE.OBSERVACIONES AS OBSERVACIONES, "
                                                                          . "    FE.VALOR_LIQ_VENCIDAS AS VALOR_LIQ_VENCIDAS "
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
                                                                          . "   AND YEAR(FE.FECHA_FACTURA) = " . $_GET['id_ano'] . ""
                                                                          . "   AND MONTH(FE.FECHA_FACTURA) = " . $_GET['id_mes'] . ""
                                                                          . "   AND MV.ID_DEPARTAMENTO = " . $_GET['departamento'] . " "
                                                                          . "   AND MV.ID_MUNICIPIO = " . $_GET['municipio'] . " "
                                                                          . " ORDER BY DV.NOMBRE, MV.NOMBRE, FE.FECHA_FACTURA DESC ");
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
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CONTRIBUYENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>NIT</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO CLIENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TARIFA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR TARIFA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA ENTREGA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA VENCIMIENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>COMERCIALIZADOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACTURADO POR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARTERA FECHA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARTERA VENCIDA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. SOPORTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. RECAUDO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_municipio = mysqli_fetch_assoc($query_select_info_municipio)) {
                            $estado = "";
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['CONTRIBUYENTE'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['NIT'] . "</td>";
                                switch ($row_info_municipio['TIPO_CLIENTE']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>ANTIGUO</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>NUEVO</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FACTURA'] . "</td>";
                                switch ($row_info_municipio['TIPO_FACTURACION']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>CONSUMO</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>SALARIOS</td>";
                                        break;
                                    case '3':
                                        $table = $table . "<td style='vertical-align:middle;'>UVT</td>";
                                        break;
                                    case '4':
                                        $table = $table . "<td style='vertical-align:middle;'>COMERCIAL</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['TARIFA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_municipio['VALOR_TARIFA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_municipio['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FECHA_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FECHA_ENTREGA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FECHA_VENCIMIENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['PERIODO'] . "</td>";
                                
                                $query_select_comercializador = mysqli_query($connection, "SELECT NOMBRE FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = '" . $row_info_municipio['COMERCIALIZADOR'] . "'");
                                $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                                switch ($row_info_municipio['FACTURADO_POR']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>COMERCIALIZADOR</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>CUENTA DE COBRO</td>";
                                        break;
                                    case '3':
                                        $table = $table . "<td style='vertical-align:middle;'>RESOLUCION</td>";
                                        break;
                                }
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
                                    case "4":
                                        $table = $table . "<td style='vertical-align:middle;'>ANULADA</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['OBSERVACIONES'] . "</td>";
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 "
                                                                                     . " WHERE ID_FACTURACION = '" . $row_info_municipio['ID_FACTURACION'] . "'");
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_municipio['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_municipio['VALOR_LIQ_VENCIDAS'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_SOPORTE'] . "</td>";
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
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_municipio['VALOR_FACTURA'] - 0, 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_municipio['VALOR_LIQ_VENCIDAS'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
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
            case '1':
                $query_select_contribuyente = mysqli_query($connection, "SELECT NOMBRE "
                                                                         . "  FROM contribuyentes_2 "
                                                                         . " WHERE ID_CONTRIBUYENTE = " . $_GET['contribuyente']);
                $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                if (strlen($_GET['id_mes']) == 1) {
                    $filename = "Reporte Cliente Especiales " . ucfirst($row_contribuyente['NOMBRE']) . " - Periodo " . $_GET['id_ano'] . "0" . $_GET['id_mes'] . ".xls";
                } else {
                    $filename = "Reporte Cliente Especiales " . ucfirst($row_contribuyente['NOMBRE']) . " - Periodo " . $_GET['id_ano'] . $_GET['id_mes'] . ".xls";
                }
                $query_info_contribuyente = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                       . "    MV.NOMBRE AS MUNICIPIO, "
                                                                       . "    CONT.NOMBRE AS CONTRIBUYENTE, "
                                                                       . "    CONT.NIT_CONTRIBUYENTE AS NIT, "
                                                                       . "    FE.ID_TIPO_CLIENTE AS TIPO_CLIENTE, "
                                                                       . "    USU.NOMBRE AS USUARIO, "
                                                                       . "    FE.CONSECUTIVO_FACT AS FACTURA, "
                                                                       . "    FE.TARIFA AS TARIFA, "
                                                                       . "    FE.VALOR_TARIFA AS VALOR_TARIFA, "
                                                                       . "    FE.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                       . "    FE.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                       . "    FE.FECHA_ENTREGA AS FECHA_ENTREGA, "
                                                                       . "    FE.FECHA_VENCIMIENTO AS FECHA_VENCIMIENTO, "
                                                                       . "    FE.PERIODO_FACTURA AS PERIODO, "
                                                                       . "    FE.ID_TIPO_FACTURACION AS TIPO_FACTURACION, "
                                                                       . "    FE.ID_COMERCIALIZADOR AS COMERCIALIZADOR, "
                                                                       . "    FE.ID_FACTURADO_POR AS FACTURADO_POR, "
                                                                       . "    FE.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                       . "    FE.ID_FACTURACION AS ID_FACTURACION, "
                                                                       . "    FE.OBSERVACIONES AS OBSERVACIONES, "
                                                                       . "    FE.VALOR_LIQ_VENCIDAS AS VALOR_LIQ_VENCIDAS "
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
                                                                    . "   AND YEAR(FE.FECHA_FACTURA) = " . $_GET['id_ano'] . ""
                                                                    . "   AND MONTH(FE.FECHA_FACTURA) = " . $_GET['id_mes'] . ""
                                                                    . "   AND FE.ID_CONTRIBUYENTE = " . $_GET['contribuyente'] . ""
                                                                    . " ORDER BY DV.NOMBRE, MV.NOMBRE, FE.FECHA_FACTURA DESC ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CONTRIBUYENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>NIT</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO CLIENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TARIFA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR TARIFA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA ENTREGA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA VENCIMIENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>COMERCIALIZADOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACTURADO POR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARTERA FECHA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARTERA VENCIDA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. SOPORTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. RECAUDO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                    while ($row_info_contribuyente = mysqli_fetch_assoc($query_info_contribuyente)) {
                            $estado = "";
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['CONTRIBUYENTE'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['NIT'] . "</td>";
                                switch ($row_info_contribuyente['TIPO_CLIENTE']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>ANTIGUO</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>NUEVO</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['FACTURA'] . "</td>";
                                switch ($row_info_contribuyente['TIPO_FACTURACION']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>CONSUMO</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>SALARIOS</td>";
                                        break;
                                    case '3':
                                        $table = $table . "<td style='vertical-align:middle;'>UVT</td>";
                                        break;
                                    case '4':
                                        $table = $table . "<td style='vertical-align:middle;'>COMERCIAL</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['TARIFA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_contribuyente['VALOR_TARIFA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_contribuyente['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['FECHA_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['FECHA_ENTREGA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['FECHA_VENCIMIENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['PERIODO'] . "</td>";
                                $query_select_comercializador = mysqli_query($connection, "SELECT NOMBRE FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = '" . $row_info_contribuyente['COMERCIALIZADOR'] . "'");
                                $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                                switch ($row_info_contribuyente['FACTURADO_POR']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>COMERCIALIZADOR</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>CUENTA DE COBRO</td>";
                                        break;
                                    case '3':
                                        $table = $table . "<td style='vertical-align:middle;'>RESOLUCION</td>";
                                        break;
                                }
                                switch ($row_info_contribuyente['ESTADO_FACTURA']) {
                                    case "1":
                                        $table = $table . "<td style='vertical-align:middle;'>ENTREGADO</td>";
                                        break;
                                    case "2":
                                        $table = $table . "<td style='vertical-align:middle;'>PENDIENTE ENVIO</td>";
                                        break;
                                    case "3":
                                        $table = $table . "<td style='vertical-align:middle;'>RECLAMADA</td>";
                                        break;
                                    case "4":
                                        $table = $table . "<td style='vertical-align:middle;'>ANULADA</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_contribuyente['OBSERVACIONES'] . "</td>";
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 "
                                                                                     . " WHERE ID_FACTURACION = '" . $row_info_contribuyente['ID_FACTURACION'] . "'");
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_contribuyente['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_contribuyente['VALOR_LIQ_VENCIDAS'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_SOPORTE'] . "</td>";
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
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_contribuyente['VALOR_FACTURA'] - 0, 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_contribuyente['VALOR_LIQ_VENCIDAS'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
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
            case '2':
                
                break;
            case '3':
                
                break;
            case '4':
                if (strlen($_GET['id_mes']) == 1) {
                    $filename = "Reporte Cliente Especiales - Periodo " . $_GET['id_ano'] . "0" . $_GET['id_mes'] . ".xls";
                } else {
                    $filename = "Reporte Cliente Especiales - Periodo " . $_GET['id_ano'] . $_GET['id_mes'] . ".xls";
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        . "    CONT.NOMBRE AS CONTRIBUYENTE, "
                                                                        . "    CONT.NIT_CONTRIBUYENTE AS NIT, "
                                                                        . "    FE.ID_TIPO_CLIENTE AS TIPO_CLIENTE, "
                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FE.CONSECUTIVO_FACT AS FACTURA, "
                                                                        . "    FE.TARIFA AS TARIFA, "
                                                                        . "    FE.VALOR_TARIFA AS VALOR_TARIFA, "
                                                                        . "    FE.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FE.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FE.FECHA_ENTREGA AS FECHA_ENTREGA, "
                                                                        . "    FE.FECHA_VENCIMIENTO AS FECHA_VENCIMIENTO, "
                                                                        . "    FE.PERIODO_FACTURA AS PERIODO, "
                                                                        . "    FE.ID_TIPO_FACTURACION AS TIPO_FACTURACION, "
                                                                        . "    FE.ID_COMERCIALIZADOR AS COMERCIALIZADOR, "
                                                                        . "    FE.ID_FACTURADO_POR AS FACTURADO_POR, "
                                                                        . "    FE.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                        . "    FE.ID_FACTURACION AS ID_FACTURACION, "
                                                                        . "    FE.OBSERVACIONES AS OBSERVACIONES, "
                                                                        . "    FE.VALOR_LIQ_VENCIDAS AS VALOR_LIQ_VENCIDAS "
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
                                                                        . "   AND YEAR(FE.FECHA_FACTURA) = " . $_GET['id_ano'] . ""
                                                                        . "   AND MONTH(FE.FECHA_FACTURA) = " . $_GET['id_mes'] . ""
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FE.FECHA_FACTURA DESC ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CONTRIBUYENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>NIT</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO CLIENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TARIFA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR TARIFA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA ENTREGA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA VENCIMIENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>COMERCIALIZADOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACTURADO POR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARTERA FECHA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARTERA VENCIDA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. SOPORTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. RECAUDO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                    while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                            $estado = "";
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['CONTRIBUYENTE'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NIT'] . "</td>";
                                switch ($row_info_mensual['TIPO_CLIENTE']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>ANTIGUO</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>NUEVO</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FACTURA'] . "</td>";
                                switch ($row_info_mensual['TIPO_FACTURACION']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>CONSUMO</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>SALARIOS</td>";
                                        break;
                                    case '3':
                                        $table = $table . "<td style='vertical-align:middle;'>UVT</td>";
                                        break;
                                    case '4':
                                        $table = $table . "<td style='vertical-align:middle;'>COMERCIAL</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['TARIFA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_TARIFA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FECHA_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FECHA_ENTREGA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FECHA_VENCIMIENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['PERIODO'] . "</td>";
                                $query_select_comercializador = mysqli_query($connection, "SELECT NOMBRE FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = '" . $row_info_mensual['COMERCIALIZADOR'] . "'");
                                $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                                switch ($row_info_mensual['FACTURADO_POR']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>COMERCIALIZADOR</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>CUENTA DE COBRO</td>";
                                        break;
                                    case '3':
                                        $table = $table . "<td style='vertical-align:middle;'>RESOLUCION</td>";
                                        break;
                                }
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
                                    case "4":
                                        $table = $table . "<td style='vertical-align:middle;'>ANULADA</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['OBSERVACIONES'] . "</td>";
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 "
                                                                                     . " WHERE ID_FACTURACION = '" . $row_info_mensual['ID_FACTURACION'] . "'");
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_LIQ_VENCIDAS'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_SOPORTE'] . "</td>";
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
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_FACTURA'] - 0, 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_mensual['VALOR_LIQ_VENCIDAS'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
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
                $filename = "Reporte Clientes Especiales - Rango " . $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin'] . ".xls";
                $query_select_info_rango = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        . "    CONT.NOMBRE AS CONTRIBUYENTE, "
                                                                        . "    CONT.NIT_CONTRIBUYENTE AS NIT, "
                                                                        . "    FE.ID_TIPO_CLIENTE AS TIPO_CLIENTE, "
                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FE.CONSECUTIVO_FACT AS FACTURA, "
                                                                        . "    FE.TARIFA AS TARIFA, "
                                                                        . "    FE.VALOR_TARIFA AS VALOR_TARIFA, "
                                                                        . "    FE.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FE.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FE.FECHA_ENTREGA AS FECHA_ENTREGA, "
                                                                        . "    FE.FECHA_VENCIMIENTO AS FECHA_VENCIMIENTO, "
                                                                        . "    FE.PERIODO_FACTURA AS PERIODO, "
                                                                        . "    FE.ID_TIPO_FACTURACION AS TIPO_FACTURACION, "
                                                                        . "    FE.ID_COMERCIALIZADOR AS COMERCIALIZADOR, "
                                                                        . "    FE.ID_FACTURADO_POR AS FACTURADO_POR, "
                                                                        . "    FE.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                        . "    FE.ID_FACTURACION AS ID_FACTURACION, "
                                                                        . "    FE.OBSERVACIONES AS OBSERVACIONES, "
                                                                        . "    FE.VALOR_LIQ_VENCIDAS AS VALOR_LIQ_VENCIDAS "
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
                                                                        . "   AND FE.FECHA_FACTURA BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "' "
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FE.FECHA_FACTURA DESC ");
                $table = "";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CONTRIBUYENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>NIT</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO CLIENTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO FACT.</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TARIFA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR TARIFA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIA FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MES FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ANO FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIA MORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>EDAD CARTERA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA ENTREGA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA VENCIMIENTO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PERIODO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>COMERCIALIZADOR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FACTURADO POR</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. FACTURA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>VALOR RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARTERA FECHA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARTERA VENCIDA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. SOPORTE</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIA RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MES RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ANO RECA. BITACORA</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO RECAUDO</th>";
                            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERV. RECAUDO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                    while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                            $estado = "";
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['CONTRIBUYENTE'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['NIT'] . "</td>";
                                switch ($row_info_rango['TIPO_CLIENTE']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>ANTIGUO</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>NUEVO</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FACTURA'] . "</td>";
                                switch ($row_info_rango['TIPO_FACTURACION']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>CONSUMO</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>SALARIOS</td>";
                                        break;
                                    case '3':
                                        $table = $table . "<td style='vertical-align:middle;'>UVT</td>";
                                        break;
                                    case '4':
                                        $table = $table . "<td style='vertical-align:middle;'>COMERCIAL</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['TARIFA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_TARIFA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_info_rango['FECHA_FACTURA'], 8, 2) . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_info_rango['FECHA_FACTURA'], 5, 2) . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . substr($row_info_rango['FECHA_FACTURA'], 0, 4) . "</td>";
                                $hoy = time();
                                $fecha_factura = strtotime($row_info_rango['FECHA_FACTURA']);
                                $diff = $hoy - $fecha_factura;
                                $table = $table . "<td style='vertical-align:middle;'>" . round($diff / (60 * 60 * 24)) . "</td>";
                                switch (round($diff / (60 * 60 * 24))) {
                                    case (round($diff / (60 * 60 * 24)) > 30 && round($diff / (60 * 60 * 24)) <= 60):
                                        $table = $table . "<td style='vertical-align:middle;'>MAYOR QUE 30 DIAS</td>";
                                        break;
                                    case (round($diff / (60 * 60 * 24)) > 60 && round($diff / (60 * 60 * 24)) <= 90):
                                        $table = $table . "<td style='vertical-align:middle;'>MAYOR QUE 60 DIAS</td>";
                                        break;
                                    case (round($diff / (60 * 60 * 24)) > 90):
                                        $table = $table . "<td style='vertical-align:middle;'>MAYOR QUE 90 DIAS</td>";
                                        break;
                                    default:
                                        $table = $table . "<td style='vertical-align:middle;'>AL DIA</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_ENTREGA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_VENCIMIENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['PERIODO'] . "</td>";
                                $query_select_comercializador = mysqli_query($connection, "SELECT NOMBRE FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = '" . $row_info_rango['COMERCIALIZADOR'] . "'");
                                $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                                switch ($row_info_rango['FACTURADO_POR']) {
                                    case '1':
                                        $table = $table . "<td style='vertical-align:middle;'>COMERCIALIZADOR</td>";
                                        break;
                                    case '2':
                                        $table = $table . "<td style='vertical-align:middle;'>CUENTA DE COBRO</td>";
                                        break;
                                    case '3':
                                        $table = $table . "<td style='vertical-align:middle;'>RESOLUCION</td>";
                                        break;
                                }
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
                                    case "4":
                                        $table = $table . "<td style='vertical-align:middle;'>ANULADA</td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['OBSERVACIONES'] . "</td>";
                                $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 "
                                                                                     . " WHERE ID_FACTURACION = '" . $row_info_rango['ID_FACTURACION'] . "'");
                                $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_FACTURA'] - $row_info_recaudo['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_LIQ_VENCIDAS'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_SOPORTE'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . $row_info_recaudo['FECHA_PAGO_BITACORA'] . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_info_recaudo['FECHA_PAGO_BITACORA'], 8, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_info_recaudo['FECHA_PAGO_BITACORA'], 5, 2) . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>" . substr($row_info_recaudo['FECHA_PAGO_BITACORA'], 0, 4) . "</td>";
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
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_FACTURA'] - 0, 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'>$ " . number_format($row_info_rango['VALOR_LIQ_VENCIDAS'], 0, ',', '.') . "</td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
                                    $table = $table . "<td style='vertical-align:middle;'></td>";
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