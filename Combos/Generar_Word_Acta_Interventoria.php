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
        $totalOymRi = 0;
        $totalFactRecaudo = 0;
        setlocale(LC_ALL, "es_CO");
        date_default_timezone_set("America/Bogota");
        $query_select_info_acta_interventoria = mysqli_query($connection, "SELECT * "
                                                                        . "  FROM actas_interventoria_2 "
                                                                        . " WHERE ID_ACTA_INTERVENTORIA = " . $_GET['id_acta_interventoria']);
        $row_info_acta_interventoria = mysqli_fetch_array($query_select_info_acta_interventoria);
        $query_select_info_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_info_acta_interventoria['ID_COD_DPTO']);
        $row_info_departamento = mysqli_fetch_array($query_select_info_departamento);
        $query_select_info_municipio = mysqli_query($connection, "SELECT * "
                                                               . "  FROM municipios_visitas_2 "
                                                               . " WHERE ID_DEPARTAMENTO = " . $row_info_acta_interventoria['ID_COD_DPTO'] . " "
                                                               . "   AND ID_MUNICIPIO = " . $row_info_acta_interventoria['ID_COD_MPIO']);
        $row_info_municipio = mysqli_fetch_array($query_select_info_municipio);
        switch (substr($row_info_acta_interventoria['PERIODO_ACTA'], 4, 2)) {
            case '1': $mesActa = "Enero"; break;
            case '2': $mesActa = "Febrero"; break;
            case '3': $mesActa = "Marzo"; break;
            case '4': $mesActa = "Abril"; break;
            case '5': $mesActa = "Mayo"; break;
            case '6': $mesActa = "Junio"; break;
            case '7': $mesActa = "Julio"; break;
            case '8': $mesActa = "Agosto"; break;
            case '9': $mesActa = "Septiembre"; break;
            case '10': $mesActa = "Octubre"; break;
            case '11': $mesActa = "Noviembre"; break;
            case '12': $mesActa = "Diciembre"; break;
        }
        switch (substr($row_info_acta_interventoria['PERIODO_LIQUIDACION'], 4, 2)) {
            case '1': $mesLiquidacionActa = "Enero"; break;
            case '2': $mesLiquidacionActa = "Febrero"; break;
            case '3': $mesLiquidacionActa = "Marzo"; break;
            case '4': $mesLiquidacionActa = "Abril"; break;
            case '5': $mesLiquidacionActa = "Mayo"; break;
            case '6': $mesLiquidacionActa = "Junio"; break;
            case '7': $mesLiquidacionActa = "Julio"; break;
            case '8': $mesLiquidacionActa = "Agosto"; break;
            case '9': $mesLiquidacionActa = "Septiembre"; break;
            case '10': $mesLiquidacionActa = "Octubre"; break;
            case '11': $mesLiquidacionActa = "Noviembre"; break;
            case '12': $mesLiquidacionActa = "Diciembre"; break;
        }
        $table = "";
        $filename = "Acta de Interventoria " . $row_info_municipio['NOMBRE'] . " - " . $row_info_acta_interventoria['PERIODO_ACTA'] . ".doc";
        $table = $table . "<img style='max-width: 15%;' src='../Images/Logos/Logo American Lighting.png' />";
        $table = $table . "<br />";
        $table = $table . "<p style='text-transform: capitalize;'>" . $row_info_municipio['NOMBRE'] . ", " . strtolower(strftime("%d de %B de %Y", strtotime($row_info_acta_interventoria['FECHA_ACTA']))) . "</p>";
        $table = $table . "<br />";
        $table = $table . "<p style='margin: 0;'>Se&ntilde;ores:</p>";
        $table = $table . "<p style='text-transform: capitalize; font-weight: bold; margin: 0;'>MUNICIPIO DE " . $row_info_municipio['NOMBRE'] . "</p>";
        $table = $table . "<p style='margin: 0;'>Att: SUPERVISOR CONTRATO DE CONCESI&Oacute;N ALUMBRADO P&Uacute;BLICO</p>";
        $table = $table . "<p style='text-transform: capitalize; margin: 0;'>" . $row_info_departamento['NOMBRE'] . " - " . $row_info_municipio['NOMBRE'] . "</p>";
        $table = $table . "<p style='text-transform: uppercase; margin: 0;'><b>Asunto: </b>Entrega de Informe Mes <b>" . $mesActa . " " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4) . "</b></p>";
        $table = $table . "<br />";
        $table = $table . "<p>Cordial saludo,</p>";
        $table = $table . "<p>En nuestra calidad de Concesionario nos permitimos remitir la informaci&oacute;n correspondiente a la gesti&oacute;n adelantada en el periodo de: <b>" . $mesActa . " " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4) . "</b></p>";
        $table = $table . "<table style='border: 1px solid; text-align: center; border-collapse: collapse;' width='100%'>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; width: 10%; font-size: 15px;'><b><center>No.:</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; width: 70%; font-size: 15px;'><b><center>Documento</center></b></th>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>1</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>Informes Operativos</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>1.1</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "Listado de reportes de da&ntilde;os de luminarias de Alumbrado P&uacute;blico presentado por los usuarios.";
                $table = $table . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>1.2</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "Listado de luminarias a las cuales se les realiz&oacute; mantenimiento indicando: fecha de mantenimiento, ";
                    $table = $table . "c&oacute;digo de luminaria a la cual se le realizo el mantenimiento, elementos cambiados a la luminaria y ";
                    $table = $table . "direcci&oacute;n y/o lugar de ubicaci&oacute;n de la luminaria.";
                $table = $table . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>1.3</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "Expansiones en alumbrado p&uacute;blico realizadas en el periodo.";
                $table = $table . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>2</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "Informe de gesti&oacute;n donde se evidencian el resultado de los indicadores contractuales del mes y que incluye lo siguiente:";
                    $table = $table . "<ul style='margin-bottom: 0;'>";
                        $table = $table . "<li>Efectividad de ejecuci&oacute;n de mantenimientos preventivos.</li>";
                        $table = $table . "<li>Efectividad de ejecuci&oacute;n para cerrar solicitudes.</li>";
                        $table = $table . "<li>Plazo medio de resoluci&oacute;n.</li>";
                    $table = $table . "</ul>";
                $table = $table . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>3</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "Conciliaci&oacute;n de facturaci&oacute;n y recaudo del impuesto de alumbrado p&uacute;blico mensual.";
                $table = $table . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>4</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "Extracto Fiduciarios y Bancarios.";
                $table = $table . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>5</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "Copia de factura por operaci&oacute;n y mantenimiento y retorno a la inversi&oacute;n.";
                $table = $table . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>6</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "Ordenes de traslado Encargo Fiduciario.";
                $table = $table . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>7</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "Certificado de pago oportuno a los sistemas de seguridad social en salud, pensi&oacute;n, riesgos profesionales, ";
                    $table = $table . "correspondientes a los trabajadores y/o empleados durante el &uacute;ltimo mes.";
                $table = $table . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;'>8</td>";
                $table = $table . "<td style='border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;'>";
                    $table = $table . "P&oacute;lizas de garant&iacute;a del contrato de concesi&oacute;n de alumbrado p&uacute;blico.";
                $table = $table . "</td>";
            $table = $table . "</tr>";
        $table = $table . "</table>";
        $table = $table . "<p>As&iacute; mismo, durante el periodo se realizaron las siguientes actividades de apoyo a la gesti&oacute;n:</p>";
        $table = $table . "<p style='text-transform: capitalize;'>" . $row_info_acta_interventoria['OBSERVACIONES'] . "</p>";
        $table = $table . "<p style='text-align: justify;'>De acuerdo con lo anterior se deja constancia del cumplimiento de las obligaciones contractuales, quedando atentos a cualquier inquietud o comentario sobre la informaci&oacute;n entregada.</p>";
        $table = $table . "<br />";
        $table = $table . "<p>Atentamente,</p>";
        $table = $table . "<br />";
        $table = $table . "<br />";
        $table = $table . "<img style='max-width: 25%;' src='../Images/Firma Melissa Escorcia.png' />";
        $table = $table . "<p style='margin: 0;'><b>MELISSA ESCORCIA VARELA</b></p>";
        $table = $table . "<p style='margin: 0;'>JEFE DE OPERACIONES COMERCIALES</p>";
        $table = $table . "<p style='margin: 0;'>UNIDAD DE ALUMBRADO P&Uacute;BLICO</p>";
        $table = $table . "<p style='font-size: 20px; page-break-before: always; font-weight: bold; text-align: center;'>6. Conciliaci&oacute;n de Facturaci&oacute;n y Recaudo del Impuesto de Alumbrado P&uacute;blico Mensual. Periodo: " . $mesLiquidacionActa . " " . substr($row_info_acta_interventoria['PERIODO_LIQUIDACION'], 0, 4) . "</p>";
        $table = $table . "<table style='border: 1px solid; text-align: center; border-collapse: collapse;' width='100%'>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;' colspan='5'><b><center>Descripci&oacute;n:</center></b></th>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; font-size: 15px;' colspan='5'><b><center>Liquidaci&oacute;n Operador de Red.</center></b></td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>FACTURADO</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>RECAUDO</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>COSTO DE ENERGIA</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>OTRAS DEDUCCIONES</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>TRASLADO NETO</center></b></th>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; font-size: 15px;'><center>$ " . number_format($row_info_acta_interventoria['VALOR_FACTURADO'], 0, ',', '.') . "</center></td>";
                $table = $table . "<td style='border: 1px solid; font-size: 15px;'><center>$ " . number_format($row_info_acta_interventoria['VALOR_RECAUDO'], 0, ',', '.') . "</center></td>";
                $table = $table . "<td style='border: 1px solid; font-size: 15px;'><center>$ " . number_format($row_info_acta_interventoria['COSTO_ENERGIA'], 0, ',', '.') . "</center></td>";
                $table = $table . "<td style='border: 1px solid; font-size: 15px;'><center>$ " . number_format($row_info_acta_interventoria['OTRAS_DEDUCCIONES'], 0, ',', '.') . "</center></td>";
                $table = $table . "<td style='border: 1px solid; font-size: 15px;'><center>$ " . number_format($row_info_acta_interventoria['TRASLADO_NETO'], 0, ',', '.') . "</center></td>";
            $table = $table . "</tr>";
        $table = $table . "</table>";
        $table = $table . "<table style='border: 1px solid; text-align: center; border-collapse: collapse;' width='100%'>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;' colspan='5'><b><center>Descripci&oacute;n:</center></b></th>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; font-size: 15px;' colspan='3'><b><center>Facturaci&oacute;n Otros Agentes de Recaudo.</center></b></td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>FUENTE DEL RECAUDO</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>VALOR TRASLADO</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>AGENTE RECAUDADOR</center></b></th>";
            $table = $table . "</tr>";
            $table = $table . "<br />";
            $query_select_fact_munc = mysqli_query($connection, "SELECT * "
                                                              . "  FROM facturacion_municipales_2 "
                                                              . " WHERE ID_COD_DPTO = '" . $row_info_acta_interventoria['ID_COD_DPTO'] . "' "
                                                              . "   AND ID_COD_MPIO = '" . $row_info_acta_interventoria['ID_COD_MPIO'] . "' "
                                                              . "   AND YEAR(FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                              . "   AND MONTH(FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 4, 2) . "");
            while ($row_fact_munc = mysqli_fetch_assoc($query_select_fact_munc)) {
                $table = $table . "<tr>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>MUNICIPIO</td>";
                    $query_select_reca_munc = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 WHERE ID_FACTURACION = '" . $row_fact_munc['ID_FACTURACION'] . "'");
                    $row_reca_munc = mysqli_fetch_array($query_select_reca_munc);
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>$ " . number_format($row_reca_munc['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>MUNICIPIO</td>";
                $table = $table . "</tr>";
                $totalFactRecaudo = $totalFactRecaudo + $row_reca_munc['VALOR_RECAUDO'];
            }
            $query_select_fact_esp = mysqli_query($connection, "SELECT * "
                                                             . "  FROM facturacion_especiales_2 FE, contribuyentes_2 C "
                                                             . " WHERE FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE "
                                                             . "   AND FE.ID_COD_DPTO = '" . $row_info_acta_interventoria['ID_COD_DPTO'] . "' "
                                                             . "   AND FE.ID_COD_MPIO = '" . $row_info_acta_interventoria['ID_COD_MPIO'] . "' "
                                                             . "   AND YEAR(FE.FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                             . "   AND MONTH(FE.FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 4, 2) . " ");
            while ($row_fact_esp = mysqli_fetch_assoc($query_select_fact_esp)) {
                $table = $table . "<tr>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>" . $row_fact_esp['NOMBRE'] . "</td>";
                    $query_select_reca_esp = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 WHERE ID_FACTURACION = '" . $row_fact_esp['ID_FACTURACION'] . "'");
                    $row_reca_esp = mysqli_fetch_array($query_select_reca_esp);
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>$ " . number_format($row_reca_esp['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>MUNICIPIO</td>";
                $table = $table . "</tr>";
                $totalFactRecaudo = $totalFactRecaudo + $row_reca_esp['VALOR_RECAUDO'];
            }
            $query_select_fact_comer = mysqli_query($connection, "SELECT * FROM facturacion_comercializadores_2 FC, comercializadores_2 C "
                                                               . " WHERE FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR "
                                                               . "   AND FC.ID_COD_DPTO = '" . $row_info_acta_interventoria['ID_COD_DPTO'] . "' "
                                                               . "   AND FC.ID_COD_MPIO = '" . $row_info_acta_interventoria['ID_COD_MPIO'] . "' "
                                                               . "   AND YEAR(FC.FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                               . "   AND MONTH(FC.FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 4, 2) . " ");
            while ($row_fact_comer = mysqli_fetch_assoc($query_select_fact_comer)) {
                $table = $table . "<tr>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>" . $row_fact_comer['NOMBRE'] . "</td>";
                    $query_select_reca_comer = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 WHERE ID_FACTURACION = '" . $row_fact_comer['ID_FACTURACION'] . "'");
                    $row_reca_comer = mysqli_fetch_array($query_select_reca_comer);
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>$ " . number_format($row_reca_comer['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>COMERCIALIZADORA</td>";
                $table = $table . "</tr>";
                $totalFactRecaudo = $totalFactRecaudo + $row_reca_comer['VALOR_RECAUDO'];
            }
            $table = $table . "<tr>";
                $table = $table . "<td style='border: 1px solid; vertical-align:middle;'><b>TOTAL</b></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align:middle;'><b>$ " . number_format($totalFactRecaudo, 0, ',', '.') . "</b></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align:middle;'></td>";
            $table = $table . "</tr>";
        $table = $table . "</table>";
        $table = $table . "<p style='font-size: 20px; page-break-before: always; font-weight: bold; text-align: center;'>8. Facturaci&oacute;n por Operaci&oacute;n y Mantenimiento y Retorno de la Inversi&oacute;n</p>";
        $table = $table . "<table style='border: 1px solid; text-align: center; border-collapse: collapse;' width='100%'>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;' colspan='5'><b><center>Descripci&oacute;n:</center></b></th>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<td style='border: 1px solid; font-size: 15px;' colspan='5'><b><center>Factura Realizada por el Concesionario.</center></b></td>";
            $table = $table . "</tr>";
            $table = $table . "<tr style='border: 1px solid;'>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>NO. FACTURA</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>FECHA FACTURA</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>CONCEPTO</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>PERIODO</center></b></th>";
                $table = $table . "<th style='border: 1px solid; background-color: #DCDCDC; font-size: 15px;'><b><center>VALOR BRUTO + I.V.A.</center></b></th>";
            $table = $table .  "</tr>";
            $query_select_fact_oymri = mysqli_query($connection, "SELECT * FROM facturacion_oymri_2021_2 FO, conceptos_facturacion_2 CF "
                                                               . " WHERE FO.ID_CONCEPTO = CF.ID_CONCEPTO_FACT "
                                                               . "   AND FO.ID_COD_DPTO = '" . $row_info_acta_interventoria['ID_COD_DPTO'] . "' "
                                                               . "   AND FO.ID_COD_MPIO = '" . $row_info_acta_interventoria['ID_COD_MPIO'] . "' "
                                                               . "   AND YEAR(FO.FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                               . "   AND MONTH(FO.FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 4, 2) . " ");
            while ($row_fact_oymri = mysqli_fetch_assoc($query_select_fact_oymri)) {
                $table = $table . "<tr>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>" . $row_fact_oymri['NO_FACTURA'] . "</td>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>" . $row_fact_oymri['FECHA_FACTURA'] . "</td>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>" . $row_fact_oymri['NOMBRE'] . "</td>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>" . $row_fact_oymri['PERIODO'] . "</td>";
                    $table = $table . "<td style='border: 1px solid; vertical-align:middle;'>$ " . number_format($row_fact_oymri['VALOR_NETO_LOCAL'], 0, ',', '.') . "</td>";
                $table = $table . "</tr>";
            }
        $table = $table . "</table>";
        header("Content-Type: application/msword");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        echo $table;
    }
?>