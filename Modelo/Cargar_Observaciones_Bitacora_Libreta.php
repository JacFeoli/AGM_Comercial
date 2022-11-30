<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        $info_observaciones = array();
        $file_id = $_POST['file_id'];
        $id_tabla_observacion = $_POST['id_tabla_observacion'];
        $query_select_bitacora_libreta = mysqli_query($connection, "SELECT * "
                                                                 . "  FROM bitacora_libretas_2 "
                                                                 . " WHERE ID_TABLA = " . $id_tabla_observacion);
        $row_bitacora_libreta = mysqli_fetch_array($query_select_bitacora_libreta);
        $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE "
                                                            . "  FROM tipo_visitas_2 "
                                                            . " WHERE ID_TIPO_VISITA = " . $row_bitacora_libreta['ID_TIPO_VISITA']);
        $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
        $info_observaciones[0] = "Observación Tipo Visita: " . $row_tipo_visita['NOMBRE'];
        $info_observaciones[1] = "<p>" . nl2br($row_bitacora_libreta['OBSERVACIONES']) . "</p>";
        echo json_encode($info_observaciones);
        exit();
    }
?>