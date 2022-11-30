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
                        $where = " WHERE (BL.ID_MUNICIPIO_LIBRETA = ML.ID_MUNICIPIO_LIBRETA AND YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND ML.ID_DEPARTAMENTO = " . $_GET['departamento'] . " AND ML.ID_MUNICIPIO = " . $_GET['municipio'] . ") ";
                        $sw2 = 1;
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    } else {
                        $or = $or . " OR (BL.ID_MUNICIPIO_LIBRETA = ML.ID_MUNICIPIO_LIBRETA AND YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND ML.ID_DEPARTAMENTO = " . $_GET['departamento'] . " AND ML.ID_MUNICIPIO = " . $_GET['municipio'] . ") ";
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
                        <title>AGM - Reporte Gestión <?php echo ucwords(strtolower($row_nombre_departamento['NOMBRE'])) . " - " . ucwords(strtolower($row_nombre_municipio['NOMBRE'])); ?></title>
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
                                        <td style="font-size: 15px;"><center>REPORTE GESTIÓN <b><?php echo $row_nombre_departamento['NOMBRE'] . " - " . $row_nombre_municipio['NOMBRE']; ?>.</b></center></td>
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
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>DEPARTAMENTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 18%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 38%;"><b style="color: #FFFFFF;"><center>TIPO VISITA</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 14%;"><b style="color: #FFFFFF;"><center>FECHA VISITA</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 20%;"><b style="color: #FFFFFF;"><center>USUARIO</center></b></th>
                            </tr>
                            <?php
                                $query_info_visitas_municipio = mysqli_query($connection, "SELECT *, DATE(BL.FECHA_VISITA) AS FECHA_VISITA, BL.OBSERVACIONES "
                                                                                        . "  FROM bitacora_libretas_2 BL, municipios_libreta_2 ML "
                                                                                        . $where
                                                                                        . $or
                                                                                        . " ORDER BY BL.FECHA_VISITA DESC ");
                                $cont = 0;
                                $sub_total = 0;
                                $iva = 0;
                                $valor_total = 0;
                                while ($row_visitas_municipio = mysqli_fetch_assoc($query_info_visitas_municipio)) {
                                    echo "<tr>";
                                        $query_select_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                                  . "  FROM municipios_libreta_2 "
                                                                                                  . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_visitas_municipio['ID_MUNICIPIO_LIBRETA']);
                                        $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                        $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE AS DEPARTAMENTO "
                                                                                             . "  FROM departamentos_visitas_2 "
                                                                                             . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                        $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE AS MUNICIPIO "
                                                                                          . "  FROM municipios_visitas_2 "
                                                                                          . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                          . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                        echo "<td style='border: 1px solid;'><center><b>" . $row_departamento['DEPARTAMENTO'] . "</b></center></td>";
                                        echo "<td style='border: 1px solid;'><center><b>" . $row_municipio['MUNICIPIO'] . "</b></center></td>";
                                        $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE AS TIPO_VISITA FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $row_visitas_municipio['ID_TIPO_VISITA']);
                                        $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                        echo "<td style='border: 1px solid;'><center><b>" . $row_tipo_visita['TIPO_VISITA'] . "</b></center></td>";
                                        echo "<td style='border: 1px solid;'><center><b>" . $row_visitas_municipio['FECHA_VISITA'] . "</b></center></td>";
                                        $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE AS USUARIO FROM usuarios_2 WHERE ID_USUARIO = " . $row_visitas_municipio['ID_USUARIO']);
                                        $row_usuario = mysqli_fetch_array($query_select_usuario);
                                        echo "<td style='border: 1px solid;'><center><b>" . $row_usuario['USUARIO'] . "</b></center></td>";
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<td colspan='5' style='border: 1px solid; text-align: justify;'>" . $row_visitas_municipio['OBSERVACIONES'] . "</td>";
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<td colspan='5' style='border: 1px solid; text-align: justify;'></td>";
                                    echo "</tr>";
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
                        $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_USUARIO = " . $_GET['usuario'] . ") ";
                        $sw2 = 1;
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    } else {
                        $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_USUARIO = " . $_GET['usuario'] . ") ";
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    }
                }
                $query_select_usuario_bitacora = mysqli_query($connection, "SELECT NOMBRE "
                                                                         . "  FROM usuarios_2 "
                                                                         . " WHERE ID_USUARIO = " . $_GET['usuario']);
                $row_usuario_bitacora = mysqli_fetch_array($query_select_usuario_bitacora);
                ?>
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>AGM - Reporte Gestión <?php echo ucwords(strtolower($row_usuario_bitacora['NOMBRE'])); ?></title>
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
                                        <td style="font-size: 15px;"><center>REPORTE GESTIÓN <b><?php echo $row_usuario_bitacora['NOMBRE']; ?>.</b></center></td>
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
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>DEPARTAMENTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 18%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 58%;"><b style="color: #FFFFFF;"><center>TIPO VISITA</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 14%;"><b style="color: #FFFFFF;"><center>FECHA VISITA</center></b></th>
                            </tr>
                            <?php
                                $query_info_visitas_usuario_bitacora = mysqli_query($connection, "SELECT *, DATE(BL.FECHA_VISITA) AS FECHA_VISITA "
                                                                                               . "  FROM bitacora_libretas_2 BL "
                                                                                               . $where
                                                                                               . $or
                                                                                               . " ORDER BY BL.FECHA_VISITA DESC ");
                                $cont = 0;
                                $sub_total = 0;
                                $iva = 0;
                                $valor_total = 0;
                                while ($row_visitas_usuario_bitacora = mysqli_fetch_assoc($query_info_visitas_usuario_bitacora)) {
                                    echo "<tr>";
                                        $query_select_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                                  . "  FROM municipios_libreta_2 "
                                                                                                  . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_visitas_usuario_bitacora['ID_MUNICIPIO_LIBRETA']);
                                        $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                        $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE AS DEPARTAMENTO "
                                                                                             . "  FROM departamentos_visitas_2 "
                                                                                             . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                        $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE AS MUNICIPIO "
                                                                                          . "  FROM municipios_visitas_2 "
                                                                                          . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                          . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                        echo "<td style='border: 1px solid;'><center>" . $row_departamento['DEPARTAMENTO'] . "</center></td>";
                                        echo "<td style='border: 1px solid;'><center>" . $row_municipio['MUNICIPIO'] . "</center></td>";
                                        $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE AS TIPO_VISITA FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $row_visitas_usuario_bitacora['ID_TIPO_VISITA']);
                                        $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                        echo "<td style='border: 1px solid;'><center>" . $row_tipo_visita['TIPO_VISITA'] . "</center></td>";
                                        echo "<td style='border: 1px solid;'><center>" . $row_visitas_usuario_bitacora['FECHA_VISITA'] . "</center></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                    </body>
                </html>
                <?php
                break;
            case '2':
                $sw2 = 0;
                $or = "";
                $where = "";
                $periodos = "";
                $myAnos = explode(',', $_GET['id_ano']);
                $myMes = explode(',', $_GET['id_mes']);
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_TIPO_VISITA = " . $_GET['tipo_visita'] . ") ";
                        $sw2 = 1;
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    } else {
                        $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_TIPO_VISITA = " . $_GET['tipo_visita'] . ") ";
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    }
                }
                $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE "
                                                                         . "  FROM tipo_visitas_2 "
                                                                         . " WHERE ID_TIPO_VISITA = " . $_GET['tipo_visita']);
                $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                ?>
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>AGM - Reporte Gestión <?php echo ucwords(strtolower($row_tipo_visita['NOMBRE'])); ?></title>
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
                                        <td style="font-size: 15px;"><center>REPORTE GESTIÓN <b><?php echo $row_tipo_visita['NOMBRE']; ?>.</b></center></td>
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
                                <th style="border: 1px solid; background-color: #003153; width: 20%;"><b style="color: #FFFFFF;"><center>USUARIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>DEPARTAMENTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 18%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 14%;"><b style="color: #FFFFFF;"><center>FECHA VISITA</center></b></th>
                            </tr>
                            <?php
                                $query_info_visitas_tipo_visita = mysqli_query($connection, "SELECT *, DATE(BL.FECHA_VISITA) AS FECHA_VISITA "
                                                                                                  . "  FROM bitacora_libretas_2 BL "
                                                                                                  . $where
                                                                                                  . $or
                                                                                                  . " ORDER BY BL.FECHA_VISITA DESC ");
                                $cont = 0;
                                $sub_total = 0;
                                $iva = 0;
                                $valor_total = 0;
                                while ($row_visitas_tipo_visita = mysqli_fetch_assoc($query_info_visitas_tipo_visita)) {
                                    echo "<tr>";
                                        $query_select_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                                  . "  FROM municipios_libreta_2 "
                                                                                                  . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_visitas_tipo_visita['ID_MUNICIPIO_LIBRETA']);
                                        $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                        $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE AS DEPARTAMENTO "
                                                                                             . "  FROM departamentos_visitas_2 "
                                                                                             . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                        $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE AS MUNICIPIO "
                                                                                          . "  FROM municipios_visitas_2 "
                                                                                          . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                          . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                        $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE AS USUARIO FROM usuarios_2 WHERE ID_USUARIO = " . $row_visitas_tipo_visita['ID_USUARIO']);
                                        $row_usuario = mysqli_fetch_array($query_select_usuario);
                                        echo "<td style='border: 1px solid;'><center>" . $row_usuario['USUARIO'] . "</center></td>";
                                        echo "<td style='border: 1px solid;'><center>" . $row_departamento['DEPARTAMENTO'] . "</center></td>";
                                        echo "<td style='border: 1px solid;'><center>" . $row_municipio['MUNICIPIO'] . "</center></td>";
                                        echo "<td style='border: 1px solid;'><center>" . $row_visitas_tipo_visita['FECHA_VISITA'] . "</center></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                    </body>
                </html>
                <?php
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
                                    $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . ") ";
                                    $sw2 = 1;
                                    $periodos = $periodos . $ano . $myMes[$index] . " - ";
                                } else {
                                    $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . ") ";
                                    $periodos = $periodos . $ano . $myMes[$index] . " - ";
                                }
                            }
                        ?>
                        <title>AGM - Reporte Gestión Periodo <?php echo substr($periodos, 0, -3); ?></title>
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
                                        <td style="font-size: 15px;"><center>REPORTE GESTIÓN <b>PERIODO <?php echo substr($periodos, 0, -3); ?></b></center></td>
                                    </tr>
                                </table>
                            </td>
                        </table>
                        <br />
                        <table style="border-collapse: collapse; border: 1px solid; font-size: 15px;" width="100%">
                            <tr style="border: 1px solid;">
                                <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;"><center>DEPARTAMENTO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 18%;"><b style="color: #FFFFFF;"><center>MUNICIPIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 38%;"><b style="color: #FFFFFF;"><center>TIPO VISITA</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 20%;"><b style="color: #FFFFFF;"><center>USUARIO</center></b></th>
                                <th style="border: 1px solid; background-color: #003153; width: 14%;"><b style="color: #FFFFFF;"><center>FECHA VISITA</center></b></th>
                            </tr>
                            <?php
                                $query_info_visitas_mensual = mysqli_query($connection, "SELECT *, DATE(BL.FECHA_VISITA) AS FECHA_VISITA "
                                                                                      . "  FROM bitacora_libretas_2 BL "
                                                                                      . $where
                                                                                      . $or
                                                                                      . " ORDER BY BL.FECHA_VISITA DESC ");
                                $cont = 0;
                                $sub_total = 0;
                                $iva = 0;
                                $valor_total = 0;
                                while ($row_visitas_mensual = mysqli_fetch_assoc($query_info_visitas_mensual)) {
                                    echo "<tr>";
                                        $query_select_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                                  . "  FROM municipios_libreta_2 "
                                                                                                  . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_visitas_mensual['ID_MUNICIPIO_LIBRETA']);
                                        $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                        $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE AS DEPARTAMENTO "
                                                                                             . "  FROM departamentos_visitas_2 "
                                                                                             . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                        $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE AS MUNICIPIO "
                                                                                          . "  FROM municipios_visitas_2 "
                                                                                          . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                          . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                        echo "<td style='border: 1px solid;'><center>" . $row_departamento['DEPARTAMENTO'] . "</center></td>";
                                        echo "<td style='border: 1px solid;'><center>" . $row_municipio['MUNICIPIO'] . "</center></td>";
                                        $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE AS TIPO_VISITA FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $row_visitas_mensual['ID_TIPO_VISITA']);
                                        $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                        echo "<td style='border: 1px solid;'><center>" . $row_tipo_visita['TIPO_VISITA'] . "</center></td>";
                                        $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE AS USUARIO FROM usuarios_2 WHERE ID_USUARIO = " . $row_visitas_mensual['ID_USUARIO']);
                                        $row_usuario = mysqli_fetch_array($query_select_usuario);
                                        echo "<td style='border: 1px solid;'><center>" . $row_usuario['USUARIO'] . "</center></td>";
                                        echo "<td style='border: 1px solid;'><center>" . $row_visitas_mensual['FECHA_VISITA'] . "</center></td>";
                                    echo "</tr>";
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