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
        $sw = $_POST['sw'];
        switch ($sw) {
            case '0':
                require_once('../Includes/Paginacion_Resultado_Municipio.php');
                $sw2 = 0;
                $or = "";
                $where = "";
                $myAnos = explode(', ', $_POST['id_ano_municipio']);
                $myMes = explode(', ', $_POST['id_mes_municipio']);
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                if ($departamento == "") {
                    $query_departamento = " ";
                } else {
                    $query_departamento = " AND ML.ID_DEPARTAMENTO = " . $departamento . " ";
                }
                if ($municipio == "") {
                    $query_municipio = "";
                } else {
                    $query_municipio = " AND ML.ID_MUNICIPIO = " . $municipio . " ";
                }
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (BL.ID_MUNICIPIO_LIBRETA = ML.ID_MUNICIPIO_LIBRETA AND YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . $query_departamento . " " . $query_municipio . ") ";
                        $sw2 = 1;
                    } else {
                        $or = $or . " OR (BL.ID_MUNICIPIO_LIBRETA = ML.ID_MUNICIPIO_LIBRETA AND YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . $query_departamento . " " . $query_municipio . ") ";
                    }
                }
                $query_select_info_municipio = mysqli_query($connection, "SELECT * "
                                                                       . "  FROM bitacora_libretas_2 BL, municipios_libreta_2 ML "
                                                                       . $where
                                                                       . $or
                                                                       . " ORDER BY BL.FECHA_VISITA DESC ");
                $count_resultado_municipio = mysqli_num_rows($query_select_info_municipio);
                $info_pagination = array();
                if ($count_resultado_municipio > 0) {
                    $paginacion_count = getPagination($count_resultado_municipio);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '1':
                require_once('../Includes/Paginacion_Resultado_Usuario_Bitacora.php');
                $sw2 = 0;
                $or = "";
                $where = "";
                $myAnos = explode(', ', $_POST['id_ano_bitacora']);
                $myMes = explode(', ', $_POST['id_mes_bitacora']);
                $usuario_bitacora = $_POST['usuario_bitacora'];
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_USUARIO = " . $usuario_bitacora . ") ";
                        $sw2 = 1;
                    } else {
                        $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_USUARIO = " . $usuario_bitacora . ") ";
                    }
                }
                $query_select_info_usuario = mysqli_query($connection, "SELECT * "
                                                                     . "  FROM bitacora_libretas_2 BL "
                                                                     . $where
                                                                     . $or
                                                                     . " ORDER BY BL.FECHA_VISITA DESC ");
                $count_resultado_usuario = mysqli_num_rows($query_select_info_usuario);
                $info_pagination = array();
                if ($count_resultado_usuario > 0) {
                    $paginacion_count = getPagination($count_resultado_usuario);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '2':
                require_once('../Includes/Paginacion_Resultado_Tipo_Visita.php');
                $sw2 = 0;
                $or = "";
                $where = "";
                $myAnos = explode(', ', $_POST['id_ano_tipo_visita']);
                $myMes = explode(', ', $_POST['id_mes_tipo_visita']);
                $tipo_visita = $_POST['tipo_visita'];
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_TIPO_VISITA = " . $tipo_visita . ") ";
                        $sw2 = 1;
                    } else {
                        $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_TIPO_VISITA = " . $tipo_visita . ") ";
                    }
                }
                $query_select_info_tipo_visita = mysqli_query($connection, "SELECT * "
                                                                            . "  FROM bitacora_libretas_2 BL "
                                                                            . $where
                                                                            . $or
                                                                            . " ORDER BY BL.FECHA_VISITA DESC ");
                $count_resultado_tipo_visita = mysqli_num_rows($query_select_info_tipo_visita);
                $info_pagination = array();
                if ($count_resultado_tipo_visita > 0) {
                    $paginacion_count = getPagination($count_resultado_tipo_visita);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
            case '3':
                
                break;
            case '4':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $sw2 = 0;
                $or = "";
                $where = "";
                $myAnos = explode(', ', $_POST['id_ano_mensual']);
                $myMes = explode(', ', $_POST['id_mes_mensual']);
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . ") ";
                        $sw2 = 1;
                    } else {
                        $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . ") ";
                    }
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT * "
                                                                     . "  FROM bitacora_libretas_2 BL "
                                                                     . $where
                                                                     . $or
                                                                     . " ORDER BY BL.FECHA_VISITA DESC ");
                $count_resultado_mensual = mysqli_num_rows($query_select_info_mensual);
                $info_pagination = array();
                if ($count_resultado_mensual > 0) {
                    $paginacion_count = getPagination($count_resultado_mensual);
                    $info_pagination[0] = $paginacion_count;
                } else {
                    $info_pagination[0] = 1;
                }
                break;
        }
        echo json_encode($info_pagination);
        exit();
    }
?>