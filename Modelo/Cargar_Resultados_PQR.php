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
        if ($_SESSION['id_departamento'] != 0) {
            $and_id_departamento = "AND PQR.ID_COD_DPTO = " . $_SESSION['id_departamento'];
            $and_id_municipio = "AND PQR.ID_COD_MPIO = " . $_SESSION['id_municipio'];
        } else {
            $and_id_departamento = " ";
            $and_id_municipio = " ";
        }
        switch ($sw) {
            case '4':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $id_ano_mensual = $_POST['id_ano_mensual'];
                $id_mes_mensual = $_POST['id_mes_mensual'];
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_MENSUAL * ($page - 1);
                }
                $query_select_info_mensual = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                                               PQR.ID_PQR, PQR.NOMBRES AS NOMBRES,
                                                                               PQR.APELLIDOS AS APELLIDOS,
                                                                               PQR.IDENTIFICACION AS IDENTIFICACION,
                                                                               PQR.ESTADO_PQR, PQR.RADICADO
                                                                          FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                                               municipios_visitas_2 MUN
                                                                         WHERE PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                           AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                           AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                           AND YEAR(PQR.FECHA_INGRESO) = " . $id_ano_mensual . "
                                                                           AND MONTH(PQR.FECHA_INGRESO) = " . $id_mes_mensual . "
                                                                           $and_id_departamento
                                                                           $and_id_municipio
                                                                         ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC
                                                                         LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_MENSUAL);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO " . $id_ano_mensual . $id_mes_mensual . "&nbsp; <a onClick='generarReporteMensual(" . $sw . ", " . $id_ano_mensual . ", " . $id_mes_mensual . ")'><button><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a>" . "&nbsp; <a onClick='generarExcelMensual(" . $sw . ", " . $id_ano_mensual . ", " . $id_mes_mensual . ")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=15%>RADICADO</th>";
                            $table = $table . "<th width=10%>IDENTIFICACIÓN</th>";
                            $table = $table . "<th width=25%>USUARIO</th>";
                            $table = $table . "<th width=15%>DEPARTAMENTO</th>";
                            $table = $table . "<th width=20%>MUNICIPIO</th>";
                            $table = $table . "<th width=5%>ESTADO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_mensual = mysqli_fetch_assoc($query_select_info_mensual)) {
                            switch ($row_info_mensual['ESTADO_PQR']) {
                                case "1":
                                    $estado = "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>RC</b></span>";
                                    break;
                                case "2":
                                    $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>RS</b></span>";
                                    break;
                            }
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['RADICADO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['IDENTIFICACION'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['NOMBRES'] . " " . $row_info_mensual['APELLIDOS'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_mensual['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                $table = $table . "<p></p>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>RC</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECIBIDO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>RS</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RESUELTO.</span>";
                break;
            case '5':
                require_once('../Includes/Paginacion_Resultado_Mensual.php');
                $fecha_inicio = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_fin'];
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_MENSUAL * ($page - 1);
                }
                $query_select_info_rango = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                                             PQR.ID_PQR, PQR.NOMBRES AS NOMBRES,
                                                                             PQR.APELLIDOS AS APELLIDOS,
                                                                             PQR.IDENTIFICACION AS IDENTIFICACION,
                                                                             PQR.ESTADO_PQR, PQR.RADICADO
                                                                        FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                                             municipios_visitas_2 MUN
                                                                       WHERE PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                         AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                         AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                         AND PQR.FECHA_INGRESO BETWEEN '" . $fecha_inicio . "' AND '$fecha_fin'
                                                                         $and_id_departamento
                                                                         $and_id_municipio
                                                                       ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC
                                                                       LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_MENSUAL);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>RANGO " . $fecha_inicio . " & " . $fecha_fin . "&nbsp; <a onClick='generarReporteRango(" . $sw . ", \"" . $fecha_inicio . "\", \"" . $fecha_fin . "\")'><button><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a>" . "&nbsp; <a onClick='generarExcelRango(" . $sw . ", \"" . $fecha_inicio . "\", \"" . $fecha_fin . "\")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
                $table = $table . "<p></p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=15%>RADICADO</th>";
                            $table = $table . "<th width=10%>IDENTIFICACIÓN</th>";
                            $table = $table . "<th width=25%>USUARIO</th>";
                            $table = $table . "<th width=15%>DEPARTAMENTO</th>";
                            $table = $table . "<th width=20%>MUNICIPIO</th>";
                            $table = $table . "<th width=5%>ESTADO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_info_rango = mysqli_fetch_assoc($query_select_info_rango)) {
                            switch ($row_info_rango['ESTADO_PQR']) {
                                case "1":
                                    $estado = "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>RC</b></span>";
                                    break;
                                case "2":
                                    $estado = "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>RS</b></span>";
                                    break;
                            }
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['RADICADO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['IDENTIFICACION'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['NOMBRES'] . " " . $row_info_rango['APELLIDOS'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['DEPARTAMENTO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_info_rango['MUNICIPIO'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>$estado</td>";
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                $table = $table . "<p></p>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>RC</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECIBIDO.</span>";
                $table = $table . "&nbsp;<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>RS</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RESUELTO.</span>";
                break;
        }
        echo $table;
    }
?>