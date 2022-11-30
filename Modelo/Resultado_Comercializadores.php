<?php
    session_start();
?>
<?php
    switch ($_POST['sw']) {
        case 4: ?>
            <h1>Resultado de la Busqueda por Periodo</h1>
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
            $id_ano_mensual = $_POST['id_ano_mensual'];
            $id_mes_mensual = $_POST['id_mes_mensual'];
            $periodo_mensual = strtolower($_POST['periodo_mensual']);
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='id_ano_mensual' name='id_ano_mensual' value='" . $id_ano_mensual . "' />";
            echo "<input type='hidden' id='id_mes_mensual' name='id_mes_mensual' value='" . $id_mes_mensual . "' />";
            $query_select_info_mensual = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                    . "    MV.NOMBRE AS MUNICIPIO, "
                                                                    . "    USU.NOMBRE AS USUARIO, "
                                                                    . "    FC.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                    . "    FC.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                    . "    FC.PERIODO_FACTURA AS PERIODO "
                                                                    . "  FROM facturacion_comercializadores_2 FC, "
                                                                    . "       departamentos_visitas_2 DV, "
                                                                    . "       municipios_visitas_2 MV, "
                                                                    . "       usuarios_2 USU "
                                                                    . " WHERE FC.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                    . "   AND FC.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                    . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                    . "   AND FC.ID_USUARIO = USU.ID_USUARIO "
                                                                    . "   AND YEAR(FC.FECHA_FACTURA) = " . $id_ano_mensual . ""
                                                                    . "   AND MONTH(FC.FECHA_FACTURA) = " . $id_mes_mensual . ""
                                                                    . " ORDER BY DV.NOMBRE, MV.NOMBRE, FC.FECHA_FACTURA DESC ");
            if (mysqli_num_rows($query_select_info_mensual) != 0) {
                echo "<div id='resultado_mensual' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=13%>DEPARTAMENTO</th>";
                                echo "<th width=17%>MUNICIPIO</th>";
                                echo "<th width=11%>VALOR FACT.</th>";
                                echo "<th width=8%>FECHA FACT.</th>";
                                echo "<th width=7%>ESTADO FACT.</th>";
                                echo "<th width=7%>ESTADO R.</th>";
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
                echo "<ul id='pagination-mensual'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            break;
        case '5':
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='fecha_inicio' name='fecha_inicio' value='" . $fecha_inicio . "' />";
            echo "<input type='hidden' id='fecha_fin' name='fecha_fin' value='" . $fecha_fin . "' />";
            $query_select_info_rango = mysqli_query($connection, "SELECT DV.NOMBRE AS DEPARTAMENTO, "
                                                                  . "    MV.NOMBRE AS MUNICIPIO, "
                                                                  . "    USU.NOMBRE AS USUARIO, "
                                                                  . "    FC.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                  . "    FC.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                  . "    FC.PERIODO_FACTURA AS PERIODO "
                                                                  . "  FROM facturacion_comercializadores_2 FC, "
                                                                  . "       departamentos_visitas_2 DV, "
                                                                  . "       municipios_visitas_2 MV, "
                                                                  . "       usuarios_2 USU "
                                                                  . " WHERE FC.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                  . "   AND FC.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                  . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                  . "   AND FC.ID_USUARIO = USU.ID_USUARIO "
                                                                  . "   AND FC.FECHA_FACTURA BETWEEN '" . $fecha_inicio . "' AND '$fecha_fin' "
                                                                  . " ORDER BY DV.NOMBRE, MV.NOMBRE, FC.FECHA_FACTURA DESC ");
            if (mysqli_num_rows($query_select_info_rango) != 0) {
                echo "<div id='resultado_rango' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=13%>DEPARTAMENTO</th>";
                                echo "<th width=17%>MUNICIPIO</th>";
                                echo "<th width=11%>VALOR FACT.</th>";
                                echo "<th width=8%>FECHA FACT.</th>";
                                echo "<th width=7%>ESTADO FACT.</th>";
                                echo "<th width=7%>ESTADO R.</th>";
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
                echo "<ul id='pagination-rango'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            break;
    }
?>
<script>
    //REPORTES PDF
    function generarReporteMensual(sw, id_ano, id_mes) {
        window.open('Combos/Generar_Reporte_Fact_Reca_Comer.php?sw='+sw+'&id_ano='+id_ano+'&id_mes='+id_mes, 'Popup', 'width=750, height=600');
    }
    function generarReporteRango(sw, fecha_inicio, fecha_fin) {
        window.open('Combos/Generar_Reporte_Fact_Reca_Comer.php?sw='+sw+'&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin, 'Popup', 'width=750, height=600');
    }
    //END REPORTES PDF
    //REPORTES EXCEL
    function generarExcelMensual(sw, id_ano, id_mes) {
        window.location.href = 'Combos/Generar_Excel_Fact_Reca_Comer.php?sw='+sw+'&id_ano='+id_ano+'&id_mes='+id_mes;
    }
    function generarExcelRango(sw, fecha_inicio, fecha_fin) {
        window.location.href = 'Combos/Generar_Excel_Fact_Reca_Comer.php?sw='+sw+'&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin;
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
                var id_ano_mensual = $("#id_ano_mensual").val();
                var id_mes_mensual = $("#id_mes_mensual").val();
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Resultados_Comercializadores.php",
                    dataType: "json",
                    data: "sw="+sw+
                          "&id_ano_mensual="+id_ano_mensual+
                          "&id_mes_mensual="+id_mes_mensual,
                    success: function(data) {
                        $("#pagination-mensual").twbsPagination('destroy');
                        $("#pagination-mensual").twbsPagination({
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
                                    url: "Modelo/Cargar_Resultados_Comercializadores.php",
                                    data: "sw="+sw+
                                          "&page="+page+
                                          "&id_ano_mensual="+id_ano_mensual+
                                          "&id_mes_mensual="+id_mes_mensual,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_mensual").html(data);
                                    }
                                });
                            }
                        });
                    }
                });
                break;
            case '5':
                var fecha_inicio = $("#fecha_inicio").val();
                var fecha_fin = $("#fecha_fin").val();
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Resultados_Comercializadores.php",
                    dataType: "json",
                    data: "sw="+sw+
                          "&fecha_inicio="+fecha_inicio+
                          "&fecha_fin="+fecha_fin,
                    success: function(data) {
                        $("#pagination-rango").twbsPagination('destroy');
                        $("#pagination-rango").twbsPagination({
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
                                    url: "Modelo/Cargar_Resultados_Comercializadores.php",
                                    data: "sw="+sw+
                                          "&page="+page+
                                          "&fecha_inicio="+fecha_inicio+
                                          "&fecha_fin="+fecha_fin,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_rango").html(data);
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