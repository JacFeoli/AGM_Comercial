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
        setlocale(LC_ALL, "es_CO");
        date_default_timezone_set("America/Bogota");
        $table = "";
        $nics = $_GET['nics'];
        $departamento = $_GET['departamento'];
        $municipio = $_GET['municipio'];
        $fecha_estado_cuenta = $_GET['fecha_estado_cuenta'];
        $query_select_info_departamento = mysqli_query($connection, "SELECT * FROM departamentos_2 WHERE ID_DEPARTAMENTO = " . $departamento);
        $row_info_departamento = mysqli_fetch_array($query_select_info_departamento);
        $query_select_info_municipio = mysqli_query($connection, "SELECT * "
                                                               . "  FROM municipios_2 "
                                                               . " WHERE ID_DEPARTAMENTO = " . $departamento . " "
                                                               . "   AND ID_MUNICIPIO = " . $municipio);
        $row_info_municipio = mysqli_fetch_array($query_select_info_municipio);
        $filename = "Cobro Persuasivo " . $row_info_departamento['NOMBRE'] . " - " . $row_info_municipio['NOMBRE'] . ".doc";
        $myNics = explode(',', $nics);
        foreach ($myNics as $item) {
            $sw = 0;
            $count = 0;
            $nic = substr($item, 0, 7);
            $ano = substr($item, 7, 4);
            $table = $table . "<p style='text-transform: capitalize;'>" . $row_info_municipio['NOMBRE'] . ", " . strtolower(strftime("%d de %B de %Y", strtotime($fecha_estado_cuenta))) . "</p>";
            $query_select_max_ano = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA), ID_MES_FACTURA, MES_FACTURA "
                                                            . "  FROM archivos_cargados_facturacion_2 "
                                                            . " WHERE ANO_FACTURA IN (" . $ano . ") "
                                                            . "   AND ID_MES_FACTURA = 12 "
                                                            . " ORDER BY ANO_FACTURA ASC");
            $row_max_ano = mysqli_fetch_array($query_select_max_ano);
            $bd_tabla_catastro = "catastro_" . strtolower($row_max_ano['MES_FACTURA']) . $row_max_ano['ANO_FACTURA'] . "_2";
            $bd_tabla_cartera = "cartera_fallida_" . $row_max_ano['ANO_FACTURA'] . "_2";
            mysqli_query($connection, "SET NAMES 'utf8'");
            $query_select_nics_catastro = mysqli_query($connection, "SELECT * "
                                                                  . "  FROM $bd_tabla_catastro "
                                                                  . " WHERE ID_COD_DPTO = '$departamento' "
                                                                  . "   AND ID_COD_MPIO = '$municipio' "
                                                                  . "   AND NIC = " . $nic);
            $row_nic_catastro = mysqli_fetch_array($query_select_nics_catastro);
            $table = $table . "<br />";
            $table = $table . "<p style='margin: 0; font-weight: bold;'>Se&ntilde;ores:</p>";
            $table = $table . "<p style='text-transform: capitalize; font-weight: bold; margin: 0;'>" . $row_nic_catastro['NOMBRE_PROPIETARIO'] . "</p>";
            $table = $table . "<p style='margin: 0;'><b>NIC: </b>" . $row_nic_catastro['NIC'] . "</p>";
            $table = $table . "<p style='margin: 0;'><b>Direcci&oacute;n: </b>" . $row_nic_catastro['DIRECCION_VIVIENDA'] . "</p>";
            $table = $table . "<p style='margin: 0; color: #FF0000; font-size: 26px; text-align: right;'><b>Aviso &uacute;nico de Cobro Persuasivo</b></p>";
            $table = $table . "<p>Cordial saludo,</p>";
            $table = $table . "<p style='text-align: justify;'>Apreciado contribuyente, con el cumplimiento del pago del IMPUESTO DE ALUMBRADO PUBLICO, es posible generar inversiones en infraestructura y operaci&oacute;n del servicio de alumbrado p&uacute;blico, le invitamos a hacer parte del <b>PLAN DE NORMALIZACI&Oacute;N DE CARTERA.</b></p>";
            $table = $table . "<p style='text-align: justify;'>Es nuestro deber informarle que, ante el incumplimiento de las obligaciones tributarias sustanciales, el Municipio se encuentra obligado a adelantar el <b style='color: #FF0000;'>PROCEDIMIENTO ADMINISTRATIVO DE COBRO COACTIVO</b> para la recuperaci&oacute;n de la cartera vencida del Impuesto de Alumbrado P&uacute;blico.</p>";
            $table = $table . "<p style='text-align: justify;'>En virtud de lo anterior, el municipio se encuentra facultado para la aplicaci&oacute;n de medidas cautelares tales como: <b style='color: #FF0000;'>EMBARGO DE DINERO, SECUESTRO DE BIENES Y POSTERIOR REMATE DE TODO TIPO</b>, muebles e inmuebles, as&iacute; como disponer de los dep&oacute;sitos de las cuentas bancarias, t&iacute;tulos valores, acciones en sociedades, cuotas sociales, etc. Adicionalmente el Municipio se ver&aacute; obligado a reportarlo al <b>bolet&iacute;n de deudores morosos de la Contadur&iacute;a General de la Naci&oacute;n</b>, reporte que lo inhabilitar&iacute;a en Entidades Financieras, contrataci&oacute;n o vinculaci&oacute;n con el Estado Colombiano.</p>";
            $table = $table . "<p style='text-align: justify;'>Una vez verificado en nuestras bases de datos, se detect&oacute; que, a la fecha, presenta mora en el pago de su(s) obligaci&oacute;n(es), correspondientes al Impuesto de Alumbrado P&uacute;blico e intereses moratorios, sobre los per&iacute;odos que a continuaci&oacute;n se relacionan:</p>";
            $table = $table . "<table style='border: 1px solid; text-align: center; border-collapse: collapse;' width='100%'>";
                $table = $table . "<tr style='border: 1px solid;'>";
                    $table = $table . "<th style='border: 1px solid; background-color: #003153; width: 10%; text-align: left; font-size: 11px;'><b style='color: #FFFFFF;'>CONTRIBUYENTE:</b></th>";
                    $table = $table . "<th style='border: 1px solid; background-color: #003153; width: 18%; font-size: 11px;'><b style='color: #FFFFFF;'><center>" . $row_nic_catastro['NOMBRE_PROPIETARIO'] . "</center></b></th>";
                $table = $table . "</tr>";
                $table = $table . "<tr style='border: 1px solid;'>";
                    $table = $table . "<th style='border: 1px solid; width: 10%; text-align: left; font-size: 11px;'><b>NIC:</b></th>";
                    $table = $table . "<th style='border: 1px solid; width: 18%; font-size: 11px;'><b><center>" . $row_nic_catastro['NIC'] . "</center></b></th>";
                $table = $table . "</tr>";
                $table = $table . "<tr style='border: 1px solid;'>";
                    $table = $table . "<th style='border: 1px solid; width: 10%; text-align: left; font-size: 11px;'><b>DIRECCI&Oacute;N:</b></th>";
                    $table = $table . "<th style='border: 1px solid; width: 18%; font-size: 11px;'><b><center>" . $row_nic_catastro['DIRECCION_VIVIENDA'] . "</center></b></th>";
                $table = $table . "</tr>";
                $table = $table . "<tr style='border: 1px solid;'>";
                    $table = $table . "<th style='border: 1px solid; width: 10%; text-align: left; font-size: 11px;'><b>PERIODO:</b></th>";
                    $table = $table . "<th style='border: 1px solid; width: 18%; font-size: 11px;'><b><center>" . $ano . "</center></b></th>";
                $table = $table . "</tr>";
                $query_select_periodos = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA), ID_MES_FACTURA, MES_FACTURA "
                                                                . "  FROM archivos_cargados_facturacion_2 "
                                                                . " WHERE ANO_FACTURA = " . $ano
                                                                . " ORDER BY ANO_FACTURA DESC");
                while ($row_periodos = mysqli_fetch_assoc($query_select_periodos)) {
                    $bd_tabla_facturacion = "facturacion_" .  strtolower($row_max_ano['MES_FACTURA']) . $row_max_ano['ANO_FACTURA'] . "_2";
                    $bd_tabla_recaudo = "recaudo_" . strtolower($row_max_ano['MES_FACTURA']) . $row_max_ano['ANO_FACTURA'] . "_2";
                    //$bd_tabla_cartera = "cartera_fallida_" . $row_max_ano['ANO_FACTURA'] . "_2";
                    //$bd_tabla_catastro = "catastro_" . strtolower($row_max_ano['MES_FACTURA']) . $row_max_ano['ANO_FACTURA'] . "_2";
                    $query_select_facturacion_nic = mysqli_query($connection, "SELECT * "
                                                                            . "  FROM $bd_tabla_facturacion FACT "
                                                                            . " WHERE FACT.NIC = " . $row_nic_catastro['NIC'] . " "
                                                                            . " GROUP BY FACT.NIC");
                    while ($row_facturacion_nic = mysqli_fetch_assoc($query_select_facturacion_nic)) {
                        $count = $count + 1;
                    }
                }
                $table = $table . "<tr style='border: 1px solid;'>";
                    $table = $table . "<th style='border: 1px solid; width: 10%; text-align: left; font-size: 11px;'><b>NO. FACTURAS:</b></th>";
                    $table = $table . "<th style='border: 1px solid; width: 18%; font-size: 11px;'><b><center>" . $count . "</center></b></th>";
                $table = $table . "</tr>";
                $table = $table . "<tr style='border: 1px solid;'>";
                    $table = $table . "<th style='border: 1px solid; width: 10%; text-align: left; font-size: 11px;'><b>SECTOR:</b></th>";
                    $table = $table . "<th style='border: 1px solid; width: 18%; font-size: 11px;'><b><center></center></b></th>";
                $table = $table . "</tr>";
                $table = $table . "<tr style='border: 1px solid;'>";
                    $table = $table . "<th style='border: 1px solid; width: 10%; text-align: left; font-size: 11px;'><b>TARIFA:</b></th>";
                    $table = $table . "<th style='border: 1px solid; width: 18%; font-size: 11px;'><b><center></center></b></th>";
                $table = $table . "</tr>";
                $table = $table . "<tr style='border: 1px solid;'>";
                    $table = $table . "<th style='border: 1px solid; width: 10%; text-align: left; font-size: 11px;'><b>VALOR MENSUAL IMPUESTO " . $ano . ":</b></th>";
                    $table = $table . "<th style='border: 1px solid; width: 18%; font-size: 11px;'><b><center><b style='font-size: 12px;'>$ </b>" . number_format(0, 0, ',', '.') . "</center></b></th>";
                $table = $table . "</tr>";
            $table = $table . "</table>";
            $table = $table . "<p style='text-align: justify;'>Teniendo en cuenta lo anterior, el Municipio le est&aacute; invitando a ponerse al d&iacute;a con sus obligaciones tributarias. Para tal efecto, le solicitamos efectuar el pago de lo adeudado a la CUENTA MUNICIPIO y enviar el soporte de pago al correo electr&oacute;nico: correo gen&eacute;rico municipio.</p>";
            $table = $table . "<p style='text-align: justify;'>Lo anterior, teniendo en cuenta que el Contribuyente <b>" . $row_nic_catastro['NOMBRE_PROPIETARIO'] . "</b> identificado con NIT n&uacute;mero <b>________</b>, es sujeto pasivo por cuanto: i) Se analiz&oacute; y determin&oacute; que es beneficiario potencial del servicio ii) Tiene una clasificaci&oacute;n especial bajo los principios de progresividad y equidad en materia tributaria iii) Opera o posee cualquier tipo de infraestructura en el Municipio y/o tiene establecimiento en la jurisdicci&oacute;n del Municipio y iv) en virtud de lo anterior, cumple el hecho generador del impuesto de alumbrado p&uacute;blico que es el beneficio por la prestaci&oacute;n del mismo.</p>";
            $table = $table . "<p style='text-align: justify;'>De no cancelar sus obligaciones tributarias dentro de las <b>72 horas</b> de recibo de este comunicado o hacer un acuerdo de pago, la Administraci&oacute;n se ver&aacute; forzada a iniciar su <b style='color: #FF0000;'>COBRO POR LA V&Iacute;A COACTIVA</b> con las consecuencias arriba señaladas m&aacute;s <b>las costas del proceso</b>, etapa a la que no quisi&eacute;ramos llegar.</p>";
            $table = $table . "<p style='text-align: justify;'>Si a la fecha de recibo de la presente usted se encuentra al d&iacute;a, hacer caso omiso.</p>";
            $table = $table . "<p style='color: #FF0000; font-size: 26px; text-align: right;'><b>Evite el Cobro Coactivo</b></p>";
            $table = $table . "<p>Cordialmente,</p>";
            $table = $table . "<p style='text-align: center;'><b>XXXXXXXXX XXXXXXXXXX</b></p>";
            $table = $table . "<p style='margin-top:0; text-align: center;'>Secretar&iacute;a de Hacienda Municipal</p>";
            $table = $table . "<p style='font-size: 20px; page-break-before: always; font-weight: bold; text-align: center;'>&nbsp;</p>";
        }
        header("Content-Type: application/msword");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        echo $table;
    }
?>