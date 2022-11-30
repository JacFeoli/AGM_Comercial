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
        $id_departamento = $_POST['id_departamento'];
        $id_municipio = $_POST['id_municipio'];
        $table_agentes = "";
        $table_concesion = "";
        $query_select_fact_munc = mysqli_query($connection, "SELECT * "
                                                          . "  FROM facturacion_municipales_2 "
                                                          . " WHERE ID_COD_DPTO = '" . $id_departamento . "' "
                                                          . "   AND ID_COD_MPIO = '" . $id_municipio . "' "
                                                          . "   AND YEAR(FECHA_FACTURA) = " . $_POST['id_ano'] . " "
                                                          . "   AND MONTH(FECHA_FACTURA) = " . $_POST['id_mes'] . "");
        while ($row_fact_munc = mysqli_fetch_assoc($query_select_fact_munc)) {
            $table_agentes = $table_agentes . "<tr>";
                $table_agentes = $table_agentes . "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                $query_select_reca_munc = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 WHERE ID_FACTURACION = '" . $row_fact_munc['ID_FACTURACION'] . "'");
                $row_reca_munc = mysqli_fetch_array($query_select_reca_munc);
                $table_agentes = $table_agentes . "<td style='vertical-align:middle;'>$ " . number_format($row_reca_munc['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                $table_agentes = $table_agentes . "<td style='vertical-align:middle;'>MUNICIPIO</td>";
            $table_agentes = $table_agentes . "</tr>";
        }
        $query_select_fact_esp = mysqli_query($connection, "SELECT * "
                                                         . "  FROM facturacion_especiales_2 FE, contribuyentes_2 C "
                                                         . " WHERE FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE "
                                                         . "   AND FE.ID_COD_DPTO = '" . $id_departamento . "' "
                                                         . "   AND FE.ID_COD_MPIO = '" . $id_municipio . "' "
                                                         . "   AND YEAR(FE.FECHA_FACTURA) = " . $_POST['id_ano'] . " "
                                                         . "   AND MONTH(FE.FECHA_FACTURA) = " . $_POST['id_mes'] . " ");
        while ($row_fact_esp = mysqli_fetch_assoc($query_select_fact_esp)) {
            $table_agentes = $table_agentes . "<tr>";
                $table_agentes = $table_agentes . "<td style='vertical-align:middle;'>" . $row_fact_esp['NOMBRE'] . "</td>";
                $query_select_reca_esp = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 WHERE ID_FACTURACION = '" . $row_fact_esp['ID_FACTURACION'] . "'");
                $row_reca_esp = mysqli_fetch_array($query_select_reca_esp);
                $table_agentes = $table_agentes . "<td style='vertical-align:middle;'>$ " . number_format($row_reca_esp['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                $table_agentes = $table_agentes . "<td style='vertical-align:middle;'>MUNICIPIO</td>";
            $table_agentes = $table_agentes . "</tr>";
        }
        $query_select_fact_comer = mysqli_query($connection, "SELECT * FROM facturacion_comercializadores_2 FC, comercializadores_2 C "
                                                           . " WHERE FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR "
                                                           . "   AND FC.ID_COD_DPTO = '" . $id_departamento . "' "
                                                           . "   AND FC.ID_COD_MPIO = '" . $id_municipio . "' "
                                                           . "   AND YEAR(FC.FECHA_FACTURA) = " . $_POST['id_ano'] . " "
                                                           . "   AND MONTH(FC.FECHA_FACTURA) = " . $_POST['id_mes'] . " ");
        while ($row_fact_comer = mysqli_fetch_assoc($query_select_fact_comer)) {
            $table_agentes = $table_agentes . "<tr>";
                $table_agentes = $table_agentes . "<td style='vertical-align:middle;'>" . $row_fact_comer['NOMBRE'] . "</td>";
                $query_select_reca_comer = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 WHERE ID_FACTURACION = '" . $row_fact_comer['ID_FACTURACION'] . "'");
                $row_reca_comer = mysqli_fetch_array($query_select_reca_comer);
                $table_agentes = $table_agentes . "<td style='vertical-align:middle;'>$ " . number_format($row_reca_comer['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                $table_agentes = $table_agentes . "<td style='vertical-align:middle;'>COMERCIALIZADORA</td>";
            $table_agentes = $table_agentes . "</tr>";
        }
        $query_select_fact_oymri = mysqli_query($connection, "SELECT * FROM facturacion_oymri_2021_2 FO, conceptos_facturacion_2 CF "
                                                           . " WHERE FO.ID_CONCEPTO = CF.ID_CONCEPTO_FACT "
                                                           . "   AND FO.ID_COD_DPTO = '" . $id_departamento . "' "
                                                           . "   AND FO.ID_COD_MPIO = '" . $id_municipio . "' "
                                                           . "   AND YEAR(FO.PERIODO) = " . $_POST['id_ano'] . " "
                                                           . "   AND MONTH(FO.PERIODO) = " . $_POST['id_mes'] . " ");
        while ($row_fact_oymri = mysqli_fetch_assoc($query_select_fact_oymri)) {
            $table_concesion = $table_concesion . "<tr>";
                $table_concesion = $table_concesion . "<td style='vertical-align:middle;'>" . $row_fact_oymri['NO_FACTURA'] . "</td>";
                $table_concesion = $table_concesion . "<td style='vertical-align:middle;'>" . $row_fact_oymri['FECHA_FACTURA'] . "</td>";
                $table_concesion = $table_concesion . "<td style='vertical-align:middle;'>" . $row_fact_oymri['NOMBRE'] . "</td>";
                $table_concesion = $table_concesion . "<td style='vertical-align:middle;'>" . $row_fact_oymri['PERIODO'] . "</td>";
                $table_concesion = $table_concesion . "<td style='vertical-align:middle;'>$ " . number_format($row_fact_oymri['VALOR_BRUTO'], 0, ',', '.') . "</td>";
            $table_concesion = $table_concesion . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table_agentes;
        $info_resultado[1] = $table_concesion;
        echo json_encode($info_resultado);
        exit();
    }
?>