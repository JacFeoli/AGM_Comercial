<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Bitacora_Municipio.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_bitacora_libreta']) != "") {
                if ($_POST['busqueda_bitacora_libreta'] != "") {
                    $busqueda_bitacora_libreta = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_bitacora_libreta'] . "%' ";
                } else {
                    $busqueda_bitacora_libreta = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_bitacora_libreta = " WHERE MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_bitacora_libreta = " WHERE MUN.NOMBRE <> ''";
        }
        $query_bitacora_municipio = mysqli_query($connection, "SELECT *
                                                                 FROM municipios_libreta_2 ML, departamentos_visitas_2 DEPT,
                                                                      municipios_visitas_2 MUN
                                                                 $busqueda_bitacora_libreta
                                                                  AND ML.ID_DEPARTAMENTO = DEPT.ID_DEPARTAMENTO
                                                                  AND ML.ID_MUNICIPIO = MUN.ID_MUNICIPIO
                                                                  AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                ORDER BY DEPT.NOMBRE, MUN.NOMBRE");
        $count_bitacora_municipio = mysqli_num_rows($query_bitacora_municipio);
        $info_pagination = array();
        if ($count_bitacora_municipio > 0) {
            $paginacion_count = getPagination($count_bitacora_municipio);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_bitacora_libreta'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>