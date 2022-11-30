<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Usuario.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        $id_area_interna = $_POST['id_area_interna'];
        $pagination = $_POST['pagination'];
        $nombre_compania = $_POST['nombre_compania'];
        $query_usuarios = "SELECT * FROM usuarios_2 U, usuarios_tipo_companias_2 UTC
                            WHERE U.ID_USUARIO = UTC.ID_USUARIO
                              AND UTC.ID_TIPO_COMPANIA = " . $id_area_interna . "
                            ORDER BY U.BLOQUEADO ASC, U.NOMBRE";
        $sql_usuarios = mysqli_query($connection, $query_usuarios);
        $count_usuarios = mysqli_num_rows($sql_usuarios);
        if ($count_usuarios > 0) {
            $info_pagination = array();
            $paginacion_count = getPagination($count_usuarios);
            $info_pagination[0] = $pagination;
            $info_pagination[1] = $paginacion_count;
            $info_pagination[2] = $nombre_compania;
            $info_pagination[3] = $id_area_interna;
            echo json_encode($info_pagination);
            exit();
        }
    }
?>