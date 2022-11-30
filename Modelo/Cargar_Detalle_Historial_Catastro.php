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
        $table = "";
        $id_tarifa = 0;
        $total_facturacion = 0;
        $info_historial = array();
        if (isset($_POST['historial_id']) != 0) {
            $historial_id = $_POST['historial_id'];
            $query_detalle_historial = mysqli_query($connection, $historial_id);
            $table = $table . "<div class='table-responsive'>";
                $table = $table . "<table class='table table-condensed table-hover table-detalle'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width='10%'>NIC</th>";
                            $table = $table . "<th width='40%'>NOMBRE</th>";
                            $table = $table . "<th width='25%'>DIRECCIÓN</th>";
                            $table = $table . "<th width='10%'>DEPARTAMENTO</th>";
                            $table = $table . "<th width='15%'>MUNICIPIO</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_detalle_historial = mysqli_fetch_assoc($query_detalle_historial)) {
                            $table = $table . "<tr>";
                                $table = $table . "<td>" . $row_detalle_historial['NIC'] . "</td>";
                                $table = $table . "<td>" . utf8_decode($row_detalle_historial['NOMBRE_PROPIETARIO']) . "</td>";
                                $table = $table . "<td>" . utf8_decode($row_detalle_historial['DIRECCION_VIVIENDA']) . "</td>";
                                $query_select_departamento = mysqli_query($connection, "SELECT NOMBRE "
                                                                                     . "  FROM departamentos_2 "
                                                                                     . " WHERE ID_DEPARTAMENTO = " . $row_detalle_historial['ID_COD_DPTO']);
                                $row_departamento = mysqli_fetch_array($query_select_departamento);
                                $table = $table . "<td>" . $row_departamento['NOMBRE'] . "</td>";
                                $query_select_municipios = mysqli_query($connection, "SELECT NOMBRE "
                                                                                   . "  FROM municipios_2 "
                                                                                   . " WHERE ID_DEPARTAMENTO = " . $row_detalle_historial['ID_COD_DPTO'] . " "
                                                                                   . "   AND ID_MUNICIPIO = " . $row_detalle_historial['ID_COD_MPIO']);
                                $row_municipios = mysqli_fetch_array($query_select_municipios);
                                $table = $table . "<td>" . $row_municipios['NOMBRE'] . "</td>";
                                $id_tarifa = $row_detalle_historial['ID_TARIFA'];
                            $table = $table . "</tr>";
                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
            $table = $table . "</div>";
            $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE ID_TARIFA = " . $id_tarifa);
            $row_tarifa = mysqli_fetch_array($query_select_tarifa);
            $info_historial[0] = "Detalle Nuevos Suministros. Tarifa: " . $row_tarifa['COD_TARIFA'] . " - " . $row_tarifa['NOMBRE'] . "<br/ >";
            $info_historial[1] = $table;
            echo json_encode($info_historial);
            exit();
        } else {
            $info_historial[0] = "Detalle Historial - Catastro: Error.";
            $info_historial[1] = "No existen datos para mostrar. Favor revisar información";
            echo json_encode($info_historial);
            exit();
        }
    }
?>