<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_PQR.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_pqr']) != "") {
                if ($_POST['busqueda_pqr'] != "") {
                    $busqueda_pqr = " WHERE PQR.NOMBRES LIKE '%" . $_POST['busqueda_pqr'] . "%' ";
                } else {
                    $busqueda_pqr = " WHERE PQR.NOMBRES <> ''";
                }
            } else {
                $busqueda_pqr = " WHERE PQR.NOMBRES <> ''";
            }
        } else {
            $busqueda_pqr = " WHERE PQR.NOMBRES <> ''";
        }
        if ($_SESSION['id_departamento'] != 0) {
            if ($_SESSION['id_user'] == 34) {
                $query_pqr = mysqli_query($connection, "SELECT *
                                                      FROM pqr_2 PQR,
                                                           departamentos_visitas_2 DEPT,
                                                           municipios_visitas_2 MUN
                                                      $busqueda_pqr
                                                       AND PQR.ID_COD_DPTO = " . $_SESSION['id_departamento'] . "
                                                       AND PQR.ID_COD_MPIO IN (518, 553) 
                                                       AND PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE");
            } else {
                $query_pqr = mysqli_query($connection, "SELECT *
                                                      FROM pqr_2 PQR,
                                                           departamentos_visitas_2 DEPT,
                                                           municipios_visitas_2 MUN
                                                      $busqueda_pqr
                                                       AND PQR.ID_COD_DPTO = " . $_SESSION['id_departamento'] . "
                                                       AND PQR.ID_COD_MPIO = " . $_SESSION['id_municipio'] . "
                                                       AND PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE");
            }
        } else {
            $query_pqr = mysqli_query($connection, "SELECT *
                                                      FROM pqr_2 PQR,
                                                           departamentos_visitas_2 DEPT,
                                                           municipios_visitas_2 MUN
                                                      $busqueda_pqr
                                                       AND PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE");
        }
        $count_pqr = mysqli_num_rows($query_pqr);
        $info_pagination = array();
        if ($count_pqr > 0) {
            $paginacion_count = getPagination($count_pqr);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_pqr'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>