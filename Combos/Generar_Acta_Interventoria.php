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
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Acta de Interventoria:  <?php echo $row_info_municipio['NOMBRE'] . " - " . $row_info_acta_interventoria['PERIODO_ACTA']; ?></title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="../Images/agm_desarrollos_2_icon" type="image/x-icon" />
    </head>
    <style>
        p {
            font-family: 'Cabin';
        }
        table {
            font-family: 'Cabin';
            page-break-inside: auto;
        }
        th {
            font-size: 12px;
            border: 1px solid #000000;
            page-break-inside: avoid;
            page-break-after: auto;
        }
        td {
            font-size: 11px;
            border: 1px solid #000000;
        }
        @media print {
            @page {
                size: auto;
                margin: 8mm 0 10mm 0;
            }
            body {
                margin-top: 0;
                margin: 1.6cm;
            }
        }
        @font-face {
            font-family: 'Cabin';
            src: url('../Fonts/Cabin.ttf');
        }
    </style>
    <body onload="window.print()">
        <img style="max-width: 15%;" src="../Images/Logos/Logo American Lighting.png" />
        <br />
        <p style="text-transform: capitalize;"><?php echo strtolower($row_info_municipio['NOMBRE']) . ", " . strtolower(strftime("%d de %B de %Y", strtotime($row_info_acta_interventoria['FECHA_ACTA']))); ?></p>
        <br />
        <p style="margin: 0;">Señores:</p>
        <p style="text-transform: capitalize; font-weight: bold; margin: 0;">MUNICIPIO DE <?php echo $row_info_municipio['NOMBRE']; ?></p>
        <p style="margin: 0;">Att: SUPERVISOR CONTRATO DE CONCESIÓN ALUMBRADO PÚBLICO</p>
        <p style="text-transform: capitalize; margin: 0;"><?php echo strtolower($row_info_departamento['NOMBRE']) . " - " . strtolower($row_info_municipio['NOMBRE']); ?></p>
        <p style="text-transform: uppercase; margin: 0;"><b>Asunto: </b>Entrega de Informe Mes <b><?php echo $mesActa . " " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4); ?></b></p>
        <br />
        <p>Cordial saludo,</p>
        <p>En nuestra calidad de Concesionario nos permitimos remitir la información correspondiente a la gestión adelantada en el periodo de: <b><?php echo $mesActa . " " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4); ?></b></p>
        <table style="border: 1px solid; text-align: center; border-collapse: collapse;" width="100%">
            <tr style="border: 1px solid;">
                <th style="border: 1px solid; background-color: #DCDCDC; width: 10%; font-size: 15px;"><b><center>No.:</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; width: 70%; font-size: 15px;"><b><center>Documento</center></b></th>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">1</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">Informes Operativos</td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">1.1</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Listado de reportes de daños de luminarias de Alumbrado Público presentado por los usuarios.
                </td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">1.2</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Listado de luminarias a las cuales se les realizó mantenimiento indicando: fecha de mantenimiento,
                    código de luminaria a la cual se le realizo el mantenimiento, elementos cambiados a la luminaria y
                    dirección y/o lugar de ubicación de la luminaria.
                </td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">1.3</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Expansiones en alumbrado público realizadas en el periodo.
                </td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">2</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Informe de gestión donde se evidencian el resultado de los indicadores contractuales del mes y que incluye lo siguiente:
                    <ul style="margin-bottom: 0;">
                        <li>Efectividad de ejecución de mantenimientos preventivos.</li>
                        <li>Efectividad de ejecución para cerrar solicitudes.</li>
                        <li>Plazo medio de resolución.</li>
                    </ul>
                </td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">3</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Conciliación de facturación y recaudo del impuesto de alumbrado público mensual.
                </td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">4</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Extracto Fiduciarios y Bancarios.
                </td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">5</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Copia de factura por operación y mantenimiento y retorno a la inversión.
                </td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">6</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Ordenes de traslado Encargo Fiduciario.
                </td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">7</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Certificado de pago oportuno a los sistemas de seguridad social en salud, pensión, riesgos profesionales,
                    correspondientes a los trabajadores y/o empleados durante el último mes.
                </td>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: center; padding: 10px;">8</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    Pólizas de garantía del contrato de concesión de alumbrado público.
                </td>
            </tr>
        </table>
        <p>Así mismo, durante el periodo se realizaron las siguientes actividades de apoyo a la gestión:</p>
        <p style="text-transform: capitalize;"><?php echo $row_info_acta_interventoria['OBSERVACIONES']; ?></p>
        <p style="text-align: justify;">De acuerdo con lo anterior se deja constancia del cumplimiento de las obligaciones contractuales, quedando atentos a cualquier inquietud o comentario sobre la información entregada.</p>
        <br />
        <p>Atentamente,</p>
        <br />
        <br />
        <img style="max-width: 25%;" src="../Images/Firma Melissa Escorcia.png" />
        <p style="margin: 0;"><b>MELISSA ESCORCIA VARELA</b></p>
        <p style="margin: 0;">JEFE DE OPERACIONES COMERCIALES</p>
        <p style="margin: 0;">UNIDAD DE ALUMBRADO PÚBLICO</p>
        <p style="font-size: 20px; page-break-before: always; font-weight: bold; text-align: center;">6. Conciliación de Facturación y Recaudo del Impuesto de Alumbrado Público Mensual. Periodo: <?php echo $mesLiquidacionActa . " " . substr($row_info_acta_interventoria['PERIODO_LIQUIDACION'], 0, 4); ?></p>
        <table style="border: 1px solid; text-align: center; border-collapse: collapse;" width="100%">
            <tr style="border: 1px solid;">
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;" colspan="5"><b><center>Descripción:</center></b></th>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; font-size: 15px;" colspan="5"><b><center>Liquidación Operador de Red.</center></b></td>
            </tr>
            <tr style="border: 1px solid;">
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>FACTURADO</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>RECAUDO</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>COSTO DE ENERGIA</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>OTRAS DEDUCCIONES</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>TRASLADO NETO</center></b></th>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; font-size: 15px;"><center>$ <?php echo number_format($row_info_acta_interventoria['VALOR_FACTURADO'], 0, ',', '.'); ?></center></td>
                <td style="border: 1px solid; font-size: 15px;"><center>$ <?php echo number_format($row_info_acta_interventoria['VALOR_RECAUDO'], 0, ',', '.'); ?></center></td>
                <td style="border: 1px solid; font-size: 15px;"><center>$ <?php echo number_format($row_info_acta_interventoria['COSTO_ENERGIA'], 0, ',', '.'); ?></center></td>
                <td style="border: 1px solid; font-size: 15px;"><center>$ <?php echo number_format($row_info_acta_interventoria['OTRAS_DEDUCCIONES'], 0, ',', '.'); ?></center></td>
                <td style="border: 1px solid; font-size: 15px;"><center>$ <?php echo number_format($row_info_acta_interventoria['TRASLADO_NETO'], 0, ',', '.'); ?></center></td>
            </tr>
        </table>
        <table style="border: 1px solid; text-align: center; border-collapse: collapse;" width="100%">
            <tr style="border: 1px solid;">
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;" colspan="5"><b><center>Descripción:</center></b></th>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; font-size: 15px;" colspan="3"><b><center>Facturación Otros Agentes de Recaudo.</center></b></td>
            </tr>
            <tr style="border: 1px solid;">
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>FUENTE DEL RECAUDO</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>VALOR TRASLADO</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>AGENTE RECAUDADOR</center></b></th>
            </tr>
            <br />
            <?php
                $query_select_fact_munc = mysqli_query($connection, "SELECT * "
                                                                  . "  FROM facturacion_municipales_2 "
                                                                  . " WHERE ID_COD_DPTO = '" . $row_info_acta_interventoria['ID_COD_DPTO'] . "' "
                                                                  . "   AND ID_COD_MPIO = '" . $row_info_acta_interventoria['ID_COD_MPIO'] . "' "
                                                                  . "   AND YEAR(FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                                  . "   AND MONTH(FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 4, 2) . "");
                while ($row_fact_munc = mysqli_fetch_assoc($query_select_fact_munc)) {
                    echo "<tr>";
                        echo "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                        $query_select_reca_munc = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 WHERE ID_FACTURACION = '" . $row_fact_munc['ID_FACTURACION'] . "'");
                        $row_reca_munc = mysqli_fetch_array($query_select_reca_munc);
                        echo "<td style='vertical-align:middle;'>$ " . number_format($row_reca_munc['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                        echo "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                    echo "</tr>";
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
                    echo "<tr>";
                        echo "<td style='vertical-align:middle;'>" . $row_fact_esp['NOMBRE'] . "</td>";
                        $query_select_reca_esp = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 WHERE ID_FACTURACION = '" . $row_fact_esp['ID_FACTURACION'] . "'");
                        $row_reca_esp = mysqli_fetch_array($query_select_reca_esp);
                        echo "<td style='vertical-align:middle;'>$ " . number_format($row_reca_esp['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                        echo "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                    echo "</tr>";
                    $totalFactRecaudo = $totalFactRecaudo + $row_reca_esp['VALOR_RECAUDO'];
                }
                $query_select_fact_comer = mysqli_query($connection, "SELECT * FROM facturacion_comercializadores_2 FC, comercializadores_2 C "
                                                                   . " WHERE FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR "
                                                                   . "   AND FC.ID_COD_DPTO = '" . $row_info_acta_interventoria['ID_COD_DPTO'] . "' "
                                                                   . "   AND FC.ID_COD_MPIO = '" . $row_info_acta_interventoria['ID_COD_MPIO'] . "' "
                                                                   . "   AND YEAR(FC.FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                                   . "   AND MONTH(FC.FECHA_FACTURA) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 4, 2) . " ");
                while ($row_fact_comer = mysqli_fetch_assoc($query_select_fact_comer)) {
                    echo "<tr>";
                        echo "<td style='vertical-align:middle;'>" . $row_fact_comer['NOMBRE'] . "</td>";
                        $query_select_reca_comer = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 WHERE ID_FACTURACION = '" . $row_fact_comer['ID_FACTURACION'] . "'");
                        $row_reca_comer = mysqli_fetch_array($query_select_reca_comer);
                        echo "<td style='vertical-align:middle;'>$ " . number_format($row_reca_comer['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                        echo "<td style='vertical-align:middle;'>COMERCIALIZADORA</td>";
                    echo "</tr>";
                    $totalFactRecaudo = $totalFactRecaudo + $row_reca_comer['VALOR_RECAUDO'];
                }
                echo "<tr>";
                    echo "<td style='vertical-align:middle;'><b>TOTAL</b></td>";
                    echo "<td style='vertical-align:middle;'><b>$ " . number_format($totalFactRecaudo, 0, ',', '.') . "</b></td>";
                    echo "<td style='vertical-align:middle;'></td>";
                echo "</tr>";
            ?>
        </table>
        <p style="font-size: 20px; page-break-before: always; font-weight: bold; text-align: center;">8. Facturación por Operación y Mantenimiento y Retorno de la Inversión</p>
        <table style="border: 1px solid; text-align: center; border-collapse: collapse;" width="100%">
            <tr style="border: 1px solid;">
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;" colspan="5"><b><center>Descripción:</center></b></th>
            </tr>
            <tr style="border: 1px solid;">
                <td style="border: 1px solid; font-size: 15px;" colspan="5"><b><center>Factura Realizada por el Concesionario.</center></b></td>
            </tr>
            <tr style="border: 1px solid;">
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>NO. FACTURA</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>FECHA FACTURA</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>CONCEPTO</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>PERIODO</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; font-size: 15px;"><b><center>VALOR BRUTO + I.V.A.</center></b></th>
            </tr>
            <?php
                $query_select_fact_oymri = mysqli_query($connection, "SELECT * FROM facturacion_oymri_2021_2 FO, conceptos_facturacion_2 CF "
                                                                   . " WHERE FO.ID_CONCEPTO = CF.ID_CONCEPTO_FACT "
                                                                   . "   AND FO.ID_COD_DPTO = '" . $row_info_acta_interventoria['ID_COD_DPTO'] . "' "
                                                                   . "   AND FO.ID_COD_MPIO = '" . $row_info_acta_interventoria['ID_COD_MPIO'] . "' "
                                                                   . "   AND YEAR(FO.PERIODO) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                                   . "   AND MONTH(FO.PERIODO) = " . substr($row_info_acta_interventoria['PERIODO_ACTA'], 4, 2) . " ");
                while ($row_fact_oymri = mysqli_fetch_assoc($query_select_fact_oymri)) {
                    echo "<tr>";
                        echo "<td style='vertical-align:middle;'>" . $row_fact_oymri['NO_FACTURA'] . "</td>";
                        echo "<td style='vertical-align:middle;'>" . $row_fact_oymri['FECHA_FACTURA'] . "</td>";
                        echo "<td style='vertical-align:middle;'>" . $row_fact_oymri['NOMBRE'] . "</td>";
                        echo "<td style='vertical-align:middle;'>" . $row_fact_oymri['PERIODO'] . "</td>";
                        echo "<td style='vertical-align:middle;'>$ " . number_format($row_fact_oymri['VALOR_NETO_LOCAL'], 0, ',', '.') . "</td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </body>
</html>