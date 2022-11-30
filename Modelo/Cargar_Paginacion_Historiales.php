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
                require_once('../Includes/Paginacion_Historial_Catastro.php');
                $bd_tabla_catastro_inicial = $_POST['bd_tabla_catastro_inicial'];
                $bd_tabla_catastro_final = $_POST['bd_tabla_catastro_final'];
                $query_select_historial_catastro = mysqli_query($connection, "SELECT TAR.ID_TARIFA, TAR.COD_TARIFA, TAR.NOMBRE, COUNT(CATA1.ID_TABLA) "
                                                                           . "  FROM $bd_tabla_catastro_inicial CATA1, tarifas_2 TAR "
                                                                           . " WHERE NIC NOT IN (SELECT NIC FROM $bd_tabla_catastro_final) "
                                                                           . "   AND CATA1.ID_TARIFA = TAR.ID_TARIFA "
                                                                           . " GROUP BY TAR.NOMBRE "
                                                                           . "HAVING COUNT(1) >= 1 "
                                                                           . " ORDER BY TAR.NOMBRE");
                $count_historial_catastro = mysqli_num_rows($query_select_historial_catastro);
                $info_pagination = array();
                if ($count_historial_catastro > 0) {
                    $paginacion_count = getPagination($count_historial_catastro);
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