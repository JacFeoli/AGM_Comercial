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
                $sw2 = 0;
                $or = "";
                $where = "";
                $periodos = "";
                $myAnos = explode(',', $_GET['id_ano']);
                $myMes = explode(',', $_GET['id_mes']);
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (FE.ID_COD_MPIO = MV.ID_MUNICIPIO AND YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . " AND MV.ID_DEPARTAMENTO = " . $_GET['departamento'] . " AND MV.ID_MUNICIPIO = " . $_GET['municipio'] . ") ";
                        $sw2 = 1;
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    } else {
                        $or = $or . " OR (FE.ID_COD_MPIO = MV.ID_MUNICIPIO AND YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . " AND MV.ID_DEPARTAMENTO = " . $_GET['departamento'] . " AND MV.ID_MUNICIPIO = " . $_GET['municipio'] . ") ";
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    }
                }
                $query_select_nombre_departamento = mysqli_query($connection, "SELECT NOMBRE "
                                                                            . "  FROM departamentos_visitas_2 "
                                                                            . " WHERE ID_DEPARTAMENTO = " . $_GET['departamento']);
                $row_nombre_departamento = mysqli_fetch_array($query_select_nombre_departamento);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE "
                                                                         . "  FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $_GET['departamento'] . " "
                                                                         . "   AND ID_MUNICIPIO = " . $_GET['municipio']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                ?>
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>AGM - Reporte Clientes Especiales <?php echo ucwords(strtolower($row_nombre_departamento['NOMBRE'])) . " - " . ucwords(strtolower($row_nombre_municipio['NOMBRE'])); ?></title>
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
                                    <tr>
                                        <td style="font-size: 15px;"><center>REPORTE CLIENTES ESPECIALES <b><?php echo $row_nombre_departamento['NOMBRE'] . " - " . $row_nombre_municipio['NOMBRE']; ?>.</b></center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 15px;"><center>PERIODO <b><?php echo substr($periodos, 0, -3); ?>.</b></center></td>
                                    </tr>
                                </table>
                            </td>
                        </table>
                        <br />
                        <table style="border-collapse: collapse; border: 1px solid; font-size: 15px;" width="100%">
                            <tr style="border: 1px solid;">
                                <!--<th style="border: 1px solid; background-color: #003153; width: 11%;"><b style="color: #FFFFFF;"><center>DPTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 17%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>-->
                                <th style="border: 1px solid; background-color: #003153; width: 30%;"><b style="color: #FFFFFF;"><center>CONTRIBUYENTE</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>FACTURA</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 9%;"><b style="color: #FFFFFF;"><center>VALOR</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 9%;"><b style="color: #FFFFFF;"><center>FECHA F.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. F.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. R.</center></b></th>
                            </tr>
                            <?php
                                $query_select_info_municipio = mysqli_query($connection, "SELECT *, DATE(FE.FECHA_FACTURA) AS FECHA_FACTURA, FE.CONSECUTIVO_FACT AS FACTURA "
                                                                                          . "  FROM facturacion_especiales_2 FE, municipios_visitas_2 MV "
                                                                                          . $where
                                                                                          . $or
                                                                                          . " ORDER BY FE.FECHA_FACTURA DESC ");
                                while ($row_info_municipio = mysqli_fetch_assoc($query_select_info_municipio)) {
                                    $estado = "";
                                    $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 "
                                                                                         . " WHERE ID_FACTURACION = '" . $row_info_municipio['ID_FACTURACION'] . "'");
                                    $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                    if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                        if ($row_info_recaudo['ESTADO_RECAUDO'] == 4) {
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                        }
                                    }
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "<span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>E</b></span>";
                                            break;
                                        case "2":
                                            $estado = "<span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span>";
                                            break;
                                        case "3":
                                            $estado = "<span style='font-size: 11px; background-color: #4D7B52; color: #FFFFFF; padding: 1px;' class='label label-success'><b>R</b></span>";
                                            break;
                                        case "4":
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                            break;
                                        case "5":
                                            $estado = "<span style='font-size: 11px; background-color: #66C77E; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PP</b></span>";
                                            break;
                                    }
                                    echo "<tr>";
                                        //echo "<td style='vertical-align:middle; border: 1px solid;'><center>" . $row_info_municipio['DEPARTAMENTO'] . "</center></td>";
                                        //echo "<td style='vertical-align:middle; border: 1px solid;'><center>" . $row_info_municipio['MUNICIPIO'] . "</center></td>";
                                        $query_select_contribuyente = mysqli_query($connection, "SELECT * FROM contribuyentes_2 WHERE ID_CONTRIBUYENTE = " . $row_info_municipio['ID_CONTRIBUYENTE']);
                                        $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_contribuyente['NOMBRE'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_municipio['FACTURA'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_municipio['VALOR_FACTURA'], 0, ',', '.') . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_municipio['FECHA_FACTURA'] . "</center></td>";
                                        switch ($row_info_municipio['ESTADO_FACTURA']) {
                                            case "1":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>E</b></span></center></td>";
                                                break;
                                            case "2":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span></center></td>";
                                                break;
                                            case "3":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #4D7B52; color: #FFFFFF; padding: 1px;' class='label label-success'><b>R</b></span></center></td>";
                                                break;
                                        }
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>$estado</center></td>";
                                    echo "</tr>";
                                    /*echo "<tr>";
                                        echo "<td colspan='6' style='border: 1px solid; text-align: justify;'></td>";
                                    echo "</tr>";*/
                                }
                            ?>
                        </table>
                    </body>
                </html>
                <?php
                break;
            case '1':
                $sw2 = 0;
                $or = "";
                $where = "";
                $periodos = "";
                $myAnos = explode(',', $_GET['id_ano']);
                $myMes = explode(',', $_GET['id_mes']);
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . " AND FE.ID_CONTRIBUYENTE = " . $_GET['contribuyente'] . ") ";
                        $sw2 = 1;
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    } else {
                        $or = $or . " OR (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . " AND FE.ID_CONTRIBUYENTE = " . $_GET['contribuyente'] . ") ";
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    }
                }
                $query_select_contribuyente = mysqli_query($connection, "SELECT NOMBRE "
                                                                         . "  FROM contribuyentes_2 "
                                                                         . " WHERE ID_CONTRIBUYENTE = " . $_GET['contribuyente']);
                $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                ?>
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>AGM - Reporte Clientes Especiales <?php echo ucwords(strtolower($row_contribuyente['NOMBRE'])); ?></title>
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
                                    <tr>
                                        <td style="font-size: 15px;"><center>REPORTE CLIENTES ESPECIALES <b><?php echo $row_contribuyente['NOMBRE']; ?>.</b></center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 15px;"><center>PERIODO <b><?php echo substr($periodos, 0, -3); ?>.</b></center></td>
                                    </tr>
                                </table>
                            </td>
                        </table>
                        <br />
                        <table style="border-collapse: collapse; border: 1px solid; font-size: 15px;" width="100%">
                            <tr style="border: 1px solid;">
                                <!--<th style="border: 1px solid; background-color: #003153; width: 30%;"><b style="color: #FFFFFF;"><center>CONTRIBUYENTE</center></b></th>-->
                                <th style="border: 1px solid; background-color: #003153; width: 11%;"><b style="color: #FFFFFF;"><center>DPTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 17%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>FACTURA</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 9%;"><b style="color: #FFFFFF;"><center>VALOR</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 9%;"><b style="color: #FFFFFF;"><center>FECHA F.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. F.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. R.</center></b></th>
                            </tr>
                            <?php
                                $query_info_contribuyente = mysqli_query($connection, "SELECT *, DATE(FE.FECHA_FACTURA) AS FECHA_FACTURA, FE.CONSECUTIVO_FACT AS FACTURA "
                                                                                       . "  FROM facturacion_especiales_2 FE "
                                                                                       . $where
                                                                                       . $or
                                                                                       . " ORDER BY FE.FECHA_FACTURA DESC ");
                                while ($row_info_contribuyente = mysqli_fetch_assoc($query_info_contribuyente)) {
                                    $estado = "";
                                    $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 "
                                                                                         . " WHERE ID_FACTURACION = '" . $row_info_contribuyente['ID_FACTURACION'] . "'");
                                    $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                    if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                        if ($row_info_recaudo['ESTADO_RECAUDO'] == 4) {
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                        }
                                    }
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "<span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>E</b></span>";
                                            break;
                                        case "2":
                                            $estado = "<span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span>";
                                            break;
                                        case "3":
                                            $estado = "<span style='font-size: 11px; background-color: #4D7B52; color: #FFFFFF; padding: 1px;' class='label label-success'><b>R</b></span>";
                                            break;
                                        case "4":
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                            break;
                                        case "5":
                                            $estado = "<span style='font-size: 11px; background-color: #66C77E; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PP</b></span>";
                                            break;
                                    }
                                    echo "<tr>";
                                        //echo "<td style='vertical-align:middle;'>" . $row_info_contribuyente['CONTRIBUYENTE'] . "</td>";
                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_info_contribuyente['ID_COD_DPTO']);
                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_departamento['NOMBRE'] . "</center></td>";
                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_info_contribuyente['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_info_contribuyente['ID_COD_MPIO']);
                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_municipio['NOMBRE'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_contribuyente['FACTURA'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_contribuyente['VALOR_FACTURA'], 0, ',', '.') . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_contribuyente['FECHA_FACTURA'] . "</center></td>";
                                        switch ($row_info_contribuyente['ESTADO_FACTURA']) {
                                            case "1":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>E</b></span></center></td>";
                                                break;
                                            case "2":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span></center></td>";
                                                break;
                                            case "3":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #4D7B52; color: #FFFFFF; padding: 1px;' class='label label-success'><b>R</b></span></center></td>";
                                                break;
                                        }
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>$estado</center></td>";
                                    echo "</tr>";
                                    /*echo "<tr>";
                                        echo "<td colspan='7' style='border: 1px solid; text-align: justify;'></td>";
                                    echo "</tr>";*/
                                }
                            ?>
                        </table>
                    </body>
                </html>
                <?php
                break;
            case '2':
                
                break;
            case '3':
                
                break;
            case '4':
                ?>
                <!DOCTYPE html>
                <html>
                    <head>
                        <?php
                            $sw2 = 0;
                            $or = "";
                            $where = "";
                            $periodos = "";
                            $myAnos = explode(',', $_GET['id_ano']);
                            $myMes = explode(',', $_GET['id_mes']);
                            foreach ($myAnos as $index => $ano) {
                                if ($sw2 == 0) {
                                    $where = " WHERE (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . ") ";
                                    $sw2 = 1;
                                    $periodos = $periodos . $ano . $myMes[$index] . " - ";
                                } else {
                                    $or = $or . " OR (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . ") ";
                                    $periodos = $periodos . $ano . $myMes[$index] . " - ";
                                }
                            }
                        ?>
                        <title>AGM - Reporte Clientes Especiales Periodo <?php echo substr($periodos, 0, -3); ?></title>
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
                                    <tr>
                                        <td style="font-size: 15px;"><center>REPORTE CLIENTES ESPECIALES <b>PERIODO <?php echo substr($periodos, 0, -3); ?></b></center></td>
                                    </tr>
                                </table>
                            </td>
                        </table>
                        <br />
                        <table style="border-collapse: collapse; border: 1px solid; font-size: 15px;" width="100%">
                            <tr style="border: 1px solid;">
                                <th style="border: 1px solid; background-color: #003153; width: 11%;"><b style="color: #FFFFFF;"><center>DPTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 17%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 28%;"><b style="color: #FFFFFF;"><center>CONTRIBUYENTE</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>FACTURA</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>VALOR</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>FECHA F.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. F.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. R.</center></b></th>
                            </tr>
                            <?php
                                $query_select_info_mensual = mysqli_query($connection, "SELECT *, DATE(FE.FECHA_FACTURA) AS FECHA_FACTURA, FE.CONSECUTIVO_FACT AS FACTURA "
                                                                                        . "  FROM facturacion_especiales_2 FE "
                                                                                        . $where
                                                                                        . $or
                                                                                        . " ORDER BY FE.FECHA_FACTURA DESC ");
                                while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                                    $estado = "";
                                    $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 "
                                                                                         . " WHERE ID_FACTURACION = '" . $row_info_mensual['ID_FACTURACION'] . "'");
                                    $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                    if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                        if ($row_info_recaudo['ESTADO_RECAUDO'] == 4) {
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                        }
                                    }
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "<span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>E</b></span>";
                                            break;
                                        case "2":
                                            $estado = "<span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span>";
                                            break;
                                        case "3":
                                            $estado = "<span style='font-size: 11px; background-color: #4D7B52; color: #FFFFFF; padding: 1px;' class='label label-success'><b>R</b></span>";
                                            break;
                                        case "4":
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                            break;
                                        case "5":
                                            $estado = "<span style='font-size: 11px; background-color: #66C77E; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PP</b></span>";
                                            break;
                                    }
                                    echo "<tr>";
                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_info_mensual['ID_COD_DPTO']);
                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_departamento['NOMBRE'] . "</center></td>";
                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_info_mensual['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_info_mensual['ID_COD_MPIO']);
                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_municipio['NOMBRE'] . "</center></td>";
                                        $query_select_contribuyente = mysqli_query($connection, "SELECT * FROM contribuyentes_2 WHERE ID_CONTRIBUYENTE = " . $row_info_mensual['ID_CONTRIBUYENTE']);
                                        $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_contribuyente['NOMBRE'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['FACTURA'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_mensual['VALOR_FACTURA'], 0, ',', '.') . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['FECHA_FACTURA'] . "</center></td>";
                                        switch ($row_info_mensual['ESTADO_FACTURA']) {
                                            case "1":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>E</b></span></center></td>";
                                                break;
                                            case "2":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span></center></td>";
                                                break;
                                            case "3":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #4D7B52; color: #FFFFFF; padding: 1px;' class='label label-success'><b>R</b></span></center></td>";
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
                        <title>AGM - Reporte Clientes Especiales Rango <?php echo $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin']; ?></title>
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
                                    <tr><td style="font-size: 15px;"><center>REPORTE CLIENTES ESPECIALES</center></td></tr>
                                    <tr><td style="font-size: 15px;"><center><b>RANGO <?php echo $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin']; ?></b></center></td></tr>
                                </table>
                            </td>
                        </table>
                        <br />
                        <table style="border-collapse: collapse; border: 1px solid; font-size: 15px;" width="100%">
                            <tr style="border: 1px solid;">
                                <th style="border: 1px solid; background-color: #003153; width: 11%;"><b style="color: #FFFFFF;"><center>DPTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 17%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 28%;"><b style="color: #FFFFFF;"><center>CONTRIBUYENTE</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>FACTURA</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>VALOR</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>FECHA F.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. F.</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;"><center>EST. R.</center></b></th>
                            </tr>
                            <?php
                                $query_select_info_rango = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                                        . "    MV.NOMBRE AS MUNICIPIO, "
                                                                                        . "    CONT.NOMBRE AS CONTRIBUYENTE, "
                                                                                        . "    USU.NOMBRE AS USUARIO, "
                                                                                        . "    FE.CONSECUTIVO_FACT AS FACTURA, "
                                                                                        . "    FE.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                                        . "    FE.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                                        . "    FE.PERIODO_FACTURA AS PERIODO, "
                                                                                        . "    FE.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                                        . "    FE.ID_FACTURACION AS ID_FACTURACION "
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
                                while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                                    $estado = "";
                                    $query_select_info_recaudo = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 "
                                                                                         . " WHERE ID_FACTURACION = '" . $row_info_rango['ID_FACTURACION'] . "'");
                                    $row_info_recaudo = mysqli_fetch_array($query_select_info_recaudo);
                                    if (mysqli_num_rows($query_select_info_recaudo) != 0) {
                                        if ($row_info_recaudo['ESTADO_RECAUDO'] == 4) {
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                        }
                                    }
                                    switch ($row_info_recaudo['ESTADO_RECAUDO']) {
                                        case "1":
                                            $estado = "<span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>E</b></span>";
                                            break;
                                        case "2":
                                            $estado = "<span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span>";
                                            break;
                                        case "3":
                                            $estado = "<span style='font-size: 11px; background-color: #4D7B52; color: #FFFFFF; padding: 1px;' class='label label-success'><b>R</b></span>";
                                            break;
                                        case "4":
                                            $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>P</b></span>";
                                            break;
                                        case "5":
                                            $estado = "<span style='font-size: 11px; background-color: #66C77E; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PP</b></span>";
                                            break;
                                    }
                                    echo "<tr>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['DEPARTAMENTO'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['MUNICIPIO'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['CONTRIBUYENTE'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['FACTURA'] . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_rango['VALOR_FACTURA'], 0, ',', '.') . "</center></td>";
                                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['FECHA_FACTURA'] . "</center></td>";
                                        switch ($row_info_rango['ESTADO_FACTURA']) {
                                            case "1":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>E</b></span></center></td>";
                                                break;
                                            case "2":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #CC3300; color: #FFFFFF; padding: 1px;' class='label label-success'><b>PE</b></span></center></td>";
                                                break;
                                            case "3":
                                                echo "<td style='border: 1px solid; vertical-align: middle;'><center><span style='font-size: 11px; background-color: #4D7B52; color: #FFFFFF; padding: 1px;' class='label label-success'><b>R</b></span></center></td>";
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