<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Fact_Esp.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_factura']) != "") {
                if ($_POST['busqueda_factura'] != "") {
                    $busqueda_factura = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_factura'] . "%' ";
                } else {
                    $busqueda_factura = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_factura = " WHERE MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_factura = " WHERE MUN.NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_FACT_ESP * ($page - 1);
        }
        $query_fact_comer = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                            FC.ID_FACTURACION, C.ID_COMERCIALIZADOR AS ID_COMERCIALIZADOR,
                                                            C.NOMBRE AS COMERCIALIZADOR, FC.VALOR_FACTURA, FC.AJUSTE_FACT,
                                                            FC.ESTADO_FACTURA
                                                       FROM facturacion_comercializadores_2 FC, departamentos_comercializadores_2 DEPT,
                                                            municipios_comercializadores_2 MUN, comercializadores_2 C
                                                       $busqueda_factura
                                                        AND FC.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                        AND FC.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                        AND FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                        AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                        AND DEPT.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                        AND MUN.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR
                                                      ORDER BY DEPT.NOMBRE, MUN.NOMBRE, FC.PERIODO_FACTURA DESC
                                                      LIMIT " . $pageLimit . ", " . PAGE_PER_FACT_ESP);
        $table = "";
        while ($row_fact_comer = mysqli_fetch_assoc($query_fact_comer)) {
            $table = $table . "<tr>";
                switch ($row_fact_comer['ESTADO_FACTURA']) {
                    case "1":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span></td>";
                        break;
                    case "2":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PP</b></span></td>";
                        break;
                }
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_comer['DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_comer['MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_comer['COMERCIALIZADOR'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_fact_comer['VALOR_FACTURA'] + $row_fact_comer['AJUSTE_FACT'], 0, ',', '.') . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Facturacion_Comercializadores.php?id_fact_comercializador_editar=" . $row_fact_comer['ID_FACTURACION'] . "'><button><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Facturacion_Comercializadores.php?id_fact_comercializador_eliminar=" . $row_fact_comer['ID_FACTURACION'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>