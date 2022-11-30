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
        if ($_SESSION['id_departamento'] != 0) {
            $and_id_departamento = "AND PQR.ID_COD_DPTO = " . $_SESSION['id_departamento'];
            $and_id_municipio = "AND PQR.ID_COD_MPIO = " . $_SESSION['id_municipio'];
        } else {
            $and_id_departamento = " ";
            $and_id_municipio = " ";
        }
        switch ($sw) {
            case '4':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $id_ano_mensual = $_POST['id_ano_mensual'];
                $id_mes_mensual = $_POST['id_mes_mensual'];
                $query_select_info_mensual = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                                               PQR.ID_PQR, PQR.NOMBRES AS NOMBRES,
                                                                               PQR.APELLIDOS AS APELLIDOS,
                                                                               PQR.IDENTIFICACION AS IDENTIFICACION,
                                                                               PQR.ESTADO_PQR, PQR.RADICADO
                                                                          FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                                               municipios_visitas_2 MUN
                                                                         WHERE PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                           AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                           AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                           AND YEAR(PQR.FECHA_INGRESO) = " . $id_ano_mensual . "
                                                                           AND MONTH(PQR.FECHA_INGRESO) = " . $id_mes_mensual . "
                                                                           $and_id_departamento
                                                                           $and_id_municipio
                                                                         ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC ");
                $count_resultado_mensual = mysqli_num_rows($query_select_info_mensual);
                $info_pagination = array();
                if ($count_resultado_mensual > 0) {
                    $paginacion_count = getPagination($count_resultado_mensual);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '5':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $fecha_inicio = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_fin'];
                $query_select_info_rango = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                                             PQR.ID_PQR, PQR.NOMBRES AS NOMBRES,
                                                                             PQR.APELLIDOS AS APELLIDOS,
                                                                             PQR.IDENTIFICACION AS IDENTIFICACION,
                                                                             PQR.ESTADO_PQR, PQR.RADICADO
                                                                        FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                                             municipios_visitas_2 MUN
                                                                       WHERE PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                         AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                         AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                         AND PQR.FECHA_INGRESO BETWEEN '" . $fecha_inicio . "' AND '$fecha_fin'
                                                                         $and_id_departamento
                                                                         $and_id_municipio
                                                                       ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC ");
                $count_resultado_rango = mysqli_num_rows($query_select_info_rango);
                $info_pagination = array();
                if ($count_resultado_rango > 0) {
                    $paginacion_count = getPagination($count_resultado_rango);
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