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
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        $nombre_rol = strtoupper($_POST['nombre_rol']);
        $area_interna_rol = strtoupper($_POST['area_interna_rol']);
        $abreviatura_rol = $_POST['abreviatura_rol'];
        if (isset($_POST['editar'])) {
            $nombre_rol_hidden = $_POST['nombre_rol_hidden'];
            $area_interna_rol_hidden = $_POST['area_interna_rol_hidden'];
            $abreviatura_rol_hidden = $_POST['abreviatura_rol_hidden'];
            //echo $nombre_rol . " - " . $area_interna_rol . " - " . $abreviatura_rol . " - " . $nombre_rol_hidden . " - " . $area_interna_rol_hidden . " - " . $abreviatura_rol_hidden;
        if ($nombre_rol != $nombre_rol_hidden) {
                //echo "Entro Nombre";
                $query_select_nombre_rol = mysqli_query($connection, "SELECT COUNT(*)
                                                                        FROM roles_2
                                                                       WHERE NOMBRE = '" . $nombre_rol . "'
                                                                         AND ID_TIPO_COMPANIA = " . $area_interna_rol);
                $row_nombre_rol = mysqli_fetch_array($query_select_nombre_rol);
                $num_nombre_rol = $row_nombre_rol['COUNT(*)'];
                echo $num_nombre_rol;
            } else {
                if ($abreviatura_rol != $abreviatura_rol_hidden) {
                    //echo "Entro Abreviatura";
                    $query_select_abreviatura_rol = mysqli_query($connection, "SELECT COUNT(*)
                                                                                 FROM roles_2
                                                                                WHERE ABREVIATURA_ROL = '" . $abreviatura_rol . "'
                                                                                  AND ID_TIPO_COMPANIA = " . $area_interna_rol);
                    $row_abreviatura_rol = mysqli_fetch_array($query_select_abreviatura_rol);
                    $num_abreviatura_rol = $row_abreviatura_rol['COUNT(*)'];
                    echo $num_abreviatura_rol;
                } else {
                    if ($area_interna_rol != $area_interna_rol_hidden) {
                        $query_select_area_interna_rol = mysqli_query($connection, "SELECT COUNT(*)
                                                                                      FROM roles_2
                                                                                     WHERE NOMBRE = '" . $nombre_rol . "'
                                                                                       AND ABREVIATURA_ROL = '" . $abreviatura_rol . "'
                                                                                       AND ID_TIPO_COMPANIA = " . $area_interna_rol);
                        $row_area_interna_rol = mysqli_fetch_array($query_select_area_interna_rol);
                        $num_area_interna_rol = $row_area_interna_rol['COUNT(*)'];
                        echo $num_area_interna_rol;
                    }
                }
            }
        } else {
            $query_select_rol = mysqli_query($connection, "SELECT COUNT(*)
                                                             FROM roles_2
                                                            WHERE NOMBRE = '" . $nombre_rol . "'
                                                              AND ID_TIPO_COMPANIA = " . $area_interna_rol);
            $row_rol = mysqli_fetch_array($query_select_rol);
            $num_rol = $row_rol['COUNT(*)'];
            if ($num_rol == 0) {
                $query_select_abreviatura = mysqli_query($connection, "SELECT COUNT(*)
                                                                        FROM roles_2
                                                                       WHERE ABREVIATURA_ROL = '" . $abreviatura_rol . "'
                                                                         AND ID_TIPO_COMPANIA = " . $area_interna_rol);
                $row_abreviatura = mysqli_fetch_array($query_select_abreviatura);
                $num_abreviatura = $row_abreviatura['COUNT(*)'];
                echo $num_abreviatura;
            } else {
                echo $num_rol;
            }
        }
    }
?>