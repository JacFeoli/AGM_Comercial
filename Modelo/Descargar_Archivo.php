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
        $query_select_tarifa = mysqli_query($connection, "SELECT NOMBRE FROM tarifas_2 WHERE ID_TARIFA = " . $_GET['id_tarifa']);
        $row_tarifa = mysqli_fetch_array($query_select_tarifa);
        $filename = $row_tarifa['NOMBRE'] . ".xls";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        $query_select_historial_catastro = mysqli_query($connection, "SELECT CATA1.NIC, CATA1.NOMBRE_PROPIETARIO, CATA1.DIRECCION_VIVIENDA, "
                                                                   . "       DEPT.NOMBRE AS DEPARTAMENTO, MUNC.NOMBRE AS MUNICIPIO "
                                                                   . "  FROM " . $_GET['bd_tabla_catastro_inicial'] . " CATA1, departamentos_2 DEPT, "
                                                                   . "       municipios_2 MUNC, tarifas_2 TAR "
                                                                   . " WHERE CATA1.NIC NOT IN (SELECT NIC FROM " . $_GET['bd_tabla_catastro_final'] . ") "
                                                                   . "   AND CATA1.ID_TARIFA = TAR.ID_TARIFA "
                                                                   . "   AND CATA1.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO "
                                                                   . "   AND CATA1.ID_COD_MPIO = MUNC.ID_MUNICIPIO "
                                                                   . "   AND DEPT.ID_DEPARTAMENTO = MUNC.ID_DEPARTAMENTO "
                                                                   . "   AND CATA1.ID_TARIFA = " . $_GET['id_tarifa']);
        $flag = false;
        while ($row_historial_catastro = mysqli_fetch_assoc($query_select_historial_catastro)) {
            if (!$flag) {
                echo implode("\t", array_keys($row_historial_catastro)) . "\r\n";
                $flag = true;
            }
            echo implode("\t", array_values($row_historial_catastro)) . "\r\n";
        }
    }
?>