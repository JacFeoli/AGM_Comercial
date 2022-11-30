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
                $periodos = "";
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
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    } else {
                        $or = $or . " OR (BL.ID_MUNICIPIO_LIBRETA = ML.ID_MUNICIPIO_LIBRETA AND YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . $query_departamento . " " . $query_municipio . ") ";
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    }
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_MUNICIPIO * ($page - 1);
                }
                $query_select_info_municipio = mysqli_query($connection, "SELECT *, DATE(BL.FECHA_VISITA) AS FECHA_VISITA "
                                                                       . "  FROM bitacora_libretas_2 BL, municipios_libreta_2 ML "
                                                                       . $where
                                                                       . $or
                                                                       . " ORDER BY BL.FECHA_VISITA DESC "
                                                                       . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_MUNICIPIO);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO " . substr($periodos, 0, -3) . "&nbsp; <a onClick='generarReporteMunicipio(" . $sw . ", " . $departamento . ", " . $municipio . ", " . json_encode($myAnos) . ", " . json_encode($myMes) . ")'><button><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=12%>DEPARTAMENTO</th>";
                            $table = $table . "<th width=18%>MUNICIPIO</th>";
                            $table = $table . "<th width=60%>TIPO VISITA</th>";
                            $table = $table . "<th width=10%>FECHA VISITA</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_municipio = mysqli_fetch_assoc($query_select_info_municipio)) {
                            $table = $table . "<tr>";
                                $query_select_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                          . "  FROM municipios_libreta_2 "
                                                                                          . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_info_municipio['ID_MUNICIPIO_LIBRETA']);
                                $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE AS DEPARTAMENTO "
                                                                                     . "  FROM departamentos_visitas_2 "
                                                                                     . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
                                $row_departamento = mysqli_fetch_array($query_select_departamento);
                                $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE AS MUNICIPIO "
                                                                                  . "  FROM municipios_visitas_2 "
                                                                                  . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                  . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                $row_municipio = mysqli_fetch_array($query_select_municipio);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_departamento['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_municipio['MUNICIPIO'] . "</td>";
                                $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE AS TIPO_VISITA FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $row_info_municipio['ID_TIPO_VISITA']);
                                $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_tipo_visita['TIPO_VISITA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_municipio['FECHA_VISITA'] . "</td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                break;
            case '1':
                require_once('../Includes/Paginacion_Resultado_Usuario_Bitacora.php');
                $sw2 = 0;
                $or = "";
                $where = "";
                $periodos = "";
                $myAnos = explode(', ', $_POST['id_ano_bitacora']);
                $myMes = explode(', ', $_POST['id_mes_bitacora']);
                $usuario_bitacora = $_POST['usuario_bitacora'];
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_USUARIO = " . $usuario_bitacora . ") ";
                        $sw2 = 1;
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    } else {
                        $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_USUARIO = " . $usuario_bitacora . ") ";
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    }
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_USUARIO_BITACORA * ($page - 1);
                }
                $query_select_info_usuario = mysqli_query($connection, "SELECT *, DATE(BL.FECHA_VISITA) AS FECHA_VISITA "
                                                                     . "  FROM bitacora_libretas_2 BL "
                                                                     . $where
                                                                     . $or
                                                                     . " ORDER BY BL.FECHA_VISITA DESC "
                                                                     . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_USUARIO_BITACORA);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO " . substr($periodos, 0, -3) . "&nbsp; <a onClick='generarReporteUsuario(" . $sw . ", " . $usuario_bitacora . ", " . json_encode($myAnos) . ", " . json_encode($myMes) . ")'><button><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=23%>USUARIO</th>";
                            $table = $table . "<th width=12%>DEPARTAMENTO</th>";
                            $table = $table . "<th width=18%>MUNICIPIO</th>";
                            $table = $table . "<th width=37%>TIPO VISITA</th>";
                            $table = $table . "<th width=10%>FECHA VISITA</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_usuario_bitacora = mysqli_fetch_assoc($query_select_info_usuario)) {
                            $table = $table . "<tr>";
                                $query_select_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                          . "  FROM municipios_libreta_2 "
                                                                                          . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_info_usuario_bitacora['ID_MUNICIPIO_LIBRETA']);
                                $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE AS DEPARTAMENTO "
                                                                                     . "  FROM departamentos_visitas_2 "
                                                                                     . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
                                $row_departamento = mysqli_fetch_array($query_select_departamento);
                                $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE AS MUNICIPIO "
                                                                                  . "  FROM municipios_visitas_2 "
                                                                                  . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                  . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                $row_municipio = mysqli_fetch_array($query_select_municipio);
                                $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE AS USUARIO FROM usuarios_2 WHERE ID_USUARIO = " . $row_info_usuario_bitacora['ID_USUARIO']);
                                $row_usuario = mysqli_fetch_array($query_select_usuario);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_usuario['USUARIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_departamento['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_municipio['MUNICIPIO'] . "</td>";
                                $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE AS TIPO_VISITA FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $row_info_usuario_bitacora['ID_TIPO_VISITA']);
                                $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_tipo_visita['TIPO_VISITA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_usuario_bitacora['FECHA_VISITA'] . "</td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                break;
            case '2':
                require_once('../Includes/Paginacion_Resultado_Tipo_Visita.php');
                $sw2 = 0;
                $or = "";
                $where = "";
                $periodos = "";
                $myAnos = explode(', ', $_POST['id_ano_tipo_visita']);
                $myMes = explode(', ', $_POST['id_mes_tipo_visita']);
                $tipo_visita = $_POST['tipo_visita'];
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_TIPO_VISITA = " . $tipo_visita . ") ";
                        $sw2 = 1;
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    } else {
                        $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_TIPO_VISITA = " . $tipo_visita . ") ";
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    }
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_TIPO_VISITA * ($page - 1);
                }
                $query_select_info_tipo_visita = mysqli_query($connection, "SELECT *, DATE(BL.FECHA_VISITA) AS FECHA_VISITA "
                                                                         . "  FROM bitacora_libretas_2 BL "
                                                                         . $where
                                                                         . $or
                                                                         . " ORDER BY BL.FECHA_VISITA DESC "
                                                                         . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_TIPO_VISITA);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO " . substr($periodos, 0, -3) . "&nbsp; <a onClick='generarReporteTipoVisita(" . $sw . ", " . $tipo_visita . ", " . json_encode($myAnos) . ", " . json_encode($myMes) . ")'><button><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=60%>TIPO VISITA</th>";
                            $table = $table . "<th width=12%>DEPARTAMENTO</th>";
                            $table = $table . "<th width=18%>MUNICIPIO</th>";
                            $table = $table . "<th width=10%>FECHA VISITA</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_tipo_visita = mysqli_fetch_assoc($query_select_info_tipo_visita)) {
                            $table = $table . "<tr>";
                                $query_select_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                          . "  FROM municipios_libreta_2 "
                                                                                          . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_info_tipo_visita['ID_MUNICIPIO_LIBRETA']);
                                $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE AS DEPARTAMENTO "
                                                                                     . "  FROM departamentos_visitas_2 "
                                                                                     . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
                                $row_departamento = mysqli_fetch_array($query_select_departamento);
                                $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE AS MUNICIPIO "
                                                                                  . "  FROM municipios_visitas_2 "
                                                                                  . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                  . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                $row_municipio = mysqli_fetch_array($query_select_municipio);
                                $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE AS TIPO_VISITA FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $row_info_tipo_visita['ID_TIPO_VISITA']);
                                $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_tipo_visita['TIPO_VISITA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_departamento['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_municipio['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_tipo_visita['FECHA_VISITA'] . "</td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                break;
            case '3':
                
                break;
            case '4':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $sw2 = 0;
                $or = "";
                $where = "";
                $periodos = "";
                $myAnos = explode(', ', $_POST['id_ano_mensual']);
                $myMes = explode(', ', $_POST['id_mes_mensual']);
                foreach ($myAnos as $index => $ano) {
                    if ($sw2 == 0) {
                        $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . ") ";
                        $sw2 = 1;
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    } else {
                        $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . ") ";
                        $periodos = $periodos . $ano . $myMes[$index] . " - ";
                    }
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_MENSUAL * ($page - 1);
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT *, DATE(BL.FECHA_VISITA) AS FECHA_VISITA "
                                                                     . "  FROM bitacora_libretas_2 BL "
                                                                     . $where
                                                                     . $or
                                                                     . " ORDER BY BL.FECHA_VISITA DESC "
                                                                     . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_MENSUAL);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO " . substr($periodos, 0, -3) . "&nbsp; <a onClick='generarReporteMensual(" . $sw . ", " . json_encode($myAnos) . ", " . json_encode($myMes) . ")'><button><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=12%>DEPARTAMENTO</th>";
                            $table = $table . "<th width=18%>MUNICIPIO</th>";
                            $table = $table . "<th width=60%>TIPO VISITA</th>";
                            $table = $table . "<th width=10%>FECHA VISITA</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                            $table = $table . "<tr>";
                                $query_select_municipio_libreta = mysqli_query($connection, "SELECT * "
                                                                                          . "  FROM municipios_libreta_2 "
                                                                                          . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_info_mensual['ID_MUNICIPIO_LIBRETA']);
                                $row_municipio_libreta = mysqli_fetch_array($query_select_municipio_libreta);
                                $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE AS DEPARTAMENTO "
                                                                                     . "  FROM departamentos_visitas_2 "
                                                                                     . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO']);
                                $row_departamento = mysqli_fetch_array($query_select_departamento);
                                $query_select_municipio = mysqli_query($connection, "SELECT NOMBRE AS MUNICIPIO "
                                                                                  . "  FROM municipios_visitas_2 "
                                                                                  . " WHERE ID_DEPARTAMENTO = " . $row_municipio_libreta['ID_DEPARTAMENTO'] . ""
                                                                                  . "   AND ID_MUNICIPIO = " . $row_municipio_libreta['ID_MUNICIPIO']);
                                $row_municipio = mysqli_fetch_array($query_select_municipio);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_departamento['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_municipio['MUNICIPIO'] . "</td>";
                                $query_select_tipo_visita = mysqli_query($connection, "SELECT NOMBRE AS TIPO_VISITA FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $row_info_mensual['ID_TIPO_VISITA']);
                                $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_tipo_visita['TIPO_VISITA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['FECHA_VISITA'] . "</td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                break;
        }
        echo $table;
    }
?>