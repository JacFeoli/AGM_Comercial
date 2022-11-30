<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Reca_Esp.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_recaudo']) != "") {
                if ($_POST['busqueda_recaudo'] != "") {
                    $busqueda_recaudo = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_recaudo'] . "%' ";
                } else {
                    $busqueda_recaudo = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_recaudo = " WHERE MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_recaudo = " WHERE MUN.NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_RECA_ESP * ($page - 1);
        }
        $query_reca_esp = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                            RC.ID_RECAUDO, FC.ID_FACTURACION,
                                                            C.ID_COMERCIALIZADOR AS ID_COMERCIALIZADOR,
                                                            C.NOMBRE AS COMERCIALIZADOR, FC.PERIODO_FACTURA,
                                                            FC.VALOR_FACTURA, RC.VALOR_RECAUDO, RC.ESTADO_RECAUDO
                                                       FROM recaudo_comercializadores_2 RC,
                                                            facturacion_comercializadores_2 FC, departamentos_comercializadores_2 DEPT,
                                                            municipios_comercializadores_2 MUN, comercializadores_2 C
                                                       $busqueda_recaudo
                                                        AND RC.ID_FACTURACION = FC.ID_FACTURACION
                                                        AND FC.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                        AND FC.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                        AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                        AND FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                        AND DEPT.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                        AND MUN.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                      ORDER BY DEPT.NOMBRE, MUN.NOMBRE
                                                      LIMIT " . $pageLimit . ", " . PAGE_PER_RECA_ESP);
        $table = "";
        while ($row_reca_esp = mysqli_fetch_assoc($query_reca_esp)) {
            $table = $table . "<tr>";
                switch ($row_reca_esp['ESTADO_RECAUDO']) {
                    case "1":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span></td>";
                        break;
                    case "2":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PP</b></span></td>";
                        break;
                }
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_esp['DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_esp['MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_esp['COMERCIALIZADOR'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_esp['PERIODO_FACTURA'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_reca_esp['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Recaudo_Comercializadores.php?id_reca_comercializador_editar=" . $row_reca_esp['ID_RECAUDO'] . "'><button><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Recaudo_Comercializadores.php?id_reca_comercializador_eliminar=" . $row_reca_esp['ID_RECAUDO'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>