<?php
    session_start();
?>
<?php
    switch ($_POST['sw']) {
        case 2: ?>
            <h1>Resultado de la Busqueda por Departamento</h1>
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
        case '2':
            $departamento = $_POST['departamento'];
            $municipio = $_POST['municipio'];
            $id_ano_factura = $_POST['id_ano_factura'];
            if ($departamento == "") {
                $query_departamento = " ";
            } else {
                $query_departamento = " AND MUN.ID_DEPARTAMENTO = " . $departamento . " ";
            }
            if ($municipio == "") {
                $query_municipio = "";
            } else {
                $query_municipio = " AND MUN.ID_MUNICIPIO = " . $municipio . " ";
            }
            if ($id_ano_factura == "") {
                $query_select_ult_ano_fact = mysqli_query($connection, "SELECT MAX(ANO_FACTURA) AS ULT_ANO "
                                                                    . "   FROM archivos_cargados_facturacion_2");
                $row_ult_ano_fact = mysqli_fetch_array($query_select_ult_ano_fact);
                $query_select_ult_mes_fact = mysqli_query($connection, "SELECT MAX(ID_MES_FACTURA) AS ULT_MES "
                                                                    . "   FROM archivos_cargados_facturacion_2 "
                                                                    . "  WHERE ANO_FACTURA = " . $row_ult_ano_fact['ULT_ANO']);
                $row_ult_mes_fact = mysqli_fetch_array($query_select_ult_mes_fact);
                $query_select_mes_factura = mysqli_query($connection, "SELECT DISTINCT(MES_FACTURA) "
                                                                    . "  FROM archivos_cargados_facturacion_2 "
                                                                    . " WHERE ANO_FACTURA = " . $row_ult_ano_fact['ULT_ANO'] . " "
                                                                    . "   AND ID_MES_FACTURA = " . $row_ult_mes_fact['ULT_MES']);
                $row_mes_factura = mysqli_fetch_array($query_select_mes_factura);
                $id_ano_factura = $row_ult_ano_fact['ULT_ANO'];
                $id_mes_factura = $row_ult_mes_fact['ULT_MES'];
                $mes_factura = strtolower($row_mes_factura['MES_FACTURA']);
                $bd_tabla_facturacion = "facturacion_" .  strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
                $bd_tabla_recaudo = "recaudo_" . strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
                $bd_tabla_catastro = "catastro_" . strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
            } else {
                $id_mes_factura = $_POST['id_mes_factura'];
                $mes_factura = strtolower($_POST['mes_factura']);
                $bd_tabla_facturacion = "facturacion_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_recaudo = "recaudo_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_catastro = "catastro_" . $mes_factura . $id_ano_factura . "_2";
            }
            echo "<ul class='nav nav-pills' role='tablist'>";
                echo "<li role='presentation' class='active'>";
                    echo "<a href='#municipio_consolidado_tab' id='tab_municipio_consolidado' aria-controls='municipio_consolidado_tab' role='tab' data-toggle='tab'>Resumen - Consolidado</a>";
                echo "</li>";
                echo "<li role='presentation'>";
                    echo "<a href='#grafica_clientes_tab' id='tab_grafica_cliente' aria-controls='grafica_clientes_tab' role='tab' data-toggle='tab'>Gráfica - Clientes</a>";
                echo "</li>";
                echo "<li role='presentation'>";
                    echo "<a href='#grafica_facturado_tab' id='tab_grafica_facturado' aria-controls='grafica_facturado_tab' role='tab' data-toggle='tab'>Gráfica - Facturado</a>";
                echo "</li>";
                echo "<li role='presentation'>";
                    echo "<a href='#grafica_recaudo_tab' id='tab_grafica_recaudo' aria-controls='grafica_recaudo_tab' role='tab' data-toggle='tab'>Gráfica - Recaudo</a>";
                echo "</li>";
            echo "</ul>";
            echo "<h2></h2>";
            echo "<div class='tab-content'>";
                echo "<div role='tabpanel' class='tab-pane fade in active' id='municipio_consolidado_tab'>";
                    echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
                    echo "<input type='hidden' id='departamento' name='departamento' value='" . $departamento . "' />";
                    echo "<input type='hidden' id='municipio' name='municipio' value='" . $municipio . "' />";
                    echo "<input type='hidden' id='id_ano_factura' name='id_ano_factura' value='" . $id_ano_factura . "' />";
                    echo "<input type='hidden' id='id_mes_factura' name='id_mes_factura' value='" . $id_mes_factura . "' />";
                    echo "<input type='hidden' id='mes_factura' name='mes_factura' value='" . $mes_factura . "' />";
                    $query_select_info_departamento = mysqli_query($connection, "SELECT MUN.ID_MUNICIPIO, MUN.NOMBRE, COUNT(DISTINCT(NIC)) AS TOTAL_CLIENTES "
                                                                              . "  FROM municipios_2 MUN, $bd_tabla_facturacion FACT, tarifas_2 TAR "
                                                                              . " WHERE MUN.ID_DEPARTAMENTO = FACT.ID_COD_DPTO "
                                                                              . "   AND MUN.ID_MUNICIPIO = FACT.ID_COD_MPIO "
                                                                              . "   AND TAR.ID_TARIFA = FACT.ID_TARIFA "
                                                                              . $query_departamento . " "
                                                                              . $query_municipio . " "
                                                                              . " GROUP BY MUN.NOMBRE "
                                                                              . "HAVING COUNT(1) >= 1 "
                                                                              . " ORDER BY MUN.NOMBRE");
                    $row_info_departamento = mysqli_fetch_array($query_select_info_departamento);
                    echo "<input style='text-transform: capitalize;' type='hidden' id='nombre_municipio' name='nombre_municipio' value='" . $row_info_departamento['NOMBRE'] . "' />";
                    if (mysqli_num_rows($query_select_info_departamento) != 0) {
                        echo "<div id='resultado_departamento' class='table-scroll'>";
                            echo "<table class='table table-condensed table-hover'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=9%></th>";
                                        echo "<th width=5%>R1</th>";
                                        echo "<th width=5%>R2</th>";
                                        echo "<th width=5%>R3</th>";
                                        echo "<th width=5%>R4</th>";
                                        echo "<th width=5%>R5</th>";
                                        echo "<th width=5%>R6</th>";
                                        echo "<th width=5%>COM.</th>";
                                        echo "<th width=5%>OFC.</th>";
                                        echo "<th width=5%>IND.</th>";
                                        echo "<th width=10%>TOTALES</th>";
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
                        echo "<ul id='pagination-departamento'></ul>";
                        echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
                    echo "</div>";
                echo "</div>";
                echo "<div role='tabpanel' class='tab-pane fade' id='grafica_clientes_tab'>";
                    /*$rows = array();
                    $query_select_clientes = mysqli_query($connection, "SELECT T.NOMBRE, COUNT(*)
                                                                          FROM $bd_tabla_catastro C, tarifas_2 T
                                                                         WHERE C.ID_TARIFA = T.ID_TARIFA
                                                                           AND C.ID_COD_DPTO = $departamento
                                                                           AND C.ID_COD_MPIO = $municipio
                                                                         GROUP BY C.ID_TARIFA
                                                                         ORDER BY COUNT(*) DESC, T.NOMBRE ASC");
                    $table = array();
                    $table['cols'] = array(
                        array('label' => 'Tarifas', 'type' => 'string'),
                        array('label' => 'Total', 'type' => 'number')
                    );
                    while ($row_clientes = mysqli_fetch_assoc($query_select_clientes)) {
                        $temp = array();
                        $temp[] = array('v' => (string) $row_clientes['NOMBRE']);
                        $temp[] = array('v' => (int) $row_clientes['COUNT(*)']);
                        $rows[] = array('c' => $temp);
                    }
                    $table['rows'] = $rows;
                    $jsonTableClientes = json_encode($table);*/
                    echo "<form class='form-horizontal row-bottom-buffer row-top-buffer' method='post'>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 16px;'>";
                                    echo "<b>TOTAL CLIENTES POR TARIFA</b>&nbsp;&nbsp;&nbsp;";
                                    //echo "<input type='hidden' name='hidden_cliente_html' id='hidden_cliente_html' />";
                                    //echo "<button class='btn_print' name='crear_cliente_pdf' id='crear_cliente_pdf' type='button' title='Generar PDF' onclick='guardarPDF();'><img src='Images/print_2.png' width='16' height='16' /></button>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 12px;' id='piechartClientes'></div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</form>";
                echo "</div>";
                echo "<div role='tabpanel' class='tab-pane fade' id='grafica_facturado_tab'>";
                    /*$rows = array();
                    $query_select_factura = mysqli_query($connection, "SELECT T.NOMBRE, SUM(F.IMPORTE_TRANS)
                                                                          FROM $bd_tabla_facturacion F, tarifas_2 T
                                                                         WHERE F.ID_TARIFA = T.ID_TARIFA
                                                                           AND F.ID_COD_DPTO = $departamento
                                                                           AND F.ID_COD_MPIO = $municipio
                                                                           AND F.CONCEPTO <> 'CI306'
                                                                         GROUP BY F.ID_TARIFA
                                                                         ORDER BY COUNT(*) DESC, T.NOMBRE ASC");
                    $table = array();
                    $table['cols'] = array(
                        array('label' => 'Tarifas', 'type' => 'string'),
                        array('label' => 'Total', 'type' => 'number')
                    );
                    while ($row_factura = mysqli_fetch_assoc($query_select_factura)) {
                        $temp = array();
                        $temp[] = array('v' => (string) $row_factura['NOMBRE']);
                        $temp[] = array('v' => (int) max($row_factura['SUM(F.IMPORTE_TRANS)'], 0));
                        $rows[] = array('c' => $temp);
                    }
                    $table['rows'] = $rows;
                    $jsonTableFactura = json_encode($table);*/
                    echo "<form class='form-horizontal row-bottom-buffer row-top-buffer' method='post'>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 16px;'>";
                                    echo "<b>TOTAL FACTURADO POR TARIFA</b>";
                                    //echo "<input type='hidden' name='hidden_factura_html' id='hidden_factura_html' />";
                                    //echo "<button class='btn_print' name='crear_pdf' id='crear_pdf' type='button' title='Generar PDF'><img src='Images/print_2.png' width='16' height='16' /></button>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 12px;' id='piechartFactura'></div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</form>";
                echo "</div>";
                echo "<div role='tabpanel' class='tab-pane fade' id='grafica_recaudo_tab'>";
                    /*$rows = array();
                    $query_select_recaudo = mysqli_query($connection, "SELECT T.NOMBRE, SUM(R.IMPORTE_TRANS)
                                                                          FROM $bd_tabla_recaudo R, tarifas_2 T
                                                                         WHERE R.ID_TARIFA = T.ID_TARIFA
                                                                           AND R.ID_COD_DPTO = $departamento
                                                                           AND R.ID_COD_MPIO = $municipio
                                                                         GROUP BY R.ID_TARIFA
                                                                         ORDER BY COUNT(*) DESC, T.NOMBRE ASC");
                    $table = array();
                    $table['cols'] = array(
                        array('label' => 'Tarifas', 'type' => 'string'),
                        array('label' => 'Total', 'type' => 'number')
                    );
                    while ($row_recaudo = mysqli_fetch_assoc($query_select_recaudo)) {
                        $temp = array();
                        $temp[] = array('v' => (string) $row_recaudo['NOMBRE']);
                        $temp[] = array('v' => (int) max($row_recaudo['SUM(R.IMPORTE_TRANS)'], 0));
                        $rows[] = array('c' => $temp);
                    }
                    $table['rows'] = $rows;
                    $jsonTableRecaudo = json_encode($table);*/
                    echo "<form class='form-horizontal row-bottom-buffer row-top-buffer' method='post'>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 16px;'>";
                                    echo "<b>TOTAL RECAUDO POR TARIFA</b>";
                                    //echo "<input type='hidden' name='hidden_recaudo_html' id='hidden_recaudo_html' />";
                                    //echo "<button class='btn_print' name='crear_pdf' id='crear_pdf' type='button' title='Generar PDF'><img src='Images/print_2.png' width='16' height='16' /></button>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 12px;' id='piechartRecaudo'></div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</form>";
                echo "</div>";
            echo "</div>";
            break;
    }
?>
    <script>
        drawChartClientes();
        drawChartFactura();
        drawChartRecaudo();
        //am4core.useTheme(am4themes_animated);
        //am4core.useTheme(am4themes_dataviz);
        function drawChartClientes() {
            am4core.useTheme(am4themes_material);
            am4core.useTheme(am4themes_animated);
            chartC = am4core.create("piechartClientes", am4charts.PieChart3D);
            chartC.hiddenState.properties.opacity = 0;
            chartC.legend = new am4charts.Legend();
            chartC.data = [
                <?php
                    $total_clientes = 0;
                    $array_count_clientes = array();
                    $array_nombre_clientes = array();
                    $query_select_clientes = mysqli_query($connection, "SELECT CASE
                                                                                    WHEN T.ID_TARIFA = 4 THEN 'R1'
                                                                                    WHEN T.ID_TARIFA = 5 THEN 'R2'
                                                                                    WHEN T.ID_TARIFA = 6 THEN 'R3'
                                                                                    WHEN T.ID_TARIFA = 10 THEN 'R4'
                                                                                    WHEN T.ID_TARIFA = 11 THEN 'R5'
                                                                                    WHEN T.ID_TARIFA = 14 THEN 'R6'
                                                                                    WHEN T.ID_TARIFA IN (1, 7, 12) THEN 'COM'
                                                                                    WHEN T.ID_TARIFA IN (3, 9) THEN 'OFC'
                                                                                    WHEN T.ID_TARIFA IN (2, 8, 13) THEN 'IND'
                                                                                END AS 'NOMBRE', T.NOMBRE AS NOMBRE_TARIFA, COUNT(C.ID_TARIFA) AS 'COUNT'
                                                                          FROM tarifas_2 T
                                                                          LEFT OUTER JOIN $bd_tabla_catastro C ON (C.ID_TARIFA = T.ID_TARIFA AND C.ID_COD_DPTO = $departamento AND C.ID_COD_MPIO = $municipio)
                                                                         GROUP BY CASE
                                                                                    WHEN T.ID_TARIFA = 4 THEN 'R1'
                                                                                    WHEN T.ID_TARIFA = 5 THEN 'R2'
                                                                                    WHEN T.ID_TARIFA = 6 THEN 'R3'
                                                                                    WHEN T.ID_TARIFA = 10 THEN 'R4'
                                                                                    WHEN T.ID_TARIFA = 11 THEN 'R5'
                                                                                    WHEN T.ID_TARIFA = 14 THEN 'R6'
                                                                                    WHEN T.ID_TARIFA IN (1, 7, 12) THEN 'COM'
                                                                                    WHEN T.ID_TARIFA IN (3, 9) THEN 'OFC'
                                                                                    WHEN T.ID_TARIFA IN (2, 8, 13) THEN 'IND'
                                                                                END
                                                                         ORDER BY COUNT(C.ID_TARIFA) DESC, T.NOMBRE ASC");
                    while ($row_clientes = mysqli_fetch_assoc($query_select_clientes)) {
                        $array_nombre_clientes[] = $row_clientes["NOMBRE"];
                        $array_count_clientes[] = $row_clientes["COUNT"];
                        $total_clientes = $total_clientes + $row_clientes['COUNT'];
                    ?>
                        {"country": "<?php echo $row_clientes['NOMBRE_TARIFA']; ?>", "litres": <?php echo $row_clientes['COUNT']; ?>},
                    <?php
                    }
                    $array_nombre_clientes[] = "TOT.";
                    $array_count_clientes[] = $total_clientes;
                ?>
            ];
            var series = chartC.series.push(new am4charts.PieSeries3D());
            series.dataFields.value = "litres";
            series.dataFields.category = "country";
            series.hiddenState.properties.endAngle = -90;
            series.labels.template.text = "{value.value} ({value.percent.formatNumber('#.0')}%)";
            //chartC.legend.valueLabels.template.text = "{value.value}";
            //chartC.exporting.menu = new am4core.ExportMenu();
    	}
        function drawChartFactura() {
            am4core.useTheme(am4themes_material);
            am4core.useTheme(am4themes_animated);
            var chartF = am4core.create("piechartFactura", am4charts.PieChart3D);
            chartF.hiddenState.properties.opacity = 0;
            chartF.legend = new am4charts.Legend();
            chartF.numberFormatter.numberFormat = '$ #,###.##';
            chartF.data = [
                <?php
                    $query_select_factura = mysqli_query($connection, "SELECT T.NOMBRE, SUM(F.IMPORTE_TRANS)
                                                                          FROM $bd_tabla_facturacion F, tarifas_2 T
                                                                         WHERE F.ID_TARIFA = T.ID_TARIFA
                                                                           AND F.ID_COD_DPTO = $departamento
                                                                           AND F.ID_COD_MPIO = $municipio
                                                                           AND F.CONCEPTO <> 'CI306'
                                                                         GROUP BY F.ID_TARIFA
                                                                         ORDER BY COUNT(*) DESC, T.NOMBRE ASC");
                    while ($row_factura = mysqli_fetch_assoc($query_select_factura)) { ?>
                        {"country": "<?php echo $row_factura['NOMBRE']; ?>","litres": <?php echo $row_factura['SUM(F.IMPORTE_TRANS)']; ?>},
                    <?php
                    }
                ?>
            ];
            var series = chartF.series.push(new am4charts.PieSeries3D());
            series.dataFields.value = "litres";
            series.dataFields.category = "country";
            series.hiddenState.properties.endAngle = -90;
            series.labels.template.text = "{value.value} ({value.percent.formatNumber('#.0')}%)";
            //series.labels.template.text = "{value.value}";
            //chartF.exporting.menu = new am4core.ExportMenu();
    	}
        function drawChartRecaudo() {
            am4core.useTheme(am4themes_material);
            am4core.useTheme(am4themes_animated);
            var chartR = am4core.create("piechartRecaudo", am4charts.PieChart3D);
            chartR.hiddenState.properties.opacity = 0;
            chartR.legend = new am4charts.Legend();
            chartR.numberFormatter.numberFormat = '$ #,###.##';
            chartR.data = [
                <?php
                    $query_select_recaudo = mysqli_query($connection, "SELECT T.NOMBRE, SUM(R.IMPORTE_TRANS)
                                                                          FROM $bd_tabla_recaudo R, tarifas_2 T
                                                                         WHERE R.ID_TARIFA = T.ID_TARIFA
                                                                           AND R.ID_COD_DPTO = $departamento
                                                                           AND R.ID_COD_MPIO = $municipio
                                                                         GROUP BY R.ID_TARIFA
                                                                         ORDER BY COUNT(*) DESC, T.NOMBRE ASC");
                    while ($row_recaudo = mysqli_fetch_assoc($query_select_recaudo)) { ?>
                        {"country": "<?php echo $row_recaudo['NOMBRE']; ?>","litres": <?php echo $row_recaudo['SUM(R.IMPORTE_TRANS)']; ?>},
                    <?php
                    }
                ?>
            ];
            var series = chartR.series.push(new am4charts.PieSeries3D());
            series.dataFields.value = "litres";
            series.dataFields.category = "country";
            series.hiddenState.properties.endAngle = -90;
            series.labels.template.text = "{value.value} ({value.percent.formatNumber('#.0')}%)";
            //series.labels.template.text = "{value.value}";
            //chartR.exporting.menu = new am4core.ExportMenu();
    	}
    </script>
    <script>
        function guardarPDF() {
            var nombre_archivo = "Reporte Totales Clientes - " + $("#nombre_municipio").val() + " Periodo " + $("#mes_factura").val().toUpperCase() + " " + $("#id_ano_factura").val();
            Promise.all([
                chartC.exporting.pdfmake,
                chartC.exporting.getImage("png")
            ]).then(function(res) {
                var pdfMake = res[0];
                var doc = {
                    pageSize: "A4",
                    pageOrientation: "portrait",
                    pageMargins: [30, 30, 30, 30],
                    content: []
                };
                doc.content.push({
                    text: "Reporte Totales Clientes - " + $("#nombre_municipio").val(),
                    fontSize: 16,
                    alignment: 'center',
                    bold: true,
                    margin: [0, 20, 0, 0]
                });
                doc.content.push({
                    text: "Periodo: " + $("#mes_factura").val().toUpperCase() + " " + $("#id_ano_factura").val(),
                    fontSize: 16,
                    alignment: 'center',
                    bold: true,
                    margin: [0, 10, 0, 15]
                });
                doc.content.push({
                    table: {
                        headerRows: 1,                        
                        widths: [ "*", "*", "*", "*", "*", "*", "*", "*", "*", "*" ],
                        body: [
                            [
                                { text: "<?php echo $array_nombre_clientes[0]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" },
                                { text: "<?php echo $array_nombre_clientes[1]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" },
                                { text: "<?php echo $array_nombre_clientes[2]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" },
                                { text: "<?php echo $array_nombre_clientes[3]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" },
                                { text: "<?php echo $array_nombre_clientes[4]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" },
                                { text: "<?php echo $array_nombre_clientes[5]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" },
                                { text: "<?php echo $array_nombre_clientes[6]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" },
                                { text: "<?php echo $array_nombre_clientes[7]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" },
                                { text: "<?php echo $array_nombre_clientes[8]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" },
                                { text: "<?php echo $array_nombre_clientes[9]; ?>", bold: true, fillColor: '#003153', color: "#FFFFFF", alignment: "center" }
                            ],
                            [ { text: "<?php echo number_format($array_count_clientes[0], 0, ',', '.'); ?>", alignment: "center" },
                              { text: "<?php echo number_format($array_count_clientes[1], 0, ',', '.'); ?>", alignment: "center" },
                              { text: "<?php echo number_format($array_count_clientes[2], 0, ',', '.'); ?>", alignment: "center" },
                              { text: "<?php echo number_format($array_count_clientes[3], 0, ',', '.'); ?>", alignment: "center" },
                              { text: "<?php echo number_format($array_count_clientes[4], 0, ',', '.'); ?>", alignment: "center" },
                              { text: "<?php echo number_format($array_count_clientes[5], 0, ',', '.'); ?>", alignment: "center" },
                              { text: "<?php echo number_format($array_count_clientes[6], 0, ',', '.'); ?>", alignment: "center" },
                              { text: "<?php echo number_format($array_count_clientes[7], 0, ',', '.'); ?>", alignment: "center" },
                              { text: "<?php echo number_format($array_count_clientes[8], 0, ',', '.'); ?>", alignment: "center" },
                              { text: "<?php echo number_format($array_count_clientes[9], 0, ',', '.'); ?>", alignment: "center" }
                            ],
                            [ { text: "<?php echo number_format(($array_count_clientes[0] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" },
                              { text: "<?php echo number_format(($array_count_clientes[1] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" },
                              { text: "<?php echo number_format(($array_count_clientes[2] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" },
                              { text: "<?php echo number_format(($array_count_clientes[3] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" },
                              { text: "<?php echo number_format(($array_count_clientes[4] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" },
                              { text: "<?php echo number_format(($array_count_clientes[5] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" },
                              { text: "<?php echo number_format(($array_count_clientes[6] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" },
                              { text: "<?php echo number_format(($array_count_clientes[7] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" },
                              { text: "<?php echo number_format(($array_count_clientes[8] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" },
                              { text: "<?php echo number_format(($array_count_clientes[9] / $total_clientes * 100), 1, ',', '.') . "%"; ?>", alignment: "center" }
                            ]
                        ]
                    },
                    margin: [0, 10, 0, 15]
                });
                doc.content.push({
                    image: res[1],
                    width: 530
                });
                pdfMake.createPdf(doc).download(nombre_archivo + ".pdf");
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            var chartC = "";
            $("#alert-consulta").attr("display", "block");
            $("#alert-consulta").fadeTo(3000, 500).fadeOut(500, function() {
                $("#alert-consulta").fadeOut();
            });
            var sw = $("#sw").val();
            switch (sw) {
                case '2':
                    var departamento = $("#departamento").val();
                    var municipio = $("#municipio").val();
                    var id_ano_factura = $("#id_ano_factura").val();
                    var id_mes_factura = $("#id_mes_factura").val();
                    var mes_factura = $("#mes_factura").val();
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Resultados.php",
                        dataType: "json",
                        data: "departamento="+departamento+"&municipio="+municipio+"&sw="+sw+
                              "&id_ano_factura="+id_ano_factura+"&id_mes_factura="+id_mes_factura+
                              "&mes_factura="+mes_factura,
                        success: function(data) {
                            $("#pagination-departamento").twbsPagination('destroy');
                            $("#pagination-departamento").twbsPagination({
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
                                        url: "Modelo/Cargar_Resultados.php",
                                        data: "departamento="+departamento+"&municipio="+municipio+"&sw="+sw+"&page="+page+
                                              "&id_ano_factura="+id_ano_factura+"&id_mes_factura="+id_mes_factura+
                                              "&mes_factura="+mes_factura,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_departamento").html(data);
                                        }
                                    });
                                }
                            });
                        }
                    });
                    break;
            }
            $("#tab_grafica_cliente").on("click", function() {
                drawChartClientes();
            });
            $("#tab_grafica_facturado").on("click", function() {
                drawChartFactura();
            });
            $("#tab_grafica_recaudo").on("click", function() {
                drawChartRecaudo();
            });
        });
    </script>