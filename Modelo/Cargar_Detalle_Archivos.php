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
        $archivo = $_POST['archivo'];
        $table = "";
        $info_detalle = array();
        switch ($archivo) {
            case 'fact':
                if (isset($_POST['detalle_id']) != 0) {
                    $detalle_id = $_POST['detalle_id'];
                    $query_select_nombre_archivo = mysqli_query($connection, "SELECT * FROM archivos_cargados_facturacion_2 WHERE ID_TABLA = " . $detalle_id);
                    $row_nombre_archivo = mysqli_fetch_array($query_select_nombre_archivo);
                    $departamento = $row_nombre_archivo['DEPARTAMENTO'];
                    $municipio = $row_nombre_archivo['MUNICIPIO'];
                    $nombre_archivo = $row_nombre_archivo['RUTA'];
                    $fecha_creacion = $row_nombre_archivo['FECHA_CREACION'];
                    $ano_archivo = $row_nombre_archivo['ANO_FACTURA'];
                    $mes_archivo = strtolower($row_nombre_archivo['MES_FACTURA']);
                    $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_nombre_archivo['ID_USUARIO']);
                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                    $usuario = $row_usuario['NOMBRE'];
                    $query_select_registros = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS REGISTROS "
                                                                      . "  FROM facturacion_" . $mes_archivo . $ano_archivo . "_2 "
                                                                      . " WHERE ID_TABLA_RUTA = " . $detalle_id);
                    $row_registros = mysqli_fetch_array($query_select_registros);
                    $registros = $row_registros['REGISTROS'];
                    $table = $table . "<div class='table-responsive'>";
                        $table = $table . "<p style='color: #003153;'>Nombre Archivo: " . $nombre_archivo . "</p>";
                        $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                            $table = $table . "<thead>";
                                $table = $table . "<tr>";
                                    $table = $table . "<th width=15%>DEPARTAMENTO</th>";
                                    $table = $table . "<th width=20%>MUNICIPIO</th>";
                                    $table = $table . "<th width=10%>REGISTROS</th>";
                                    $table = $table . "<th width=20%>USUARIO</th>";
                                    $table = $table . "<th width=15%>FECHA CREACIÓN</th>";
                                $table = $table . "</tr>";
                            $table = $table . "</thead>";
                            $table = $table . "<tbody>";
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $departamento . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $municipio . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $registros . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $usuario . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $fecha_creacion . "</td>";
                                $table = $table . "</tr>";
                            $table = $table . "</tbody>";
                        $table = $table . "</table>";
                    $table = $table . "</div>";
                    $info_detalle[0] = "Detalle Archivo Cargado.";
                    $info_detalle[1] = $table;
                } else {
                    $info_detalle[0] = "Detalle Archivo Cargado: Error.";
                    $info_detalle[1] = "No existen datos para mostrar. Favor revisar información";
                }
                break;
            case 'reca':
                if (isset($_POST['detalle_id']) != 0) {
                    $detalle_id = $_POST['detalle_id'];
                    $query_select_nombre_archivo = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 WHERE ID_TABLA = " . $detalle_id);
                    $row_nombre_archivo = mysqli_fetch_array($query_select_nombre_archivo);
                    $departamento = $row_nombre_archivo['DEPARTAMENTO'];
                    $municipio = $row_nombre_archivo['MUNICIPIO'];
                    $nombre_archivo = $row_nombre_archivo['RUTA'];
                    $fecha_creacion = $row_nombre_archivo['FECHA_CREACION'];
                    $ano_archivo = $row_nombre_archivo['ANO_FACTURA'];
                    $mes_archivo = strtolower($row_nombre_archivo['MES_FACTURA']);
                    $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_nombre_archivo['ID_USUARIO']);
                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                    $usuario = $row_usuario['NOMBRE'];
                    $query_select_registros = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS REGISTROS "
                                                                      . "  FROM recaudo_" . $mes_archivo . $ano_archivo . "_2 "
                                                                      . " WHERE ID_TABLA_RUTA = " . $detalle_id);
                    $row_registros = mysqli_fetch_array($query_select_registros);
                    $registros = $row_registros['REGISTROS'];
                    $table = $table . "<div class='table-responsive'>";
                        $table = $table . "<p style='color: #003153;'>Nombre Archivo: " . $nombre_archivo . "</p>";
                        $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                            $table = $table . "<thead>";
                                $table = $table . "<tr>";
                                    $table = $table . "<th width=15%>DEPARTAMENTO</th>";
                                    $table = $table . "<th width=20%>MUNICIPIO</th>";
                                    $table = $table . "<th width=10%>REGISTROS</th>";
                                    $table = $table . "<th width=20%>USUARIO</th>";
                                    $table = $table . "<th width=15%>FECHA CREACIÓN</th>";
                                $table = $table . "</tr>";
                            $table = $table . "</thead>";
                            $table = $table . "<tbody>";
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $departamento . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $municipio . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $registros . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $usuario . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $fecha_creacion . "</td>";
                                $table = $table . "</tr>";
                            $table = $table . "</tbody>";
                        $table = $table . "</table>";
                    $table = $table . "</div>";
                    $info_detalle[0] = "Detalle Archivo Cargado.";
                    $info_detalle[1] = $table;
                } else {
                    $info_detalle[0] = "Detalle Archivo Cargado: Error.";
                    $info_detalle[1] = "No existen datos para mostrar. Favor revisar información";
                }
                break;
            case 'cata':
                if (isset($_POST['detalle_id']) != 0) {
                    $detalle_id = $_POST['detalle_id'];
                    $query_select_nombre_archivo = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 WHERE ID_TABLA = " . $detalle_id);
                    $row_nombre_archivo = mysqli_fetch_array($query_select_nombre_archivo);
                    $departamento = $row_nombre_archivo['DEPARTAMENTO'];
                    $municipio = $row_nombre_archivo['MUNICIPIO'];
                    $nombre_archivo = $row_nombre_archivo['RUTA'];
                    $fecha_creacion = $row_nombre_archivo['FECHA_CREACION'];
                    $ano_archivo = $row_nombre_archivo['ANO_FACTURA'];
                    $mes_archivo = strtolower($row_nombre_archivo['MES_FACTURA']);
                    $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_nombre_archivo['ID_USUARIO']);
                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                    $usuario = $row_usuario['NOMBRE'];
                    $query_select_registros = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS REGISTROS "
                                                                      . "  FROM catastro_" . $mes_archivo . $ano_archivo . "_2 "
                                                                      . " WHERE ID_TABLA_RUTA = " . $detalle_id);
                    $row_registros = mysqli_fetch_array($query_select_registros);
                    $registros = $row_registros['REGISTROS'];
                    $table = $table . "<div class='table-responsive'>";
                        $table = $table . "<p style='color: #003153;'>Nombre Archivo: " . $nombre_archivo . "</p>";
                        $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                            $table = $table . "<thead>";
                                $table = $table . "<tr>";
                                    $table = $table . "<th width=15%>DEPARTAMENTO</th>";
                                    $table = $table . "<th width=20%>MUNICIPIO</th>";
                                    $table = $table . "<th width=10%>REGISTROS</th>";
                                    $table = $table . "<th width=20%>USUARIO</th>";
                                    $table = $table . "<th width=15%>FECHA CREACIÓN</th>";
                                $table = $table . "</tr>";
                            $table = $table . "</thead>";
                            $table = $table . "<tbody>";
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $departamento . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $municipio . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $registros . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $usuario . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $fecha_creacion . "</td>";
                                $table = $table . "</tr>";
                            $table = $table . "</tbody>";
                        $table = $table . "</table>";
                    $table = $table . "</div>";
                    $info_detalle[0] = "Detalle Archivo Cargado.";
                    $info_detalle[1] = $table;
                } else {
                    $info_detalle[0] = "Detalle Archivo Cargado: Error.";
                    $info_detalle[1] = "No existen datos para mostrar. Favor revisar información";
                }
                break;
            case 'nove':
                if (isset($_POST['detalle_id']) != 0) {
                    $detalle_id = $_POST['detalle_id'];
                    $query_select_nombre_archivo = mysqli_query($connection, "SELECT * FROM archivos_cargados_novedades_2 WHERE ID_TABLA = " . $detalle_id);
                    $row_nombre_archivo = mysqli_fetch_array($query_select_nombre_archivo);
                    $departamento = $row_nombre_archivo['DEPARTAMENTO'];
                    $municipio = $row_nombre_archivo['MUNICIPIO'];
                    $nombre_archivo = $row_nombre_archivo['RUTA'];
                    $fecha_creacion = $row_nombre_archivo['FECHA_CREACION'];
                    $ano_archivo = $row_nombre_archivo['ANO_FACTURA'];
                    $mes_archivo = strtolower($row_nombre_archivo['MES_FACTURA']);
                    $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_nombre_archivo['ID_USUARIO']);
                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                    $usuario = $row_usuario['NOMBRE'];
                    $query_select_registros = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS REGISTROS "
                                                                      . "  FROM novedades_" . $mes_archivo . $ano_archivo . "_2 "
                                                                      . " WHERE ID_TABLA_RUTA = " . $detalle_id);
                    $row_registros = mysqli_fetch_array($query_select_registros);
                    $registros = $row_registros['REGISTROS'];
                    $table = $table . "<div class='table-responsive'>";
                        $table = $table . "<p style='color: #003153;'>Nombre Archivo: " . $nombre_archivo . "</p>";
                        $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                            $table = $table . "<thead>";
                                $table = $table . "<tr>";
                                    $table = $table . "<th width=15%>DEPARTAMENTO</th>";
                                    $table = $table . "<th width=20%>MUNICIPIO</th>";
                                    $table = $table . "<th width=10%>REGISTROS</th>";
                                    $table = $table . "<th width=20%>USUARIO</th>";
                                    $table = $table . "<th width=15%>FECHA CREACIÓN</th>";
                                $table = $table . "</tr>";
                            $table = $table . "</thead>";
                            $table = $table . "<tbody>";
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $departamento . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $municipio . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $registros . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $usuario . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $fecha_creacion . "</td>";
                                $table = $table . "</tr>";
                            $table = $table . "</tbody>";
                        $table = $table . "</table>";
                    $table = $table . "</div>";
                    $info_detalle[0] = "Detalle Archivo Cargado.";
                    $info_detalle[1] = $table;
                } else {
                    $info_detalle[0] = "Detalle Archivo Cargado: Error.";
                    $info_detalle[1] = "No existen datos para mostrar. Favor revisar información";
                }
                break;
            case 'cartera':
                if (isset($_POST['detalle_id']) != 0) {
                    $detalle_id = $_POST['detalle_id'];
                    $query_select_nombre_archivo = mysqli_query($connection, "SELECT * FROM archivos_cargados_cartera_fallida_2 WHERE ID_TABLA = " . $detalle_id);
                    $row_nombre_archivo = mysqli_fetch_array($query_select_nombre_archivo);
                    $departamento = $row_nombre_archivo['DEPARTAMENTO'];
                    $municipio = $row_nombre_archivo['MUNICIPIO'];
                    $nombre_archivo = $row_nombre_archivo['RUTA'];
                    $fecha_creacion = $row_nombre_archivo['FECHA_CREACION'];
                    $ano_archivo = $row_nombre_archivo['ANO_FACTURA'];
                    $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_nombre_archivo['ID_USUARIO']);
                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                    $usuario = $row_usuario['NOMBRE'];
                    $query_select_registros = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS REGISTROS "
                                                                      . "  FROM cartera_fallida_" . $ano_archivo . "_2 "
                                                                      . " WHERE ID_TABLA_RUTA = " . $detalle_id);
                    $row_registros = mysqli_fetch_array($query_select_registros);
                    $registros = $row_registros['REGISTROS'];
                    $table = $table . "<div class='table-responsive'>";
                        $table = $table . "<p style='color: #003153;'>Nombre Archivo: " . $nombre_archivo . "</p>";
                        $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                            $table = $table . "<thead>";
                                $table = $table . "<tr>";
                                    $table = $table . "<th width=15%>DEPARTAMENTO</th>";
                                    $table = $table . "<th width=20%>MUNICIPIO</th>";
                                    $table = $table . "<th width=10%>REGISTROS</th>";
                                    $table = $table . "<th width=20%>USUARIO</th>";
                                    $table = $table . "<th width=15%>FECHA CREACIÓN</th>";
                                $table = $table . "</tr>";
                            $table = $table . "</thead>";
                            $table = $table . "<tbody>";
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $departamento . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $municipio . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $registros . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $usuario . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $fecha_creacion . "</td>";
                                $table = $table . "</tr>";
                            $table = $table . "</tbody>";
                        $table = $table . "</table>";
                    $table = $table . "</div>";
                    $info_detalle[0] = "Detalle Archivo Cargado.";
                    $info_detalle[1] = $table;
                } else {
                    $info_detalle[0] = "Detalle Archivo Cargado: Error.";
                    $info_detalle[1] = "No existen datos para mostrar. Favor revisar información";
                }
                break;
            case 'refact':
                if (isset($_POST['detalle_id']) != 0) {
                    $detalle_id = $_POST['detalle_id'];
                    $query_select_nombre_archivo = mysqli_query($connection, "SELECT * FROM archivos_cargados_refacturacion_2 WHERE ID_TABLA = " . $detalle_id);
                    $row_nombre_archivo = mysqli_fetch_array($query_select_nombre_archivo);
                    $departamento = $row_nombre_archivo['DEPARTAMENTO'];
                    $municipio = $row_nombre_archivo['MUNICIPIO'];
                    $nombre_archivo = $row_nombre_archivo['RUTA'];
                    $fecha_creacion = $row_nombre_archivo['FECHA_CREACION'];
                    $ano_archivo = $row_nombre_archivo['ANO_FACTURA'];
                    $mes_archivo = strtolower($row_nombre_archivo['MES_FACTURA']);
                    $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_nombre_archivo['ID_USUARIO']);
                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                    $usuario = $row_usuario['NOMBRE'];
                    $query_select_registros = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS REGISTROS "
                                                                      . "  FROM refacturacion_" . $mes_archivo . $ano_archivo . "_2 "
                                                                      . " WHERE ID_TABLA_RUTA = " . $detalle_id);
                    $row_registros = mysqli_fetch_array($query_select_registros);
                    $registros = $row_registros['REGISTROS'];
                    $table = $table . "<div class='table-responsive'>";
                        $table = $table . "<p style='color: #003153;'>Nombre Archivo: " . $nombre_archivo . "</p>";
                        $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                            $table = $table . "<thead>";
                                $table = $table . "<tr>";
                                    $table = $table . "<th width=15%>DEPARTAMENTO</th>";
                                    $table = $table . "<th width=20%>MUNICIPIO</th>";
                                    $table = $table . "<th width=10%>REGISTROS</th>";
                                    $table = $table . "<th width=20%>USUARIO</th>";
                                    $table = $table . "<th width=15%>FECHA CREACIÓN</th>";
                                $table = $table . "</tr>";
                            $table = $table . "</thead>";
                            $table = $table . "<tbody>";
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $departamento . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $municipio . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $registros . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $usuario . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $fecha_creacion . "</td>";
                                $table = $table . "</tr>";
                            $table = $table . "</tbody>";
                        $table = $table . "</table>";
                    $table = $table . "</div>";
                    $info_detalle[0] = "Detalle Archivo Cargado.";
                    $info_detalle[1] = $table;
                } else {
                    $info_detalle[0] = "Detalle Archivo Cargado: Error.";
                    $info_detalle[1] = "No existen datos para mostrar. Favor revisar información";
                }
                break;
            case 'oymri':
                if (isset($_POST['detalle_id']) != 0) {
                    $detalle_id = $_POST['detalle_id'];
                    $query_select_nombre_archivo = mysqli_query($connection, "SELECT * FROM archivos_cargados_oymri_2 WHERE ID_TABLA = " . $detalle_id);
                    $row_nombre_archivo = mysqli_fetch_array($query_select_nombre_archivo);
                    $ano_factura = $row_nombre_archivo['ANO_FACTURA'];
                    $periodo = $row_nombre_archivo['PERIODO'];
                    $nombre_archivo = $row_nombre_archivo['RUTA'];
                    $fecha_creacion = $row_nombre_archivo['FECHA_CREACION'];
                    $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_nombre_archivo['ID_USUARIO']);
                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                    $usuario = $row_usuario['NOMBRE'];
                    $query_select_registros = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS REGISTROS "
                                                                      . "  FROM facturacion_oymri_" . $ano_factura . "_2 "
                                                                      . " WHERE ID_TABLA_RUTA = " . $detalle_id);
                    $row_registros = mysqli_fetch_array($query_select_registros);
                    $registros = $row_registros['REGISTROS'];
                    $table = $table . "<div class='table-responsive'>";
                        $table = $table . "<p style='color: #003153;'>Nombre Archivo: " . $nombre_archivo . "</p>";
                        $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                            $table = $table . "<thead>";
                                $table = $table . "<tr>";
                                    $table = $table . "<th width=15%>AÑO</th>";
                                    $table = $table . "<th width=20%>PERIODO</th>";
                                    $table = $table . "<th width=10%>REGISTROS</th>";
                                    $table = $table . "<th width=20%>USUARIO</th>";
                                    $table = $table . "<th width=15%>FECHA CREACIÓN</th>";
                                $table = $table . "</tr>";
                            $table = $table . "</thead>";
                            $table = $table . "<tbody>";
                                $table = $table . "<tr>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $ano_factura . "</td>";
                                    switch ($periodo) {
                                        case '01':
                                            $table = $table . "<td style='vertical-align: middle;'>ENERO</td>";
                                            break;
                                        case '02':
                                            $table = $table . "<td style='vertical-align: middle;'>FEBRERO</td>";
                                            break;
                                        case '03':
                                            $table = $table . "<td style='vertical-align: middle;'>MARZO</td>";
                                            break;
                                        case '04':
                                            $table = $table . "<td style='vertical-align: middle;'>ABRIL</td>";
                                            break;
                                        case '05':
                                            $table = $table . "<td style='vertical-align: middle;'>MAYO</td>";
                                            break;
                                        case '06':
                                            $table = $table . "<td style='vertical-align: middle;'>JUNIO</td>";
                                            break;
                                        case '07':
                                            $table = $table . "<td style='vertical-align: middle;'>JULIO</td>";
                                            break;
                                        case '08':
                                            $table = $table . "<td style='vertical-align: middle;'>AGOSTO</td>";
                                            break;
                                        case '09':
                                            $table = $table . "<td style='vertical-align: middle;'>SEPTIEMBRE</td>";
                                            break;
                                        case '10':
                                            $table = $table . "<td style='vertical-align: middle;'>OCTUBRE</td>";
                                            break;
                                        case '11':
                                            $table = $table . "<td style='vertical-align: middle;'>NOVIEMBRE</td>";
                                            break;
                                        case '12':
                                            $table = $table . "<td style='vertical-align: middle;'>DICIEMBRE</td>";
                                            break;
                                    }
                                    $table = $table . "<td style='vertical-align: middle;'>" . $registros . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $usuario . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $fecha_creacion . "</td>";
                                $table = $table . "</tr>";
                            $table = $table . "</tbody>";
                        $table = $table . "</table>";
                    $table = $table . "</div>";
                    $info_detalle[0] = "Detalle Archivo Cargado.";
                    $info_detalle[1] = $table;
                } else {
                    $info_detalle[0] = "Detalle Archivo Cargado: Error.";
                    $info_detalle[1] = "No existen datos para mostrar. Favor revisar información";
                }
                break;
            case 'fact_comer':
                if (isset($_POST['detalle_id']) != 0) {
                    $detalle_id = $_POST['detalle_id'];
                    $query_select_nombre_archivo = mysqli_query($connection, "SELECT * FROM archivos_cargados_fact_comer_2 WHERE ID_TABLA = " . $detalle_id);
                    $row_nombre_archivo = mysqli_fetch_array($query_select_nombre_archivo);
                    $id_comercializador = $row_nombre_archivo['ID_COMERCIALIZADOR'];
                    $nombre_archivo = $row_nombre_archivo['RUTA'];
                    $fecha_creacion = $row_nombre_archivo['FECHA_CREACION'];
                    $ano_archivo = $row_nombre_archivo['ANO_FACTURA'];
                    $mes_archivo = strtolower($row_nombre_archivo['PERIODO']);
                    $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_nombre_archivo['ID_USUARIO']);
                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                    $usuario = $row_usuario['NOMBRE'];
                    $query_select_registros = mysqli_query($connection, "SELECT COUNT(ID_TABLA) AS REGISTROS "
                                                                      . "  FROM detalle_fact_comer_" . $ano_archivo . "_2 "
                                                                      . " WHERE ID_TABLA_RUTA = " . $detalle_id);
                    $row_registros = mysqli_fetch_array($query_select_registros);
                    $registros = $row_registros['REGISTROS'];
                    $table = $table . "<div class='table-responsive'>";
                        $table = $table . "<p style='color: #003153;'>Nombre Archivo: " . $nombre_archivo . "</p>";
                        $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                            $table = $table . "<thead>";
                                $table = $table . "<tr>";
                                    $table = $table . "<th width=55%>COMERCIALIZADOR</th>";
                                    $table = $table . "<th width=10%>REGISTROS</th>";
                                    $table = $table . "<th width=20%>USUARIO</th>";
                                    $table = $table . "<th width=15%>FECHA CREACIÓN</th>";
                                $table = $table . "</tr>";
                            $table = $table . "</thead>";
                            $table = $table . "<tbody>";
                                $table = $table . "<tr>";
                                    $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = '$id_comercializador'");
                                    $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                    $table = $table . "<td style='vertical-align: middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $registros . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $usuario . "</td>";
                                    $table = $table . "<td style='vertical-align: middle;'>" . $fecha_creacion . "</td>";
                                $table = $table . "</tr>";
                            $table = $table . "</tbody>";
                        $table = $table . "</table>";
                    $table = $table . "</div>";
                    $info_detalle[0] = "Detalle Archivo Cargado.";
                    $info_detalle[1] = $table;
                } else {
                    $info_detalle[0] = "Detalle Archivo Cargado: Error.";
                    $info_detalle[1] = "No existen datos para mostrar. Favor revisar información";
                }
                break;
        }
        echo json_encode($info_detalle);
        exit();
    }
?>