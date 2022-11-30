<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'];
        switch ($_POST['sw']) {
            case 0:
                $total_fact_oper = 0;
                $total_reca_oper = 0;
                $total_ener_oper = 0;
                $total_favor_oper = 0;
                $total_fact_comer = 0;
                $total_reca_comer = 0;
                $total_ener_comer = 0;
                $total_favor_comer = 0;
                $periodo_inicio = str_replace("-", "", $_POST['periodo_inicio']);
                $periodo_fin = str_replace("-", "", $_POST['periodo_fin']);
                $departamento_departamento = $_POST['departamento_departamento'];
                $municipio_departamento = $_POST['municipio_departamento'];
                $array_data = array();
                $info_informe_municipio = array();
                $query_select_info_informe_municipio_oper = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                               . "       MV.NOMBRE AS MUNICIPIO, "
                                                                               . "       USU.NOMBRE AS USUARIO, "
                                                                               . "       FO.ID_OPERADOR AS ID_OPERADOR, "
                                                                               . "       FO.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                               . "       FO.PERIODO_FACTURA AS PERIODO, "
                                                                               . "       SUM(FO.VALOR_FACTURA) AS VALOR_FACTURA, "
                                                                               . "       FO.AJUSTE_FACT AS AJUSTE_FACT, "
                                                                               . "       SUM(FO.VALOR_RECAUDO) AS VALOR_RECAUDO, "
                                                                               . "       FO.AJUSTE_RECA AS AJUSTE_RECA, "
                                                                               . "       SUM(FO.VALOR_ENERGIA) AS VALOR_ENERGIA, "
                                                                               . "       FO.CUOTA_ENERGIA AS CUOTA_ENERGIA, "
                                                                               . "       FO.OTROS_AJUSTES AS OTROS_AJUSTES, "
                                                                               . "       SUM(FO.VALOR_FAVOR) AS VALOR_FAVOR, "
                                                                               . "       FO.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                               . "       FO.ID_FACTURACION AS ID_FACTURACION "
                                                                               . "  FROM facturacion_operadores_2 FO, "
                                                                               . "       departamentos_visitas_2 DV, "
                                                                               . "       municipios_visitas_2 MV, "
                                                                               . "       usuarios_2 USU "
                                                                               . " WHERE FO.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                               . "   AND FO.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                               . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                               . "   AND FO.ID_USUARIO = USU.ID_USUARIO "
                                                                               . "   AND FO.PERIODO_FACTURA BETWEEN '$periodo_inicio' AND '$periodo_fin' "
                                                                               . "   AND FO.ID_COD_DPTO = " . $departamento_departamento . ""
                                                                               . "   AND FO.ID_COD_MPIO = " . $municipio_departamento . ""
                                                                               . " GROUP BY FO.ID_OPERADOR "
                                                                               . " ORDER BY FO.ID_OPERADOR, DV.NOMBRE, MV.NOMBRE, FO.FECHA_FACTURA DESC ");
                $table_oper = "";
                while ($row_info_informe_municipio_oper = mysqli_fetch_assoc($query_select_info_informe_municipio_oper)) {
                    $info_municipio_oper = "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>INFORME MUNICIPIO: " . $row_info_informe_municipio_oper['MUNICIPIO'] . " - " . $row_info_informe_municipio_oper['DEPARTAMENTO'] . "</p>";
                    $query_select_operador = mysqli_query($connection, "SELECT * FROM operadores_2 WHERE ID_OPERADOR = '" . $row_info_informe_municipio_oper['ID_OPERADOR'] . "'");
                    $row_operador = mysqli_fetch_array($query_select_operador);
                    $array_data_oper[] = array("category" => $row_operador['NOMBRE'],
                                          "facturacion" => $row_info_informe_municipio_oper['VALOR_FACTURA'],
                                          "recaudo" => $row_info_informe_municipio_oper['VALOR_RECAUDO'],
                                          "valor_energia" => $row_info_informe_municipio_oper['VALOR_ENERGIA'],
                                          "valor_favor" => $row_info_informe_municipio_oper['VALOR_FAVOR']);
                    $table_oper = $table_oper . "<tr>";
                        $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . $row_operador['NOMBRE'] . "</td>";
                        $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_informe_municipio_oper['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                        $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_informe_municipio_oper['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                        $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_informe_municipio_oper['VALOR_ENERGIA'], 0, ',', '.') . "</td>";
                        $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_informe_municipio_oper['VALOR_FAVOR'], 0, ',', '.') . "</td>";
                    $table_oper = $table_oper . "</tr>";
                    $total_fact_oper = $total_fact_oper + $row_info_informe_municipio_oper['VALOR_FACTURA'];
                    $total_reca_oper = $total_reca_oper + $row_info_informe_municipio_oper['VALOR_RECAUDO'];
                    $total_ener_oper = $total_ener_oper + $row_info_informe_municipio_oper['VALOR_ENERGIA'];
                    $total_favor_oper = $total_favor_oper + $row_info_informe_municipio_oper['VALOR_FAVOR'];
                }
                $table_oper = $table_oper . "<tr style='border-top: 2px solid'>";
                    $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>TOTALES</td>";
                    $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>$ " . number_format($total_fact_oper, 0, ',', '.') . "</td>";
                    $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>$ " . number_format($total_reca_oper, 0, ',', '.') . "</td>";
                    $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>$ " . number_format($total_ener_oper, 0, ',', '.') . "</td>";
                    $table_oper = $table_oper . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>$ " . number_format($total_favor_oper, 0, ',', '.') . "</td>";
                $table_oper = $table_oper . "</tr>";
                $query_select_info_informe_municipio_comer = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                               . "       MV.NOMBRE AS MUNICIPIO, "
                                                                               . "       USU.NOMBRE AS USUARIO, "
                                                                               . "       FC.ID_COMERCIALIZADOR AS ID_COMERCIALIZADOR, "
                                                                               . "       FC.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                               . "       FC.PERIODO_FACTURA AS PERIODO, "
                                                                               . "       SUM(FC.VALOR_FACTURA) AS VALOR_FACTURA, "
                                                                               . "       FC.AJUSTE_FACT AS AJUSTE_FACT, "
                                                                               . "       SUM(FC.VALOR_RECAUDO) AS VALOR_RECAUDO, "
                                                                               . "       FC.AJUSTE_RECA AS AJUSTE_RECA, "
                                                                               . "       SUM(FC.VALOR_ENERGIA) AS VALOR_ENERGIA, "
                                                                               . "       FC.CUOTA_ENERGIA AS CUOTA_ENERGIA, "
                                                                               . "       FC.OTROS_AJUSTES AS OTROS_AJUSTES, "
                                                                               . "       SUM(FC.VALOR_FAVOR) AS VALOR_FAVOR, "
                                                                               . "       FC.ESTADO_FACTURA AS ESTADO_FACTURA, "
                                                                               . "       FC.ID_FACTURACION AS ID_FACTURACION "
                                                                               . "  FROM facturacion_comercializadores_2 FC, "
                                                                               . "       departamentos_visitas_2 DV, "
                                                                               . "       municipios_visitas_2 MV, "
                                                                               . "       usuarios_2 USU "
                                                                               . " WHERE FC.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                               . "   AND FC.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                               . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                               . "   AND FC.ID_USUARIO = USU.ID_USUARIO "
                                                                               . "   AND FC.PERIODO_FACTURA BETWEEN '$periodo_inicio' AND '$periodo_fin' "
                                                                               . "   AND FC.ID_COD_DPTO = " . $departamento_departamento . ""
                                                                               . "   AND FC.ID_COD_MPIO = " . $municipio_departamento . ""
                                                                               . " GROUP BY FC.ID_COMERCIALIZADOR "
                                                                               . " ORDER BY FC.ID_COMERCIALIZADOR, DV.NOMBRE, MV.NOMBRE, FC.FECHA_FACTURA DESC ");
                $table_comer = "";
                while ($row_info_informe_municipio_comer = mysqli_fetch_assoc($query_select_info_informe_municipio_comer)) {
                    $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = '" . $row_info_informe_municipio_comer['ID_COMERCIALIZADOR'] . "'");
                    $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                    $array_data_comer[] = array("category" => $row_comercializador['NOMBRE'],
                                          "facturacion" => $row_info_informe_municipio_comer['VALOR_FACTURA'],
                                          "recaudo" => $row_info_informe_municipio_comer['VALOR_RECAUDO'],
                                          "valor_energia" => $row_info_informe_municipio_comer['VALOR_ENERGIA'],
                                          "valor_favor" => $row_info_informe_municipio_comer['VALOR_FAVOR']);
                    $table_comer = $table_comer . "<tr>";
                        $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . $row_comercializador['NOMBRE'] . "</td>";
                        $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_informe_municipio_comer['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                        $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_informe_municipio_comer['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                        $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_informe_municipio_comer['VALOR_ENERGIA'], 0, ',', '.') . "</td>";
                        $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_info_informe_municipio_comer['VALOR_FAVOR'], 0, ',', '.') . "</td>";
                    $table_comer = $table_comer . "</tr>";
                    $total_fact_comer = $total_fact_comer + $row_info_informe_municipio_comer['VALOR_FACTURA'];
                    $total_reca_comer = $total_reca_comer + $row_info_informe_municipio_comer['VALOR_RECAUDO'];
                    $total_ener_comer = $total_ener_comer + $row_info_informe_municipio_comer['VALOR_ENERGIA'];
                    $total_favor_comer = $total_favor_comer + $row_info_informe_municipio_comer['VALOR_FAVOR'];
                }
                $table_comer = $table_comer . "<tr style='border-top: 2px solid'>";
                    $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>TOTALES</td>";
                    $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>$ " . number_format($total_fact_comer, 0, ',', '.') . "</td>";
                    $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>$ " . number_format($total_reca_comer, 0, ',', '.') . "</td>";
                    $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>$ " . number_format($total_ener_comer, 0, ',', '.') . "</td>";
                    $table_comer = $table_comer . "<td style='vertical-align: middle;  border-bottom-width: 0; font-weight: bold;'>$ " . number_format($total_favor_comer, 0, ',', '.') . "</td>";
                $table_comer = $table_comer . "</tr>";
                break;
        }
        $info_informe_municipio[0] = $table_oper;
        $info_informe_municipio[1] = $array_data_oper;
        $info_informe_municipio[2] = $info_municipio_oper;
        $info_informe_municipio[3] = $table_comer;
        $info_informe_municipio[4] = $array_data_comer;
        echo json_encode($info_informe_municipio);
        //echo $table;
    }
?>