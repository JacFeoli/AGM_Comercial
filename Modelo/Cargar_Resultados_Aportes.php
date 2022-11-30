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
                $id_ano_municipio = $_POST['id_ano_municipio'];
                $id_mes_municipio = $_POST['id_mes_municipio'];
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_MUNICIPIO * ($page - 1);
                }
                $query_select_info_municipio = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                            . "    MV.NOMBRE AS MUNICIPIO, "
                                                                            //. "    USU.NOMBRE AS USUARIO, "
                                                                            . "    FM.CONSECUTIVO_FACT AS FACTURA, "
                                                                            . "    FM.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                            . "    FM.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                            . "    FM.PERIODO_FACTURA AS PERIODO, "
                                                                            . "    FM.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                            . "    FM.ID_FACTURACION AS ID_FACTURACION "
                                                                            . "  FROM facturacion_municipales_2 FM, "
                                                                            . "       departamentos_visitas_2 DV, "
                                                                            . "       municipios_visitas_2 MV "
                                                                            //. "       usuarios_2 USU "
                                                                            . " WHERE FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                            . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                            . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                            //. "   AND FM.ID_USUARIO = USU.ID_USUARIO "
                                                                            . "   AND YEAR(FM.FECHA_FACTURA) = " . $id_ano_municipio . ""
                                                                            . "   AND MONTH(FM.FECHA_FACTURA) = " . $id_mes_municipio . ""
                                                                            . $query_departamento . " "
                                                                            . $query_municipio . " "
                                                                            . " ORDER BY DV.NOMBRE, MV.NOMBRE, FM.FECHA_FACTURA DESC "
                                                                            . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_MUNICIPIO);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO " . $id_ano_municipio . $id_mes_municipio . "&nbsp; <a onClick='generarReporteMunicipio(" . $sw . ", " . $departamento . ", " . $municipio . ", " . $id_ano_municipio . ", " . $id_mes_municipio . ")'><button><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a>" . "&nbsp; <a onClick='generarExcelMunicipio(" . $sw . ", " . $departamento . ", " . $municipio . ", " . $id_ano_municipio . ", " . $id_mes_municipio . ")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=13%>DEPARTAMENTO</th>";
                            $table = $table . "<th width=17%>MUNICIPIO</th>";
                            $table = $table . "<th width=11%>C. COBRO</th>";
                            $table = $table . "<th width=11%>VALOR</th>";
                            $table = $table . "<th width=8%>FECHA C.C.</th>";
                            $table = $table . "<th width=7%>EST. C.C.</th>";
                            $table = $table . "<th width=7%>EST. R.</th>";
                            $table = $table . "<th width=5%>IMPRIMIR</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_municipio = mysqli_fetch_assoc($query_select_info_municipio)) {
                            $estado = "";
                            $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                                 . " WHERE ID_FACTURACION = '" . $row_info_municipio['ID_FACTURACION'] . "'");
                            $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                            if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                if ($row_info_recaudo['ESTADO_RECAUDO'] == 4) {
                                    $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                }
                            }
                            switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                case "1":
                                    $estado = "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span>";
                                    break;
                                case "2":
                                    $estado = "<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span>";
                                    break;
                                case "3":
                                    $estado = "<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span>";
                                    break;
                                case "4":
                                    $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                    break;
                                case "5":
                                    $estado = "<span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span>";
                                    break;
                                case "6":
                                    $estado = "<span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span>";
                                    break;
                            }
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_municipio['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FECHA_FACTURA'] . "</td>";
                                switch ($row_info_municipio['ESTADO_FACTURA']) {
                                    case "1":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span></td>";
                                        break;
                                    case "2":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span></td>";
                                        break;
                                    case "3":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span></td>";
                                        break;
                                    case "6":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span></td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                $table = $table . "<td style='vertical-align:middle;'><a onClick='generarFacturaAportesMun(" . $row_info_municipio['ID_FACTURACION'] . ")'><button style='border: 1px solid;'><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a></td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                $table = $table . "<p></p>";
                $table = $table . "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = ENTREGADO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGO PARCIAL.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE ENVIO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span>";
                break;
            case '4':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $id_ano_mensual = $_POST['id_ano_mensual'];
                $id_mes_mensual = $_POST['id_mes_mensual'];
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_MENSUAL * ($page - 1);
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        //. "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FM.CONSECUTIVO_FACT AS FACTURA, "
                                                                        . "    FM.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FM.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FM.PERIODO_FACTURA AS PERIODO, "
                                                                        . "    FM.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                        . "    FM.ID_FACTURACION AS ID_FACTURACION "
                                                                        . "  FROM facturacion_municipales_2 FM, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV "
                                                                        //. "       usuarios_2 USU "
                                                                        . " WHERE FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        //. "   AND FM.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND YEAR(FM.FECHA_FACTURA) = " . $id_ano_mensual . ""
                                                                        . "   AND MONTH(FM.FECHA_FACTURA) = " . $id_mes_mensual . ""
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FM.FECHA_FACTURA DESC "
                                                                        . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_MENSUAL);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO " . $id_ano_mensual . $id_mes_mensual . "&nbsp; <a onClick='generarReporteMensual(" . $sw . ", " . $id_ano_mensual . ", " . $id_mes_mensual . ")'><button><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a>" . "&nbsp; <a onClick='generarExcelMensual(" . $sw . ", " . $id_ano_mensual . ", " . $id_mes_mensual . ")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=13%>DEPARTAMENTO</th>";
                            $table = $table . "<th width=17%>MUNICIPIO</th>";
                            $table = $table . "<th width=11%>C. COBRO</th>";
                            $table = $table . "<th width=11%>VALOR</th>";
                            $table = $table . "<th width=8%>FECHA C.C.</th>";
                            $table = $table . "<th width=7%>EST. C.C.</th>";
                            $table = $table . "<th width=7%>EST. R.</th>";
                            $table = $table . "<th width=5%>IMPRIMIR</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                            $estado = "";
                            $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                                 . " WHERE ID_FACTURACION = '" . $row_info_mensual['ID_FACTURACION'] . "'");
                            $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                            if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                if ($row_info_recaudo['ESTADO_RECAUDO'] == 4) {
                                    $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                }
                            }
                            switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                case "1":
                                    $estado = "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span>";
                                    break;
                                case "2":
                                    $estado = "<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span>";
                                    break;
                                case "3":
                                    $estado = "<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span>";
                                    break;
                                case "4":
                                    $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                    break;
                                case "5":
                                    $estado = "<span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span>";
                                    break;
                                case "6":
                                    $estado = "<span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span>";
                                    break;
                            }
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_mensual['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FECHA_FACTURA'] . "</td>";
                                switch ($row_info_mensual['ESTADO_FACTURA']) {
                                    case "1":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span></td>";
                                        break;
                                    case "2":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span></td>";
                                        break;
                                    case "3":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span></td>";
                                        break;
                                    case "6":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span></td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                $table = $table . "<td style='vertical-align:middle;'><a onClick='generarFacturaAportesMun(" . $row_info_mensual['ID_FACTURACION'] . ")'><button style='border: 1px solid;'><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a></td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                $table = $table . "<p></p>";
                $table = $table . "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = ENTREGADO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGO PARCIAL.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE ENVIO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span>";
                break;
            case '5':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $fecha_inicio = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_fin'];
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_MENSUAL * ($page - 1);
                }
                $query_select_info_rango = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                        //. "    USU.NOMBRE AS USUARIO, "
                                                                        . "    FM.CONSECUTIVO_FACT AS FACTURA, "
                                                                        . "    FM.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                        . "    FM.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                        . "    FM.PERIODO_FACTURA AS PERIODO, "
                                                                        . "    FM.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                        . "    FM.ID_FACTURACION AS ID_FACTURACION "
                                                                        . "  FROM facturacion_municipales_2 FM, "
                                                                        . "       departamentos_visitas_2 DV, "
                                                                        . "       municipios_visitas_2 MV "
                                                                        //. "       usuarios_2 USU "
                                                                        . " WHERE FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                        . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                        . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                        //. "   AND FM.ID_USUARIO = USU.ID_USUARIO "
                                                                        . "   AND FM.FECHA_FACTURA BETWEEN '" . $fecha_inicio . "' AND '$fecha_fin' "
                                                                        . " ORDER BY DV.NOMBRE, MV.NOMBRE, FM.FECHA_FACTURA DESC "
                                                                        . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_MENSUAL);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>RANGO " . $fecha_inicio . " & " . $fecha_fin . "&nbsp; <a onClick='generarReporteRango(" . $sw . ", \"" . $fecha_inicio . "\", \"" . $fecha_fin . "\")'><button><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a>" . "&nbsp; <a onClick='generarExcelRango(" . $sw . ", \"" . $fecha_inicio . "\", \"" . $fecha_fin . "\")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=13%>DEPARTAMENTO</th>";
                            $table = $table . "<th width=17%>MUNICIPIO</th>";
                            $table = $table . "<th width=11%>C. COBRO</th>";
                            $table = $table . "<th width=11%>VALOR</th>";
                            $table = $table . "<th width=8%>FECHA C.C.</th>";
                            $table = $table . "<th width=7%>EST. C.C.</th>";
                            $table = $table . "<th width=7%>EST. R.</th>";
                            $table = $table . "<th width=5%>IMPRIMIR</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                            $estado = "";
                            $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                                 . " WHERE ID_FACTURACION = '" . $row_info_rango['ID_FACTURACION'] . "'");
                            $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                            if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                if ($row_info_recaudo['ESTADO_RECAUDO'] == 4) {
                                    $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                }
                            }
                            switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                case "1":
                                    $estado = "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span>";
                                    break;
                                case "2":
                                    $estado = "<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span>";
                                    break;
                                case "3":
                                    $estado = "<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span>";
                                    break;
                                case "4":
                                    $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span>";
                                    break;
                                case "5":
                                    $estado = "<span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span>";
                                    break;
                                case "6":
                                    $estado = "<span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span>";
                                    break;
                            }
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FACTURA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_rango['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['FECHA_FACTURA'] . "</td>";
                                switch ($row_info_rango['ESTADO_FACTURA']) {
                                    case "1":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span></td>";
                                        break;
                                    case "2":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span></td>";
                                        break;
                                    case "3":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span></td>";
                                        break;
                                    case "6":
                                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span></td>";
                                        break;
                                }
                                $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                                $table = $table . "<td style='vertical-align:middle;'><a onClick='generarFacturaAportesMun(" . $row_info_rango['ID_FACTURACION'] . ")'><button style='border: 1px solid;'><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a></td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                $table = $table . "<p></p>";
                $table = $table . "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = ENTREGADO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADA POR ACUERDO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGO PARCIAL.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE ENVIO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span>";
                break;
        }
        echo $table;
    }
?>