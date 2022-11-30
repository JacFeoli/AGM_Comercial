<?php
    session_start();
?>
<?php
    switch ($_POST['sw']) {
        case 4: ?>
            <h1>Resultado de la Busqueda Histórico - Balance de Flujo</h1>
        <?php
            break;
        case 5: ?>
            <h1>Resultado de la Busqueda por Rango de Fechas</h1>
        <?php
            break;
    }
?>
<div style="display: none;" class="alert alert-success alert-dismissible text-center" role="alert" id="alert-consulta">
    Resultado Generado Satisfactoriamente. <i class="fas fa-check" aria-hidden="true"></i>
</div>
<h2></h2>
<?php
    require_once('../Includes/Config.php');
    $sw = $_POST['sw'];
    switch ($sw) {
        case '4':
            $id_departamento_historico = $_POST['id_departamento_historico'];
            $id_municipio_historico = $_POST['id_municipio_historico'];
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='id_departamento_historico' name='id_departamento_historico' value='" . $id_departamento_historico . "' />";
            echo "<input type='hidden' id='id_municipio_historico' name='id_municipio_historico' value='" . $id_municipio_historico . "' />";
            /*$query_select_info_historico = mysqli_query($connection, "SELECT FC.ID_COD_DPTO, DV.NOMBRE AS DEPARTAMENTO, "
                                                                   . "       FC.ID_COD_MPIO, MV.NOMBRE AS MUNICIPIO, "
                                                                   . "       FC.PERIODO_FACTURA, SUM(FC.VALOR_FACTURA) "
                                                                   . "  FROM facturacion_comercializadores_2 FC, departamentos_visitas_2 DV, municipios_visitas_2 MV "
                                                                   . " WHERE FC.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                   . "   AND FC.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                   . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                   . "   AND FC.ID_COD_DPTO = $id_departamento_historico "
                                                                   . "   AND FC.ID_COD_MPIO = $id_municipio_historico "
                                                                   . " GROUP BY FC.PERIODO_FACTURA "
                                                                   . "HAVING COUNT(FC.PERIODO_FACTURA) >= 1 "
                                                                   . " ORDER BY FC.PERIODO_FACTURA ");*/
            $query_select_periodos_historico = mysqli_query($connection, "SELECT DISTINCT(SUBSTR(PERIODO_FACTURA, 1, 4)) AS PERIODO_FACTURA "
                                                                       . "  FROM facturacion_especiales_2 "
                                                                       . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                       . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                       . " UNION "
                                                                       . "SELECT DISTINCT(SUBSTR(PERIODO_FACTURA, 1, 4)) AS PERIODO_FACTURA "
                                                                       . "  FROM facturacion_municipales_2 "
                                                                       . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                       . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                       . " UNION "
                                                                       . "SELECT DISTINCT(SUBSTR(PERIODO_FACTURA, 1, 4)) AS PERIODO_FACTURA "
                                                                       . "  FROM facturacion_comercializadores_2 "
                                                                       . " WHERE ID_COD_DPTO = " . $id_departamento_historico . " "
                                                                       . "   AND ID_COD_MPIO = " . $id_municipio_historico . " "
                                                                       . " ORDER BY PERIODO_FACTURA");
            if (mysqli_num_rows($query_select_periodos_historico) != 0) {
                //$row_info_historico = mysqli_fetch_array($query_select_info_historico);
                //echo "<p style='color: #003153; font-weight: bold;'>" . $row_info_historico['DEPARTAMENTO'] . " - " . $row_info_historico['MUNICIPIO'] . "&nbsp; <a onClick='generarExcelHistorico(" . $sw . ", " . $id_departamento_historico . ", " . $id_municipio_historico . ")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
                echo "<div id='resultado_historico' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=5%>PERIODO</th>";
                                echo "<th width=15%>INGRESOS</th>";
                                echo "<th width=15%>EGRESOS</th>";
                                echo "<th width=15%>DIFERENCIA</th>";
                                //echo "<th width=10%>FACT. COM.</th>";
                                //echo "<th width=9%>RECA. COM.</th>";
                                //echo "<th width=10%>FACT. OPER.</th>";
                                //echo "<th width=9%>RECA. OPER.</th>";
                                //echo "<th width=10%>FACT. APOR.</th>";
                                //echo "<th width=9%>RECA. APOR.</th>";
                                //echo "<th width=10%>FACT. CLI.</th>";
                                //echo "<th width=9%>RECA. CLI.</th>";
                                //echo "<th width=10%>FACT. OYM.</th>";
                                //echo "<th width=9%>RECA. OYM.</th>";
                                //echo "<th width=10%>FACT. RI.</th>";
                                //echo "<th width=9%>RECA. RI.</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                            
                        echo "</tbody>";
                    echo "</table>";
                echo "</div>";
            } else {
                echo "<br />";
		echo "<p class='message'>La Consulta no generó Resultados.</p>";
            }
            echo "<div id='div-pagination'>";
                echo "<ul id='pagination-historico'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            break;
        case '5':
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            echo "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>RANGO " . $fecha_inicio . " & " . $fecha_fin . "&nbsp; <a onClick='generarExcelRango(" . $sw . ", \"" . $fecha_inicio . "\", \"" . $fecha_fin . "\")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
            break;
    }
?>
<script>
    //REPORTES EXCEL
    function generarExcelHistorico(sw, id_departamento, id_municipio) {
        window.location.href = 'Combos/Generar_Excel_Informe_BalanceF.php?sw='+sw+'&id_departamento='+id_departamento+'&id_municipio='+id_municipio;
    }
    function generarExcelRango(sw, fecha_inicio, fecha_fin) {
        window.location.href = 'Combos/Generar_Excel_Informe_Reca.php?sw='+sw+'&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin;
    }
    //END REPORTES EXCEL
</script>
<script>
    $(document).ready(function() {
        $("#alert-consulta").attr("display", "block");
        $("#alert-consulta").fadeTo(3000, 500).fadeOut(500, function() {
            $("#alert-consulta").fadeOut();
        });
        var sw = $("#sw").val();
        switch (sw) {
            case '4':
                var id_departamento_historico = $("#id_departamento_historico").val();
                var id_municipio_historico = $("#id_municipio_historico").val();
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Resultados_BalanceF.php",
                    dataType: "json",
                    data: "sw="+sw+
                          "&id_departamento_historico="+id_departamento_historico+
                          "&id_municipio_historico="+id_municipio_historico,
                    success: function(data) {
                        $("#pagination-historico").twbsPagination('destroy');
                        $("#pagination-historico").twbsPagination({
                            totalPages: data[0],
                            visiblePages: 15,
                            first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                            prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                            next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                            last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                            onPageClick: function (event, page) {
                                $("#loading-spinner").css('display', 'block');
                                $.ajax({
                                    type: "POST",
                                    url: "Modelo/Cargar_Resultados_BalanceF.php",
                                    data: "sw="+sw+
                                          "&page="+page+
                                          "&id_departamento_historico="+id_departamento_historico+
                                          "&id_municipio_historico="+id_municipio_historico,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_historico").html(data);
                                    }
                                });
                            }
                        });
                    }
                });
                break;
        }
    });
</script>