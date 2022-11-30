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
                    $busqueda_factura = " WHERE C.NOMBRE LIKE '%" . $_POST['busqueda_factura'] . "%' ";
                } else {
                    $busqueda_factura = " WHERE C.NOMBRE <> ''";
                }
            } else {
                $busqueda_factura = " WHERE C.NOMBRE <> ''";
            }
            if ($_POST['estado_factura_busqueda'] == "") {
                $estado_factura = " AND FE.ESTADO_FACTURA IN (1, 2, 3)";
            } else {
                $estado_factura = " AND FE.ESTADO_FACTURA = " . $_POST['estado_factura_busqueda'];
            }
        } else {
            $busqueda_factura = " WHERE C.NOMBRE <> ''";
            $estado_factura = " AND FE.ESTADO_FACTURA IN (1, 2, 3)";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_FACT_ESP * ($page - 1);
        }
        $query_fact_esp = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                            FE.ID_FACTURACION, C.ID_CONTRIBUYENTE AS ID_CONTRIBUYENTE,
                                                            C.NOMBRE AS CONTRIBUYENTE, FE.CONSECUTIVO_FACT, FE.VALOR_FACTURA,
                                                            FE.ESTADO_FACTURA, FE.PERIODO_FACTURA
                                                       FROM facturacion_especiales_2 FE, departamentos_visitas_2 DEPT,
                                                            municipios_visitas_2 MUN, contribuyentes_2 C
                                                       $busqueda_factura
                                                       $estado_factura
                                                        AND FE.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                        AND FE.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                        AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                        AND FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE
                                                      ORDER BY DEPT.NOMBRE, MUN.NOMBRE, FE.PERIODO_FACTURA DESC
                                                      LIMIT " . $pageLimit . ", " . PAGE_PER_FACT_ESP);
        $table = "";
        while ($row_fact_esp = mysqli_fetch_assoc($query_fact_esp)) {
            $table = $table . "<tr>";
                switch ($row_fact_esp['ESTADO_FACTURA']) {
                    case "1":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span></td>";
                        break;
                    case "2":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span></td>";
                        break;
                    case "3":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span></td>";
                        break;
                }
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_esp['DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_esp['MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_esp['CONTRIBUYENTE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_fact_esp['CONSECUTIVO_FACT'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_fact_esp['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Facturacion_Especiales.php?id_fact_especial_editar=" . $row_fact_esp['ID_FACTURACION'] . "'><button><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Facturacion_Especiales.php?id_fact_especial_eliminar=" . $row_fact_esp['ID_FACTURACION'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a onClick='generarFacturaClienteEsp(" . $row_fact_esp['ID_FACTURACION'] . ")'><button style='border: 1px solid;'><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>