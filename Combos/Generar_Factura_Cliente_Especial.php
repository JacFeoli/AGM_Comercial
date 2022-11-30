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
    }
    $query_select_info_fact_esp = mysqli_query($connection, "SELECT * "
                                                          . "  FROM facturacion_especiales_2 "
                                                          . " WHERE ID_FACTURACION = " . $_GET['id_fact_especial']);
    $row_info_fact_esp = mysqli_fetch_array($query_select_info_fact_esp);
    $query_select_contribuyente_info = mysqli_query($connection, "SELECT * FROM contribuyentes_2 "
                                                               . " WHERE ID_CONTRIBUYENTE = " . $row_info_fact_esp['ID_CONTRIBUYENTE']);
    $row_contribuyente_info = mysqli_fetch_array($query_select_contribuyente_info);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Factura Cliente Especial:  <?php echo $row_contribuyente_info['NOMBRE']; ?></title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="../Images/agm_desarrollos_2_icon" type="image/x-icon" />
    </head>
    <style>
        table {
            font-family: 'Cabin';
        }
        th {
            font-size: 12px;
            border: 1px solid #000000;
        }
        td {
            font-size: 11px;
            border: 1px solid #000000;
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
        <?php
            $query_select_alcaldia = mysqli_query($connection, "SELECT * FROM alcaldias_2 "
                                                             . " WHERE ID_COD_DPTO = " . $row_info_fact_esp['ID_COD_DPTO'] . " "
                                                             . "   AND ID_COD_MPIO = " . $row_info_fact_esp['ID_COD_MPIO']);
            $row_alcaldia = mysqli_fetch_array($query_select_alcaldia);
        ?>
        <table style="width: 100%; text-align: center;">
            <tr>
                <?php
                    switch ($row_alcaldia['NOMBRE']) {
                        case 'ALCALDIA MUNICIPAL DE MAGANGUE': ?>
                            <td style="width: 20%; background-color: #00B050;"><img style="max-width: 60%;" src="../Images/Magangue/Escudo Magangue.png" /></td>
                            <td style="padding: 0;">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="font-size: 15px; background-color: #00B050;">
                                            <center><?php echo $row_alcaldia['NOMBRE']; ?></center>
                                            <center><?php echo "NIT " . $row_alcaldia['NIT_ALCALDIA']; ?></center>
                                        </td>
                                        <td style="font-size: 12px; background-color: #00B050;">
                                            <center>Vigencia:</center>
                                            <center>01/08/2018</center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 13px; background-color: #8CE89B;"><center>LIQUIDACIÓN IMPUESTO ALUMBRADO PÚBLICO</center></td>
                                        <td style="font-size: 12px; background-color: #00B050;"></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 13px; background-color: #8DE7C5;"><center><b>SECRETARÍA DE HACIENDA MUNICIPAL</b></center></td>
                                        <td style="font-size: 12px; background-color: #00B050;">Versión: 01</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 20%; background-color: #00B050;"><img style="max-width: 60%;" src="../Images/Magangue/Escudo Colombia.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE SAN JUAN DEL CESAR': ?>
                            <td style="width: 20%; border: 0px;"><img style="max-width: 60%;" src="../Images/San Juan del Cesar/Escudo San Juan del Cesar.png" /></td>
                            <td style="padding: 0; border: 0px;">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="font-size: 15px; border: 0px;">
                                            <center>REPUBLICA DE COLOMBIA</center>
                                            <center>DEPARTAMENTO DE LA GUAJIRA</center>
                                            <center>MUNICIPIO DE SAN JUAN DEL CESAR</center>
                                            <center><?php echo "NIT. " . $row_alcaldia['NIT_ALCALDIA']; ?></center>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 20%; border: 0px;"><img style="max-width: 60%;" src="../Images/San Juan del Cesar/Escudo Alcaldia.png" /></td>
                            <td style="width: 20%; border: 0px;"><img style="max-width: 60%;" src="../Images/San Juan del Cesar/Escudo 1.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE ARJONA': ?>
                            <td style="width: 100%;"><img style="max-width: 60%;" src="../Images/Arjona/Escudo Alcaldia Arjona.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE SAN JACINTO': ?>
                            <td style="width: 100%;"><img style="max-width: 60%;" src="../Images/San Jacinto/Membrete San Jacinto 1.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE EL PASO': ?>
                            <td style="width: 100%;"><img style="max-width: 60%;" src="../Images/El Paso/Membrete El Paso 1.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE SANTA ROSA': ?>
                            <td style="width: 20%;"><img style="max-width: 60%;" src="../Images/Santa Rosa/Escudo Colombia.png" /></td>
                            <td>
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="font-size: 19px; color: #FF0000;"><center>REPÚBLICA DE COLOMBIA</center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 19px; color: #FF0000;"><center>DEPARTAMENTO DE BOLÍVAR</center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 19px;">
                                            <center><?php echo $row_alcaldia['NOMBRE']; ?></center>
                                            <center><?php echo "NIT " . $row_alcaldia['NIT_ALCALDIA']; ?></center>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 20%;"><img style="max-width: 60%;" src="../Images/Santa Rosa/Escudo Santa Rosa.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE VILLANUEVA-BOLIVAR': ?>
                            <td style="width: 100%;"><img style="max-width: 60%;" src="../Images/Villanueva-Bolivar/Escudos Villanueva - Colombia.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE AGUSTIN CODAZZI': ?>
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 20%; text-align: center;"><img style="max-width: 60%;" src="../Images/Agustin Codazzi/Escudo Agustin Codazzi.png" /></td>
                                    <td>
                                        <table style="width: 100%;">
                                            <tr>
                                                <td style="font-size: 17px; border: 0px;">
                                                    <center>REPUBLICA DE COLOMBIA</center>
                                                    <center>DEPARTAMENTO DEL CESAR</center>
                                                    <center><?php echo $row_alcaldia['NOMBRE']; ?> CESAR</center>
                                                    <center><?php echo "NIT " . $row_alcaldia['NIT_ALCALDIA']; ?></center>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width: 20%; text-align: center;" colspan="2"><img style="max-width: 60%;" src="../Images/Agustin Codazzi/Escudo Agustin Codazzi 2.png" /></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 7px;">
                                        <center>VERSIÓN: 01</center>
                                        <center>ACTUALIZACION: 13/02/2020</center>
                                    </td>
                                    <td style="font-size: 19px;"><center>SECRETARIA DE HACIENDA</center></td>
                                    <td style="font-size: 7px;"><center>ESTADO: CONTROLADO</center></td>
                                    <td style="font-size: 7px;"><center>Página 1 de 2</center></td>
                                </tr>
                            </table>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE EL COPEY': ?>
                            <td style="width: 20%;"><img style="max-width: 60%;" src="../Images/El Copey/Escudo 1 El Copey.png" /></td>
                            <td>
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="font-size: 19px; color: #FF0000;"><center>REPÚBLICA DE COLOMBIA</center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 19px; color: #FF0000;"><center>DEPARTAMENTO DEL CESAR</center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 19px;">
                                            <center><?php echo $row_alcaldia['NOMBRE']; ?></center>
                                            <center><?php echo "NIT " . $row_alcaldia['NIT_ALCALDIA']; ?></center>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 20%;"><img style="max-width: 60%;" src="../Images/El Copey/Escudo 2 El Copey.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE LA PAZ': ?>
                            <td style="width: 20%;"><img style="max-width: 60%;" src="../Images/La Paz/Escudo La Paz.png" /></td>
                            <td>
                                <table style="width: 100%;">
                                    <tr>
                                        <td colspan="3" style="font-size: 19px;">
                                            <center><?php echo $row_alcaldia['NOMBRE']; ?></center>
                                            <center><?php echo "NIT " . $row_alcaldia['NIT_ALCALDIA']; ?></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 13px; color: #FF0000;"><center>Codigo: 110</center></td>
                                        <td style="font-size: 13px; color: #FF0000;"><center>Versión: 1 Fecha 01-2013</center></td>
                                        <td style="font-size: 13px; color: #FF0000;"><center>Pág: 1 de 1</center></td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 20%;"><img style="max-width: 60%;" src="../Images/La Paz/Escudo La Paz.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE RIOHACHA': ?>
                            <td style="width: 30%; border: 0px;"><img style="max-width: 100%;" src="../Images/Riohacha/Escudo Riohacha.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE TURBACO': ?>
                            <td style="width: 100%; border: 0px;"><img style="max-width: 70%;" src="../Images/Turbaco/Membrete 1 Turbaco.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE FACATATIVA': ?>
                            <td style="width: 20%;"><img style="max-width: 60%;" src="../Images/Facatativa/Escudo Facatativa.png" /></td>
                            <td>
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="font-size: 14px; border: 0px;"><center><b>LIQUIDACIÓN OFICIAL DE IMPUESTO DE ALUMBRADO PÚBLICO</b></center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px; border: 0px;"><center><b>REPÚBLICA DE COLOMBIA</b></center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px; border: 0px;"><center><b>DEPARTAMENTO DE CUNDINAMARCA</b></center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px; border: 0px;"><center><b>MUNICIPIO DE FACATATIVA</b></center></td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 20%;"><img style="max-width: 100%;" src="../Images/Facatativa/Escudo Cundinamarca.png" /></td>
                            <?php
                            break;
                        case 'ALCALDIA MUNICIPAL DE AGUACHICA': ?>
                            <td style="width: 100%; border: 0px;"><img style="max-width: 100%;" src="../Images/Aguachica/Membrete 1 - Aguachica.png" /></td>
                            <?php
                            break;
                    }
                ?>
            </tr>
            <tr>
                <?php
                    switch ($row_alcaldia['NOMBRE']) {
                        case 'ALCALDIA MUNICIPAL DE FACATATIVA': ?>
                        
                        <?php
                            break;
                        default: ?>
                            <td style="border: 0px; text-align: right;" colspan="3">
                                <?php
                                    $fecha_creacion = date_create($row_info_fact_esp['FECHA_CREACION']);
                                ?>
                                <b style="font-size: 14px; float: left;">Fecha Emisión: <?php echo date_format($fecha_creacion, 'd/m/Y'); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <b style="font-size: 14px; float: right;">Fecha Impresión: <?php echo date('d/m/Y'); ?></b>
                            </td>
                        <?php
                            break;
                    }
                ?>
            </tr>
        </table>
        <table width="100%">
            <?php
                switch ($row_alcaldia['NOMBRE']) {
                case 'ALCALDIA MUNICIPAL DE FACATATIVA': ?>
                    <td>
                        <table width="100%">
                            <tr>
                                <td style="font-size: 15px; border: 0px;">
                                    <center>La Secretaría de Hacienda del Municipio de Facatativá Cundinamarca,
                                        en uso de las atribuciones conferidas por los artículos 684, 686, 688
                                        del Estatuto Tributario Nacional y El Acuerdo Municipal  007 de 2018 por
                                        medio del cual se actualiza el Estatuto Tributario Municipal, Expide la
                                        presente Liquidación Oficial del impuesto de Alumbrado Público:
                                    </center>
                                </td>
                            </tr>
                        </table>
                    </td>
                <?php
                    break;
                default: ?>
                    <td style="border: 0px;">
                        <table width="100%">
                            <tr>
                                <td style="font-size: 15px; border: 0px; font-weight: bold;"><center>LIQUIDACIÓN OFICIAL NO. <?php echo $row_info_fact_esp['CONSECUTIVO_FACT']; ?></center></td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px; border: 0px; font-weight: bold;"><center>IMPUESTO ALUMBRADO PÚBLICO</center></td>
                            </tr>
                        </table>
                    </td>
                <?php
                }
            ?>
        </table>
        <?php
            if ($row_alcaldia['NOMBRE'] != 'ALCALDIA MUNICIPAL DE FACATATIVA') { ?>
                <hr style="margin-bottom: 0px;" />
                <p style="text-align: justify; margin-top: 0px; font-size: 14px;">
                    <?php
                        $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE "
                                                                                 . "  FROM municipios_visitas_2 "
                                                                                 . " WHERE ID_DEPARTAMENTO = '" . $row_info_fact_esp['ID_COD_DPTO'] . "' "
                                                                                 . "   AND ID_MUNICIPIO = '" . $row_info_fact_esp['ID_COD_MPIO'] . "'");
                        $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                    ?>
                    La Secretaría de Hacienda del Municipio de <?php echo $row_nombre_municipio['NOMBRE']; ?>, en uso de las atribuciones conferidas por los artículos 684, 686, 688
                    del Estatuto Tributario Nacional y El <i><?php echo $row_info_fact_esp['ACUERDO_MCPAL']; ?> por medio del cual se actualiza
                    el Estatuto Tributario Municipal</i>, expide la presente Liquidación Oficial del impuesto de Alumbrado Público:
                </p>
            <?php
            }
        ?>
        <?php
            switch ($row_alcaldia['NOMBRE']) {
                case 'ALCALDIA MUNICIPAL DE FACATATIVA': ?>
                    <table style="border-collapse: collapse; border: 1px solid; font-size: 13px;" width="100%">
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>1. AÑO GRABABLE:</b>
                                <?php
                                    $ano = str_split(substr($row_info_fact_esp['PERIODO_FACTURA'], 0, 4));
                                    echo "<span style='border: 1px solid; padding: 2px;'>" . $ano[0] . "</span>";
                                    echo "<span style='border: 1px solid; padding: 2px;'>" . $ano[1] . "</span>";
                                    echo "<span style='border: 1px solid; padding: 2px;'>" . $ano[2] . "</span>";
                                    echo "<span style='border: 1px solid; padding: 2px;'>" . $ano[3] . "</span>";
                                ?>
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                <b>2. PERIODO:</b>
                                <?php
                                    $i = 0;
                                    $anoPeriodo = substr($row_info_fact_esp['PERIODO_FACTURA'], 0, 4);
                                    $mesPeriodo = substr($row_info_fact_esp['PERIODO_FACTURA'], 4, 2);
                                    $query_select_periodo_fact = mysqli_query($connection, "SELECT PERIODO "
                                                                                         . "  FROM periodos_facturacion_especiales_2 "
                                                                                         . " WHERE ANO_FACTURA = '" . $anoPeriodo . "' "
                                                                                         . "   AND MES_FACTURA = '" . $mesPeriodo . "'");
                                    $row_periodo_fact = mysqli_fetch_array($query_select_periodo_fact);
                                    $periodo = str_split($row_periodo_fact['PERIODO']);
                                    foreach ($periodo as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $periodo[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #8497B0;"><b>A. INFORMACIÓN DEL CONTRIBUYENTE</b></td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>3. APELLIDOS Y NOMBRES O RAZÓN SOCIAL</b></td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;">
                                <?php
                                    $i = 0;
                                    $contribuyente = str_split($row_contribuyente_info['NOMBRE']);
                                    foreach ($contribuyente as $letters) {
                                        if ($contribuyente[$i] == " ") {
                                            echo "<span style='border: 1px solid; display: inline-block; padding: 2px; margin-bottom: 5px;'>&nbsp;</span>";
                                        } else {
                                            echo "<span style='border: 1px solid; display: inline-block; padding: 2px; margin-bottom: 5px;'>" . $contribuyente[$i] . "</span>";
                                        }
                                        $i = $i + 1;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>4. IDENTIFICACIÓN DEL RESPONSABLE</b></td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;">
                                <table>
                                    <tr>
                                        <td style="border: 0px;"><b>CC </b></td>
                                        <td style="border: 0px;">&nbsp;</td>
                                        <td style="border: 0px;"><b>NIT</b></td>
                                        <td style="border: 0px;">&nbsp;</td>
                                        <td style="border: 0px;"><b>TI </b></td>
                                        <td style="border: 0px;">&nbsp;</td>
                                        <td style="border: 0px;"><b>CE </b></td>
                                        <td style="border: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td style="border: 0px;"><b>NÚMERO</b></td>
                                        <td style="border: 0px;">&nbsp;</td>
                                        <td style="border: 0px;"><b>DV</b></td>
                                        <td style="border: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td style="border: 0px;"><b>5. TELÉFONO FIJO O MÓVIL</b></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td style="border: 0px;">&nbsp;</td>
                                        <td>X</td>
                                        <td style="border: 0px;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border: 0px;">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td style="border: 0px;">&nbsp;</td>
                                        <td style="border: 0px;">
                                            <?php
                                                $i = 0;
                                                $nit = str_split($row_contribuyente_info['NIT_CONTRIBUYENTE']);
                                                foreach ($nit as $letters) {
                                                    if ($i <= 8) {
                                                        echo "<span style='border: 1px solid #000000; padding: 2px;'>" . $nit[$i] . "</span>";
                                                        $i = $i + 1;
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td style="border: 0px;">-</td>
                                        <td style="border: 0px;"><?php echo "<span style='border: 1px solid #000000; padding: 2px;'>" . $nit[9] . "</span>"; ?></td>
                                        <td style="border: 0px;">&nbsp;</td>
                                        <td style="border: 0px;">
                                            <?php
                                                $i = 0;
                                                $nit = str_split($row_contribuyente_info['NIT_CONTRIBUYENTE']);
                                                foreach ($nit as $letters) {
                                                    if ($i <= 14) {
                                                        echo "<span style='border: 1px solid #000000; padding: 2px; color: #FFFFFF;'>" . $nit[$i] . "</span>";
                                                        $i = $i + 1;
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid; font-size: 8px;"><b>6. DIRECCIÓN DE NOTIFICACIÓN. Escriba la dirección donde la Secretaria de Hacienda puede comunicarse con usted. Recuerde el apartado aéreo no sirve como dirección de notificación</b></td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;">
                                <?php
                                    $i = 0;
                                    $direccion = str_split($row_contribuyente_info['DIRECCION_CONTRIBUYENTE']);
                                    foreach ($direccion as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $direccion[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid; font-size: 8px;"><b>6.1 CIUDAD</b></td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;">
                                <?php
                                    $i = 0;
                                    $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE "
                                                                                             . "  FROM municipios_visitas_2 "
                                                                                             . " WHERE ID_DEPARTAMENTO = '" . $row_info_fact_esp['ID_COD_DPTO'] . "' "
                                                                                             . "   AND ID_MUNICIPIO = '" . $row_info_fact_esp['ID_COD_MPIO'] . "'");
                                    $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                                    $nombre_municipio = str_split($row_nombre_municipio['NOMBRE']);
                                    foreach ($nombre_municipio as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $nombre_municipio[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background-color: #8497B0;"><b>B. PAGO</b></td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>7. PERIODO CORRIENTE</b>
                                <?php
                                    $i = 0;
                                    $anoPeriodo = substr($row_info_fact_esp['PERIODO_FACTURA'], 0, 4);
                                    $mesPeriodo = substr($row_info_fact_esp['PERIODO_FACTURA'], 4, 2);
                                    $query_select_periodo_fact = mysqli_query($connection, "SELECT PERIODO "
                                                                                         . "  FROM periodos_facturacion_especiales_2 "
                                                                                         . " WHERE ANO_FACTURA = '" . $anoPeriodo . "' "
                                                                                         . "   AND MES_FACTURA = '" . $mesPeriodo . "'");
                                    $row_periodo_fact = mysqli_fetch_array($query_select_periodo_fact);
                                    $periodo = str_split($row_periodo_fact['PERIODO']);
                                    foreach ($periodo as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $periodo[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                <b>8. NO. LIQUIDACIONES VENCIDAS</b>
                                <?php
                                    $i = 0;
                                    $liq_vencidas = str_split($row_info_fact_esp['NO_LIQ_VENCIDAS']);
                                    foreach ($liq_vencidas as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $liq_vencidas[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>9. SECTOR Y/O ACTIVIDAD</b>
                                <?php
                                    $i = 0;
                                    $sector = str_split($row_contribuyente_info['SECTOR_CONTRIBUYENTE']);
                                    foreach ($sector as $letters) {
                                        if ($sector[$i] == " ") {
                                            echo "<span style='border: 1px solid; padding: 2px; display: inline-block; margin-bottom: 5px;'>&nbsp;</span>";
                                        } else {
                                            echo "<span style='border: 1px solid; padding: 2px; display: inline-block; margin-bottom: 5px;'>" . $sector[$i] . "</span>";
                                        }
                                        $i = $i + 1;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>10. TIPO DE BASE GRAVABLE</b>
                                <?php
                                    $i = 0;
                                    switch ($row_info_fact_esp['ID_TIPO_FACTURACION']) {
                                        case '1':
                                            $base_gravable = str_split("VALOR CONSUMO DE ENERGIA");
                                            foreach ($base_gravable as $letters) {
                                                echo "<span style='border: 1px solid; padding: 2px;'>" . $base_gravable[$i] . "</span>";
                                                $i = $i + 1;
                                            }
                                            break;
                                        case '2':
                                            $base_gravable = str_split("VALOR SMMLV");
                                            foreach ($base_gravable as $letters) {
                                                echo "<span style='border: 1px solid; padding: 2px;'>" . $base_gravable[$i] . "</span>";
                                                $i = $i + 1;
                                            }
                                            break;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>11. VALOR BASE GRAVABLE</b>
                                <?php
                                    $i = 0;
                                    $valor_tarifa = str_split($row_info_fact_esp['VALOR_TARIFA']);
                                    foreach ($valor_tarifa as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $valor_tarifa[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                <b>12. TARIFA</b>
                                <?php
                                    $i = 0;
                                    switch ($row_info_fact_esp['ID_TIPO_FACTURACION']) {
                                        case '1':
                                            $tarifa = str_split($row_info_fact_esp['TARIFA'] . "%");
                                            foreach ($tarifa as $letters) {
                                                echo "<span style='border: 1px solid; padding: 2px;'>" . $tarifa[$i] . "</span>";
                                                $i = $i + 1;
                                            }
                                            break;
                                        case '2':
                                            $tarifa = str_split($row_info_fact_esp['TARIFA']);
                                            foreach ($tarifa as $letters) {
                                                echo "<span style='border: 1px solid; padding: 2px;'>" . $tarifa[$i] . "</span>";
                                                $i = $i + 1;
                                            }
                                            break;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>13. VALOR IMPUESTO MENSUAL</b>
                                <?php
                                    $i = 0;
                                    $valor_factura = str_split($row_info_fact_esp['VALOR_FACTURA']);
                                    foreach ($valor_factura as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $valor_factura[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                <b>14. VALOR SANCIÓN</b>
                                <?php
                                    $i = 0;
                                    $valor_sancion = str_split("0");
                                    foreach ($valor_sancion as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $valor_sancion[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                <b>15. INTERESES POR MORA</b>
                                <?php
                                    $i = 0;
                                    $intereses = str_split("0");
                                    foreach ($intereses as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $intereses[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>16. PAGO TOTAL</b>
                                <?php
                                    $i = 0;
                                    $valor_total = str_split($row_info_fact_esp['VALOR_FACTURA'] + $row_info_fact_esp['VALOR_LIQ_VENCIDAS']);
                                    foreach ($valor_total as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $valor_total[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                                &nbsp;
                                &nbsp;
                                &nbsp;
                                <b>17. FECHA</b>
                                <?php
                                    $i = 0;
                                    $fecha_factura = date_create($row_info_fact_esp['FECHA_FACTURA']);
                                    $fecha_factura2 = date_format($fecha_factura, 'd/m/Y');
                                    $fecha_factura3 = str_split(str_replace("/", "", $fecha_factura2));
                                    foreach ($fecha_factura3 as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $fecha_factura3[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;"><b>18. CUENTA DE AHORROS DEL <?php echo $row_alcaldia['BANCO_FIDUCIA']; ?></b>
                                <?php
                                    $i = 0;
                                    $cuenta_fiducia = str_split($row_alcaldia['CUENTA_FIDUCIA']);
                                    foreach ($cuenta_fiducia as $letters) {
                                        echo "<span style='border: 1px solid; padding: 2px;'>" . $cuenta_fiducia[$i] . "</span>";
                                        $i = $i + 1;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border: 0px; border-right: 1px solid;">
                                <b>19. A NOMBRE DE: </b><span style="font-weight: bold; border: 1px solid; padding-left: 5px; padding-right: 5px;"><?php echo $row_alcaldia['NOMBRE_FIDUCIA']; ?></span>&nbsp;&nbsp;&nbsp;
                                <b>20. NIT: </b><span style="font-weight: bold; border: 1px solid; padding-left: 5px; padding-right: 5px;"><?php echo $row_alcaldia['NIT_FIDUCIA']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="border-right: 1px solid; font-size: 9px;">
                                <p>
                                    El Contribuyente es sujeto pasivo del impuesto de Alumbrado Público por cuanto:
                                    i) Se analizó y determinó que es usuario potencial del servicio ii) Está clasificado
                                    de acuerdo a los principios de progresividad y equidad en materia tributaria iii)
                                    Opera o posee cualquier tipo de infraestructura en el Municipio y/o tiene establecimiento
                                    físico en la jurisdicción del Municipio y iv) en virtud de lo anterior, cumple el hecho
                                    generador del impuesto de alumbrado público que es el beneficio por la prestación del mismo.
                                </p>
                                <p>
                                    Los artículos 12 y 13 del Acuerdo No. 007 de 2018 fijaron la base gravable y la tarifa del
                                    impuesto de alumbrado público de acuerdo al tipo de usuario y actividad que desarrolla
                                </p>
                                <p>
                                    Contra la presente Liquidación Oficial del Impuesto de Alumbrado Público, procede el recurso
                                    de reconsideración de que trata el artículo 720 del Estatuto Tributario Nacional, el cual deberá
                                    interponerse dentro de los dos (2) meses siguientes a su notificación, cumpliendo los requisitos
                                    señalados en el artículo 722 del mismo ordenamiento jurídico, y presentarla en la oficina de la
                                    Secretaria de Hacienda del Municipio de Facatativá Cundinamarca.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">
                                <b style="font-size: 9px;">
                                    Nota esta LIQUIDACIÓN OFICIAL DE IMPUESTO DE ALUMBRADO PÚBLICO deberá pagarse a más tardar el
                                    último día hábil de mes siguiente a su liquidación  periodo gravable
                                </b>
                            </td>
                        </tr>
                    </table>
                    <?php
                    break;
                default: ?>
                    <table style="border-collapse: collapse; border: 1px solid; font-size: 13px; text-align: center;" width="100%">
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; background-color: #003153; width: 10%; text-align: left; font-size: 11px;"><b style="color: #FFFFFF;">CONTRIBUYENTE:</b></th>
                            <th style="border: 1px solid; background-color: #003153; width: 18%; font-size: 11px;"><b style="color: #FFFFFF;"><center><?php echo $row_contribuyente_info['NOMBRE']; ?></center></b></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 10%; text-align: left; font-size: 11px;"><b>NIT:</b></th>
                            <th style="border: 1px solid; width: 18%; font-size: 11px;"><b><center><?php echo $row_contribuyente_info['NIT_CONTRIBUYENTE']; ?></center></b></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 10%; text-align: left; font-size: 11px;"><b>DIRECCIÓN:</b></th>
                            <th style="border: 1px solid; width: 18%; font-size: 11px;"><b><center><?php echo $row_contribuyente_info['DIRECCION_CONTRIBUYENTE']; ?></center></b></th>
                        </tr>
                    </table>
                    <br />
                    <table style="border-collapse: collapse; border: 1px solid; font-size: 13px; text-align: center;" width="100%">
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 10%; text-align: left; font-weight: normal; font-size: 11px;">PERIODO CORRIENTE:</th>
                            <?php
                                $ano = substr($row_info_fact_esp['PERIODO_FACTURA'], 0, 4);
                                $mes = substr($row_info_fact_esp['PERIODO_FACTURA'], 4, 2);
                                $query_select_periodo_fact = mysqli_query($connection, "SELECT PERIODO "
                                                                                     . "  FROM periodos_facturacion_especiales_2 "
                                                                                     . " WHERE ANO_FACTURA = '" . $ano . "' "
                                                                                     . "   AND MES_FACTURA = '" . $mes . "'");
                                $row_periodo_fact = mysqli_fetch_array($query_select_periodo_fact);
                            ?>
                            <th style="border: 1px solid; width: 18%; font-weight: normal; font-size: 11px;"><center><?php echo $row_periodo_fact['PERIODO'] . " de " . $ano; ?></center></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 10%; text-align: left; font-weight: normal; font-size: 11px;">NO. LIQUIDACIONES VENCIDAS:</th>
                            <th style="border: 1px solid; width: 18%; font-weight: normal; font-size: 11px;"><center><?php echo $row_info_fact_esp['NO_LIQ_VENCIDAS']; ?></center></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 10%; text-align: left; font-weight: normal; font-size: 11px;">VALOR TOTAL LIQUIDACIONES VENCIDAS:</th>
                            <th style="border: 1px solid; width: 18%; font-weight: normal; font-size: 11px;"><center>$ <?php echo number_format($row_info_fact_esp['VALOR_LIQ_VENCIDAS'], 0, ',', '.'); ?></center></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 10%; text-align: left; font-weight: normal; font-size: 11px;">SECTOR:</th>
                            <th style="border: 1px solid; width: 18%; font-weight: normal; font-size: 11px;"><center><?php echo $row_contribuyente_info['SECTOR_CONTRIBUYENTE']; ?></center></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 10%; text-align: left; font-weight: normal; font-size: 11px;">TARIFA:</th>
                            <?php
                                switch ($row_info_fact_esp['ID_TIPO_FACTURACION']) {
                                    case '1': ?>
                                        <th style="border: 1px solid; width: 18%; font-weight: normal; font-size: 11px;"><b><center><?php echo $row_info_fact_esp['TARIFA']; ?>% DEL CONSUMO DE ENERGÍA</center></b></th>
                                        <?php
                                        break;
                                    case '2': ?>
                                        <th style="border: 1px solid; width: 18%; font-weight: normal; font-size: 11px;"><b><center><?php echo $row_info_fact_esp['TARIFA']; ?> SMMLV</center></b></th>
                                        <?php
                                        break;
                                    case '3': ?>
                                        <th style="border: 1px solid; width: 18%; font-weight: normal; font-size: 11px;"><b><center><?php echo $row_info_fact_esp['TARIFA']; ?> UVT</center></b></th>
                                        <?php
                                        break;
                                }
                            ?>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 10%; text-align: left; font-weight: normal; font-size: 11px;">VALOR IMPUESTO:</th>
                            <th style="border: 1px solid; width: 18%; font-weight: normal; font-size: 11px;"><center>$ <?php echo number_format($row_info_fact_esp['VALOR_FACTURA'], 0, ',', '.'); ?></center></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; background-color: #003153; width: 10%; text-align: left; font-size: 11px;"><b style="color: #FFFFFF;">VALOR TOTAL PERIODO:</b></th>
                            <th style="border: 1px solid; background-color: #003153; width: 18%; font-size: 11px;"><center><b style="color: #FFFFFF;">$ <?php echo number_format($row_info_fact_esp['VALOR_FACTURA'] + $row_info_fact_esp['VALOR_LIQ_VENCIDAS'], 0, ',', '.'); ?></b></center></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; background-color: #003153; width: 10%; text-align: left; font-size: 11px;"><b style="color: #FFFFFF;">FECHA VENCIMIENTO:</b></th>
                            <?php
                                $fecha_vencimiento = date_create($row_info_fact_esp['FECHA_VENCIMIENTO']);
                            ?>
                            <th style="border: 1px solid; background-color: #003153; width: 18%; font-size: 11px;"><center><b style="color: #FFFFFF;"><?php echo date_format($fecha_vencimiento, 'd/m/Y'); ?></b></center></th>
                        </tr>
                    </table>
                    <p style="font-size: 12px; font-weight: bold; margin-top: 0px;">Adjunto relación de valores liquidados.</p>
                    <p style="text-align: justify; margin-top: 0px; margin-bottom: 0px; font-size: 13px;">
                        El Contribuyente es sujeto pasivo del impuesto de Alumbrado Público por cuanto: i) Se analizó y determinó que es usuario
                        potencial del servicio ii) Está clasificado de acuerdo a los principios de progresividad y equidad en materia tributaria.
                        iii) Opera o posee cualquier tipo de infraestructura en el Municipio y/o tiene establecimiento físico en la jurisdicción
                        del Municipio y iv) en virtud de lo anterior, cumple el hecho generador del impuesto de alumbrado público que es el beneficio
                        por la prestación del mismo.
                    </p>
                    <p style="text-align: justify; margin-top: 0px; margin-bottom: 0px; font-size: 13px;">
                        Los contribuyentes del Impuesto de Alumbrado Público están en la obligación de aplicar las tarifas correspondientes a cada
                        sector y de realizar el pago mensual, según lo establecido en el <i><?php echo $row_info_fact_esp['ACUERDO_MCPAL']; ?></i>.
                    </p>
                    <p style="font-size: 13px; margin-top: 0px; margin-bottom: 0px;">Las facturas vencidas generan intereses moratorios por cada día de retraso.</p>
                    <table style="border-collapse: collapse; border: 1px solid; font-size: 13px; text-align: center;" width="100%">
                        <tr style="border: 1px solid;">
                            <th colspan="2" style="border: 1px solid; width: 10%; text-align: center; font-size: 11px; background-color: #003153; color: #FFFFFF;"><b>CONSIGNAR EN LA SIGUIENTE CUENTA</b></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 15%; text-align: left; font-size: 11px;"><b>CUENTA DE AHORROS DEL <?php echo $row_alcaldia['BANCO_FIDUCIA']; ?>:</b></th>
                            <th style="border: 1px solid; width: 18%; font-size: 11px;"><b><center><?php echo $row_alcaldia['CUENTA_FIDUCIA']; ?></center></b></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 15%; text-align: left; font-size: 11px;"><b>A NOMBRE DE:</b></th>
                            <th style="border: 1px solid; width: 18%; font-size: 11px;"><b><center><?php echo $row_alcaldia['NOMBRE_FIDUCIA']; ?></center></b></th>
                        </tr>
                        <tr style="border: 1px solid;">
                            <th style="border: 1px solid; width: 15%; text-align: left; font-size: 11px;"><b>NIT:</b></th>
                            <th style="border: 1px solid; width: 18%; font-size: 11px;"><b><center><?php echo $row_alcaldia['NIT_FIDUCIA']; ?></center></b></th>
                        </tr>
                    </table>
                    <p style="font-size: 13px; margin-top: 0px; margin-bottom: 0px;">Enviar soporte de pago al correo electrónico: <u><?php echo $row_alcaldia['CORREO_ELECTRONICO_ALCALDIA']; ?></u></p>
                    <p style="font-size: 12px;">
                        Contra la presente Liquidación Oficial del Impuesto de Alumbrado Público, procede el recurso de reconsideración de que trata
                        el artículo 720 del Estatuto Tributario Nacional, el cual deberá interponerse dentro de los dos (2) meses siguientes a su
                        notificación, cumpliendo los requisitos señalados en el artículo 722 del mismo ordenamiento jurídico, y presentarla en la
                        oficina de la Secretaria de Hacienda del Municipio.
                    </p>
                    <?php
                    break;
            }
        ?>
        <?php
            switch ($row_alcaldia['NOMBRE']) {
                case 'ALCALDIA MUNICIPAL DE FACATATIVA': ?>
                    <table style="border-collapse: collapse; border: 1px solid; font-size: 13px; text-align: center;" width="100%">
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;">&nbsp;</td>
                        </tr>
                    </table>
                    <table style="border-collapse: collapse; border: 1px solid; font-size: 13px; text-align: center;" width="100%">
                        <tr>
                            <td colspan="5" style="border: 0px;"><b>SECRETARIO DE HACIENDA</b></td>
                        </tr>
                        <tr>
                            <td colspan="5" style="border: 0px;"><b>MUNICIPIO DE FACATATIVA</b></td>
                        </tr>
                    </table>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE MAGANGUE': ?>
                    <br />
                    <br />
                    <p style="text-align: center; margin: 0px; font-size: 13px; font-weight: bold;">DIRECTOR FINANCIERO</p>
                    <p style="text-align: center; margin: 0px; font-size: 13px; font-weight: bold;">Magangué (Bolivar)</p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE RIOHACHA': ?>
                    <br />
                    <br />
                    <p style="text-align: center; margin: 0px; font-size: 13px;"><?php echo $row_alcaldia['NOMBRE_SEC_HACIENDA'] ?></p>
                    <p style="text-align: center; margin: 0px; font-size: 13px; font-weight: bold;">DIRECTORA DE RENTAS</p>
                    <!--<p style="text-align: center; margin: 0px; font-size: 13px; font-weight: bold;">SECRETARIO DE HACIENDA</p>-->
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE TURBACO': ?>
                    <br />
                    <br />
                    <p style="text-align: center; margin: 0px; font-size: 13px;"><?php echo $row_alcaldia['NOMBRE_SEC_HACIENDA'] ?></p>
                    <!--<p style="text-align: center; margin: 0px; font-size: 13px; font-weight: bold;">SECRETARIO DE HACIENDA (E)</p>-->
                    <p style="text-align: center; margin: 0px; font-size: 13px; font-weight: bold;">SECRETARIO DE HACIENDA</p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE AGUSTIN CODAZZI': ?>
                        <br />
                        <br />
                        <p style="text-align: center; margin: 0px; font-size: 13px;"><?php echo $row_alcaldia['NOMBRE_SEC_HACIENDA'] ?></p>
                        <!--<p style="text-align: center; margin: 0px; font-size: 13px; font-weight: bold;">DIRECTORA DE RENTAS</p>-->
                        <p style="text-align: center; margin: 0px; font-size: 13px; font-weight: bold;">SECRETARIA DE HACIENDA</p>
                        <?php
                        break;
                default: ?>
                    <br />
                    <br />
                    <p style="text-align: center; margin: 0px; font-size: 13px;"><?php echo $row_alcaldia['NOMBRE_SEC_HACIENDA'] ?></p>
                    <p style="text-align: center; margin: 0px; font-size: 13px;">SECRETARIO DE HACIENDA</p>
                    <?php
                    break;
            }
        ?>
        <?php
            switch ($row_alcaldia['NOMBRE']) {
                case 'ALCALDIA MUNICIPAL DE MAGANGUE': ?>
                    <br />
                    <br />
                    <p style="text-align: center; margin: 0px; font-weight: bold; font-style: italic; font-size: 14px;">"Magangué educada, comunal e incluyente"</p>
                    <hr style="width: 70%; margin-top: 0; margin-bottom: 0;" />
                    <p style="text-align: center; margin: 0px; font-weight: bold; font-size: 14px;">Alcadía Municipal de Magangué - Calle 16B No. 16a - 208 Barrio San Martín</p>
                    <p style="text-align: center; margin: 0px; font-weight: bold; font-size: 14px;">Teléfono: 6877720 - 6876020</p>
                    <hr />
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE SAN JUAN DEL CESAR': ?>
                    <br />
                    <br />
                    <p style="text-align: center; margin: 0px; font-weight: bold; font-size: 14px;">Dirección: Calle 7 # 9ª-36 Avenida Manuel Antonio Dávila – Teléfono: (095) 7740090</p>
                    <p style="text-align: center; margin: 0px; font-weight: bold; font-size: 14px;">– (095) 7740000 www.sanjuandelcesar-laguajira.gov.co - Email: </p>
                    <p style="text-align: center; margin: 0px; font-weight: bold; font-size: 14px;">alcaldia@sanjuandelcesar-laguajira.gov.co Código Postal: 444030</p>
                    <hr />
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE ARJONA': ?>
                    <br />
                    <p style="text-align: center;"><img style="max-width: 60%;" src="../Images/Arjona/Escudo - Membrete Arjona.png" /></p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE EL PASO': ?>
                    <br />
                    <p style="text-align: center;"><img style="max-width: 60%;" src="../Images/El Paso/Membrete El Paso 2.png" /></p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE SAN JACINTO': ?>
                    <br />
                    <p style="text-align: center;"><img style="max-width: 60%;" src="../Images/San Jacinto/Membrete San Jacinto 2.png" /></p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE SANTA ROSA': ?>
                    <br />
                    <hr style="border-top: 3px solid #00B050; margin: 0px;" />
                    <hr style="border-top: 6px solid #EA0000; margin: 0px;" />
                    <p style="text-align: center; margin: 0px; font-weight: bold; font-style: italic;">“Unidos Hacemos Más por Santa Rosa”</p>
                    <p style="text-align: center; margin: 0px; font-weight: normal;">CALLE 16 Nº 27-71.     TEL 6297108.     Página web: <u>www.santarosadelnorte-bolivar.gov.co</u></p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE VILLANUEVA-BOLIVAR': ?>
                    <br />
                    <p style="text-align: center;"><img style="max-width: 60%;" src="../Images/Villanueva-Bolivar/Escudo Villanueva-Bolivar.png" /></p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE AGUSTIN CODAZZI': ?>
                    <br />
                    <!--<table style="width: 100%; text-align: center;">
                        <tr>
                            <td style="font-size: 8px;"><center>ELABORÓ:</center></td>
                            <td style="font-size: 8px;"><center>REVISÓ: BREILIS K VELASQUEZ CORZO</center></td>
                            <td style="font-size: 8px;"><center>APROBÓ: OMAR ENRIQUE BENJUMEA OSPINA</center></td>
                        </tr>
                        <tr>
                            <td style="font-size: 8px;"><center>CARGO: SECRETARIO DE</center></td>
                            <td style="font-size: 8px;"><center>CARGO: SECRETARIO DE JURÍDICA</center></td>
                            <td style="font-size: 8px;"><center>CARGO: ALCADE MUNICIPAL</center></td>
                        </tr>
                    </table>-->
                    <p style="text-align: center; margin: 0px;">Carrera 16 No. 17 - 02. Agustín Codazzi, Cesar</p>
                    <p style="text-align: center; margin: 0px;"><b>Telefono:</b> (57) 5 5765733 - <b>Fax:</b> (57)5 5765733</p>
                    <p style="text-align: center; margin: 0px;"><u>alcaldia@agustincodazzi-cesar.gov.co</u></p>
                    <p style="text-align: center; margin: 0px;"><b>Bienestar para Todos</b></p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE EL COPEY': ?>
                    <br />
                    <p style="text-align: center; margin: 0px; color: #339966;">¡Una Oportunidad para el Desarrollo¡</p>
                    <p style="text-align: center; margin: 0px;">El Copey -  Cesar,  Carrera 16 No. 9 – 10,    Teléfonos  5255629 - 3216990632</p>
                    <p style="text-align: center; margin: 0px;">email: <u>contactenos@elcopey-cesar.gov.co</u></p>
                    <p style="text-align: center; margin: 0px;"><u>www.elcopeycesar.migobiernodigital.gov.co</u></p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE LA PAZ': ?>
                    <br />
                    <p style="text-align: center; margin: 0px; font-weight: bold; font-style: italic;">LA PAZ SOMOS TODOS</p>
                    <p style="text-align: center; margin: 0px;">Palacio Municipal: Cra. 7 No. 8A -09 La Paz Cesar Colombia  / Telefax: (095) 5771240</p>
                    <p style="text-align: center; margin: 0px;"><u>www.lapazrobles-cesar.gov.co</u>  /  E:mail: alcaldia@lapazrobles-cesar.gov.co</p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE RIOHACHA': ?>
                    <br />
                    <table style="width: 100%; text-align: center;">
                        <tr>
                            <td style="width: 30%;"><img style="max-width: 60%;" src="../Images/Riohacha/Membrete 1 Riohacha.png" /></td>                            
                            <td style="width: 70%;"><img style="max-width: 60%;" src="../Images/Riohacha/Membrete 2 Riohacha.png" /></td>
                        </tr>
                    </table>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE TURBACO': ?>
                    <br />
                    <p style="text-align: center;"><img style="max-width: 70%;" src="../Images/Turbaco/Membrete 2 Turbaco.png" /></p>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE FACATATIVA': ?>
                    <table style="width: 100%; border: 1px solid;">
                        <tr>
                            <td style="width: 70%; border: 0px;"><img style="max-width: 60%;" src="../Images/Facatativa/Membrete Facatativa.png" /></td>                            
                            <td style="width: 30%; border: 0px;">
                                <table style="border-collapse: collapse; width: 100%; text-align: center;">
                                    <tr>
                                        <td>CÓDIGO: GF-FR-152</td>
                                    </tr>
                                    <tr>
                                        <td>VERSIÓN: 01</td>
                                    </tr>
                                    <tr>
                                        <td>FECHA: 21 AGO 2020</td>
                                    </tr>
                                    <tr>
                                        <td>DOCUMENTO CONTROLADO</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <?php
                    break;
                case 'ALCALDIA MUNICIPAL DE AGUACHICA': ?>
                    <br />
                    <p style="text-align: center;"><img style="max-width: 100%;" src="../Images/Aguachica/Membrete 2 - Aguachica.png" /></p>
                    <?php
                    break;
            }
        ?>
    </body>
</html>
<?php