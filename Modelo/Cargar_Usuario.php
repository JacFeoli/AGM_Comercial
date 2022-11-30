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
        $nombre_compania = $_POST['nombre_compania'];
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_USUARIO * ($page - 1);
        }
        $query_usuarios = "SELECT * FROM usuarios_2 U, usuarios_tipo_companias_2 UTC
                            WHERE U.ID_USUARIO = UTC.ID_USUARIO
                              AND UTC.ID_TIPO_COMPANIA = " . $id_area_interna . "
                            ORDER BY U.BLOQUEADO ASC, U.NOMBRE
                            LIMIT " . $pageLimit . ", " . PAGE_PER_USUARIO;
        $sql_usuarios = mysqli_query($connection, $query_usuarios);
        $table = "";
        $info_resultado = array();
        while ($row_usuarios = mysqli_fetch_assoc($sql_usuarios)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_usuarios['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_usuarios['IDENTIFICACION'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_usuarios['CORREO_ELECTRONICO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_usuarios['USUARIO'] . "</td>";
                if ($row_usuarios['BLOQUEADO'] == 1) {
                        $table = $table . "<td style='vertical-align:middle;'>SI</td>";
                } else {
                        $table = $table . "<td style='vertical-align:middle;'>NO</td>";
                }
                $table = $table . "<td style='vertical-align:middle;'><button><a href='Usuarios.php?id_usuario_editar=" . $row_usuarios['ID_USUARIO'] . "&id_area_interna=" . $id_area_interna . "'><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></a></button></td>";
                $table = $table . "<td style='vertical-align:middle;'><button><a href='Usuarios.php?id_usuario_eliminar=" . $row_usuarios['ID_USUARIO'] . "&id_area_interna=" . $id_area_interna . "'><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></a></button></td>";
            $table = $table . "</tr>";
        }
        $info_resultado[0] = $nombre_compania;
        $info_resultado[1] = $table;
        $info_resultado[2] = $id_area_interna;
        echo json_encode($info_resultado);
        exit();
    }
?>