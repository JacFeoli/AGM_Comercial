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
        $archivo = $_GET['archivo'];
        switch ($archivo) {
            case 'catastro':
                $total = count($_FILES['files']['name']);
                for($k=0; $k<$total; $k++) {
                    //echo $_FILES['files']['name'][$k];
                    //echo "<br />";
                    $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    if (mysqli_num_rows($query_select_ruta) == 0) {
                        if (substr($_FILES['files']['name'][$k], 0, 4) == 'CATA') {
                            $total_deuda_corriente = 0;
                            $total_deuda_cuota = 0;
                            $ano_factura = $_POST['ano_factura'];
                            $mes_consolidado = $_POST['mes_consolidado'];
                            $departamento = $_POST['departamento'];
                            $municipio = $_POST['municipio'];
                            switch ($mes_consolidado) {
                                case "Enero": $id_mes = 1; break;
                                case "Febrero": $id_mes = 2; break;
                                case "Marzo": $id_mes = 3; break;
                                case "Abril": $id_mes = 4; break;
                                case "Mayo": $id_mes = 5; break;
                                case "Junio": $id_mes = 6; break;
                                case "Julio": $id_mes = 7; break;
                                case "Agosto": $id_mes = 8; break;
                                case "Septiembre": $id_mes = 9; break;
                                case "Octubre": $id_mes = 10; break;
                                case "Noviembre": $id_mes = 11; break;
                                case "Diciembre": $id_mes = 12; break;
                            }
                            $registros = array();
                            $fecha_creacion = date('Y-m-d');
                            $id_usuario = $_SESSION['id_user'];
                            echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/". $mes_consolidado . "/Catastro/" . $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                            echo "<br />";
                            $data = file($fullpath);
                            $i = 0;
                            $registros = array();
                            mysqli_query($connection, "INSERT INTO archivos_cargados_catastro_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                             . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                                                                                     . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', "
                                                                                     . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                            $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                            $row_filename = mysqli_fetch_array($query_select_filename);
                            $id_tabla_ruta = $row_filename['ID_TABLA'];
                            foreach ($data as $lines) {
                                $registros[] = explode("|", $lines);
                                //echo implode("\t", $registros[$i]);
                                $query_select_tipo_servicio = mysqli_query($connection, "SELECT * FROM tipo_servicios_2 WHERE COD_TIPO_SERVICIO = '" . strtoupper(trim($registros[$i][0])) . "'");
                                $row_tipo_servicio = mysqli_fetch_array($query_select_tipo_servicio);
                                if (mysqli_num_rows($query_select_tipo_servicio) == 0) {
                                    mysqli_query($connection, "INSERT INTO tipo_servicios_2 (COD_TIPO_SERVICIO, NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][0])) . "', '" . strtoupper(trim($registros[$i][1])) . "')");
                                    echo "<br />";
                                    $query_select_new_tipo_servicio = mysqli_query($connection, "SELECT * FROM tipo_servicios_2 WHERE COD_TIPO_SERVICIO = '" . strtoupper(trim($registros[$i][0])) . "'");
                                    $row_new_tipo_servicio = mysqli_fetch_array($query_select_new_tipo_servicio);
                                    echo "Registro agregado en la tabla 'tipo_servicios_2': " . strtoupper(trim($registros[$i][0])) . " - " . strtoupper(trim($registros[$i][1])) . " - Pos: " . $i;
                                    echo "<br />";
                                    $id_tipo_servicio = trim($row_new_tipo_servicio['ID_TIPO_SERVICIO']);
                                } else {
                                    $id_tipo_servicio = trim($row_tipo_servicio['ID_TIPO_SERVICIO']);
                                }
                                $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(str_replace(" ", "_", trim($registros[$i][3]))) . "'");
                                $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                if (mysqli_num_rows($query_select_tarifa) == 0) {
                                    mysqli_query($connection, "INSERT INTO tarifas_2 (COD_TARIFA, NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][2])) . "', '" . strtoupper(trim(str_replace(" ", "_", $registros[$i][3]))) . "')");
                                    $query_select_new_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(str_replace(" ", "_", trim($registros[$i][3]))) . "'");
                                    $row_new_tarifa = mysqli_fetch_array($query_select_new_tarifa);
                                    echo "Registro agregado en la tabla 'tarifas_2': " . strtoupper(trim($registros[$i][2])) . " - " . strtoupper(str_replace(" ", "_", trim($registros[$i][3]))) . " - Pos: " . $i;
                                    echo "<br />";
                                    $id_tarifa = trim($row_new_tarifa['ID_TARIFA']);
                                } else {
                                    if ($row_tarifa['COD_TARIFA'] == "") {
                                        mysqli_query($connection, "UPDATE tarifas_2 SET COD_TARIFA = '" . strtoupper(trim($registros[$i][2])) . "' "
                                                                               . "WHERE NOMBRE = '" . strtoupper(str_replace(" ", "_", trim($registros[$i][3]))) . "'");
                                        echo "Registro actualizado en la tabla 'tarifas_2': " . strtoupper(trim($registros[$i][2])) . " - " . strtoupper(str_replace(" ", "_", trim($registros[$i][3]))) . " - Pos: " . $i;
                                        echo "<br />";
                                    }
                                    $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                                }
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
                                                                                         . "AND NOMBRE = '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . "'");
                                $row_corregimiento = mysqli_fetch_array($query_select_corregimiento);
                                if (mysqli_num_rows($query_select_corregimiento) == 0) {
                                    $query_select_id_corregimiento = mysqli_query($connection, "SELECT * FROM facturacion_enero2019_2 WHERE NIC = " . $nic . " LIMIT 1");
                                    if (mysqli_num_rows($query_select_id_corregimiento) != 0) {
                                        $row_id_corregimiento = mysqli_fetch_array($query_select_id_corregimiento);
                                        $id_corregimiento = $row_id_corregimiento['ID_COD_CORREG'];
                                        mysqli_query($connection, "INSERT INTO corregimientos_2 (ID_DEPARTAMENTO, ID_MUNICIPIO, ID_CORREGIMIENTO, NOMBRE) "
                                                                        . "VALUES ('$id_departamento', '$id_municipio', '$id_corregimiento', '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . "')");
                                        $query_select_new_corregimiento = mysqli_query($connection, "SELECT MAX(ID_TABLA) AS ID_TABLA FROM corregimientos_2");
                                        $row_new_corregimiento = mysqli_fetch_array($query_select_new_corregimiento);
                                        $id_corregimiento = $row_new_corregimiento['ID_TABLA'];
                                        echo "Registro agregado en la tabla 'corregimientos_2': " . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . " - Pos: " . $i;
                                        echo "<br />";
                                    } else {
                                        $query_select_id_corregimiento = mysqli_query($connection, "SELECT * FROM recaudo_enero2019_2 WHERE NIC = " . $nic . " LIMIT 1");
                                        if (mysqli_num_rows($query_select_id_corregimiento) != 0) {
                                            $row_id_corregimiento = mysqli_fetch_array($query_select_id_corregimiento);
                                            $id_corregimiento = $row_id_corregimiento['ID_COD_CORREG'];
                                            mysqli_query($connection, "INSERT INTO corregimientos_2 (ID_DEPARTAMENTO, ID_MUNICIPIO, ID_CORREGIMIENTO, NOMBRE) "
                                                                            . "VALUES ('$id_departamento', '$id_municipio', '$id_corregimiento', '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . "')");
                                            $query_select_new_corregimiento = mysqli_query($connection, "SELECT MAX(ID_TABLA) AS ID_TABLA FROM corregimientos_2");
                                            $row_new_corregimiento = mysqli_fetch_array($query_select_new_corregimiento);
                                            $id_corregimiento = $row_new_corregimiento['ID_TABLA'];
                                            echo "Registro agregado en la tabla 'corregimientos_2': " . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . " - Pos: " . $i;
                                            echo "<br />";
                                        } else {
                                            $query_select_max_id_corregimiento = mysqli_query($connection, "SELECT MAX(ID_CORREGIMIENTO) AS ID_CORREGIMIENTO "
                                                                                                           . "FROM corregimientos_2 "
                                                                                                          . "WHERE ID_DEPARTAMENTO = " . $id_departamento . " "
                                                                                                            . "AND ID_MUNICIPIO = " . $id_municipio);
                                            $row_max_id_corregimiento = mysqli_fetch_array($query_select_max_id_corregimiento);
                                            $id_corregimiento = $row_max_id_corregimiento['ID_CORREGIMIENTO'] + 1;
                                            mysqli_query($connection, "INSERT INTO corregimientos_2 (ID_DEPARTAMENTO, ID_MUNICIPIO, ID_CORREGIMIENTO, NOMBRE) "
                                                                            . "VALUES ('$id_departamento', '$id_municipio', '$id_corregimiento', '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . "')");
                                            $query_select_new_corregimiento = mysqli_query($connection, "SELECT MAX(ID_TABLA) AS ID_TABLA FROM corregimientos_2");
                                            $row_new_corregimiento = mysqli_fetch_array($query_select_new_corregimiento);
                                            $id_corregimiento = $row_new_corregimiento['ID_TABLA'];
                                            echo "Registro agregado en la tabla 'corregimientos_2': " . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . " - Pos: " . $i;
                                            echo "<br />";
                                        }
                                    }
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
                            echo "<p class='message'>El archivo que intenta cargar no es de Catastro. Favor verificar la Información.</p>";
                        }
                    } else {
                        echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                    }
                }
                break;
            case 'facturacion':
                $total = count($_FILES['files']['name']);
                for($k=0; $k<$total; $k++) {
                    $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_facturacion_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    if (mysqli_num_rows($query_select_ruta) == 0) {
                        if (substr($_FILES['files']['name'][$k], 0, 4) == 'FACT') {
                            $total_importe_trans = 0;
                            $total_valor_recibo = 0;
                            $ano_factura = $_POST['ano_factura'];
                            $mes_consolidado = $_POST['mes_consolidado'];
                            $departamento = $_POST['departamento'];
                            $municipio = $_POST['municipio'];
                            $tipo_poblacion = $_POST['tipo_poblacion'];
                            switch ($mes_consolidado) {
                                case "Enero": $id_mes = 1; break;
                                case "Febrero": $id_mes = 2; break;
                                case "Marzo": $id_mes = 3; break;
                                case "Abril": $id_mes = 4; break;
                                case "Mayo": $id_mes = 5; break;
                                case "Junio": $id_mes = 6; break;
                                case "Julio": $id_mes = 7; break;
                                case "Agosto": $id_mes = 8; break;
                                case "Septiembre": $id_mes = 9; break;
                                case "Octubre": $id_mes = 10; break;
                                case "Noviembre": $id_mes = 11; break;
                                case "Diciembre": $id_mes = 12; break;
                            }
                            /*$result = scandir("D:/BASES DE DATOS/Consolidados/Enero/Facturacion/Bolivar/");
                            foreach ($result as $directories) {
                                if ($directories === '.' or $directories === '..') continue;
                                echo $directories;
                                echo "<br />";
                            }*/
                            $registros = array();
                            $fecha_creacion = date('Y-m-d');
                            $id_usuario = $_SESSION['id_user'];
                            echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/". $mes_consolidado . "/Facturacion/" . $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                            echo "<br />";
                            $data = file($fullpath);
                            $i = 0;
                            mysqli_query($connection, "INSERT INTO archivos_cargados_facturacion_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                             . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                                                                                     . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', "
                                                                                     . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                            $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_facturacion_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                            $row_filename = mysqli_fetch_array($query_select_filename);
                            $id_tabla_ruta = $row_filename['ID_TABLA'];
                            foreach ($data as $lines) {
                                $registros[] = explode("\t", $lines);
                                //echo implode("\t", $registros[$i]);
                                $fecha_proc_reg = trim(substr($registros[$i][0], 0, 4) . "-" . substr($registros[$i][0], 4, 2) . "-" . substr($registros[$i][0], 6, 2));
                                $cod_oper_cont = trim($registros[$i][1]);
                                $nic = trim($registros[$i][2]);
                                $nis = trim($registros[$i][3]);
                                $sec_nis = trim($registros[$i][4]);
                                $sec_rec = trim($registros[$i][5]);
                                $fecha_fact_lect = substr($registros[$i][6], 0, 4) . "-" . substr($registros[$i][6], 4, 2) . "-" . substr($registros[$i][6], 6, 2);
                                $query_select_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                                $row_tipo_cliente = mysqli_fetch_array($query_select_tipo_cliente);
                                if (mysqli_num_rows($query_select_tipo_cliente) == 0) {
                                    mysqli_query($connection, "INSERT INTO tipo_clientes_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][7])) . "')");
                                    echo "<br />";
                                    $query_select_new_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                                    $row_new_tipo_cliente = mysqli_fetch_array($query_select_new_tipo_cliente);
                                    echo "Registro agregado en la tabla 'tipo_clientes_2': " . strtoupper(trim($registros[$i][7])) . " - Pos: " . $i;
                                    echo "<br />";
                                    $id_tipo_cliente = trim($row_new_tipo_cliente['ID_TIPO_CLIENTE']);
                                } else {
                                    $id_tipo_cliente = trim($row_tipo_cliente['ID_TIPO_CLIENTE']);
                                }
                                $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                                $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                if (mysqli_num_rows($query_select_tarifa) == 0) {
                                    mysqli_query($connection, "INSERT INTO tarifas_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][8])) . "')");
                                    $query_select_new_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                                    $row_new_tarifa = mysqli_fetch_array($query_select_new_tarifa);
                                    echo "Registro agregado en la tabla 'tarifas_2': " . strtoupper(trim($registros[$i][8])) . " - Pos: " . $i;
                                    echo "<br />";
                                    $id_tarifa = trim($row_new_tarifa['ID_TARIFA']);
                                } else {
                                    $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                                }
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
                                mysqli_query($connection, "INSERT INTO facturacion_" . strtolower($mes_consolidado) . $ano_factura . "_2 (FECHA_PROC_REG, COD_OPER_CONT, NIC, NIS, SEC_NIS, SEC_REC, FECHA_FACT_LECT, ID_TIPO_CLIENTE, "
                                                                                    . "ID_TARIFA, ID_ESTADO_CONTRATO, CONCEPTO, IMPORTE_TRANS, FECHA_TRANS, VALOR_RECIBO, "
                                                                                    . "ID_SECTOR_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_DPTO, ID_TIPO_POBLACION, ANO_FACTURA, MES_FACTURA, "
                                                                                    . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                           . "VALUES ('$fecha_proc_reg', '$cod_oper_cont', '$nic', '$nis', '$sec_nis', '$sec_rec', '$fecha_fact_lect', "
                                                                                   . "'$id_tipo_cliente', '$id_tarifa', '$id_estado_contrato', '$concepto', '$importe_trans', "
                                                                                   . "'$fecha_trans', '$valor_recibo', '$id_sector_dpto', '$id_cod_mpio', '$id_cod_correg', "
                                                                                   . "'$id_cod_depto', '$tipo_poblacion', '$ano_factura', '$id_mes', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
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
                        } else {
                            echo "<p class='message'>El archivo que intenta cargar no es de Facturación. Favor verificar la Información.</p>";
                        }
                    } else {
                        echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                    }
                }
                break;
            case 'recaudo':
                $total = count($_FILES['files']['name']);
                for($k=0; $k<$total; $k++) {
                    $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    if (mysqli_num_rows($query_select_ruta) == 0) {
                        if (substr($_FILES['files']['name'][$k], 0, 4) == 'RECA') {
                            $total_importe_trans = 0;
                            $total_valor_recibo = 0;
                            $ano_factura = $_POST['ano_factura'];
                            $mes_consolidado = $_POST['mes_consolidado'];
                            $departamento = $_POST['departamento'];
                            $municipio = $_POST['municipio'];
                            $tipo_poblacion = $_POST['tipo_poblacion'];
                            switch ($mes_consolidado) {
                                case "Enero": $id_mes = 1; break;
                                case "Febrero": $id_mes = 2; break;
                                case "Marzo": $id_mes = 3; break;
                                case "Abril": $id_mes = 4; break;
                                case "Mayo": $id_mes = 5; break;
                                case "Junio": $id_mes = 6; break;
                                case "Julio": $id_mes = 7; break;
                                case "Agosto": $id_mes = 8; break;
                                case "Septiembre": $id_mes = 9; break;
                                case "Octubre": $id_mes = 10; break;
                                case "Noviembre": $id_mes = 11; break;
                                case "Diciembre": $id_mes = 12; break;
                            }
                            $registros = array();
                            $fecha_creacion = date('Y-m-d');
                            $id_usuario = $_SESSION['id_user'];
                            echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/". $mes_consolidado . "/Recaudo/" . $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                            echo "<br />";
                            $data = file($fullpath);
                            $i = 0;
                            mysqli_query($connection, "INSERT INTO archivos_cargados_recaudo_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                             . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                                                                                     . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', "
                                                                                     . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                            $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                            $row_filename = mysqli_fetch_array($query_select_filename);
                            $id_tabla_ruta = $row_filename['ID_TABLA'];
                            foreach ($data as $lines) {
                                $registros[] = explode("\t", $lines);
                                //echo implode("\t", $registros[$i]);
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
                                if (mysqli_num_rows($query_select_tipo_cliente) == 0) {
                                    mysqli_query($connection, "INSERT INTO tipo_clientes_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][7])) . "')");
                                    echo "<br />";
                                    $query_select_new_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                                    $row_new_tipo_cliente = mysqli_fetch_array($query_select_new_tipo_cliente);
                                    echo "Registro agregado en la tabla 'tipo_clientes_2': " . strtoupper(trim($registros[$i][7])) . " - Pos: " . $i;
                                    echo "<br />";
                                    $id_tipo_cliente = trim($row_new_tipo_cliente['ID_TIPO_CLIENTE']);
                                } else {
                                    $id_tipo_cliente = trim($row_tipo_cliente['ID_TIPO_CLIENTE']);
                                }
                                $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                                $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                if (mysqli_num_rows($query_select_tarifa) == 0) {
                                    mysqli_query($connection, "INSERT INTO tarifas_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][8])) . "')");
                                    $query_select_new_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                                    $row_new_tarifa = mysqli_fetch_array($query_select_new_tarifa);
                                    echo "Registro agregado en la tabla 'tarifas_2': " . strtoupper(trim($registros[$i][8])) . " - Pos: " . $i;
                                    echo "<br />";
                                    $id_tarifa = trim($row_new_tarifa['ID_TARIFA']);
                                } else {
                                    $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                                }
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
                                                                                    . "ID_SECTOR_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_DPTO, ID_TIPO_POBLACION, ANO_FACTURA, MES_FACTURA, "
                                                                                    . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                           . "VALUES ('$fecha_proc_reg', '$cod_oper_cont', '$nic', '$nis', '$sec_nis', '$sec_rec', '$fecha_fact_lect', "
                                                                                   . "'$id_tipo_cliente', '$id_tarifa', '$id_estado_contrato', '$concepto', '$importe_trans', "
                                                                                   . "'$fecha_trans', '$valor_recibo', '$id_sector_dpto', '$id_cod_mpio', '$id_cod_correg', "
                                                                                   . "'$id_cod_depto', '$tipo_poblacion', '$ano_factura', '$id_mes', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
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
                        } else {
                            echo "<p class='message'>El archivo que intenta cargar no es de Recaudo. Favor verificar la Información.</p>";
                        }
                    } else {
                        echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                    }
                }
                break;
            case 'cartera':
                $total = count($_FILES['files']['name']);
                for($k=0; $k<$total; $k++) {
                    //echo $_FILES['files']['name'][$k];
                    //echo "<br />";
                    $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_cartera_fallida_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    if (mysqli_num_rows($query_select_ruta) == 0) {
                        if (substr($_FILES['files']['name'][$k], 0, 4) == 'CART') {
                            $total_importe_total = 0;
                            $total_importe_cta = 0;
                            $ano_factura = $_POST['ano_factura'];
                            $departamento = $_POST['departamento'];
                            $municipio = $_POST['municipio'];
                            $registros = array();
                            $fecha_creacion = date('Y-m-d');
                            $id_usuario = $_SESSION['id_user'];
                            echo $fullpath = "D:/BASES DE DATOS/Cartera Fallida/" . $ano_factura . "/". $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                            echo "<br />";
                            $data = file($fullpath);
                            $i = 0;
                            mysqli_query($connection, "INSERT INTO archivos_cargados_cartera_fallida_2 (ANO_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                             . "VALUES ('" . $ano_factura . "', '" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', "
                                                                                     . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                            $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_cartera_fallida_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                            $row_filename = mysqli_fetch_array($query_select_filename);
                            $id_tabla_ruta = $row_filename['ID_TABLA'];
                            foreach ($data as $lines) {
                                $registros[] = explode(";", $lines);
                                //echo implode("\t", $registros[$i]);
                                $nis = trim($registros[$i][1]);
                                $nic = trim($registros[$i][2]);
                                $simb_var = trim($registros[$i][3]);
                                $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(str_replace(" ", "_", trim($registros[$i][4]))) . "'");
                                $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                                $fecha_fact_ant = substr($registros[$i][5], 0, 4) . "-" . substr($registros[$i][5], 4, 2) . "-" . substr($registros[$i][5], 6, 2);
                                $fecha_lectura = substr($registros[$i][6], 0, 4) . "-" . substr($registros[$i][6], 4, 2) . "-" . substr($registros[$i][6], 6, 2);
                                $fecha_fact = substr($registros[$i][7], 0, 4) . "-" . substr($registros[$i][7], 4, 2) . "-" . substr($registros[$i][7], 6, 2);
                                $fecha_puesta_cobro = substr($registros[$i][8], 0, 4) . "-" . substr($registros[$i][8], 4, 2) . "-" . substr($registros[$i][8], 6, 2);
                                $fecha_venc = substr($registros[$i][9], 0, 4) . "-" . substr($registros[$i][9], 4, 2) . "-" . substr($registros[$i][9], 6, 2);
                                $unicom = trim($registros[$i][10]);
                                $nombre_cliente = strtoupper(trim(str_replace("'", "", $registros[$i][11])));
                                $documento_cliente = trim(str_replace(",", "", $registros[$i][12]));
                                $telefono_cliente = trim($registros[$i][13]);
                                $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_2 WHERE NOMBRE = '" . strtoupper(str_replace("_", " ", trim($registros[$i][15]))) . "'");
                                $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                                $id_departamento = $row_id_municipio["ID_DEPARTAMENTO"];
                                $id_municipio = $row_id_municipio["ID_MUNICIPIO"];
                                $query_select_localidad = mysqli_query($connection, "SELECT * FROM localidades_2 "
                                                                                  . " WHERE ID_DEPARTAMENTO = '$id_departamento' "
                                                                                  . "   AND ID_MUNICIPIO = '$id_municipio' "
                                                                                  . "   AND NOMBRE = '" . strtoupper(trim($registros[$i][16])) . "'");
                                $row_localidad = mysqli_fetch_array($query_select_localidad);
                                if (mysqli_num_rows($query_select_localidad) == 0) {
                                    mysqli_query($connection, "INSERT INTO localidades_2 (ID_DEPARTAMENTO, ID_MUNICIPIO, NOMBRE) "
                                                            . " VALUES ('$id_departamento', '$id_municipio', '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][16]))) . "')");
                                    $query_select_new_localidad = mysqli_query($connection, "SELECT * FROM localidades_2 WHERE NOMBRE = '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][16]))) . "'");
                                    $row_new_localidad = mysqli_fetch_array($query_select_new_localidad);
                                    echo "Registro agregado en la tabla 'localidades_2': " . strtoupper(trim($registros[$i][16])) . " - Pos: " . $i;
                                    echo "<br />";
                                    $id_localidad = trim($row_new_localidad['ID_TABLA']);
                                } else {
                                    $id_localidad = trim($row_localidad['ID_TABLA']);
                                }
                                
                                $query_select_corregimiento = mysqli_query($connection, "SELECT * FROM corregimientos_2 "
                                                                                      . " WHERE ID_DEPARTAMENTO = '$id_departamento' "
                                                                                      . "   AND ID_MUNICIPIO = '$id_municipio' "
                                                                                      . "   AND NOMBRE = '" . strtoupper(trim($registros[$i][17])) . "' ");
                                $row_id_corregimiento = mysqli_fetch_array($query_select_corregimiento);
                                $id_corregimiento = $row_id_corregimiento['ID_TABLA'];
                                $direccion_suministro = strtoupper(trim(str_replace("'", "", $registros[$i][18])));
                                $ref_direccion_suministro = strtoupper(trim(str_replace("'", "", $registros[$i][19])));
                                $departamento_envio = strtoupper(trim(str_replace("Ñ", "N", $registros[$i][20])));
                                $municipio_envio = strtoupper(trim(str_replace("Ñ", "N", $registros[$i][21])));
                                $zona_envio = strtoupper(trim(str_replace("Ñ", "N", $registros[$i][22])));
                                $barrio_envio = strtoupper(trim(str_replace("Ñ", "N", $registros[$i][23])));
                                $direccion_envio = strtoupper(trim(str_replace("Ñ", "n", $registros[$i][24])));
                                $importe_total = trim(str_replace(",", ".", $registros[$i][25]));
                                $importe_cta = trim(str_replace(",", ".", $registros[$i][26]));
                                $total_importe_total = $total_importe_total + trim(str_replace(",", ".", $registros[$i][25]));
                                $total_importe_cta = $total_importe_cta + trim(str_replace(",", ".", $registros[$i][26]));
                                mysqli_query($connection, "INSERT INTO cartera_fallida_" . $ano_factura . "_2 "
                                                        . " (NIS, NIC, SIMBOLO_VARIABLE, ID_TARIFA, FECHA_FACT_ANT, FECHA_LECTURA, FECHA_FACT, FECHA_PUESTA_COBRO, FECHA_VENC, "
                                                        . "  UNICOM, NOMBRE_CLIENTE, DOC_CLIENTE, TEL_CLIENTE, ID_COD_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_LDAD, DIR_SUMINISTRO, "
                                                        . "  REF_DIR_SUMINISTRO, DPTO_ENVIO, MPIO_ENVIO, LDAD_ENVIO, CORREG_ENVIO, DIR_ENVIO, IMPORTE_TOTAL, IMPORTE_CTA, ANO_CARTERA_FALLIDA, "
                                                        . "  ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                        . " VALUES ('$nis', '$nic', '$simb_var', '$id_tarifa', '$fecha_fact_ant', '$fecha_lectura', '$fecha_fact', '$fecha_puesta_cobro', '$fecha_venc', "
                                                                 . "'$unicom', '$nombre_cliente', '$documento_cliente', '$telefono_cliente', '$id_departamento', '$id_municipio', '$id_corregimiento', "
                                                                 . "'$id_localidad', '$direccion_suministro', '$ref_direccion_suministro', '$departamento_envio', '$municipio_envio', '$zona_envio', "
                                                                 . "'$barrio_envio', '$direccion_envio', '$importe_total', '$importe_cta', '$ano_factura', '$id_tabla_ruta', "
                                                                 . "'$fecha_creacion', '$id_usuario')");
                                $i++;
                            }
                            $query_select_totales = mysqli_query($connection, "SELECT COUNT(*), SUM(IMPORTE_TOTAL), SUM(IMPORTE_CTA) "
                                                                            . "  FROM cartera_fallida_" . $ano_factura . "_2 "
                                                                            . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                            $row_totales = mysqli_fetch_array($query_select_totales);
                            echo "<br />";
                            echo "<p class='message'>Registros cargados: " . $i . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                            echo "<p class='message'>Valor Importe Total cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_importe_total, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(IMPORTE_TOTAL)'], 0, ',', '.') . ".</p>";
                            echo "<p class='message'>Valor Importe Cta. cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_importe_cta, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(IMPORTE_CTA)'], 0, ',', '.') . ".</p>";
                            echo "<p class='message'>Archivo cargado con Exito.</p>";
                            echo "<br />";
                        } else {
                            echo "<p class='message'>El archivo que intenta cargar no es de Cartera Fallida. Favor verificar la Información.</p>";
                        }
                    } else {
                        echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                    }
                }
                break;
            case 'refacturacion':
                $total = count($_FILES['files']['name']);
                for($k=0; $k<$total; $k++) {
                    $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_refacturacion_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    if (mysqli_num_rows($query_select_ruta) == 0) {
                        if (substr($_FILES['files']['name'][$k], 0, 4) == 'REFA') {
                            $total_importe_trans = 0;
                            $total_valor_recibo = 0;
                            $ano_factura = $_POST['ano_factura'];
                            $mes_consolidado = $_POST['mes_consolidado'];
                            $departamento = $_POST['departamento'];
                            $municipio = $_POST['municipio'];
                            $tipo_poblacion = $_POST['tipo_poblacion'];
                            switch ($mes_consolidado) {
                                case "Enero": $id_mes = 1; break;
                                case "Febrero": $id_mes = 2; break;
                                case "Marzo": $id_mes = 3; break;
                                case "Abril": $id_mes = 4; break;
                                case "Mayo": $id_mes = 5; break;
                                case "Junio": $id_mes = 6; break;
                                case "Julio": $id_mes = 7; break;
                                case "Agosto": $id_mes = 8; break;
                                case "Septiembre": $id_mes = 9; break;
                                case "Octubre": $id_mes = 10; break;
                                case "Noviembre": $id_mes = 11; break;
                                case "Diciembre": $id_mes = 12; break;
                            }
                            /*$result = scandir("D:/BASES DE DATOS/Consolidados/Enero/Facturacion/Bolivar/");
                            foreach ($result as $directories) {
                                if ($directories === '.' or $directories === '..') continue;
                                echo $directories;
                                echo "<br />";
                            }*/
                            $registros = array();
                            $fecha_creacion = date('Y-m-d');
                            $id_usuario = $_SESSION['id_user'];
                            echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/". $mes_consolidado . "/Refacturacion/" . $departamento . "/" . $municipio . "/" . $_FILES['files']['name'][$k];
                            echo "<br />";
                            $data = file($fullpath);
                            $i = 0;
                            mysqli_query($connection, "INSERT INTO archivos_cargados_refacturacion_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                             . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                                                                                     . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', "
                                                                                     . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                            $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_refacturacion_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                            $row_filename = mysqli_fetch_array($query_select_filename);
                            $id_tabla_ruta = $row_filename['ID_TABLA'];
                            foreach ($data as $lines) {
                                $registros[] = explode("\t", $lines);
                                //echo implode("\t", $registros[$i]);
                                $fecha_proc_reg = trim(substr($registros[$i][0], 0, 4) . "-" . substr($registros[$i][0], 4, 2) . "-" . substr($registros[$i][0], 6, 2));
                                $cod_oper_cont = trim($registros[$i][1]);
                                $nic = trim($registros[$i][2]);
                                $nis = trim($registros[$i][3]);
                                $sec_nis = trim($registros[$i][4]);
                                $sec_rec = trim($registros[$i][5]);
                                $fecha_fact_lect = substr($registros[$i][6], 0, 4) . "-" . substr($registros[$i][6], 4, 2) . "-" . substr($registros[$i][6], 6, 2);
                                $query_select_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                                $row_tipo_cliente = mysqli_fetch_array($query_select_tipo_cliente);
                                if (mysqli_num_rows($query_select_tipo_cliente) == 0) {
                                    mysqli_query($connection, "INSERT INTO tipo_clientes_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][7])) . "')");
                                    echo "<br />";
                                    $query_select_new_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                                    $row_new_tipo_cliente = mysqli_fetch_array($query_select_new_tipo_cliente);
                                    echo "Registro agregado en la tabla 'tipo_clientes_2': " . strtoupper(trim($registros[$i][7])) . " - Pos: " . $i;
                                    echo "<br />";
                                    $id_tipo_cliente = trim($row_new_tipo_cliente['ID_TIPO_CLIENTE']);
                                } else {
                                    $id_tipo_cliente = trim($row_tipo_cliente['ID_TIPO_CLIENTE']);
                                }
                                $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                                $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                if (mysqli_num_rows($query_select_tarifa) == 0) {
                                    mysqli_query($connection, "INSERT INTO tarifas_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][8])) . "')");
                                    $query_select_new_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                                    $row_new_tarifa = mysqli_fetch_array($query_select_new_tarifa);
                                    echo "Registro agregado en la tabla 'tarifas_2': " . strtoupper(trim($registros[$i][8])) . " - Pos: " . $i;
                                    echo "<br />";
                                    $id_tarifa = trim($row_new_tarifa['ID_TARIFA']);
                                } else {
                                    $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                                }
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
                                mysqli_query($connection, "INSERT INTO refacturacion_" . strtolower($mes_consolidado) . $ano_factura . "_2 (FECHA_PROC_REG, COD_OPER_CONT, NIC, NIS, SEC_NIS, SEC_REC, FECHA_FACT_LECT, ID_TIPO_CLIENTE, "
                                                                                    . "ID_TARIFA, ID_ESTADO_CONTRATO, CONCEPTO, IMPORTE_TRANS, FECHA_TRANS, VALOR_RECIBO, "
                                                                                    . "ID_SECTOR_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_DPTO, ID_TIPO_POBLACION, ANO_FACTURA, MES_FACTURA, "
                                                                                    . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                           . "VALUES ('$fecha_proc_reg', '$cod_oper_cont', '$nic', '$nis', '$sec_nis', '$sec_rec', '$fecha_fact_lect', "
                                                                                   . "'$id_tipo_cliente', '$id_tarifa', '$id_estado_contrato', '$concepto', '$importe_trans', "
                                                                                   . "'$fecha_trans', '$valor_recibo', '$id_sector_dpto', '$id_cod_mpio', '$id_cod_correg', "
                                                                                   . "'$id_cod_depto', '$tipo_poblacion', '$ano_factura', '$id_mes', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
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
                        } else {
                            echo "<p class='message'>El archivo que intenta cargar no es de Refacturación. Favor verificar la Información.</p>";
                        }
                    } else {
                        echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                    }
                }
                break;
            case 'oymri':
                $total = count($_FILES['files']['name']);
                for($k=0; $k<$total; $k++) {
                    //echo $_FILES['files']['name'][$k];
                    //echo "<br />";
                    $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_oymri_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    if (mysqli_num_rows($query_select_ruta) == 0) {
                        if (substr($_FILES['files']['name'][$k], 0, 5) == 'OYMRI') {
                            $total_valor_bruto = 0;
                            $ano_factura = $_POST['ano_factura'];
                            $periodo_factura = $_POST['periodo_factura'];
                            $registros = array();
                            $fecha_creacion = date('Y-m-d');
                            $id_usuario = $_SESSION['id_user'];
                            echo $fullpath = "D:/BASES DE DATOS/OYM - RI/" . $ano_factura . "/". $_FILES['files']['name'][$k];
                            echo "<br />";
                            $data = file($fullpath);
                            $i = 0;
                            mysqli_query($connection, "INSERT INTO archivos_cargados_oymri_2 (ANO_FACTURA, PERIODO, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                             . "VALUES ('" . $ano_factura . "', '" . $periodo_factura . "', "
                                                                                     . "'" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                            $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_oymri_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                            $row_filename = mysqli_fetch_array($query_select_filename);
                            $id_tabla_ruta = $row_filename['ID_TABLA'];
                            foreach ($data as $lines) {
                                $registros[] = explode(";", $lines);
                                //echo implode("\t", $registros[$i]);
                                $no_factura = trim($registros[$i][0]);
                                $fecha_fact = substr($registros[$i][1], 0, 4) . "-" . substr($registros[$i][1], 5, 2) . "-" . substr($registros[$i][1], 8, 2);
                                $estado = strtoupper(trim(str_replace("'", "", $registros[$i][2])));
                                $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE NOMBRE = '" . strtoupper(str_replace("_", " ", trim($registros[$i][3]))) . "'");
                                $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                                $id_departamento = $row_id_municipio["ID_DEPARTAMENTO"];
                                $id_municipio = $row_id_municipio["ID_MUNICIPIO"];
                                $valor_bruto = trim(str_replace(",", ".", $registros[$i][4]));
                                $query_select_id_concepto = mysqli_query($connection, "SELECT * FROM conceptos_facturacion_2 WHERE NOMBRE = '" . strtoupper(str_replace("_", " ", trim($registros[$i][5]))) . "'");
                                $row_id_concepto = mysqli_fetch_array($query_select_id_concepto);
                                $id_concepto = $row_id_concepto['ID_CONCEPTO_FACT'];
                                $periodo = substr($registros[$i][6], 0, 4) . "-" . substr($registros[$i][6], 5, 2) . "-" . substr($registros[$i][6], 8, 2);
                                $id_mes = trim(substr($registros[$i][6], 5, 2));
                                $id_ano = trim(substr($registros[$i][6], 0, 4));
                                $query_select_empresa = mysqli_query($connection, "SELECT * FROM empresas_2 WHERE NOMBRE = '" . strtoupper(str_replace("_", " ", trim($registros[$i][9]))) . "'");
                                $row_id_empresa = mysqli_fetch_array($query_select_empresa);
                                $id_empresa = $row_id_empresa['ID_EMPRESA'];
                                $total_valor_bruto = $total_valor_bruto + trim(str_replace(",", ".", $registros[$i][4]));
                                mysqli_query($connection, "INSERT INTO facturacion_oymri_" . $ano_factura . "_2 "
                                                        . " (NO_FACTURA, FECHA_FACTURA, ESTADO_FACTURA, ID_COD_DPTO, ID_COD_MPIO, VALOR_BRUTO, ID_CONCEPTO, PERIODO, "
                                                        . "  MES_FACTURA, ANO_FACTURA, ID_EMPRESA, ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                        . " VALUES ('$no_factura', '$fecha_fact', '$estado', '$id_departamento', '$id_municipio', '$valor_bruto', '$id_concepto', "
                                                                 . "'$periodo', '$id_mes', '$id_ano', '$id_empresa', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                                $i++;
                            }
                            $query_select_totales = mysqli_query($connection, "SELECT COUNT(*), SUM(VALOR_BRUTO) "
                                                                            . "  FROM facturacion_oymri_" . $ano_factura . "_2 "
                                                                            . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                            $row_totales = mysqli_fetch_array($query_select_totales);
                            echo "<br />";
                            echo "<p class='message'>Registros cargados: " . $i . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                            echo "<p class='message'>Valor Bruto cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_valor_bruto, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(VALOR_BRUTO)'], 0, ',', '.') . ".</p>";
                            echo "<p class='message'>Archivo cargado con Exito.</p>";
                            echo "<br />";
                        } else {
                            echo "<p class='message'>El archivo que intenta cargar no es de OYM - RI. Favor verificar la Información.</p>";
                        }
                    } else {
                        echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                    }
                }
                break;
            case 'fact_comer':
                $total = count($_FILES['files']['name']);
                for($k=0; $k<$total; $k++) {
                    //echo $_FILES['files']['name'][$k];
                    //echo "<br />";
                    $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_fact_comer_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                    if (mysqli_num_rows($query_select_ruta) == 0) {
                        if (substr($_FILES['files']['name'][$k], 0, 10) == 'FACT_COMER') {
                            $sw = 0;
                            $fecha_factura = $_POST['fecha_factura_comer'];
                            $ano_factura = $_POST['ano_factura_comer'];
                            $estado_factura = $_POST['estado_factura_comer'];
                            $comercializador = $_POST['id_comercializador_comer'];
                            $registros = array();
                            $fecha_creacion = date('Y-m-d');
                            $id_usuario = $_SESSION['id_user'];
                            echo $fullpath = "D:/BASES DE DATOS/Fact. Comercializadores/" . $ano_factura . "/". $_FILES['files']['name'][$k];
                            echo "<br />";
                            $data = file($fullpath);
                            $i = 0;
                            foreach ($data as $lines) {
                                $registros[] = explode(";", $lines);
                                if ($sw == 0) {
                                    $sw = 1;
                                    mysqli_query($connection, "INSERT INTO archivos_cargados_fact_comer_2 (ANO_FACTURA, PERIODO, ID_COMERCIALIZADOR, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                                           . "VALUES ('" . $ano_factura . "', '" . substr($registros[$i][0], 4, 2) . "', "
                                                                                                   . "'" . $comercializador . "', '" . $_FILES['files']['name'][$k] . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                                    $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_fact_comer_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                                    $row_filename = mysqli_fetch_array($query_select_filename);
                                    $id_tabla_ruta = $row_filename['ID_TABLA'];
                                    $periodo = $registros[$i][0];
                                    $factura = $registros[$i][1];
                                    $cliente_id = $registros[$i][2];
                                    $factura_tipo_id = $registros[$i][3];
                                    $nombre_cliente = $registros[$i][4];
                                    $direccion_cliente = $registros[$i][5];
                                    $query_select_tipo_mercado = mysqli_query($connection, "SELECT * FROM tipo_mercado_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][6])) . "'");
                                    $row_tipo_mercado = mysqli_fetch_array($query_select_tipo_mercado);
                                    $tipo_mercado = $row_tipo_mercado['ID_TIPO_MERCADO'];
                                    $query_select_tipo_sub_mercado = mysqli_query($connection, "SELECT * FROM tipo_sub_mercado_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                                    $row_tipo_sub_mercado = mysqli_fetch_array($query_select_tipo_sub_mercado);
                                    $tipo_sub_mercado = $row_tipo_sub_mercado['ID_TIPO_SUB_MERCADO'];
                                    if ($registros[$i][8] == "") {
                                        $estrato = 0;
                                    } else {
                                        $estrato = $registros[$i][8];
                                    }
                                    $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE NOMBRE = '" . strtoupper(str_replace("_", " ", trim(utf8_encode($registros[$i][10])))) . "'");
                                    $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                                    $id_departamento = $row_id_municipio["ID_DEPARTAMENTO"];
                                    $id_municipio = $row_id_municipio["ID_MUNICIPIO"];
                                    $fecha_elab_fact = substr($registros[$i][11], 0, 4) . "-" . substr($registros[$i][11], 5, 2) . "-" . substr($registros[$i][11], 8, 2);
                                    $fecha_limite1 = substr($registros[$i][12], 6, 4) . "-" . substr($registros[$i][12], 3, 2) . "-" . substr($registros[$i][12], 0, 2);
                                    $fecha_limite2 = substr($registros[$i][13], 6, 4) . "-" . substr($registros[$i][13], 3, 2) . "-" . substr($registros[$i][13], 0, 2);
                                    $valor_total_fact = trim(str_replace(",", ".", $registros[$i][16]));
                                    $valor_fact_concepto = trim(str_replace(",", ".", $registros[$i][17]));
                                    $fecha_pago = substr($registros[$i][18], 6, 4) . "-" . substr($registros[$i][18], 3, 2) . "-" . substr($registros[$i][18], 0, 2);
                                    $valor_pago_total = trim(str_replace(",", ".", $registros[$i][19]));
                                    $valor_pago_concepto = trim(str_replace(",", ".", $registros[$i][20]));
                                    $consumo = trim(str_replace(",", ".", $registros[$i][21]));
                                    $csm_rea_cobrable = trim(str_replace(",", ".", $registros[$i][22]));
                                    $csm_sin_con_vlr = trim(str_replace(",", ".", $registros[$i][23]));
                                    $tipo_nota = trim($registros[$i][24]);
                                    $valor_nota = trim(str_replace(",", ".", $registros[$i][25]));
                                    $fact_total_concepto = trim(str_replace(",", ".", $registros[$i][26]));
                                    $query_select_tipo_pago = mysqli_query($connection, "SELECT * FROM tipo_pagos_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][27])) . "'");
                                    $row_tipo_pago = mysqli_fetch_array($query_select_tipo_pago);
                                    $tipo_pago = $row_tipo_pago['ID_TIPO_PAGO'];
                                    $cartera_recuperada = trim(str_replace(",", ".", $registros[$i][28]));
                                    $saldo_cartera = trim(str_replace(",", ".", $registros[$i][29]));
                                    $query_select_tipo_edad = mysqli_query($connection, "SELECT * FROM tipo_edades_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][30])) . "'");
                                    $row_tipo_edad = mysqli_fetch_array($query_select_tipo_edad);
                                    $tipo_edad = $row_tipo_edad['ID_TIPO_EDAD'];
                                    $fact_cartera = trim(str_replace(",", ".", $registros[$i][31]));
                                    mysqli_query($connection, "INSERT INTO detalle_fact_comer_" . $ano_factura . "_2 "
                                                            . " (PERIODO, FACTURA, CLIENTE_ID, FACTURA_TIPO_ID, NOMBRE_CLIENTE, DIRECCION_CLIENTE, ID_TIPO_MERCADO, ID_TIPO_SUB_MERCADO, "
                                                            . "  ESTRATO, ID_COD_DPTO, ID_COD_MPIO, FECHA_ELAB_FACT, FECHA_LIMITE1, FECHA_LIMITE2, VALOR_TOT_FACT, VALOR_FACT_CONC, "
                                                            . "  FECHA_PAGO, VALOR_PAGO_TOT, VALOR_PAGO_CONC, CONSUMO, CSM_REA_COBRABLE, CSM_SIN_CON_VALOR, TIPO_NOTA, VALOR_NOTA, "
                                                            . "  FACT_TOTAL_CONC, ID_TIPO_PAGO, CARTERA_RECUP, SALDO_CARTERA, ID_TIPO_EDAD, FACT_CARTERA, ID_COMERCIALIZADOR, FECHA_FACTURA, "
                                                            . "  ESTADO_FACTURA, ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                            . " VALUES ('$periodo', '$factura', '$cliente_id', '$factura_tipo_id', '$nombre_cliente', '$direccion_cliente', '$tipo_mercado', '$tipo_sub_mercado', "
                                                                     . "'$estrato', '$id_departamento', '$id_municipio', '$fecha_elab_fact', '$fecha_limite1', '$fecha_limite2', '$valor_total_fact', '$valor_fact_concepto', "
                                                                     . "'$fecha_pago', '$valor_pago_total', '$valor_pago_concepto', '$consumo', '$csm_rea_cobrable', '$csm_sin_con_vlr', '$tipo_nota', '$valor_nota', "
                                                                     . "'$fact_total_concepto', '$tipo_pago', '$cartera_recuperada', '$saldo_cartera', '$tipo_edad', '$fact_cartera', "
                                                                     . "'$comercializador', '$fecha_factura', '$estado_factura', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                                } else {
                                    $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_fact_comer_2 WHERE RUTA = '" . $_FILES['files']['name'][$k] . "'");
                                    $row_filename = mysqli_fetch_array($query_select_filename);
                                    $id_tabla_ruta = $row_filename['ID_TABLA'];
                                    $ano_factura = substr($registros[$i][0], 0, 4);
                                    $periodo = $registros[$i][0];
                                    $factura = $registros[$i][1];
                                    $cliente_id = $registros[$i][2];
                                    $factura_tipo_id = $registros[$i][3];
                                    $nombre_cliente = $registros[$i][4];
                                    $direccion_cliente = $registros[$i][5];
                                    $query_select_tipo_mercado = mysqli_query($connection, "SELECT * FROM tipo_mercado_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][6])) . "'");
                                    $row_tipo_mercado = mysqli_fetch_array($query_select_tipo_mercado);
                                    $tipo_mercado = $row_tipo_mercado['ID_TIPO_MERCADO'];
                                    $query_select_tipo_sub_mercado = mysqli_query($connection, "SELECT * FROM tipo_sub_mercado_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                                    $row_tipo_sub_mercado = mysqli_fetch_array($query_select_tipo_sub_mercado);
                                    $tipo_sub_mercado = $row_tipo_sub_mercado['ID_TIPO_SUB_MERCADO'];
                                    if ($registros[$i][8] == "") {
                                        $estrato = 0;
                                    } else {
                                        $estrato = $registros[$i][8];
                                    }
                                    $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE NOMBRE = '" . strtoupper(str_replace("_", " ", trim(utf8_encode($registros[$i][10])))) . "'");
                                    $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                                    $id_departamento = $row_id_municipio["ID_DEPARTAMENTO"];
                                    $id_municipio = $row_id_municipio["ID_MUNICIPIO"];
                                    $fecha_elab_fact = substr($registros[$i][11], 0, 4) . "-" . substr($registros[$i][11], 5, 2) . "-" . substr($registros[$i][11], 8, 2);
                                    $fecha_limite1 = substr($registros[$i][12], 6, 4) . "-" . substr($registros[$i][12], 3, 2) . "-" . substr($registros[$i][12], 0, 2);
                                    $fecha_limite2 = substr($registros[$i][13], 6, 4) . "-" . substr($registros[$i][13], 3, 2) . "-" . substr($registros[$i][13], 0, 2);
                                    $valor_total_fact = trim(str_replace(",", ".", $registros[$i][16]));
                                    $valor_fact_concepto = trim(str_replace(",", ".", $registros[$i][17]));
                                    $fecha_pago = substr($registros[$i][18], 6, 4) . "-" . substr($registros[$i][18], 3, 2) . "-" . substr($registros[$i][18], 0, 2);
                                    $valor_pago_total = trim(str_replace(",", ".", $registros[$i][19]));
                                    $valor_pago_concepto = trim(str_replace(",", ".", $registros[$i][20]));
                                    $consumo = trim(str_replace(",", ".", $registros[$i][21]));
                                    $csm_rea_cobrable = trim(str_replace(",", ".", $registros[$i][22]));
                                    $csm_sin_con_vlr = trim(str_replace(",", ".", $registros[$i][23]));
                                    $tipo_nota = trim($registros[$i][24]);
                                    $valor_nota = trim(str_replace(",", ".", $registros[$i][25]));
                                    $fact_total_concepto = trim(str_replace(",", ".", $registros[$i][26]));
                                    $query_select_tipo_pago = mysqli_query($connection, "SELECT * FROM tipo_pagos_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][27])) . "'");
                                    $row_tipo_pago = mysqli_fetch_array($query_select_tipo_pago);
                                    $tipo_pago = $row_tipo_pago['ID_TIPO_PAGO'];
                                    $cartera_recuperada = trim(str_replace(",", ".", $registros[$i][28]));
                                    $saldo_cartera = trim(str_replace(",", ".", $registros[$i][29]));
                                    $query_select_tipo_edad = mysqli_query($connection, "SELECT * FROM tipo_edades_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][30])) . "'");
                                    $row_tipo_edad = mysqli_fetch_array($query_select_tipo_edad);
                                    $tipo_edad = $row_tipo_edad['ID_TIPO_EDAD'];
                                    $fact_cartera = trim(str_replace(",", ".", $registros[$i][31]));
                                    mysqli_query($connection, "INSERT INTO detalle_fact_comer_" . $ano_factura . "_2 "
                                                            . " (PERIODO, FACTURA, CLIENTE_ID, FACTURA_TIPO_ID, NOMBRE_CLIENTE, DIRECCION_CLIENTE, ID_TIPO_MERCADO, ID_TIPO_SUB_MERCADO, "
                                                            . "  ESTRATO, ID_COD_DPTO, ID_COD_MPIO, FECHA_ELAB_FACT, FECHA_LIMITE1, FECHA_LIMITE2, VALOR_TOT_FACT, VALOR_FACT_CONC, "
                                                            . "  FECHA_PAGO, VALOR_PAGO_TOT, VALOR_PAGO_CONC, CONSUMO, CSM_REA_COBRABLE, CSM_SIN_CON_VALOR, TIPO_NOTA, VALOR_NOTA, "
                                                            . "  FACT_TOTAL_CONC, ID_TIPO_PAGO, CARTERA_RECUP, SALDO_CARTERA, ID_TIPO_EDAD, FACT_CARTERA, ID_COMERCIALIZADOR, FECHA_FACTURA, "
                                                            . "  ESTADO_FACTURA, ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                            . " VALUES ('$periodo', '$factura', '$cliente_id', '$factura_tipo_id', '$nombre_cliente', '$direccion_cliente', '$tipo_mercado', '$tipo_sub_mercado', "
                                                                     . "'$estrato', '$id_departamento', '$id_municipio', '$fecha_elab_fact', '$fecha_limite1', '$fecha_limite2', '$valor_total_fact', '$valor_fact_concepto', "
                                                                     . "'$fecha_pago', '$valor_pago_total', '$valor_pago_concepto', '$consumo', '$csm_rea_cobrable', '$csm_sin_con_vlr', '$tipo_nota', '$valor_nota', "
                                                                     . "'$fact_total_concepto', '$tipo_pago', '$cartera_recuperada', '$saldo_cartera', '$tipo_edad', '$fact_cartera', "
                                                                     . "'$comercializador', '$fecha_factura', '$estado_factura', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                                }
                                $i++;
                            }
                            $query_select_datos_fact_comer = mysqli_query($connection, "SELECT DISTINCT(DFC.ID_COD_MPIO), MV.NOMBRE, DFC.ID_COD_DPTO, DV.NOMBRE,
                                                                                               SUM(DFC.VALOR_FACT_CONC) AS VALOR_FACT,
                                                                                               SUM(DFC.VALOR_NOTA) AS AJUSTE_FACT,
                                                                                               SUM(DFC.VALOR_PAGO_CONC) AS VALOR_RECA,
                                                                                               SUM(DFC.CSM_SIN_CON_VALOR) AS VALOR_ENER,
                                                                                               SUM(DFC.VALOR_PAGO_CONC + DFC.CARTERA_RECUP) AS VALOR_FAV,
                                                                                               SUM(DFC.CONSUMO) AS CONSUMO, COUNT(DFC.ID_COD_MPIO) AS NO_USUARIOS 
                                                                                          FROM detalle_fact_comer_2021_2 DFC, departamentos_visitas_2 DV, municipios_visitas_2 MV 
                                                                                         WHERE DFC.ID_COD_DPTO = DV.ID_DEPARTAMENTO
                                                                                               AND DFC.ID_COD_MPIO = MV.ID_MUNICIPIO
                                                                                               AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO 
                                                                                               AND DFC.ID_TABLA_RUTA = $id_tabla_ruta 
                                                                                         GROUP BY DFC.ID_COD_MPIO
                                                                                        HAVING COUNT(DFC.ID_COD_MPIO) >= 1");
                            while ($row_datos_fact_comer = mysqli_fetch_assoc($query_select_datos_fact_comer)) {
                                mysqli_query($connection, "INSERT INTO facturacion_comercializadores_2 (ID_COMERCIALIZADOR, ID_COD_DPTO, ID_COD_MPIO, FECHA_FACTURA, PERIODO_FACTURA, "
                                                                                                     . "VALOR_FACTURA, AJUSTE_FACT, VALOR_RECAUDO, AJUSTE_RECA, VALOR_ENERGIA, CUOTA_ENERGIA, "
                                                                                                     . "OTROS_AJUSTES, VALOR_FAVOR, CONSUMO, NO_USUARIOS, ESTADO_FACTURA, FECHA_CREACION, ID_USUARIO) "
                                                                                             . "VALUES ('$comercializador', '" . $row_datos_fact_comer['ID_COD_DPTO'] . "', "
                                                                                                     . "'" . $row_datos_fact_comer['ID_COD_MPIO'] . "', '$fecha_factura', '$periodo', "
                                                                                                     . "'" . $row_datos_fact_comer['VALOR_FACT'] . "', "
                                                                                                     . "'" . $row_datos_fact_comer['AJUSTE_FACT'] . "', "
                                                                                                     . "'" . $row_datos_fact_comer['VALOR_RECA'] . "', '0', "
                                                                                                     . "'" . $row_datos_fact_comer['VALOR_ENER'] . "', '0', '0', "
                                                                                                     . "'" . $row_datos_fact_comer['VALOR_FAV'] . "', "
                                                                                                     . "'" . $row_datos_fact_comer['CONSUMO'] . "', "
                                                                                                     . "'" . $row_datos_fact_comer['NO_USUARIOS'] . "', '$estado_factura', "
                                                                                                     . "'$fecha_creacion', '$id_usuario')");
                            }
                            $query_select_totales = mysqli_query($connection, "SELECT COUNT(*) "
                                                                            . "  FROM detalle_fact_comer_" . $ano_factura . "_2 "
                                                                            . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                            $row_totales = mysqli_fetch_array($query_select_totales);
                            echo "<br />";
                            echo "<p class='message'>Registros cargados: " . $i . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                            echo "<p class='message'>Archivo cargado con Exito.</p>";
                            echo "<br />";
                        } else {
                            echo "<p class='message'>El archivo que intenta cargar no es de Facturación Comercializadores. Favor verificar la Información.</p>";
                        }
                    } else {
                        echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                    }
                }
                break;
        }
    }
?>