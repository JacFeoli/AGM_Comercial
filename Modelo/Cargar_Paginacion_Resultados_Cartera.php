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
                $bd_tabla_cartera_fallida = $_POST['bd_tabla_cartera_fallida'];
                $query_select_cartera_fallida_nic = mysqli_query($connection, "SELECT * "
                                                                        . "  FROM $bd_tabla_cartera_fallida CART "
                                                                        . " WHERE CART.NIC = " . $nic . " ");
                $count_resultado_nic = mysqli_num_rows($query_select_cartera_fallida_nic);
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
        }
        echo json_encode($info_pagination);
        exit();
    }
?>