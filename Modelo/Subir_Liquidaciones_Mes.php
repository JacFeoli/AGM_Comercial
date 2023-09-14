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
    $total = count($_FILES['files']['name']);
    for ($k = 0; $k < $total; $k++) {
        switch (substr($_FILES['files']['name'][$k], 0, 4)) {
            case 'CATA':
                $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                if (mysqli_num_rows($query_select_ruta) == 0) {
                    $total_deuda_corriente = 0;
                    $total_deuda_cuota = 0;
                    $ano_factura = $_POST['ano_factura'];
                    $mes_consolidado = $_POST['mes_consolidado'];
                    $departamento = $_POST['departamento'];
                    $municipio = $_POST['municipio'];
                    $operador_red = $_POST['operador_red'];
                    switch ($mes_consolidado) {
                        case "Enero":
                            $id_mes = 1;
                            break;
                        case "Febrero":
                            $id_mes = 2;
                            break;
                        case "Marzo":
                            $id_mes = 3;
                            break;
                        case "Abril":
                            $id_mes = 4;
                            break;
                        case "Mayo":
                            $id_mes = 5;
                            break;
                        case "Junio":
                            $id_mes = 6;
                            break;
                        case "Julio":
                            $id_mes = 7;
                            break;
                        case "Agosto":
                            $id_mes = 8;
                            break;
                        case "Septiembre":
                            $id_mes = 9;
                            break;
                        case "Octubre":
                            $id_mes = 10;
                            break;
                        case "Noviembre":
                            $id_mes = 11;
                            break;
                        case "Diciembre":
                            $id_mes = 12;
                            break;
                    }
                    switch ($operador_red) {
                        case "6":
                            $nombre_operador_red = "AFINIA";
                            break;
                        case "7":
                            $nombre_operador_red = "AIR-E";
                            break;
                    }
                    $i = 0;
                    $registros = array();
                    $fecha_creacion = date('Y-m-d');
                    $id_usuario = $_SESSION['id_user'];
                    echo $fullpath = "C:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Liquidaciones/" . $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                    //echo $fullpath = "C:/Users/ASUS/Documents/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Liquidaciones/" . $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                    echo "<br />";
                    $data = file($fullpath);
                    mysqli_query($connection, "INSERT INTO archivos_cargados_catastro_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, OPERADOR_RED, RUTA, FECHA_CREACION, ID_USUARIO) "
                        . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                        . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', '" . $nombre_operador_red . "', "
                        . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                    $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    $row_filename = mysqli_fetch_array($query_select_filename);
                    $id_tabla_ruta = $row_filename['ID_TABLA'];
                    foreach ($data as $lines) {
                        $registros[] = explode("|", $lines);
                        $query_select_tipo_servicio = mysqli_query($connection, "SELECT * FROM tipo_servicios_2 WHERE COD_TIPO_SERVICIO = '" . strtoupper(trim($registros[$i][0])) . "'");
                        $row_tipo_servicio = mysqli_fetch_array($query_select_tipo_servicio);
                        $id_tipo_servicio = trim($row_tipo_servicio['ID_TIPO_SERVICIO']);
                        $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(str_replace(" ", "_", trim($registros[$i][3]))) . "'");
                        $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                        $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                        $nic = trim($registros[$i][4]);
                        $nis = trim($registros[$i][5]);
                        $nombre_propietario = strtoupper(trim(str_replace("'", "", $registros[$i][6])));
                        $direccion_vivienda = strtoupper(trim(str_replace("'", "", $registros[$i][7])));
                        $consumo_facturado = trim(str_replace(",", ".", $registros[$i][8]));
                        $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_2 WHERE NOMBRE = '" . strtoupper(str_replace("_", " ", trim($registros[$i][9]))) . "'");
                        $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                        $id_departamento = $row_id_municipio["ID_DEPARTAMENTO"];
                        $id_municipio = $row_id_municipio["ID_MUNICIPIO"];
                        $query_select_corregimiento = mysqli_query($connection, "SELECT * "
                            . "FROM corregimientos_2 "
                            . "WHERE ID_DEPARTAMENTO = " . $id_departamento . " "
                            . "AND ID_MUNICIPIO = " . $id_municipio . " "
                            //. "AND NOMBRE = '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . "'");
                            . "AND NOMBRE = '" . strtoupper(trim(utf8_encode($registros[$i][10]))) . "'");
                        $row_corregimiento = mysqli_fetch_array($query_select_corregimiento);
                        if (mysqli_num_rows($query_select_corregimiento) == 0) {
                            $query_select_max_id_corregimiento = mysqli_query($connection, "SELECT MAX(ID_CORREGIMIENTO) AS ID_CORREGIMIENTO "
                                . "FROM corregimientos_2 "
                                . "WHERE ID_DEPARTAMENTO = " . $id_departamento . " "
                                . "AND ID_MUNICIPIO = " . $id_municipio);
                            $row_max_id_corregimiento = mysqli_fetch_array($query_select_max_id_corregimiento);
                            $id_corregimiento = $row_max_id_corregimiento['ID_CORREGIMIENTO'] + 1;
                            mysqli_query($connection, "INSERT INTO corregimientos_2 (ID_DEPARTAMENTO, ID_MUNICIPIO, ID_CORREGIMIENTO, NOMBRE) "
                                . "VALUES ('$id_departamento', '$id_municipio', '$id_corregimiento', '" . strtoupper(trim($registros[$i][10])) . "')");
                            $query_select_new_corregimiento = mysqli_query($connection, "SELECT MAX(ID_TABLA) AS ID_TABLA FROM corregimientos_2");
                            $row_new_corregimiento = mysqli_fetch_array($query_select_new_corregimiento);
                            $id_corregimiento = $row_new_corregimiento['ID_TABLA'];
                            echo "Registro agregado en la tabla 'corregimientos_2': " . strtoupper(trim(utf8_encode($registros[$i][10]))) . " - Pos: " . $i;
                            echo "<br />";
                        } else {
                            $id_corregimiento = $row_corregimiento['ID_TABLA'];
                        }
                        $deuda_corriente = trim(str_replace(",", ".", $registros[$i][11]));
                        $deuda_cuota = trim(str_replace(",", ".", $registros[$i][12]));
                        $query_select_estado_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][13])) . "'");
                        $row_estado_suministro = mysqli_fetch_array($query_select_estado_suministro);
                        if (mysqli_num_rows($query_select_estado_suministro) == 0) {
                            mysqli_query($connection, "INSERT INTO estados_suministro_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][13])) . "')");
                            $query_select_new_estado_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][13])) . "'");
                            $row_new_estado_suministro = mysqli_fetch_array($query_select_new_estado_suministro);
                            echo "Registro agregado en la tabla 'estados_suministro_2': " . strtoupper(trim($registros[$i][13])) . " - Pos: " . $i;
                            echo "<br />";
                            $id_estado_suministro = trim($row_new_estado_suministro['ID_ESTADO_SUMINISTRO']);
                        } else {
                            $id_estado_suministro = trim($row_estado_suministro['ID_ESTADO_SUMINISTRO']);
                        }
                        $total_deuda_corriente = $total_deuda_corriente + $registros[$i][11];
                        $total_deuda_cuota = $total_deuda_cuota + $registros[$i][12];
                        mysqli_query($connection, "INSERT INTO catastro_" . strtolower($mes_consolidado) . $ano_factura . "_2 (ID_TIPO_SERVICIO, ID_TARIFA, NIC, NIS, "
                            . "NOMBRE_PROPIETARIO, DIRECCION_VIVIENDA, CONSUMO_FACTURADO, ID_COD_DPTO, ID_COD_MPIO, "
                            . "ID_COD_CORREG, DEUDA_CORRIENTE, DEUDA_CUOTA, ID_ESTADO_SUMINISTRO, ANO_CATASTRO, MES_CATASTRO, "
                            . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                            . "VALUES ('$id_tipo_servicio', '$id_tarifa', '$nic', '$nis', '$nombre_propietario', '$direccion_vivienda', "
                            . "'$consumo_facturado', '$id_departamento', '$id_municipio', '$id_corregimiento', '$deuda_corriente', "
                            . "'$deuda_cuota', '$id_estado_suministro', '$ano_factura', '$id_mes', '$id_tabla_ruta', "
                            . "'$fecha_creacion', '$id_usuario')");
                        $i++;
                    }
                    $query_select_totales = mysqli_query($connection, "SELECT COUNT(*), SUM(DEUDA_CORRIENTE), SUM(DEUDA_CUOTA) "
                        . "  FROM catastro_" . strtolower($mes_consolidado) . $ano_factura . "_2 "
                        . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                    $row_totales = mysqli_fetch_array($query_select_totales);
                    echo "<br />";
                    echo "<p class='message'>Registros cargados: " . $i . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                    echo "<p class='message'>Valor Deuda Corriente cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_deuda_corriente, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(DEUDA_CORRIENTE)'], 0, ',', '.') . ".</p>";
                    echo "<p class='message'>Valor Deuda Cuota cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_deuda_cuota, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(DEUDA_CUOTA)'], 0, ',', '.') . ".</p>";
                    echo "<p class='message'>Archivo cargado con Exito.</p>";
                    echo "<br />";
                } else {
                    echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                }
                break;
            case 'FACT':
                $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_facturacion_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                if (mysqli_num_rows($query_select_ruta) == 0) {
                    $total_importe_trans = 0;
                    $total_valor_recibo = 0;
                    $ano_factura = $_POST['ano_factura'];
                    $mes_consolidado = $_POST['mes_consolidado'];
                    $departamento = $_POST['departamento'];
                    $municipio = $_POST['municipio'];
                    $tipo_poblacion = $_POST['tipo_poblacion'];
                    $operador_red = $_POST['operador_red'];
                    switch ($mes_consolidado) {
                        case "Enero":
                            $id_mes = 1;
                            break;
                        case "Febrero":
                            $id_mes = 2;
                            break;
                        case "Marzo":
                            $id_mes = 3;
                            break;
                        case "Abril":
                            $id_mes = 4;
                            break;
                        case "Mayo":
                            $id_mes = 5;
                            break;
                        case "Junio":
                            $id_mes = 6;
                            break;
                        case "Julio":
                            $id_mes = 7;
                            break;
                        case "Agosto":
                            $id_mes = 8;
                            break;
                        case "Septiembre":
                            $id_mes = 9;
                            break;
                        case "Octubre":
                            $id_mes = 10;
                            break;
                        case "Noviembre":
                            $id_mes = 11;
                            break;
                        case "Diciembre":
                            $id_mes = 12;
                            break;
                    }
                    switch ($operador_red) {
                        case "6":
                            $nombre_operador_red = "AFINIA";
                            break;
                        case "7":
                            $nombre_operador_red = "AIR-E";
                            break;
                    }
                    $i = 0;
                    $registros = array();
                    $fecha_creacion = date('Y-m-d');
                    $id_usuario = $_SESSION['id_user'];
                    echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Liquidaciones/" . $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                    echo "<br />";
                    $data = file($fullpath);
                    mysqli_query($connection, "INSERT INTO archivos_cargados_facturacion_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, OPERADOR_RED, RUTA, FECHA_CREACION, ID_USUARIO) "
                        . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                        . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', '" . $nombre_operador_red . "', "
                        . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                    $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_facturacion_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    $row_filename = mysqli_fetch_array($query_select_filename);
                    $id_tabla_ruta = $row_filename['ID_TABLA'];
                    foreach ($data as $lines) {
                        $registros[] = explode("\t", $lines);
                        $fecha_proc_reg = trim(substr($registros[$i][0], 0, 4) . "-" . substr($registros[$i][0], 4, 2) . "-" . substr($registros[$i][0], 6, 2));
                        $cod_oper_cont = trim($registros[$i][1]);
                        $nic = trim($registros[$i][2]);
                        $nis = trim($registros[$i][3]);
                        $sec_nis = trim($registros[$i][4]);
                        $sec_rec = trim($registros[$i][5]);
                        $fecha_fact_lect = substr($registros[$i][6], 0, 4) . "-" . substr($registros[$i][6], 4, 2) . "-" . substr($registros[$i][6], 6, 2);
                        $query_select_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                        $row_tipo_cliente = mysqli_fetch_array($query_select_tipo_cliente);
                        $id_tipo_cliente = trim($row_tipo_cliente['ID_TIPO_CLIENTE']);
                        $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                        $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                        $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                        $id_estado_contrato = trim($registros[$i][9]);
                        $concepto = trim($registros[$i][10]);
                        $importe_trans = trim(str_replace(",", ".", $registros[$i][11]));
                        $total_importe_trans = $total_importe_trans + $registros[$i][11];
                        //$importe_trans = floatval(str_replace(",", ".", str_replace(".", "", $registros[$i][11])));
                        $fecha_trans = substr($registros[$i][12], 0, 4) . "-" . substr($registros[$i][12], 4, 2) . "-" . substr($registros[$i][12], 6, 2);
                        $valor_recibo = trim(str_replace(",", ".", $registros[$i][13]));
                        $total_valor_recibo = $total_valor_recibo + $registros[$i][13];
                        $id_sector_dpto = trim($registros[$i][14]);
                        //$id_cod_depto = trim($registros[$i][17]); ESTO CORREGÍ
                        switch (trim($registros[$i][15])) {
                            case '286':
                                $id_cod_mpio = 1;
                                break;
                            case '264':
                                $id_cod_mpio = 1;
                                break;
                            default:
                                $id_cod_mpio = trim($registros[$i][15]);
                                break;
                        }
                        $id_cod_correg = trim($registros[$i][16]);
                        switch (trim($registros[$i][17])) {
                            case '4':
                                $id_cod_depto = 1;
                                break;
                            case '24':
                                $id_cod_depto = 54;
                                break;
                            case '21':
                                $id_cod_depto = 41;
                                break;
                            default:
                                $id_cod_depto = trim($registros[$i][17]);
                                break;
                        }
                        switch ($id_cod_depto) {
                            case '1':
                            case '3':
                            case '6':
                            case '7':
                            case '41':
                            case '54':
                                $simbolo_variable = trim($registros[$i][18]);
                                $consumo_kwh = trim($registros[$i][19]);
                                break;
                            default:
                                $simbolo_variable = 0;
                                $consumo_kwh = 0;
                                break;
                        }
                        mysqli_query($connection, "INSERT INTO facturacion_" . strtolower($mes_consolidado) . $ano_factura . "_2 (FECHA_PROC_REG, COD_OPER_CONT, NIC, NIS, SEC_NIS, SEC_REC, FECHA_FACT_LECT, ID_TIPO_CLIENTE, "
                            . "ID_TARIFA, ID_ESTADO_CONTRATO, CONCEPTO, IMPORTE_TRANS, FECHA_TRANS, VALOR_RECIBO, "
                            . "ID_SECTOR_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_DPTO, SIMBOLO_VARIABLE, CONSUMO_KWH, "
                            . "ID_TIPO_POBLACION, ANO_FACTURA, MES_FACTURA, ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                            . "VALUES ('$fecha_proc_reg', '$cod_oper_cont', '$nic', '$nis', '$sec_nis', '$sec_rec', '$fecha_fact_lect', "
                            . "'$id_tipo_cliente', '$id_tarifa', '$id_estado_contrato', '$concepto', '$importe_trans', "
                            . "'$fecha_trans', '$valor_recibo', '$id_sector_dpto', '$id_cod_mpio', '$id_cod_correg', "
                            . "'$id_cod_depto', '$simbolo_variable', '$consumo_kwh', '$tipo_poblacion', '$ano_factura', '$id_mes', "
                            . "'$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                        $i++;
                    }
                    $query_select_totales = mysqli_query($connection, "SELECT COUNT(*), SUM(IMPORTE_TRANS), SUM(VALOR_RECIBO) "
                        . "  FROM facturacion_" . strtolower($mes_consolidado) . $ano_factura . "_2 "
                        . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                    $row_totales = mysqli_fetch_array($query_select_totales);
                    echo "<br />";
                    echo "<p class='message'>Registros cargados: " . $i . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                    echo "<p class='message'>Valor Importe cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_importe_trans, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(IMPORTE_TRANS)'], 0, ',', '.') . ".</p>";
                    echo "<p class='message'>Valor Recibo cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_valor_recibo, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(VALOR_RECIBO)'], 0, ',', '.') . ".</p>";
                    echo "<p class='message'>Archivo cargado con Exito.</p>";
                    echo "<br />";
                } else {
                    echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                }
                break;
            case 'RECA':
                $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                if (mysqli_num_rows($query_select_ruta) == 0) {
                    $total_importe_trans = 0;
                    $total_valor_recibo = 0;
                    $ano_factura = $_POST['ano_factura'];
                    $mes_consolidado = $_POST['mes_consolidado'];
                    $departamento = $_POST['departamento'];
                    $municipio = $_POST['municipio'];
                    $tipo_poblacion = $_POST['tipo_poblacion'];
                    $operador_red = $_POST['operador_red'];
                    switch ($mes_consolidado) {
                        case "Enero":
                            $id_mes = 1;
                            break;
                        case "Febrero":
                            $id_mes = 2;
                            break;
                        case "Marzo":
                            $id_mes = 3;
                            break;
                        case "Abril":
                            $id_mes = 4;
                            break;
                        case "Mayo":
                            $id_mes = 5;
                            break;
                        case "Junio":
                            $id_mes = 6;
                            break;
                        case "Julio":
                            $id_mes = 7;
                            break;
                        case "Agosto":
                            $id_mes = 8;
                            break;
                        case "Septiembre":
                            $id_mes = 9;
                            break;
                        case "Octubre":
                            $id_mes = 10;
                            break;
                        case "Noviembre":
                            $id_mes = 11;
                            break;
                        case "Diciembre":
                            $id_mes = 12;
                            break;
                    }
                    switch ($operador_red) {
                        case "6":
                            $nombre_operador_red = "AFINIA";
                            break;
                        case "7":
                            $nombre_operador_red = "AIR-E";
                            break;
                    }
                    $i = 0;
                    $registros = array();
                    $fecha_creacion = date('Y-m-d');
                    $id_usuario = $_SESSION['id_user'];
                    echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Liquidaciones/" . $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                    echo "<br />";
                    $data = file($fullpath);
                    mysqli_query($connection, "INSERT INTO archivos_cargados_recaudo_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, OPERADOR_RED, RUTA, FECHA_CREACION, ID_USUARIO) "
                        . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                        . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', '" . $nombre_operador_red . "', "
                        . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                    $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    $row_filename = mysqli_fetch_array($query_select_filename);
                    $id_tabla_ruta = $row_filename['ID_TABLA'];
                    foreach ($data as $lines) {
                        $registros[] = explode("\t", $lines);
                        $fecha_proc_reg = trim(substr($registros[$i][0], 0, 4) . "-" . substr($registros[$i][0], 4, 2) . "-" . substr($registros[$i][0], 6, 2));
                        $cod_oper_cont = trim($registros[$i][1]);
                        $nic = trim($registros[$i][2]);
                        $nis = trim($registros[$i][3]);
                        $sec_nis = trim($registros[$i][4]);
                        $sec_rec = trim($registros[$i][5]);
                        $fecha_fact_lect = substr($registros[$i][6], 0, 4) . "-" . substr($registros[$i][6], 4, 2) . "-" . substr($registros[$i][6], 6, 2);
                        //CODIGO NUEVO
                        $ano_periodo_anterior = substr($registros[$i][6], 0, 4);
                        $mes_periodo_anterior = substr($registros[$i][6], 4, 2);
                        //FIN CODIGO NUEVO
                        $query_select_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                        $row_tipo_cliente = mysqli_fetch_array($query_select_tipo_cliente);
                        $id_tipo_cliente = trim($row_tipo_cliente['ID_TIPO_CLIENTE']);
                        $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                        $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                        $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                        $id_estado_contrato = trim($registros[$i][9]);
                        $concepto = trim($registros[$i][10]);
                        $importe_trans = trim(str_replace(",", ".", $registros[$i][11]));
                        $total_importe_trans = $total_importe_trans + $registros[$i][11];
                        //$importe_trans = floatval(str_replace(",", ".", str_replace(".", "", $registros[$i][11])));
                        $fecha_trans = substr($registros[$i][12], 0, 4) . "-" . substr($registros[$i][12], 4, 2) . "-" . substr($registros[$i][12], 6, 2);
                        $valor_recibo = trim(str_replace(",", ".", $registros[$i][13]));
                        $total_valor_recibo = $total_valor_recibo + $registros[$i][13];
                        $id_sector_dpto = trim($registros[$i][14]);
                        //$id_cod_depto = trim($registros[$i][17]); ESTO CORREGÍ
                        switch (trim($registros[$i][15])) {
                            case '286':
                                $id_cod_mpio = 1;
                                break;
                            case '264':
                                $id_cod_mpio = 1;
                                break;
                            default:
                                $id_cod_mpio = trim($registros[$i][15]);
                                break;
                        }
                        $id_cod_correg = trim($registros[$i][16]);
                        switch (trim($registros[$i][17])) {
                            case '4':
                                $id_cod_depto = 1;
                                break;
                            case '24':
                                $id_cod_depto = 54;
                                break;
                            case '21':
                                $id_cod_depto = 41;
                                break;
                            default:
                                $id_cod_depto = trim($registros[$i][17]);
                                break;
                        }
                        switch ($id_cod_depto) {
                            case '1':
                            case '3':
                            case '6':
                            case '7':
                            case '41':
                            case '54':
                                $simbolo_variable = trim($registros[$i][18]);
                                break;
                            default:
                                $simbolo_variable = 0;
                                break;
                        }
                        //CODIGO NUEVO
                        switch ($ano_periodo_anterior) {
                            case '2018':
                                mysqli_query($connection, "INSERT INTO recaudo_pruebas2018_2 (FECHA_PROC_REG, COD_OPER_CONT, NIC, NIS, SEC_NIS, SEC_REC, FECHA_FACT_LECT, ID_TIPO_CLIENTE, "
                                    . "ID_TARIFA, ID_ESTADO_CONTRATO, CONCEPTO, IMPORTE_TRANS, FECHA_TRANS, VALOR_RECIBO, "
                                    . "ID_SECTOR_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_DPTO, ID_TIPO_POBLACION, ANO_FACTURA, MES_FACTURA, "
                                    . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                    . "VALUES ('$fecha_proc_reg', '$cod_oper_cont', '$nic', '$nis', '$sec_nis', '$sec_rec', '$fecha_fact_lect', "
                                    . "'$id_tipo_cliente', '$id_tarifa', '$id_estado_contrato', '$concepto', '$importe_trans', "
                                    . "'$fecha_trans', '$valor_recibo', '$id_sector_dpto', '$id_cod_mpio', '$id_cod_correg', "
                                    . "'$id_cod_depto', '$tipo_poblacion', '$ano_periodo_anterior', '$mes_periodo_anterior', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                                break;
                            case '2019':
                                mysqli_query($connection, "INSERT INTO recaudo_pruebas2019_2 (FECHA_PROC_REG, COD_OPER_CONT, NIC, NIS, SEC_NIS, SEC_REC, FECHA_FACT_LECT, ID_TIPO_CLIENTE, "
                                    . "ID_TARIFA, ID_ESTADO_CONTRATO, CONCEPTO, IMPORTE_TRANS, FECHA_TRANS, VALOR_RECIBO, "
                                    . "ID_SECTOR_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_DPTO, ID_TIPO_POBLACION, ANO_FACTURA, MES_FACTURA, "
                                    . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                    . "VALUES ('$fecha_proc_reg', '$cod_oper_cont', '$nic', '$nis', '$sec_nis', '$sec_rec', '$fecha_fact_lect', "
                                    . "'$id_tipo_cliente', '$id_tarifa', '$id_estado_contrato', '$concepto', '$importe_trans', "
                                    . "'$fecha_trans', '$valor_recibo', '$id_sector_dpto', '$id_cod_mpio', '$id_cod_correg', "
                                    . "'$id_cod_depto', '$tipo_poblacion', '$ano_periodo_anterior', '$mes_periodo_anterior', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                                break;
                        }
                        //FIN CODIGO NUEVO
                        mysqli_query($connection, "INSERT INTO recaudo_" . strtolower($mes_consolidado) . $ano_factura . "_2 (FECHA_PROC_REG, COD_OPER_CONT, NIC, NIS, SEC_NIS, SEC_REC, FECHA_FACT_LECT, ID_TIPO_CLIENTE, "
                            . "ID_TARIFA, ID_ESTADO_CONTRATO, CONCEPTO, IMPORTE_TRANS, FECHA_TRANS, VALOR_RECIBO, "
                            . "ID_SECTOR_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_DPTO, SIMBOLO_VARIABLE, ID_TIPO_POBLACION, ANO_FACTURA, MES_FACTURA, "
                            . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                            . "VALUES ('$fecha_proc_reg', '$cod_oper_cont', '$nic', '$nis', '$sec_nis', '$sec_rec', '$fecha_fact_lect', "
                            . "'$id_tipo_cliente', '$id_tarifa', '$id_estado_contrato', '$concepto', '$importe_trans', "
                            . "'$fecha_trans', '$valor_recibo', '$id_sector_dpto', '$id_cod_mpio', '$id_cod_correg', "
                            . "'$id_cod_depto', '$simbolo_variable', '$tipo_poblacion', '$ano_factura', '$id_mes', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                        $i++;
                    }
                    $query_select_totales = mysqli_query($connection, "SELECT COUNT(*), SUM(IMPORTE_TRANS), SUM(VALOR_RECIBO) "
                        . "  FROM recaudo_" . strtolower($mes_consolidado) . $ano_factura . "_2 "
                        . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                    $row_totales = mysqli_fetch_array($query_select_totales);
                    echo "<br />";
                    echo "<p class='message'>Registros cargados: " . $i . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                    echo "<p class='message'>Valor Importe cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_importe_trans, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(IMPORTE_TRANS)'], 0, ',', '.') . ".</p>";
                    echo "<p class='message'>Valor Recibo cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_valor_recibo, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(VALOR_RECIBO)'], 0, ',', '.') . ".</p>";
                    echo "<p class='message'>Archivo cargado con Exito.</p>";
                    echo "<br />";
                } else {
                    echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                }
                break;
            case 'REFA':
                $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_refacturacion_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                if (mysqli_num_rows($query_select_ruta) == 0) {
                    $total_importe_trans = 0;
                    $total_valor_recibo = 0;
                    $ano_factura = $_POST['ano_factura'];
                    $mes_consolidado = $_POST['mes_consolidado'];
                    $departamento = $_POST['departamento'];
                    $municipio = $_POST['municipio'];
                    $tipo_poblacion = $_POST['tipo_poblacion'];
                    $operador_red = $_POST['operador_red'];
                    switch ($mes_consolidado) {
                        case "Enero":
                            $id_mes = 1;
                            break;
                        case "Febrero":
                            $id_mes = 2;
                            break;
                        case "Marzo":
                            $id_mes = 3;
                            break;
                        case "Abril":
                            $id_mes = 4;
                            break;
                        case "Mayo":
                            $id_mes = 5;
                            break;
                        case "Junio":
                            $id_mes = 6;
                            break;
                        case "Julio":
                            $id_mes = 7;
                            break;
                        case "Agosto":
                            $id_mes = 8;
                            break;
                        case "Septiembre":
                            $id_mes = 9;
                            break;
                        case "Octubre":
                            $id_mes = 10;
                            break;
                        case "Noviembre":
                            $id_mes = 11;
                            break;
                        case "Diciembre":
                            $id_mes = 12;
                            break;
                    }
                    switch ($operador_red) {
                        case "6":
                            $nombre_operador_red = "AFINIA";
                            break;
                        case "7":
                            $nombre_operador_red = "AIR-E";
                            break;
                    }
                    $i = 0;
                    $registros = array();
                    $fecha_creacion = date('Y-m-d');
                    $id_usuario = $_SESSION['id_user'];
                    echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/" . $mes_consolidado . "/Liquidaciones/" . $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                    echo "<br />";
                    $data = file($fullpath);
                    mysqli_query($connection, "INSERT INTO archivos_cargados_refacturacion_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, OPERADOR_RED, RUTA, FECHA_CREACION, ID_USUARIO) "
                        . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                        . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', '" . $nombre_operador_red . "', "
                        . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                    $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_refacturacion_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    $row_filename = mysqli_fetch_array($query_select_filename);
                    $id_tabla_ruta = $row_filename['ID_TABLA'];
                    foreach ($data as $lines) {
                        $registros[] = explode("\t", $lines);
                        $fecha_proc_reg = trim(substr($registros[$i][0], 0, 4) . "-" . substr($registros[$i][0], 4, 2) . "-" . substr($registros[$i][0], 6, 2));
                        $cod_oper_cont = trim($registros[$i][1]);
                        $nic = trim($registros[$i][2]);
                        $nis = trim($registros[$i][3]);
                        $sec_nis = trim($registros[$i][4]);
                        $sec_rec = trim($registros[$i][5]);
                        $fecha_fact_lect = substr($registros[$i][6], 0, 4) . "-" . substr($registros[$i][6], 4, 2) . "-" . substr($registros[$i][6], 6, 2);
                        $query_select_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                        $row_tipo_cliente = mysqli_fetch_array($query_select_tipo_cliente);
                        $id_tipo_cliente = trim($row_tipo_cliente['ID_TIPO_CLIENTE']);
                        $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                        $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                        $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                        $id_estado_contrato = trim($registros[$i][9]);
                        $concepto = trim($registros[$i][10]);
                        $importe_trans = trim(str_replace(",", ".", $registros[$i][11]));
                        $total_importe_trans = $total_importe_trans + $registros[$i][11];
                        //$importe_trans = floatval(str_replace(",", ".", str_replace(".", "", $registros[$i][11])));
                        $fecha_trans = substr($registros[$i][12], 0, 4) . "-" . substr($registros[$i][12], 4, 2) . "-" . substr($registros[$i][12], 6, 2);
                        $valor_recibo = trim(str_replace(",", ".", $registros[$i][13]));
                        $total_valor_recibo = $total_valor_recibo + $registros[$i][13];
                        $id_sector_dpto = trim($registros[$i][14]);
                        //$id_cod_depto = trim($registros[$i][17]); ESTO CORREGÍ
                        switch (trim($registros[$i][15])) {
                            case '286':
                                $id_cod_mpio = 1;
                                break;
                            case '264':
                                $id_cod_mpio = 1;
                                break;
                            default:
                                $id_cod_mpio = trim($registros[$i][15]);
                                break;
                        }
                        $id_cod_correg = trim($registros[$i][16]);
                        switch (trim($registros[$i][17])) {
                            case '4':
                                $id_cod_depto = 1;
                                break;
                            case '24':
                                $id_cod_depto = 54;
                                break;
                            case '21':
                                $id_cod_depto = 41;
                                break;
                            default:
                                $id_cod_depto = trim($registros[$i][17]);
                                break;
                        }
                        switch ($id_cod_depto) {
                            case '1':
                            case '3':
                            case '6':
                            case '7':
                            case '41':
                            case '54':
                                $simbolo_variable = trim($registros[$i][18]);
                                break;
                            default:
                                $simbolo_variable = 0;
                                break;
                        }
                        mysqli_query($connection, "INSERT INTO refacturacion_" . strtolower($mes_consolidado) . $ano_factura . "_2 (FECHA_PROC_REG, COD_OPER_CONT, NIC, NIS, SEC_NIS, SEC_REC, FECHA_FACT_LECT, ID_TIPO_CLIENTE, "
                            . "ID_TARIFA, ID_ESTADO_CONTRATO, CONCEPTO, IMPORTE_TRANS, FECHA_TRANS, VALOR_RECIBO, "
                            . "ID_SECTOR_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_DPTO, SIMBOLO_VARIABLE, ID_TIPO_POBLACION, ANO_FACTURA, MES_FACTURA, "
                            . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                            . "VALUES ('$fecha_proc_reg', '$cod_oper_cont', '$nic', '$nis', '$sec_nis', '$sec_rec', '$fecha_fact_lect', "
                            . "'$id_tipo_cliente', '$id_tarifa', '$id_estado_contrato', '$concepto', '$importe_trans', "
                            . "'$fecha_trans', '$valor_recibo', '$id_sector_dpto', '$id_cod_mpio', '$id_cod_correg', "
                            . "'$id_cod_depto', '$simbolo_variable', '$tipo_poblacion', '$ano_factura', '$id_mes', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                        $i++;
                    }
                    $query_select_totales = mysqli_query($connection, "SELECT COUNT(*), SUM(IMPORTE_TRANS), SUM(VALOR_RECIBO) "
                        . "  FROM refacturacion_" . strtolower($mes_consolidado) . $ano_factura . "_2 "
                        . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                    $row_totales = mysqli_fetch_array($query_select_totales);
                    echo "<br />";
                    echo "<p class='message'>Registros cargados: " . $i . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                    echo "<p class='message'>Valor Importe cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_importe_trans, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(IMPORTE_TRANS)'], 0, ',', '.') . ".</p>";
                    echo "<p class='message'>Valor Recibo cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_valor_recibo, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(VALOR_RECIBO)'], 0, ',', '.') . ".</p>";
                    echo "<p class='message'>Archivo cargado con Exito.</p>";
                    echo "<br />";
                } else {
                    echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                }
                break;
        }
    }
}