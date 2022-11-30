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
                ?>
                <!DOCTYPE html>
                <html>
                    <head>
                        <?php
                            if (strlen($_GET['id_mes']) == 1) { ?>
                                <title>AGM - Reporte Comercializadores Periodo <?php echo $_GET['id_ano'] . "0" . $_GET['id_mes']; ?></title>
                            <?php
                            } else { ?>
                                <title>AGM - Reporte Comercializadores Periodo <?php echo $_GET['id_ano'] . $_GET['id_mes']; ?></title>
                            <?php
                            }
                        ?>
                        <meta charset="UTF-8" />
                        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
                        <link rel="icon" href="../Images/agm_desarrollos_2_icon" type="image/x-icon" />
                    </head>
                    <style>
                        table {
                            font-family: 'Cabin';
                        }
                        th {
                            font-size: 13px;
                        }
                        td {
                            font-size: 11px;
                        }
                        @media print {
                            @page { margin: 0; }
                            body { margin: 1.6cm; }
                        }
                        @font-face {
                            font-family: 'Cabin';
                            src: url('../Fonts/Cabin.ttf');
                        }
                    </style>
                    <body onload="window.print()">
                        <table width="100%">
                            <tr>
                                <td style="width: 20%;"><img style="max-width: 60%;" src="../Images/AGM Desarrollos.jpg" /></td>
                                <td>
                                    <table width="90%">
                                        <tr>
                                            <td style="font-size: 17px;"><center>AGM DESARROLLOS S.A.S.</center></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;"><center>NIT: 800186313-0</center></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;"><center>CENTRO, SECTOR MATUNA, AV. DANIEL LEMAITRE, EDF. BANCO DEL ESTADO PISO 5</center></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;"><center>CARTAGENA - COLOMBIA</center></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="20%">
                                    <table style="border-collapse: collapse; border: 1px solid;" width="100%">
                                        <tr>
                                            <th style="border: 1px solid; background-color: #003153; width: 20%;"><b style="color: #FFFFFF;"><center><?php echo "FECHA REPORTE"; ?></center></b></th>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid;"><center><?php echo date('Y-m-d'); ?></center></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br />
                        <table width="100%">
                            <td>
                                <table width="100%">
                                    <?php
                                        if (strlen($_GET['id_mes']) == 1) { ?>
                                            <tr><td style="font-size: 15px;"><center>REPORTE COMERCIALIZADORES</center></td></tr>
                                            <tr><td style="font-size: 15px;"><center><b>PERIODO <?php echo $_GET['id_ano'] . "0" . $_GET['id_mes']; ?></b></center></td></tr>
                                        <?php
                                        } else { ?>
                                            <tr><td style="font-size: 15px;"><center>REPORTE COMERCIALIZADORES</center></td></tr>
                                            <tr><td style="font-size: 15px;"><center><b>PERIODO <?php echo $_GET['id_ano'] . $_GET['id_mes']; ?></b></center></td></tr>
                                        <?php
                                        }
                                    ?>
                                </table>
                            </td>
                        </table>
                        <br />
                        <table style="border-collapse: collapse; border: 1px solid; font-size: 15px;" width="100%">
                            <tr style="border: 1px solid;">
                                <th style="border: 1px solid; background-color: #003153; width: 11%;"><b style="color: #FFFFFF;"><center>DPTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 17%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>VALOR FACT.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>FECHA FACT.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. FACT.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. R.</center></b></th>
                            </tr>
                            <?php
                                $query_select_info_mensual = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                                        . "    FC.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                                        . "    FC.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                                        . "    FC.PERIODO_FACTURA AS PERIODO, "
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
                                while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                                    $estado = "";
                                    $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 "
                                                                                         . " WHERE ID_FACTURACION = '" . $row_info_mensual['ID_FACTURACION'] . "'");
                                    $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                    if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                        if ($row_info_recaudo['ESTADO_RECAUDO'] == 1) {
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                        }
                                    }
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                            break;
                                        case "2":
                                            $estado = "<span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span>";
                                            break;
                                    }
                                    echo "<tr>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['DEPARTAMENTO'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['MUNICIPIO'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_mensual['VALOR_FACTURA'], 0, ',', '.') . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['FECHA_FACTURA'] . "</center></td>";
                                        switch ($row_info_mensual['ESTADO_FACTURA']) {
                                            case "1":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span></center></td>";
                                                break;
                                            case "2":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span></center></td>";
                                                break;
                                        }
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>$estado</center></td>";
                                    echo "</tr>";
                                    /*echo "<tr>";
                                        echo "<td colspan='8' style='border: 1px solid; text-align: justify;'></td>";
                                    echo "</tr>";*/
                                }
                            ?>
                        </table>
                    </body>
                </html>
                <?php
                break;
            case '5':
                ?>
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>AGM - Reporte Comercializadores Rango <?php echo $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin']; ?></title>
                        <meta charset="UTF-8" />
                        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
                        <link rel="icon" href="../Images/agm_desarrollos_2_icon" type="image/x-icon" />
                    </head>
                    <style>
                        table {
                            font-family: 'Cabin';
                        }
                        th {
                            font-size: 13px;
                        }
                        td {
                            font-size: 11px;
                        }
                        @media print {
                            @page { margin: 0; }
                            body { margin: 1.6cm; }
                        }
                        @font-face {
                            font-family: 'Cabin';
                            src: url('../Fonts/Cabin.ttf');
                        }
                    </style>
                    <body onload="window.print()">
                        <table width="100%">
                            <tr>
                                <td style="width: 20%;"><img style="max-width: 60%;" src="../Images/AGM Desarrollos.jpg" /></td>
                                <td>
                                    <table width="90%">
                                        <tr>
                                            <td style="font-size: 17px;"><center>AGM DESARROLLOS S.A.S.</center></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;"><center>NIT: 800186313-0</center></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;"><center>CENTRO, SECTOR MATUNA, AV. DANIEL LEMAITRE, EDF. BANCO DEL ESTADO PISO 5</center></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;"><center>CARTAGENA - COLOMBIA</center></td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="20%">
                                    <table style="border-collapse: collapse; border: 1px solid;" width="100%">
                                        <tr>
                                            <th style="border: 1px solid; background-color: #003153; width: 20%;"><b style="color: #FFFFFF;"><center><?php echo "FECHA REPORTE"; ?></center></b></th>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid;"><center><?php echo date('Y-m-d'); ?></center></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br />
                        <table width="100%">
                            <td>
                                <table width="100%">
                                    <tr><td style="font-size: 15px;"><center>REPORTE COMERCIALIZADORES</center></td></tr>
                                    <tr><td style="font-size: 15px;"><center><b>RANGO <?php echo $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin']; ?></b></center></td></tr>
                                </table>
                            </td>
                        </table>
                        <br />
                        <table style="border-collapse: collapse; border: 1px solid; font-size: 15px;" width="100%">
                            <tr style="border: 1px solid;">
                                <th style="border: 1px solid; background-color: #003153; width: 11%;"><b style="color: #FFFFFF;"><center>DPTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 17%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>VALOR FACT.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>FECHA FACT.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. FACT.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. R.</center></b></th>
                            </tr>
                            <?php
                                $query_select_info_rango = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                                        . "    FC.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                                        . "    FC.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                                        . "    FC.PERIODO_FACTURA AS PERIODO, "
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
                                while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                                    $estado = "";
                                    $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 "
                                                                                         . " WHERE ID_FACTURACION = '" . $row_info_rango['ID_FACTURACION'] . "'");
                                    $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                    if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                        if ($row_info_recaudo['ESTADO_RECAUDO'] == 1) {
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                        }
                                    }
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                            break;
                                        case "2":
                                            $estado = "<span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span>";
                                            break;
                                    }
                                    echo "<tr>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['DEPARTAMENTO'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['MUNICIPIO'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_rango['VALOR_FACTURA'], 0, ',', '.') . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['FECHA_FACTURA'] . "</center></td>";
                                        switch ($row_info_rango['ESTADO_FACTURA']) {
                                            case "1":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span></center></td>";
                                                break;
                                            case "2":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span></center></td>";
                                                break;
                                        }
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>$estado</center></td>";
                                    echo "</tr>";
                                    /*echo "<tr>";
                                        echo "<td colspan='8' style='border: 1px solid; text-align: justify;'></td>";
                                    echo "</tr>";*/
                                }
                            ?>
                        </table>
                    </body>
                </html>
                <?php
                break;
        }
    }
?>