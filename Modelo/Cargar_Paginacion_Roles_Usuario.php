<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Rol_Usuario.php');
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
        $query_roles_usuarios = "SELECT *
                                   FROM usuarios_2 USU, usuarios_tipo_companias_2 USTP,
                                        roles_2 ROL, roles_usuarios_2 ROLUSU, tipo_companias_2 TP
                                  WHERE USU.ID_USUARIO = USTP.ID_USUARIO
                                    AND USU.ID_USUARIO = ROLUSU.ID_USUARIO
                                    AND ROL.ID_ROL = ROLUSU.ID_ROL
                                    AND USTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                    AND ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                    AND USTP.ID_TIPO_COMPANIA = " . $id_area_interna . "
                                  ORDER BY USU.ID_USUARIO, ROL.NOMBRE";
        $sql_roles_usuarios = mysqli_query($connection, $query_roles_usuarios);
        $count_roles_usuarios = mysqli_num_rows($sql_roles_usuarios);
        if ($count_roles_usuarios > 0) {
            $info_pagination = array();
            $paginacion_count = getPagination($count_roles_usuarios);
            $info_pagination[0] = $pagination;
            $info_pagination[1] = $paginacion_count;
            $info_pagination[2] = $nombre_compania;
            $info_pagination[3] = $id_area_interna;
            echo json_encode($info_pagination);
            exit();
        }
    }
?>