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
    $id_departamento = $_SESSION['id_departamento'];
    if ($_SESSION['id_user'] == 34) {
        $id_municipio = 518;
    } else {
        $id_municipio = $_SESSION['id_municipio'];
    }
    switch ($id_departamento) {
        case '41':
            switch ($id_municipio) {
                case '551':
                    $imagen = "<td style='width: 20%;'><img style='max-width: 100%;' src='../Images/Logos/Logo SEMAPP.jpg' /></td>";
                    $nombre_empresa = "<td style='font-size: 17px;'><center>SEMAPP S.A.S. E.S.P.</center></td>";
                    $nit_empresa = "<td style='font-size: 15px;'><center>NIT: 901253688-0</center></td>";
                    $direccion_empresa = "<td style='font-size: 15px;'><center>CRA. 4 # 18 - 59 BARRIO GUADUALES</center></td>";
                    $ciudad_empresa = "<td style='font-size: 15px;'><center>PITALITO - HUILA</center></td>";
                    break;
                case '1':
                    $imagen = "<td style='width: 20%;'><img style='max-width: 100%;' src='../Images/Logos/Logo ESIP.png' /></td>";
                    $nombre_empresa = "<td style='font-size: 17px;'><center>ESIP S.A.S. E.S.P.</center></td>";
                    $nit_empresa = "<td style='font-size: 15px;'><center>NIT: 901489509-4</center></td>";
                    $direccion_empresa = "<td style='font-size: 15px;'><center>CRA. 5 # 6 - 44 CENTRO COMERCIAL METROPOLITANO TORRE B LOCAL 229</center></td>";
                    $ciudad_empresa = "<td style='font-size: 15px;'><center>NEIVA - HUILA</center></td>";
                    break;
            }
            break;
        case '54':
            switch ($id_municipio) {
                case '874':
                    $imagen = "<td style='width: 20%;'><img style='max-width: 100%;' src='../Images/Logos/Logo American Lighting.png' /></td>";
                    $nombre_empresa = "<td style='font-size: 17px;'><center>AMERICAN LIGHTING S.A.S.</center></td>";
                    $nit_empresa = "<td style='font-size: 15px;'><center>NIT: 806010696-2</center></td>";
                    $direccion_empresa = "<td style='font-size: 15px;'><center>CALLE 13 CRA. 7MA # 13 - 79 ESQ. BARRIO LA PALMITA</center></td>";
                    $ciudad_empresa = "<td style='font-size: 15px;'><center>VILLA DEL ROSARIO - N. DE SANTANDER</center></td>";
                    break;
                case '498':
                    $imagen = "<td style='width: 20%;'><img style='max-width: 60%;' src='../Images/AGM Desarrollos.jpg' /></td>";
                    $nombre_empresa = "<td style='font-size: 17px;'><center>AGM DESARROLLOS S.A.S.</center></td>";
                    $nit_empresa = "<td style='font-size: 15px;'><center>NIT: 800186313-0</center></td>";
                    $direccion_empresa = "<td style='font-size: 15px;'><center>-</center></td>";
                    $ciudad_empresa = "<td style='font-size: 15px;'><center>OCAÑA - N. DE SANTANDER</center></td>";
                    break;
                case '1':
                    $imagen = "<td style='width: 20%;'><img style='max-width: 60%;' src='../Images/Logos/Logo SJC.png' /></td>";
                    $nombre_empresa = "<td style='font-size: 17px;'><center>CONSORCIO ALUMBRADO PUBLICO SAN JOSE DE CUCUTA</center></td>";
                    $nit_empresa = "<td style='font-size: 15px;'><center>NIT: 901034269-9</center></td>";
                    $direccion_empresa = "<td style='font-size: 15px;'><center>-</center></td>";
                    $ciudad_empresa = "<td style='font-size: 15px;'><center>CUCUTA - N. DE SANTANDER</center></td>";
                    break;
                case '518':
                    $imagen = "<td style='width: 20%;'><img style='max-width: 60%;' src='../Images/AGM Desarrollos.jpg' /></td>";
                    $nombre_empresa = "<td style='font-size: 17px;'><center>AGM DESARROLLOS S.A.S.</center></td>";
                    $nit_empresa = "<td style='font-size: 15px;'><center>NIT: 800186313-0</center></td>";
                    $direccion_empresa = "<td style='font-size: 15px;'><center>-</center></td>";
                    $ciudad_empresa = "<td style='font-size: 15px;'><center>PAMPLONA - N. DE SANTANDER</center></td>";
                    break;
                case '553':
                    $imagen = "<td style='width: 20%;'><img style='max-width: 60%;' src='../Images/AGM Desarrollos.jpg' /></td>";
                    $nombre_empresa = "<td style='font-size: 17px;'><center>AGM DESARROLLOS S.A.S.</center></td>";
                    $nit_empresa = "<td style='font-size: 15px;'><center>NIT: 800186313-0</center></td>";
                    $direccion_empresa = "<td style='font-size: 15px;'><center>-</center></td>";
                    $ciudad_empresa = "<td style='font-size: 15px;'><center>PUERTO SANTANDER - N. DE SANTANDER</center></td>";
                    break;
            }
            break;
        case '8':
            $imagen = "<td style='width: 20%;'><img style='max-width: 100%;' src='../Images/Logos/Logo American Lighting.png' /></td>";
            $nombre_empresa = "<td style='font-size: 17px;'><center>AMERICAN LIGHTING S.A.S.</center></td>";
            $nit_empresa = "<td style='font-size: 15px;'><center>NIT: 806010696-2</center></td>";
            $direccion_empresa = "<td style='font-size: 15px;'><center>-</center></td>";
            $ciudad_empresa = "<td style='font-size: 15px;'><center>RIOHACHA - LA GUAJIRA</center></td>";
            break;
        default:
            $imagen = "<td style='width: 20%;'><img style='max-width: 60%;' src='../Images/AGM Desarrollos.jpg' /></td>";
            $nombre_empresa = "<td style='font-size: 17px;'><center>AGM DESARROLLOS S.A.S.</center></td>";
            $nit_empresa = "<td style='font-size: 15px;'><center>NIT: 800186313-0</center></td>";
            $direccion_empresa = "<td style='font-size: 15px;'><center>CENTRO, SECTOR MATUNA, AV. DANIEL LEMAITRE, EDF. BANCO DEL ESTADO PISO 5</center></td>";
            $ciudad_empresa = "<td style='font-size: 15px;'><center>CARTAGENA - COLOMBIA</center></td>";
            break;
    }
    if ($_SESSION['id_departamento'] != 0) {
        $and_id_departamento = "AND PQR.ID_COD_DPTO = " . $_SESSION['id_departamento'];
        $and_id_municipio = "AND PQR.ID_COD_MPIO = " . $_SESSION['id_municipio'];
    } else {
        $and_id_departamento = " ";
        $and_id_municipio = " ";
    }
    switch ($sw) {
        case '4':
?>
            <!DOCTYPE html>
            <html>

            <head>
                <?php
                if (strlen($_GET['id_mes']) == 1) { ?>
                    <title>AGM - Reporte P.Q.R. Periodo <?php echo $_GET['id_ano'] . "0" . $_GET['id_mes']; ?></title>
                <?php
                } else { ?>
                    <title>AGM - Reporte P.Q.R. Periodo <?php echo $_GET['id_ano'] . $_GET['id_mes']; ?></title>
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
                    @page {
                        margin: 0;
                    }

                    body {
                        margin: 1.6cm;
                    }
                }

                @font-face {
                    font-family: 'Cabin';
                    src: url('../Fonts/Cabin.ttf');
                }
            </style>

            <body onload="window.print()">
                <table width="100%">
                    <tr>
                        <?php echo $imagen; ?>
                        <td>
                            <table width="90%">
                                <tr>
                                    <?php echo $nombre_empresa; ?>
                                </tr>
                                <tr>
                                    <?php echo $nit_empresa; ?>
                                </tr>
                                <tr>
                                    <?php echo $direccion_empresa; ?>
                                </tr>
                                <tr>
                                    <?php echo $ciudad_empresa; ?>
                                </tr>
                            </table>
                        </td>
                        <td width="20%">
                            <table style="border-collapse: collapse; border: 1px solid;" width="100%">
                                <tr>
                                    <th style="border: 1px solid; background-color: #003153; width: 20%;"><b style="color: #FFFFFF;">
                                            <center><?php echo "FECHA REPORTE"; ?></center>
                                        </b></th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid;">
                                        <center><?php echo date('Y-m-d'); ?></center>
                                    </td>
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
                                <tr>
                                    <td style="font-size: 15px;">
                                        <center>REPORTE P.Q.R.</center>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;">
                                        <center><b>PERIODO <?php echo $_GET['id_ano'] . "0" . $_GET['id_mes']; ?></b></center>
                                    </td>
                                </tr>
                            <?php
                            } else { ?>
                                <tr>
                                    <td style="font-size: 15px;">
                                        <center>REPORTE P.Q.R.</center>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;">
                                        <center><b>PERIODO <?php echo $_GET['id_ano'] . $_GET['id_mes']; ?></b></center>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </td>
                </table>
                <br />
                <table style="border-collapse: collapse; border: 1px solid; font-size: 15px;" width="100%">
                    <tr style="border: 1px solid;">
                        <th style="border: 1px solid; background-color: #003153; width: 11%;"><b style="color: #FFFFFF;">
                                <center>RADICADO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 17%;"><b style="color: #FFFFFF;">
                                <center>IDENTIFICACIÓN</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;">
                                <center>USUARIO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;">
                                <center>DEPARTAMENTO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;">
                                <center>MUNICIPIO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;">
                                <center>FECHA INGRESO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;">
                                <center>ESTADO</center>
                            </b></th>
                    </tr>
                    <?php
                    $query_select_info_mensual = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                                                               PQR.ID_PQR, PQR.NOMBRES AS NOMBRES,
                                                                                               PQR.APELLIDOS AS APELLIDOS,
                                                                                               PQR.IDENTIFICACION AS IDENTIFICACION,
                                                                                               PQR.ESTADO_PQR, PQR.RADICADO, PQR.FECHA_INGRESO
                                                                                          FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                                                               municipios_visitas_2 MUN
                                                                                         WHERE PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                                           AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                                           AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                                           AND YEAR(PQR.FECHA_INGRESO) = " . $_GET['id_ano'] . "
                                                                                           AND MONTH(PQR.FECHA_INGRESO) = " . $_GET['id_mes'] . "
                                                                                           $and_id_departamento
                                                                                           $and_id_municipio
                                                                                         ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC ");
                    while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                        $estado = "";
                        switch ($row_info_mensual['ESTADO_PQR']) {
                            case "1":
                                $estado = "<span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>RC</b></span>";
                                break;
                            case "2":
                                $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>RS</b></span>";
                                break;
                        }
                        echo "<tr>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['RADICADO'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['IDENTIFICACION'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['NOMBRES'] . " " . $row_info_mensual['APELLIDOS'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['DEPARTAMENTO'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['MUNICIPIO'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['FECHA_INGRESO'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>$estado</center></td>";
                        echo "</tr>";
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
                <title>AGM - Reporte P.Q.R. Rango <?php echo $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin']; ?></title>
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
                    @page {
                        margin: 0;
                    }

                    body {
                        margin: 1.6cm;
                    }
                }

                @font-face {
                    font-family: 'Cabin';
                    src: url('../Fonts/Cabin.ttf');
                }
            </style>

            <body onload="window.print()">
                <table width="100%">
                    <tr>
                        <?php echo $imagen; ?>
                        <td>
                            <table width="90%">
                                <tr>
                                    <?php echo $nombre_empresa; ?>
                                </tr>
                                <tr>
                                    <?php echo $nit_empresa; ?>
                                </tr>
                                <tr>
                                    <?php echo $direccion_empresa; ?>
                                </tr>
                                <tr>
                                    <?php echo $ciudad_empresa; ?>
                                </tr>
                            </table>
                        </td>
                        <td width="20%">
                            <table style="border-collapse: collapse; border: 1px solid;" width="100%">
                                <tr>
                                    <th style="border: 1px solid; background-color: #003153; width: 20%;"><b style="color: #FFFFFF;">
                                            <center><?php echo "FECHA REPORTE"; ?></center>
                                        </b></th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid;">
                                        <center><?php echo date('Y-m-d'); ?></center>
                                    </td>
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
                                <td style="font-size: 15px;">
                                    <center>REPORTE P.Q.R.</center>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;">
                                    <center><b>RANGO <?php echo $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin']; ?></b></center>
                                </td>
                            </tr>
                        </table>
                    </td>
                </table>
                <br />
                <table style="border-collapse: collapse; border: 1px solid; font-size: 15px;" width="100%">
                    <tr style="border: 1px solid;">
                        <th style="border: 1px solid; background-color: #003153; width: 11%;"><b style="color: #FFFFFF;">
                                <center>RADICADO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 17%;"><b style="color: #FFFFFF;">
                                <center>IDENTIFICACIÓN</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;">
                                <center>USUARIO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 10%;"><b style="color: #FFFFFF;">
                                <center>DEPARTAMENTO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;">
                                <center>MUNICIPIO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;">
                                <center>FECHA INGRESO</center>
                            </b></th>
                        <th style="border: 1px solid; background-color: #003153; width: 7%;"><b style="color: #FFFFFF;">
                                <center>ESTADO</center>
                            </b></th>
                    </tr>
                    <?php
                    $query_select_info_rango = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                                                             PQR.ID_PQR, PQR.NOMBRES AS NOMBRES,
                                                                                             PQR.APELLIDOS AS APELLIDOS,
                                                                                             PQR.IDENTIFICACION AS IDENTIFICACION,
                                                                                             PQR.ESTADO_PQR, PQR.RADICADO, PQR.FECHA_INGRESO
                                                                                        FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                                                             municipios_visitas_2 MUN
                                                                                       WHERE PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                                         AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                                         AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                                         AND PQR.FECHA_INGRESO BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "'
                                                                                         $and_id_departamento
                                                                                         $and_id_municipio
                                                                                       ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC ");
                    while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                        $estado = "";
                        switch ($row_info_rango['ESTADO_PQR']) {
                            case "1":
                                $estado = "<span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>RC</b></span>";
                                break;
                            case "2":
                                $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>RS</b></span>";
                                break;
                        }
                        echo "<tr>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['RADICADO'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['IDENTIFICACION'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['NOMBRES'] . " " . $row_info_rango['APELLIDOS'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['DEPARTAMENTO'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['MUNICIPIO'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['FECHA_INGRESO'] . "</center></td>";
                        echo "<td style='border: 1px solid; vertical-align: middle;'><center>$estado</center></td>";
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