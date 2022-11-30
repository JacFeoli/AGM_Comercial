<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Historico_Municipio.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        $id_municipio_libreta_historico = $_POST['id_municipio_libreta_historico'];
        $query_historico_municipio = mysqli_query($connection, "SELECT *
                                                                  FROM historico_municipios_libreta_2 HML, departamentos_visitas_2 DEPT,
                                                                       municipios_visitas_2 MUN
                                                                 WHERE HML.ID_DEPARTAMENTO = DEPT.ID_DEPARTAMENTO
                                                                   AND HML.ID_MUNICIPIO = MUN.ID_MUNICIPIO
                                                                   AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                   AND HML.ID_MUNICIPIO_LIBRETA = " . $id_municipio_libreta_historico . "
                                                                 ORDER BY DEPT.NOMBRE, MUN.NOMBRE");
        $count_historico_municipio = mysqli_num_rows($query_historico_municipio);
        $info_pagination = array();
        if ($count_historico_municipio > 0) {
            $paginacion_count = getPagination($count_historico_municipio);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        echo json_encode($info_pagination);
        exit();
    }
?>