<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Reca_Esp.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_recaudo']) != "") {
                if ($_POST['busqueda_recaudo'] != "") {
                    $busqueda_recaudo = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_recaudo'] . "%' ";
                } else {
                    $busqueda_recaudo = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_recaudo = " WHERE MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_recaudo = " WHERE MUN.NOMBRE <> ''";
        }
        $query_recaudo = mysqli_query($connection, "SELECT *
                                                      FROM recaudo_operadores_2 RO,
                                                           facturacion_operadores_2 FO,
                                                           departamentos_operadores_2 DEPT,
                                                           municipios_operadores_2 MUN, operadores_2 O
                                                      $busqueda_recaudo
                                                       AND RO.ID_FACTURACION = FO.ID_FACTURACION
                                                       AND FO.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND FO.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                       AND FO.ID_OPERADOR = O.ID_OPERADOR
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE");
        $count_recaudo = mysqli_num_rows($query_recaudo);
        $info_pagination = array();
        if ($count_recaudo > 0) {
            $paginacion_count = getPagination($count_recaudo);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_recaudo'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>