<?php
session_start();
require_once('../Includes/Config.php');
require_once('../Includes/Paginacion_PQR.php');
if ($_SESSION['timeout'] + 60 * 60 < time()) {
    session_unset();
    session_destroy();
    $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location:$ruta/Login.php");
} else {
    $_SESSION['timeout'] = time();
    if ($_POST['sw'] == 1) {
        if (isset($_POST['busqueda_pqr']) != "") {
            if ($_POST['busqueda_pqr'] != "") {
                $busqueda_pqr = " WHERE CONCAT( PQR.NOMBRES, ' ', PQR.APELLIDOS ) LIKE '%" . $_POST['busqueda_pqr'] . "%' ";
            } else {
                $busqueda_pqr = " WHERE PQR.NOMBRES <> ''";
            }
        } else {
            $busqueda_pqr = " WHERE PQR.NOMBRES <> ''";
        }
    } else {
        $busqueda_pqr = " WHERE PQR.NOMBRES <> ''";
    }
    $page = $_POST['page'];
    if ($page == 1) {
        $pageLimit = 0;
    } else {
        $pageLimit = PAGE_PER_PQR * ($page - 1);
    }
    if ($_SESSION['id_departamento'] != 0) {
        if ($_SESSION['id_user'] == 34) {
            $query_pqr = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                           PQR.ID_PQR, PQR.NOMBRES AS NOMBRES,
                                                           PQR.APELLIDOS AS APELLIDOS,
                                                           PQR.IDENTIFICACION AS IDENTIFICACION,
                                                           PQR.ESTADO_PQR, PQR.RADICADO,
                                                           PQR.FECHA_VENCIMIENTO
                                                      FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                           municipios_visitas_2 MUN
                                                      $busqueda_pqr
                                                       AND PQR.ID_COD_DPTO = " . $_SESSION['id_departamento'] . "
                                                       AND PQR.ID_COD_MPIO IN (518, 553) 
                                                       AND PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_PQR);
        } else {
            $query_pqr = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                           PQR.ID_PQR, PQR.NOMBRES AS NOMBRES,
                                                           PQR.APELLIDOS AS APELLIDOS,
                                                           PQR.IDENTIFICACION AS IDENTIFICACION,
                                                           PQR.ESTADO_PQR, PQR.RADICADO,
                                                           PQR.FECHA_VENCIMIENTO
                                                      FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                           municipios_visitas_2 MUN
                                                      $busqueda_pqr
                                                       AND PQR.ID_COD_DPTO = " . $_SESSION['id_departamento'] . "
                                                       AND PQR.ID_COD_MPIO = " . $_SESSION['id_municipio'] . "
                                                       AND PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_PQR);
        }
    } else {
        $query_pqr = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                           PQR.ID_PQR, PQR.NOMBRES AS NOMBRES,
                                                           PQR.APELLIDOS AS APELLIDOS,
                                                           PQR.IDENTIFICACION AS IDENTIFICACION,
                                                           PQR.ESTADO_PQR, PQR.RADICADO,
                                                           PQR.FECHA_VENCIMIENTO
                                                      FROM pqr_2 PQR, departamentos_visitas_2 DEPT,
                                                           municipios_visitas_2 MUN
                                                      $busqueda_pqr
                                                       AND PQR.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND PQR.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE, PQR.FECHA_INGRESO DESC
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_PQR);
    }
    $table = "";
    $tableDiasRestantes = "";
    //$hoy = strtotime(date('Y-m-d'));
    $hoy = new DateTime(date('Y-m-d'));
    while ($row_pqr = mysqli_fetch_assoc($query_pqr)) {
        $table = $table . "<tr>";
        switch ($row_pqr['ESTADO_PQR']) {
            case "1":
                $dias_restantes = 0;
                $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>RC</b></span></td>";
                //$fecha_vencimiento = strtotime($row_pqr['FECHA_VENCIMIENTO']);
                //$dias_restantes = round(($fecha_vencimiento - $hoy) / 60 / 60 / 24);
                $fecha_vencimiento = new DateTime($row_pqr['FECHA_VENCIMIENTO']);
                $fecha_vencimiento->modify('+1 day');
                $interval = $fecha_vencimiento->diff($hoy);
                $dias_restantes = $interval->days;
                $periodo = new DatePeriod($hoy, new DateInterval('P1D'), $fecha_vencimiento);
                $dias_festivos = array(
                    '2023-01-09', '2023-03-20', '2023-04-06', '2023-04-07', '2023-05-01',
                    '2023-05-22', '2023-06-12', '2023-06-19', '2023-07-03', '2023-07-20', '2023-08-07',
                    '2023-08-21', '2023-10-16', '2023-11-06', '2023-11-13', '2023-12-08', '2023-12-25'
                );
                foreach ($periodo as $dt) {
                    $curr = $dt->format('D');
                    if ($curr == 'Sat' || $curr == 'Sun') {
                        $dias_restantes--;
                    } elseif (in_array($dt->format('Y-m-d'), $dias_festivos)) {
                        $dias_restantes--;
                    }
                }
                if ($hoy <= $fecha_vencimiento) {
                    if ($dias_restantes >= 15) {
                        $tableDiasRestantes = "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>" . $dias_restantes . " DÍAS</b></span></td>";
                    } else {
                        if ($dias_restantes >= 8 && $dias_restantes < 15) {
                            $tableDiasRestantes = "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #F6ED0E; color: #323232' class='label label-warning'><b>" . $dias_restantes . " DÍAS</b></span></td>";
                        } else {
                            if ($dias_restantes >= 0 && $dias_restantes < 8) {
                                $tableDiasRestantes = "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-danger'><b>" . $dias_restantes . " DÍAS</b></span></td>";
                            } else {
                                $tableDiasRestantes = "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-danger'><b>VENCIDA!!</b></span></td>";
                            }
                        }
                    }
                } else {
                    $tableDiasRestantes = "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-danger'><b>VENCIDA!!</b></span></td>";
                }
                break;
            case "2":
                $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>RS</b></span></td>";
                $tableDiasRestantes = "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #00A328;' class='label label-danger'><b>-</b></span></td>";
                break;
        }
        $table = $table . "<td style='vertical-align:middle;'>" . $row_pqr['RADICADO'] . "</td>";
        $table = $table . "<td style='vertical-align:middle;'>" . $row_pqr['NOMBRES'] . " " . $row_pqr['APELLIDOS'] . "</td>";
        $table = $table . "<td style='vertical-align:middle;'>" . $row_pqr['MUNICIPIO'] . "</td>";
        $table = $table . "<td style='vertical-align:middle;'>" . $row_pqr['FECHA_VENCIMIENTO'] . "</td>";
        $table = $table . $tableDiasRestantes;
        $table = $table . "<td style='vertical-align:middle;'><a href='P_Q_R.php?id_pqr_editar=" . $row_pqr['ID_PQR'] . "'><button><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></a></td>";
        $table = $table . "<td style='vertical-align:middle;'><a href='P_Q_R.php?id_pqr_eliminar=" . $row_pqr['ID_PQR'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
        $table = $table . "</tr>";
    }
    $info_resultado = array();
    $info_resultado[0] = $table;
    echo json_encode($info_resultado);
    exit();
}
