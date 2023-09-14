<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Fact_Esp.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if ($_POST['tipo_busqueda_interventoria'] == 1) {
                $periodo = "";
                if (isset($_POST['busqueda_acta']) != "") {
                    if ($_POST['busqueda_acta'] != "") {
                        $busqueda_acta = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_acta'] . "%' ";
                    } else {
                        $busqueda_acta = " WHERE MUN.NOMBRE <> ''";
                    }
                } else {
                    $busqueda_acta = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_acta = "";
                if ($_POST['busqueda_acta'] != '') {
                    $periodo = " WHERE AC.PERIODO_ACTA LIKE '%" . $_POST['busqueda_acta'] . "%' ";
                } else {
                    $periodo = " WHERE AC.PERIODO_ACTA != '' ";
                }
            }
        } else {
            $busqueda_acta = " WHERE MUN.NOMBRE <> ''";
            $periodo = "";
        }
        $query_acta_interventoria = mysqli_query($connection, "SELECT DISTINCT(DEPT.ID_DEPARTAMENTO) AS ID_DEPARTAMENTO,
                                                                        AC.ID_ACTA_INTERVENTORIA, MUN.ID_MUNICIPIO,
                                                                        AC.ID_COD_DPTO, AC.ID_COD_MPIO, DEPT.NOMBRE, MUN.NOMBRE
                                                                    FROM actas_interventoria_2 AC,
                                                                        departamentos_visitas_2 DEPT,
                                                                        municipios_visitas_2 MUN
                                                                    $busqueda_acta
                                                                    $periodo
                                                                    AND AC.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                    AND AC.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                    AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                    ORDER BY MUN.NOMBRE");
        $count_acta_interventoria = mysqli_num_rows($query_acta_interventoria);
        $info_pagination = array();
        if ($count_acta_interventoria > 0) {
            $paginacion_count = getPagination($count_acta_interventoria);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_acta'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>