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
        $sw = $_POST['sw'];
        switch ($sw) {
            case '0':
                require_once('../Includes/Paginacion_Resultado_Nic.php');
                $nic = $_POST['nic'];
                $bd_tabla_facturacion = $_POST['bd_tabla_facturacion'];
                $bd_tabla_recaudo = $_POST['bd_tabla_recaudo'];
                $query_select_facturacion_nic = mysqli_query($connection, "SELECT FACT.VALOR_RECIBO AS VALOR_FACTURA, FACT.FECHA_TRANS AS FECHA_FACTURA, "
                                                                               . "RECA.VALOR_RECIBO AS VALOR_RECAUDO, RECA.FECHA_TRANS AS FECHA_RECAUDO, "
                                                                               . "FACT.ANO_FACTURA, FACT.MES_FACTURA "
                                                                        . "  FROM $bd_tabla_facturacion FACT "
                                                                        . "  LEFT JOIN $bd_tabla_recaudo RECA "
                                                                        . "    ON FACT.NIC = RECA.NIC "
                                                                        . "   AND FACT.FECHA_FACT_LECT = RECA.FECHA_FACT_LECT "
                                                                        . "   AND FACT.SEC_REC = RECA.SEC_REC "
                                                                        . " WHERE FACT.NIC = " . $nic . " "
                                                                        . " GROUP BY FACT.FECHA_FACT_LECT, FACT.SEC_REC");
                $count_resultado_nic = mysqli_num_rows($query_select_facturacion_nic);
                $info_pagination = array();
                if ($count_resultado_nic > 0) {
                    $paginacion_count = getPagination($count_resultado_nic);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '1':
                require_once('../Includes/Paginacion_Resultado_Propietario.php');
                $propietario = $_POST['propietario'];
                $bd_tabla_catastro = $_POST['bd_tabla_catastro'];
                $query_select_info_propietario = mysqli_query($connection, "SELECT CAT.NIC AS NIC, CAT.NOMBRE_PROPIETARIO AS PROPIETARIO, "
                                                                                . "CAT.DIRECCION_VIVIENDA AS DIRECCION, "
                                                                                . "CAT.DEUDA_CORRIENTE AS DEUDA_CORRIENTE, "
                                                                                . "CAT.DEUDA_CUOTA AS DEUDA_CUOTA, "
                                                                                . "CAT.ANO_CATASTRO AS ANO, CAT.MES_CATASTRO AS MES, "
                                                                                . "DEP.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO, "
                                                                                . "CORR.NOMBRE AS CORREGIMIENTO, "
                                                                                . "EST.NOMBRE AS ESTADO_SUMINISTRO, TAR.NOMBRE AS TARIFA "
                                                                           . "FROM $bd_tabla_catastro CAT, departamentos_2 DEP, "
                                                                                . "municipios_2 MUN, corregimientos_2 CORR, "
                                                                                . "estados_suministro_2 EST, tarifas_2 TAR "
                                                                          . "WHERE CAT.ID_COD_DPTO = DEP.ID_DEPARTAMENTO "
                                                                            . "AND CAT.ID_COD_MPIO = MUN.ID_MUNICIPIO "
                                                                            . "AND CAT.ID_COD_CORREG = CORR.ID_TABLA "
                                                                            . "AND DEP.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO "
                                                                            . "AND DEP.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                            . "AND MUN.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                            . "AND MUN.ID_MUNICIPIO = CORR.ID_MUNICIPIO "
                                                                            . "AND CAT.ID_TARIFA = TAR.ID_TARIFA "
                                                                            . "AND CAT.ID_ESTADO_SUMINISTRO = EST.ID_ESTADO_SUMINISTRO "
                                                                            . "AND CAT.NOMBRE_PROPIETARIO LIKE '%" . $propietario . "%' "
                                                                          . "ORDER BY CAT.NOMBRE_PROPIETARIO");
                $count_resultado_propietario = mysqli_num_rows($query_select_info_propietario);
                $info_pagination = array();
                if ($count_resultado_propietario > 0) {
                    $paginacion_count = getPagination($count_resultado_propietario);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '2':
                require_once('../Includes/Paginacion_Resultado_Departamento.php');
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                if ($departamento == "") {
                    $query_departamento = " ";
                } else {
                    $query_departamento = " AND MUN.ID_DEPARTAMENTO = " . $departamento . " ";
                }
                if ($municipio == "") {
                    $query_municipio = "";
                } else {
                    $query_municipio = " AND MUN.ID_MUNICIPIO = " . $municipio . " ";
                }
                $id_ano_factura = $_POST['id_ano_factura'];
                $id_mes_factura = $_POST['id_mes_factura'];
                $mes_factura = $_POST['mes_factura'];
                $bd_tabla_catastro = "catastro_" . $mes_factura . $id_ano_factura . "_2";
                $query_select_info_departamento = mysqli_query($connection, "SELECT MUN.ID_MUNICIPIO, MUN.NOMBRE, COUNT(CATA.NIC) AS TOTAL_CLIENTES "
                                                                          . "  FROM municipios_2 MUN, $bd_tabla_catastro CATA "
                                                                          . " WHERE MUN.ID_DEPARTAMENTO = CATA.ID_COD_DPTO "
                                                                          . "   AND MUN.ID_MUNICIPIO = CATA.ID_COD_MPIO "
                                                                          . $query_departamento . " "
                                                                          . $query_municipio . " "
                                                                          . " GROUP BY MUN.NOMBRE "
                                                                          . "HAVING COUNT(1) >= 1 "
                                                                          . " ORDER BY MUN.NOMBRE");
                $count_resultado_departamento = mysqli_num_rows($query_select_info_departamento);
                $info_pagination = array();
                if ($count_resultado_departamento > 0) {
                    $paginacion_count = getPagination($count_resultado_departamento);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '3':
                require_once('../Includes/Paginacion_Resultado_Tarifa.php');
                $tarifa = $_POST['tarifa'];
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                if ($departamento == "") {
                    $query_departamento = " ";
                } else {
                    $query_departamento = " AND MUN.ID_DEPARTAMENTO = " . $departamento . " ";
                }
                if ($municipio == "") {
                    $query_municipio = "";
                } else {
                    $query_municipio = " AND MUN.ID_MUNICIPIO = " . $municipio . " ";
                }
                $id_ano_factura = $_POST['id_ano_factura'];
                $id_mes_factura = $_POST['id_mes_factura'];
                $mes_factura = $_POST['mes_factura'];
                $bd_tabla_catastro = "catastro_" . $mes_factura . $id_ano_factura . "_2";
                $query_select_info_tarifa = mysqli_query($connection, "SELECT MUN.ID_MUNICIPIO, MUN.NOMBRE, COUNT(CATA.NIC) AS TOTAL_CLIENTES "
                                                                    . "  FROM municipios_2 MUN, $bd_tabla_catastro CATA "
                                                                    . " WHERE MUN.ID_DEPARTAMENTO = CATA.ID_COD_DPTO "
                                                                    . "   AND MUN.ID_MUNICIPIO = CATA.ID_COD_MPIO "
                                                                    . $query_departamento . " "
                                                                    . $query_municipio . " "
                                                                    . " GROUP BY MUN.NOMBRE "
                                                                    . "HAVING COUNT(1) >= 1 "
                                                                    . " ORDER BY MUN.NOMBRE");
                $count_resultado_tarifa = mysqli_num_rows($query_select_info_tarifa);
                $info_pagination = array();
                if ($count_resultado_tarifa > 0) {
                    $paginacion_count = getPagination($count_resultado_tarifa);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '4':
                require_once('../Includes/Paginacion_Resultado_Cambio_Tarifa.php');
                $id_ano_cambio_tarifa = $_POST['id_ano_cambio_tarifa'];
                $id_mes_cambio_tarifa = $_POST['id_mes_cambio_tarifa'];
                $mes_cambio_tarifa = $_POST['mes_cambio_tarifa'];
                $bd_tabla_novedades = "novedades_" . $mes_cambio_tarifa . $id_ano_cambio_tarifa . "_2";
                $query_select_info_cambio_tarifa = mysqli_query($connection, " SELECT * "
                                                                           . "   FROM $bd_tabla_novedades "
                                                                           . "  WHERE COD_TARIFA_ACTUAL <> COD_TARIFA_ANTERIOR "
                                                                           . "  ORDER BY NIC");
                $count_resultado_cambio_tarifa = mysqli_num_rows($query_select_info_cambio_tarifa);
                $info_pagination = array();
                if ($count_resultado_cambio_tarifa > 0) {
                    $paginacion_count = getPagination($count_resultado_cambio_tarifa);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
        }
        echo json_encode($info_pagination);
        exit();
    }
?>