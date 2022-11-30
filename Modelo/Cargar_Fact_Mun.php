<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Fact_Mun.php');
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
            if ($_POST['estado_factura_busqueda'] == "") {
                $estado_factura = " AND FM.ESTADO_FACTURA IN (1, 2, 3, 6)";
            } else {
                $estado_factura = " AND FM.ESTADO_FACTURA = " . $_POST['estado_factura_busqueda'];
            }
        } else {
            $busqueda_factura = " WHERE MUN.NOMBRE <> ''";
            $estado_factura = " AND FM.ESTADO_FACTURA IN (1, 2, 3, 6)";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_FACT_MUN * ($page - 1);
        }
        $query_fact_mun = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                            FM.ID_FACTURACION, FM.CONSECUTIVO_FACT, FM.VALOR_FACTURA,
                                                            FM.ESTADO_FACTURA, FM.PERIODO_FACTURA
                                                       FROM facturacion_municipales_2 FM, departamentos_visitas_2 DEPT,
                                                            municipios_visitas_2 MUN
                                                       $busqueda_factura
                                                       $estado_factura
                                                        AND FM.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                        AND FM.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                        AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                      ORDER BY DEPT.NOMBRE, MUN.NOMBRE, FM.PERIODO_FACTURA DESC
                                                      LIMIT " . $pageLimit . ", " . PAGE_PER_FACT_MUN);
        $table = "";
        while ($row_fact_mun = mysqli_fetch_assoc($query_fact_mun)) {
            $table = $table . "<tr>";
                switch ($row_fact_mun['ESTADO_FACTURA']) {
                    case "1":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span></td>";
                        break;
                    case "2":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span></td>";
                        break;
                    case "3":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span></td>";
                        break;
                    case "6":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span></td>";
                        break;
                }
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_mun['DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_mun['MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_mun['CONSECUTIVO_FACT'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_fact_mun['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Facturacion_Municipales.php?id_fact_municipal_editar=" . $row_fact_mun['ID_FACTURACION'] . "'><button><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Facturacion_Municipales.php?id_fact_municipal_eliminar=" . $row_fact_mun['ID_FACTURACION'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
                
                $table = $table . "<td style='vertical-align:middle;'><a onClick='generarFacturaAportesMun(" . $row_fact_mun['ID_FACTURACION'] . ")'><button type='button' data-tooltip='tooltip' title='Imprimir'><img src='Images/print_2.png' width='16' height='16' /></button></a></td>";
                
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>