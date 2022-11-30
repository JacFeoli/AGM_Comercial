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
                require_once('../Includes/Paginacion_Historial_Catastro.php');
                $id_ano_factura_inicial = $_POST['id_ano_factura_inicial'];
                $id_mes_factura_inicial = $_POST['id_mes_factura_inicial'];
                $mes_factura_inicial = strtolower($_POST['mes_factura_inicial']);
                $id_ano_factura_final = $_POST['id_ano_factura_final'];
                $id_mes_factura_final = $_POST['id_mes_factura_final'];
                $mes_factura_final = strtolower($_POST['mes_factura_final']);
                $bd_tabla_catastro_inicial = $_POST['bd_tabla_catastro_inicial'];
                $bd_tabla_catastro_final = $_POST['bd_tabla_catastro_final'];
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_HISTORIAL_CATASTRO * ($page - 1);
                }
                if (strlen($_POST['id_mes_factura_inicial']) == 1) {
                    $id_mes_factura_inicial = "0" . $_POST['id_mes_factura_inicial'];
                } else {
                    $id_mes_factura_inicial = $_POST['id_mes_factura_inicial'];
                }
                if (strlen($_POST['id_mes_factura_final']) == 1) {
                    $id_mes_factura_final = "0" . $_POST['id_mes_factura_final'];
                } else {
                    $id_mes_factura_final = $_POST['id_mes_factura_final'];
                }
                $query_select_historial_catastro = mysqli_query($connection, "SELECT TAR.ID_TARIFA, TAR.COD_TARIFA, TAR.NOMBRE, "
                                                                           . "       CATA1.ANO_CATASTRO, CATA1.MES_CATASTRO, COUNT(CATA1.ID_TABLA) "
                                                                           . "  FROM $bd_tabla_catastro_inicial CATA1, tarifas_2 TAR "
                                                                           . " WHERE NIC NOT IN (SELECT NIC FROM $bd_tabla_catastro_final) "
                                                                           . "   AND CATA1.ID_TARIFA = TAR.ID_TARIFA "
                                                                           . " GROUP BY TAR.NOMBRE "
                                                                           . "HAVING COUNT(1) >= 1 "
                                                                           . " ORDER BY TAR.NOMBRE "
                                                                           . " LIMIT " . $pageLimit . ", " . PAGE_PER_HISTORIAL_CATASTRO);
                $table = "";
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO INICIAL: " . $id_ano_factura_inicial . $id_mes_factura_inicial . " - PERIODO FINAL: " . $id_ano_factura_final . $id_mes_factura_final . "</p>";
                $table = $table . "<table class='table table-condensed table-hover'>";
                    $table = $table . "<thead>";
                        $table = $table . "<tr>";
                            $table = $table . "<th width=10%>COD. TARIFA</th>";
                            $table = $table . "<th width=70%>TARIFA</th>";
                            $table = $table . "<th width=10%>NUEVOS SUM.</th>";
                            $table = $table . "<th width=5%>DETALLE</th>";
                            $table = $table . "<th width=5%>EXPORTAR</th>";
                        $table = $table . "</tr>";
                    $table = $table . "</thead>";
                    $table = $table . "<tbody>";
                        while ($row_historial_catastro = mysqli_fetch_assoc($query_select_historial_catastro)) {
                            $table = $table . "<tr>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_historial_catastro['COD_TARIFA'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_historial_catastro['NOMBRE'] . "</td>";
                                $table = $table . "<td style='vertical-align:middle;'>" . $row_historial_catastro['COUNT(CATA1.ID_TABLA)'] . "</td>";
                                if (strlen($row_historial_catastro['MES_CATASTRO']) == 1) {
                                    $mes_factura = "0" . $row_historial_catastro['MES_CATASTRO'];
                                } else {
                                    $mes_factura = $row_historial_catastro['MES_CATASTRO'];
                                }
                                if (strlen($row_historial_catastro['ID_TARIFA']) == 1) {
                                    $id_tarifa = "0" . $row_historial_catastro['ID_TARIFA'];
                                } else {
                                    $id_tarifa = $row_historial_catastro['ID_TARIFA'];
                                }
                                $query_detalle_historial = "SELECT * FROM $bd_tabla_catastro_inicial WHERE NIC NOT IN (SELECT NIC FROM $bd_tabla_catastro_final) AND ID_TARIFA = " . $row_historial_catastro['ID_TARIFA'];
                                $table = $table . "<td style='vertical-align:middle;'><button type='button' data-toggle='modal' id='" . $query_detalle_historial . "' data-target='#modalDetalleHistorial'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                                $table = $table . '<td style="vertical-align:middle;"><button type="button" name="exportar_excel" onclick="downloadFile(\'' . $bd_tabla_catastro_inicial . '\', \'' . $bd_tabla_catastro_final . '\', \'' . $row_historial_catastro['ID_TARIFA'] . '\')" ><img src="Images/excel_2.png" title="Exportar" width="16" height="16" /></button></td>';
                                //$table = $table . "<td style='vertical-align:middle;'><button type='button' name='exportar_excel' id='" . $query_detalle_historial . "'><img src='Images/excel.png' title='Exportar' width='16' height='16' /></button></td>";
                                //$table = $table . "<td style='vertical-align:middle;'><button type='button' data-toggle='modal' id='" . $row_historial_catastro['ANO_CATASTRO'] . $mes_factura . $id_tarifa . "' data-target='#modalDetalleHistorial'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                            $table = $table . "</tr>";

                        }
                    $table = $table . "</tbody>";
                $table = $table . "</table>";
                break;
        }
        echo $table;
    }
?>
<script>
    function downloadFile(bd_tabla_catastro_inicial, bd_tabla_catastro_final, id_tarifa) {
        //alert(mes_ano_catastro_inicial + " - " + mes_ano_catastro_final + " - " + id_tarifa);
        window.location.href = 'Modelo/Descargar_Archivo.php?bd_tabla_catastro_inicial='+bd_tabla_catastro_inicial+'&bd_tabla_catastro_final='+bd_tabla_catastro_final+'&id_tarifa='+id_tarifa;
    }
</script>
<script>
    /*$(document).ready(function() {
        $("button[type=button][name=exportar_excel]").click(function(e) {
            var historial_id = this.id;
            window.location.href = 'Modelo/Descargar_Archivo.php?query=' + historial_id;
        });
    });*/
</script>