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
        $query_select_datos_process = mysqli_query($connection, "SELECT DISTINCT(DFC.ID_COD_MPIO), MV.NOMBRE,
                                                                        DFC.ID_COD_DPTO, DV.NOMBRE, DFC.ID_COMERCIALIZADOR,
                                                                        DFC.ESTADO_FACTURA, DFC.PERIODO, DFC.FECHA_FACTURA,
                                                                        DFC.FECHA_CREACION,
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
                                                                    AND DFC.ID_TABLA_RUTA = 7
                                                                    AND DFC.PERIODO = 202106
                                                                  GROUP BY DFC.ID_COD_MPIO, DFC.ID_COMERCIALIZADOR
                                                                  HAVING COUNT(DFC.ID_COD_MPIO) >= 1");
        while ($row_datos_process = mysqli_fetch_assoc($query_select_datos_process)) {
            mysqli_query($connection, "INSERT INTO facturacion_comercializadores_2 (ID_COMERCIALIZADOR, ID_COD_DPTO, ID_COD_MPIO, FECHA_FACTURA, PERIODO_FACTURA, "
                                                                                  . "VALOR_FACTURA, AJUSTE_FACT, VALOR_RECAUDO, AJUSTE_RECA, VALOR_ENERGIA, CUOTA_ENERGIA, "
                                                                                  . "OTROS_AJUSTES, VALOR_FAVOR, CONSUMO, NO_USUARIOS, ESTADO_FACTURA, FECHA_CREACION, ID_USUARIO) "
                                                                          . "VALUES ('" . $row_datos_process['ID_COMERCIALIZADOR'] . "', "
                                                                                  . "'" . $row_datos_process['ID_COD_DPTO'] . "', "
                                                                                  . "'" . $row_datos_process['ID_COD_MPIO'] . "', "
                                                                                  . "'" . $row_datos_process['FECHA_FACTURA'] . "', "
                                                                                  . "'" . $row_datos_process['PERIODO'] . "', "
                                                                                  . "'" . $row_datos_process['VALOR_FACT'] . "', "
                                                                                  . "'" . $row_datos_process['AJUSTE_FACT'] . "', "
                                                                                  . "'" . $row_datos_process['VALOR_RECA'] . "', '0', "
                                                                                  . "'" . $row_datos_process['VALOR_ENER'] . "', '0', '0', "
                                                                                  . "'" . $row_datos_process['VALOR_FAV'] . "', "
                                                                                  . "'" . $row_datos_process['CONSUMO'] . "', "
                                                                                  . "'" . $row_datos_process['NO_USUARIOS'] . "', "
                                                                                  . "'" . $row_datos_process['ESTADO_FACTURA'] . "', "
                                                                                  . "'" . $row_datos_process['FECHA_CREACION'] . "', '1')");
        }
    }
?>