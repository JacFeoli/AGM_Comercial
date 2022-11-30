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
        $nombre_compania = $_POST['nombre_compania'];
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_ROL_USUARIO * ($page - 1);
        }
        $query_roles_usuarios = "SELECT USU.ID_USUARIO AS ID_USUARIO, USU.NOMBRE AS NOMBRE_USUARIO, USU.IDENTIFICACION AS IDENTIFICACION_USUARIO,
                                        USU.CORREO_ELECTRONICO AS CORREO_ELECTRONICO, USU.USUARIO AS USUARIO, ROL.NOMBRE AS NOMBRE_ROL,
                                        ROLUSU.ID_TABLA AS ID_ROL_USUARIO
                                   FROM usuarios_2 USU, usuarios_tipo_companias_2 USTP,
                                        roles_2 ROL, roles_usuarios_2 ROLUSU, tipo_companias_2 TP
                                   WHERE USU.ID_USUARIO = USTP.ID_USUARIO
                                     AND USU.ID_USUARIO = ROLUSU.ID_USUARIO
                                     AND ROL.ID_ROL = ROLUSU.ID_ROL
                                     AND USTP.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                     AND ROL.ID_TIPO_COMPANIA = TP.ID_TIPO_COMPANIA
                                     AND USTP.ID_TIPO_COMPANIA = " . $id_area_interna . "
                                   ORDER BY USU.ID_USUARIO, ROL.NOMBRE
                                   LIMIT " . $pageLimit . ", " . PAGE_PER_ROL_USUARIO;
        $sql_rol_usuarios = mysqli_query($connection, $query_roles_usuarios);
        $table = "";
        $info_resultado = array();
        while ($row_rol_usuarios = mysqli_fetch_assoc($sql_rol_usuarios)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_rol_usuarios['NOMBRE_USUARIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_rol_usuarios['IDENTIFICACION_USUARIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_rol_usuarios['CORREO_ELECTRONICO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_rol_usuarios['USUARIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_rol_usuarios['NOMBRE_ROL'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><button><a href='Roles_Usuarios.php?id_rol_usuario_editar=" . $row_rol_usuarios['ID_ROL_USUARIO'] . "&id_area_interna=" . $id_area_interna . "'><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></a></button></td>";
                $table = $table . "<td style='vertical-align:middle;'><button><a href='Roles_Usuarios.php?id_rol_usuario_eliminar=" . $row_rol_usuarios['ID_ROL_USUARIO'] . "&id_area_interna=" . $id_area_interna . "'><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></a></button></td>";
                //$table = $table . "<td style='vertical-align:middle;'></td>";
            $table = $table . "</tr>";
        }
        $info_resultado[0] = $nombre_compania;
        $info_resultado[1] = $table;
        $info_resultado[2] = $id_area_interna;
        echo json_encode($info_resultado);
        exit();
    }
?>