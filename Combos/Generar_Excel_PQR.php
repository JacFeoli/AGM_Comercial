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
    $sw = $_GET['sw'];
    if ($_SESSION['id_departamento'] != 0) {
        $and_id_departamento = "AND PQR.ID_COD_DPTO = " . $_SESSION['id_departamento'];
        if ($_SESSION['id_user'] == 34) {
            $and_id_municipio = "AND PQR.ID_COD_MPIO IN (518, 553) ";
        } else {
            $and_id_municipio = "AND PQR.ID_COD_MPIO = " . $_SESSION['id_municipio'];
        }
    } else {
        $and_id_departamento = " ";
        $and_id_municipio = " ";
    }
    switch ($sw) {
        case '4':
            if (strlen($_GET['id_mes']) == 1) {
                $filename = "Reporte P.Q.R. - Periodo " . $_GET['id_ano'] . "0" . $_GET['id_mes'] . ".xls";
            } else {
                $filename = "Reporte P.Q.R. - Periodo " . $_GET['id_ano'] . $_GET['id_mes'] . ".xls";
            }
            $query_select_info_mensual = mysqli_query($connection, "SELECT PQR.RADICADO,
                                                                               DEPT.NOMBRE AS DEPARTAMENTO,
                                                                               MUN.NOMBRE AS MUNICIPIO,
                                                                               PQR.BARRIO, PQR.TIPO_PERSONA, PQR.IDENTIFICACION,
                                                                               PQR.NOMBRES AS NOMBRES, PQR.APELLIDOS AS APELLIDOS,
                                                                               PQR.CARGO_USUARIO, PQR.DIRECCION, PQR.TELEFONO,
                                                                               PQR.CORREO_ELECTRONICO, PQR.ID_TIPO_ASUNTO,
                                                                               PQR.PETICION, PQR.REQUIERE_VISITA, PQR.OBSERVACIONES,
                                                                               PQR.OBSERVACIONES_RESPUESTA, PQR.ESTADO_PQR,
                                                                               PQR.FECHA_INGRESO, PQR.FECHA_RESPUESTA
                                                                          FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                                               municipios_visitas_2 MUN
                                                                         WHERE PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                           AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                           AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                           AND YEAR(PQR.FECHA_INGRESO) = " . $_GET['id_ano'] . "
                                                                           AND MONTH(PQR.FECHA_INGRESO) = " . $_GET['id_mes'] . "
                                                                           $and_id_departamento
                                                                           $and_id_municipio
                                                                         ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC ");
            $table = "";
            $table = $table . "<table class='table table-condensed table-hover'>";
            $table = $table . "<thead>";
            $table = $table . "<tr>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>RADICADO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>BARRIO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO PERSONA</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>IDENTIFICACION</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>USUARIO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARGO USUARIO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIRECCION</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TELFONO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>EMAIL</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO ASUNTO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PETICION</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>REQ. VISITA</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERVACIONES</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERVACIONES RESPUESTA</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA INGRESO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RESPUESTA</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO P.Q.R.</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIEMPO TRANSCURRIDO</th>";
            $table = $table . "</tr>";
            $table = $table . "</thead>";
            $table = $table . "<tbody>";
            while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                $table = $table . "<tr>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['RADICADO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['DEPARTAMENTO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['MUNICIPIO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['BARRIO'] . "</center></td>";
                switch ($row_info_mensual['TIPO_PERSONA']) {
                    case 1:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>NATURAL</center></td>";
                        break;
                    case 2:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>JURIDICA</center></td>";
                        break;
                }
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['IDENTIFICACION'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['NOMBRES'] . " " . $row_info_mensual['APELLIDOS'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['CARGO_USUARIO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['DIRECCION'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['TELEFONO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['CORREO_ELECTRONICO'] . "</center></td>";
                $query_select_tipo_asunto = mysqli_query($connection, "SELECT * FROM tipo_asuntos_pqr_2 WHERE ID_TIPO_ASUNTO = " . $row_info_mensual['ID_TIPO_ASUNTO']);
                $row_tipo_asunto = mysqli_fetch_array($query_select_tipo_asunto);
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_tipo_asunto['NOMBRE'] . "</center></td>";
                switch ($row_info_mensual['PETICION']) {
                    case 1:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>ESCRITA</center></td>";
                        break;
                    case 2:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>VERBAL</center></td>";
                        break;
                }
                switch ($row_info_mensual['REQUIERE_VISITA']) {
                    case 1:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>SI</center></td>";
                        break;
                    case 2:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>NO</center></td>";
                        break;
                }
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['OBSERVACIONES'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['OBSERVACIONES_RESPUESTA'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['FECHA_INGRESO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_mensual['FECHA_RESPUESTA'] . "</center></td>";
                switch ($row_info_mensual['ESTADO_PQR']) {
                    case 1:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>RECIBIDO</center></td>";
                        break;
                    case 2:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>RESUELTO</center></td>";
                        break;
                }
                $dias_restantes = 0;
                $fecha_ingreso = strtotime($row_info_mensual['FECHA_INGRESO']);
                $fecha_respuesta = strtotime($row_info_mensual['FECHA_RESPUESTA']);
                for ($fecha_ingreso; $fecha_ingreso <= $fecha_respuesta; $fecha_ingreso = strtotime('+1 day' . date('Y-m-d', $fecha_ingreso))) {
                    if ((strcmp(date('D', $fecha_ingreso), 'Sun') != 0) and (strcmp(date('D', $fecha_ingreso), 'Sat') != 0)) {
                        $dias_restantes = $dias_restantes + 1;
                    }
                }
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $dias_restantes . "</center></td>";
                $table = $table . "</tr>";
            }
            $table = $table . "</tbody>";
            $table = $table . "</table>";
            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo $table;
            break;
        case '5':
            $filename = "Reporte P.Q.R. - Rango " . $_GET['fecha_inicio'] . " & " . $_GET['fecha_fin'] . ".xls";
            $query_select_info_rango = mysqli_query($connection, "SELECT PQR.RADICADO,
                                                                             DEPT.NOMBRE AS DEPARTAMENTO,
                                                                             MUN.NOMBRE AS MUNICIPIO,
                                                                             PQR.BARRIO, PQR.TIPO_PERSONA, PQR.IDENTIFICACION,
                                                                             PQR.NOMBRES AS NOMBRES, PQR.APELLIDOS AS APELLIDOS,
                                                                             PQR.CARGO_USUARIO, PQR.DIRECCION, PQR.TELEFONO,
                                                                             PQR.CORREO_ELECTRONICO, PQR.ID_TIPO_ASUNTO,
                                                                             PQR.PETICION, PQR.REQUIERE_VISITA, PQR.OBSERVACIONES,
                                                                             PQR.OBSERVACIONES_RESPUESTA, PQR.ESTADO_PQR,
                                                                             PQR.FECHA_INGRESO, PQR.FECHA_RESPUESTA
                                                                        FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                                             municipios_visitas_2 MUN
                                                                       WHERE PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                         AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                         AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                         AND PQR.FECHA_INGRESO BETWEEN '" . $_GET['fecha_inicio'] . "' AND '" . $_GET['fecha_fin'] . "'
                                                                         $and_id_departamento
                                                                         $and_id_municipio
                                                                       ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC ");
            $table = "";
            $table = $table . "<table class='table table-condensed table-hover'>";
            $table = $table . "<thead>";
            $table = $table . "<tr>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>RADICADO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DEPARTAMENTO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>MUNICIPIO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>BARRIO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO PERSONA</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>IDENTIFICACION</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>USUARIO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>CARGO USUARIO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>DIRECCION</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TELFONO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>EMAIL</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIPO ASUNTO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>PETICION</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>REQ. VISITA</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERVACIONES</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>OBSERVACIONES RESPUESTA</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA INGRESO</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>FECHA RESPUESTA</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>ESTADO P.Q.R.</th>";
            $table = $table . "<th style='background-color: #4472C4; color: #FFFFFF;'>TIEMPO TRANSCURRIDO</th>";
            $table = $table . "</tr>";
            $table = $table . "</thead>";
            $table = $table . "<tbody>";
            while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                switch ($row_info_rango['ESTADO_PQR']) {
                    case "1":
                        $estado = "<span style='font-size: 11px; background-color: #0676C0; color: #FFFFFF; padding: 1px;' class='label label-success'><b>RC</b></span>";
                        break;
                    case "2":
                        $estado = "<span style='font-size: 11px; background-color: #00A328; color: #FFFFFF; padding: 1px;' class='label label-success'><b>RS</b></span>";
                        break;
                }
                $table = $table . "<tr>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['RADICADO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['DEPARTAMENTO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['MUNICIPIO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['BARRIO'] . "</center></td>";
                switch ($row_info_rango['TIPO_PERSONA']) {
                    case 1:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>NATURAL</center></td>";
                        break;
                    case 2:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>JURIDICA</center></td>";
                        break;
                }
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['IDENTIFICACION'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['NOMBRES'] . " " . $row_info_rango['APELLIDOS'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['CARGO_USUARIO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['DIRECCION'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['TELEFONO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['CORREO_ELECTRONICO'] . "</center></td>";
                $query_select_tipo_asunto = mysqli_query($connection, "SELECT * FROM tipo_asuntos_pqr_2 WHERE ID_TIPO_ASUNTO = " . $row_info_rango['ID_TIPO_ASUNTO']);
                $row_tipo_asunto = mysqli_fetch_array($query_select_tipo_asunto);
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_tipo_asunto['NOMBRE'] . "</center></td>";
                switch ($row_info_rango['PETICION']) {
                    case 1:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>ESCRITA</center></td>";
                        break;
                    case 2:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>VERBAL</center></td>";
                        break;
                }
                switch ($row_info_rango['REQUIERE_VISITA']) {
                    case 1:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>SI</center></td>";
                        break;
                    case 2:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>NO</center></td>";
                        break;
                }
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['OBSERVACIONES'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['OBSERVACIONES_RESPUESTA'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['FECHA_INGRESO'] . "</center></td>";
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $row_info_rango['FECHA_RESPUESTA'] . "</center></td>";
                switch ($row_info_rango['ESTADO_PQR']) {
                    case 1:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>RECIBIDO</center></td>";
                        break;
                    case 2:
                        $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>RESUELTO</center></td>";
                        break;
                }
                $dias_restantes = 0;
                $fecha_ingreso = strtotime($row_info_rango['FECHA_INGRESO']);
                $fecha_respuesta = strtotime($row_info_rango['FECHA_RESPUESTA']);
                for ($fecha_ingreso; $fecha_ingreso <= $fecha_respuesta; $fecha_ingreso = strtotime('+1 day' . date('Y-m-d', $fecha_ingreso))) {
                    if ((strcmp(date('D', $fecha_ingreso), 'Sun') != 0) and (strcmp(date('D', $fecha_ingreso), 'Sat') != 0)) {
                        $dias_restantes = $dias_restantes + 1;
                    }
                }
                $table = $table . "<td style='border: 1px solid; vertical-align: middle;'><center>" . $dias_restantes . "</center></td>";
                $table = $table . "</tr>";
            }
            $table = $table . "</tbody>";
            $table = $table . "</table>";
            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            echo $table;
            break;
    }
}
