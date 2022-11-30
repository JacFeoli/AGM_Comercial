<?php
    session_start();
?>
<?php
    switch ($_POST['sw']) {
        case 0: ?>
            <h1>Resultado de la Busqueda por Municipio</h1>
        <?php
            break;
        case 1: ?>
            <h1>Resultado de la Busqueda por Contribuyente</h1>
        <?php
            break;
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
        case '0':
            $sw2 = 0;
            $or = "";
            $where = "";
            $myAnos = explode(', ', $_POST['id_ano_municipio']);
            $myMes = explode(', ', $_POST['id_mes_municipio']);
            $departamento = $_POST['departamento'];
            $municipio = $_POST['municipio'];
            if ($departamento == "") {
                $query_departamento = " ";
            } else {
                $query_departamento = " AND MV.ID_DEPARTAMENTO = " . $departamento . " ";
            }
            if ($municipio == "") {
                $query_municipio = "";
            } else {
                $query_municipio = " AND MV.ID_MUNICIPIO = " . $municipio . " ";
            }
            foreach ($myAnos as $index => $ano) {
                if ($sw2 == 0) {
                    $where = " WHERE (FE.ID_COD_MPIO = MV.ID_MUNICIPIO AND YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . $query_departamento . " " . $query_municipio . ") ";
                    $sw2 = 1;
                } else {
                    $or = $or . " OR (FE.ID_COD_MPIO = MV.ID_MUNICIPIO AND YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . $query_departamento . " " . $query_municipio . ") ";
                }
            }
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='departamento' name='departamento' value='" . $departamento . "' />";
            echo "<input type='hidden' id='municipio' name='municipio' value='" . $municipio . "' />";
            echo "<input type='hidden' id='id_ano_municipio' name='id_ano_municipio' value='" . $_POST['id_ano_municipio'] . "' />";
            echo "<input type='hidden' id='id_mes_municipio' name='id_mes_municipio' value='" . $_POST['id_mes_municipio'] . "' />";
            $query_select_info_municipio = mysqli_query($connection, "SELECT * "
                                                                   . "  FROM facturacion_especiales_2 FE, municipios_visitas_2 MV "
                                                                   . $where
                                                                   . $or
                                                                   . " ORDER BY FE.FECHA_FACTURA DESC ");
            if (mysqli_num_rows($query_select_info_municipio) != 0) {
                echo "<div id='resultado_municipio' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=13%>DEPARTAMENTO</th>";
                                echo "<th width=17%>MUNICIPIO</th>";
                                echo "<th width=32%>CONTRIBUYENTE</th>";
                                echo "<th width=8%>FACTURA</th>";
                                echo "<th width=8%>VALOR</th>";
                                echo "<th width=8%>FECHA FACTURA</th>";
                                echo "<th width=7%>ESTADO F.</th>";
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
                echo "<ul id='pagination-municipio'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            break;
        case '1':
            $sw2 = 0;
            $or = "";
            $where = "";
            $myAnos = explode(', ', $_POST['id_ano_contribuyente']);
            $myMes = explode(', ', $_POST['id_mes_contribuyente']);
            $contribuyente = $_POST['contribuyente'];
            foreach ($myAnos as $index => $ano) {
                if ($sw2 == 0) {
                    $where = " WHERE (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . " AND FE.ID_CONTRIBUYENTE = " . $contribuyente . ") ";
                    $sw2 = 1;
                } else {
                    $or = $or . " OR (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . " AND FE.ID_CONTRIBUYENTE = " . $contribuyente . ") ";
                }
            }
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='contribuyente' name='contribuyente' value='" . $contribuyente . "' />";
            echo "<input type='hidden' id='id_ano_contribuyente' name='id_ano_contribuyente' value='" . $_POST['id_ano_contribuyente'] . "' />";
            echo "<input type='hidden' id='id_mes_contribuyente' name='id_mes_contribuyente' value='" . $_POST['id_mes_contribuyente'] . "' />";
            $query_select_info_contribuyente = mysqli_query($connection, "SELECT * "
                                                                       . "  FROM facturacion_especiales_2 FE "
                                                                       . $where
                                                                       . $or
                                                                       . " ORDER BY FE.FECHA_FACTURA DESC ");
            if (mysqli_num_rows($query_select_info_contribuyente) != 0) {
                echo "<div id='resultado_contribuyente' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=32%>CONTRIBUYENTE</th>";
                                echo "<th width=13%>DEPARTAMENTO</th>";
                                echo "<th width=17%>MUNICIPIO</th>";
                                echo "<th width=8%>FACTURA</th>";
                                echo "<th width=8%>VALOR</th>";
                                echo "<th width=8%>FECHA FACTURA</th>";
                                echo "<th width=7%>ESTADO FACT.</th>";
                                echo "<th width=7%>ESTADO RECA.</th>";
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
                echo "<ul id='pagination-contribuyente'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            break;
        case '2':
            
            break;
        case '3':
            
            break;
        case '4':
            $sw2 = 0;
            $or = "";
            $where = "";
            $myAnos = explode(', ', $_POST['id_ano_mensual']);
            $myMes = explode(', ', $_POST['id_mes_mensual']);
            foreach ($myAnos as $index => $ano) {
                if ($sw2 == 0) {
                    $where = " WHERE (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . ") ";
                    $sw2 = 1;
                } else {
                    $or = $or . " OR (YEAR(FE.FECHA_FACTURA) = " . $ano . " AND MONTH(FE.FECHA_FACTURA) = " . $myMes[$index] . ") ";
                }
            }
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='id_ano_mensual' name='id_ano_mensual' value='" . $_POST['id_ano_mensual'] . "' />";
            echo "<input type='hidden' id='id_mes_mensual' name='id_mes_mensual' value='" . $_POST['id_mes_mensual'] . "' />";
            $query_select_info_mensual = mysqli_query($connection, "SELECT * "
                                                                 . "  FROM facturacion_especiales_2 FE "
                                                                 . $where
                                                                 . $or
                                                                . " ORDER BY FE.FECHA_FACTURA DESC ");
            if (mysqli_num_rows($query_select_info_mensual) != 0) {
                echo "<div id='resultado_mensual' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=13%>DEPARTAMENTO</th>";
                                echo "<th width=17%>MUNICIPIO</th>";
                                echo "<th width=32%>CONTRIBUYENTE</th>";
                                echo "<th width=8%>FACTURA</th>";
                                echo "<th width=8%>VALOR</th>";
                                echo "<th width=8%>FECHA FACTURA</th>";
                                echo "<th width=7%>ESTADO FACT.</th>";
                                echo "<th width=7%>ESTADO RECA.</th>";
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
                                                                  . "    CONT.NOMBRE AS CONTRIBUYENTE, "
                                                                  . "    USU.NOMBRE AS USUARIO, "
                                                                  . "    FE.CONSECUTIVO_FACT AS FACTURA, "
                                                                  . "    FE.VALOR_FACTURA AS VALOR_FACTURA, "
                                                                  . "    FE.FECHA_FACTURA AS FECHA_FACTURA, "
                                                                  . "    FE.PERIODO_FACTURA AS PERIODO "
                                                                  . "  FROM facturacion_especiales_2 FE, "
                                                                  . "       departamentos_visitas_2 DV, "
                                                                  . "       municipios_visitas_2 MV, "
                                                                  . "       contribuyentes_2 CONT, "
                                                                  . "       usuarios_2 USU "
                                                                  . " WHERE FE.ID_CONTRIBUYENTE = CONT.ID_CONTRIBUYENTE "
                                                                  . "   AND FE.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                  . "   AND FE.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                  . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                  . "   AND FE.ID_USUARIO = USU.ID_USUARIO "
                                                                  . "   AND FE.FECHA_FACTURA BETWEEN '" . $fecha_inicio . "' AND '$fecha_fin' "
                                                                  . " ORDER BY DV.NOMBRE, MV.NOMBRE, FE.FECHA_FACTURA DESC ");
            if (mysqli_num_rows($query_select_info_rango) != 0) {
                echo "<div id='resultado_rango' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=13%>DEPARTAMENTO</th>";
                                echo "<th width=17%>MUNICIPIO</th>";
                                echo "<th width=11%>C. COBRO</th>";
                                echo "<th width=11%>VALOR</th>";
                                echo "<th width=8%>FECHA C.C.</th>";
                                echo "<th width=7%>ESTADO C.C.</th>";
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
    function generarReporteMunicipio(sw, departamento, municipio, id_ano, id_mes) {
        window.open('Combos/Generar_Reporte_Fact_Reca_Esp.php?sw='+sw+'&departamento='+departamento+'&municipio='+municipio+'&id_ano='+id_ano+'&id_mes='+id_mes, 'Popup', 'width=750, height=600');
    }
    function generarReporteContribuyente(sw, contribuyente, id_ano, id_mes) {
        window.open('Combos/Generar_Reporte_Fact_Reca_Esp.php?sw='+sw+'&contribuyente='+contribuyente+'&id_ano='+id_ano+'&id_mes='+id_mes, 'Popup', 'width=750, height=600');
    }
    function generarReporteMensual(sw, id_ano, id_mes) {
        window.open('Combos/Generar_Reporte_Fact_Reca_Esp.php?sw='+sw+'&id_ano='+id_ano+'&id_mes='+id_mes, 'Popup', 'width=750, height=600');
    }
    function generarReporteRango(sw, fecha_inicio, fecha_fin) {
        window.open('Combos/Generar_Reporte_Fact_Reca_Esp.php?sw='+sw+'&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin, 'Popup', 'width=750, height=600');
    }
    //END REPORTES PDF
    //REPORTES EXCEL
    function generarExcelMunicipio(sw, departamento, municipio, id_ano, id_mes) {
        window.location.href = 'Combos/Generar_Excel_Fact_Reca_Esp.php?sw='+sw+'&departamento='+departamento+'&municipio='+municipio+'&id_ano='+id_ano+'&id_mes='+id_mes;
    }
    function generarExcelContribuyente(sw, contribuyente, id_ano, id_mes) {
        window.location.href = 'Combos/Generar_Excel_Fact_Reca_Esp.php?sw='+sw+'&contribuyente='+contribuyente+'&id_ano='+id_ano+'&id_mes='+id_mes;
    }
    function generarExcelMensual(sw, id_ano, id_mes) {
        window.location.href = 'Combos/Generar_Excel_Fact_Reca_Esp.php?sw='+sw+'&id_ano='+id_ano+'&id_mes='+id_mes;
    }
    function generarExcelRango(sw, fecha_inicio, fecha_fin) {
        window.location.href = 'Combos/Generar_Excel_Fact_Reca_Esp.php?sw='+sw+'&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin;
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
            case '0':
                var departamento = $("#departamento").val();
                var municipio = $("#municipio").val();
                var id_ano_municipio = $("#id_ano_municipio").val();
                var id_mes_municipio = $("#id_mes_municipio").val();
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Resultados_Liquidacion.php",
                    dataType: "json",
                    data: "sw="+sw+
                          "&departamento="+departamento+
                          "&municipio="+municipio+
                          "&id_ano_municipio="+id_ano_municipio+
                          "&id_mes_municipio="+id_mes_municipio,
                    success: function(data) {
                        $("#pagination-municipio").twbsPagination('destroy');
                        $("#pagination-municipio").twbsPagination({
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
                                    url: "Modelo/Cargar_Resultados_Liquidacion.php",
                                    data: "sw="+sw+
                                          "&departamento="+departamento+
                                          "&municipio="+municipio+
                                          "&page="+page+
                                          "&id_ano_municipio="+id_ano_municipio+
                                          "&id_mes_municipio="+id_mes_municipio,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_municipio").html(data);
                                    }
                                });
                            }
                        });
                    }
                });
                break;
            case '1':
                var contribuyente = $("#contribuyente").val();
                var id_ano_contribuyente = $("#id_ano_contribuyente").val();
                var id_mes_contribuyente = $("#id_mes_contribuyente").val();
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Resultados_Liquidacion.php",
                    dataType: "json",
                    data: "sw="+sw+
                          "&contribuyente="+contribuyente+
                          "&id_ano_contribuyente="+id_ano_contribuyente+
                          "&id_mes_contribuyente="+id_mes_contribuyente,
                    success: function(data) {
                        $("#pagination-contribuyente").twbsPagination('destroy');
                        $("#pagination-contribuyente").twbsPagination({
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
                                    url: "Modelo/Cargar_Resultados_Liquidacion.php",
                                    data: "sw="+sw+
                                          "&contribuyente="+contribuyente+
                                          "&page="+page+
                                          "&id_ano_contribuyente="+id_ano_contribuyente+
                                          "&id_mes_contribuyente="+id_mes_contribuyente,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_contribuyente").html(data);
                                    }
                                });
                            }
                        });
                    }
                });
                break;
            case '2':
                
                break;
            case '3':
                
                break;
            case '4':
                var id_ano_mensual = $("#id_ano_mensual").val();
                var id_mes_mensual = $("#id_mes_mensual").val();
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Resultados_Liquidacion.php",
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
                                    url: "Modelo/Cargar_Resultados_Liquidacion.php",
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
                    url: "Modelo/Cargar_Paginacion_Resultados_Liquidacion.php",
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
                                    url: "Modelo/Cargar_Resultados_Liquidacion.php",
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