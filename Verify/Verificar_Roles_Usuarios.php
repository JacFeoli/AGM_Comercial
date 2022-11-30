<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        // *** ESTA CONEXIÓN ES PARA LOCAL (PC) *** \\.
        //header("Location:/Juridica/Login_2.php");
        // *** ESTA CONEXIÓN ES PARA EL SERVIDOR *** \\.
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login_2.php");
    } else {
        $_SESSION['timeout'] = time();
        $id_tabla_eliminar = $_POST['id_tabla_eliminar'];
        $area_interna_rol = $_POST['area_interna_rol'];
        $query_select_roles_usuarios = mysqli_query($connection, "SELECT COUNT(*)
                                                                    FROM roles_2 R, roles_usuarios_2 RU
                                                                   WHERE R.ID_ROL = RU.ID_ROL
                                                                     AND R.ID_ROL = " . $id_tabla_eliminar . "
                                                                     AND R.ID_TIPO_COMPANIA = " . $area_interna_rol);
        $row_roles_usuarios = mysqli_fetch_array($query_select_roles_usuarios);
        $num_usuarios = $row_roles_usuarios['COUNT(*)'];
        echo $num_usuarios;
    }
?>