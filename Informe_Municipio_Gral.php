<?php
    session_start();
    require_once('Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Reporte Municipio General</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
        <style type="text/css">
            #pieColumnChartMunicipioOper {
                width: 100%;
                max-height: 600px;
                height: 100vh;
            }
            #pieColumnChartMunicipioComer {
                width: 100%;
                max-height: 600px;
                height: 100vh;
            }
            .text-divider{margin: 2em 0; line-height: 0; text-align: center;}
            .text-divider span{background-color: #D0DEE7; padding: 1em;}
            .text-divider:before{ content: " "; display: block; border-top: 1px solid #A9BDC8;}
        </style>
    </head>
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Consultas_Generales.php");?>
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="leftcol">
                                            <div class="page-wrapper chiller-theme toggled">
                                                <nav id="sidebar" class="sidebar-wrapper">
                                                    <div class="sidebar-content">
                                                        <div class="sidebar-menu">
                                                            <ul>
                                                                <li class="header-menu">
                                                                    <h3 style="text-align: center;">OPCIONES DISPONIBLES</h3>
                                                                </li>
                                                                <li class="sidebar-dropdown active">
                                                                    <a href='#'>
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-city"></i>
                                                                        <span>Inf. Municipio Gral.</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Informe_Municipio_Gral.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-city"></i>
                                                                                    <span>Inf. Municipio Gral.</span>
                                                                                </a>
                                                                            </li>
                                                                            <!--<li>
                                                                                <a href='Informe_Recaudo_Gral.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-invoice-dollar"></i>
                                                                                    <span>Inf. Recaudo Gral.</span>
                                                                                </a>
                                                                            </li>-->
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="rightcol">
                                            <h1>Informe Municipio General</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#consulta_departamento_tab" id="tab_consulta_departamento" aria-controls="consulta_departamento_tab" role="tab" data-toggle="tab">Por Departamento</a>
                                                </li>
                                                <!--<li role="presentation">
                                                    <a href="#consulta_operador_tab" id="tab_consulta_operador" aria-controls="consulta_operador_tab" role="tab" data-toggle="tab">Por Operador</a>
                                                </li>-->
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="consulta_departamento_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="consultar_departamento" name="consultar_departamento">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_inicio">Periodo I.:</label>
                                                            <div class="col-xs-2">
                                                                <div class="input-group date" id="periodo_inicio" data-toogle="tooltip" title="PERIODO INICIO">
                                                                    <input type="text" class="form-control input-text input-sm" name="periodo_inicio" value="<?php echo date('Y-m'); ?>" placeholder="Periodo Inicio" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                    <span class="input-group-addon">
                                                                        <span class="far fa-calendar-alt"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_fin">Periodo F.:</label>
                                                            <div class="col-xs-2">
                                                                <div class="input-group date" id="periodo_fin" data-toogle="tooltip" title="PERIODO FIN">
                                                                    <input type="text" class="form-control input-text input-sm" name="periodo_fin" value="" placeholder="Periodo Fin" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                    <span class="input-group-addon">
                                                                        <span class="far fa-calendar-alt"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <!--<div class="col-xs-2">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="periodo_departamento" name="periodo_departamento" data-toggle="tooltip" title="PERIODO">
                                                                        <option value="" selected="selected">-</option>
                                                                        <?php
                                                                            $query_select_periodo_fact_operador = mysqli_query($connection, "SELECT DISTINCT(PERIODO_FACTURA) FROM facturacion_operadores_2");
                                                                            while ($row_periodo_fact_operador = mysqli_fetch_assoc($query_select_periodo_fact_operador)) {
                                                                                echo "<option value='" . $row_periodo_fact_operador['PERIODO_FACTURA'] . "'>" . $row_periodo_fact_operador['PERIODO_FACTURA'] . "</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>-->
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento_departamento">Dpto.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="departamento_departamento" name="departamento_departamento" data-toggle="tooltip" title="DEPARTAMENTO">
                                                                        <option value="" selected="selected">-</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="municipio_departamento">Mpio.:</label>
                                                            <div class="col-xs-4">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="municipio_departamento" name="municipio_departamento" data-toggle="tooltip" title="MUNICIPIO">
                                                                        <option value="" selected="selected">-</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-1">
                                                                <span id="loading-spinner" style="display: none; float: left;"><img src="Images/squares.gif" /></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetField(0);"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="consulta_operador_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="consultar_operador" name="consultar_operador">
                                                        
                                                    </form>
                                                </div>
                                            </div>
                                            <div style="margin-bottom: 15px; margin-top: 15px;" class="divider"></div>
                                            <div style="text-align: right; margin-bottom: 10px;" class="col-xs-12">
                                                <button class="btn btn-primary btn-sm font background cursor" id="btn_generar_informe" type="submit"><i style="font-size: 14px;" class="fas fa-search"></i>&nbsp;&nbsp;Generar Informe</button>&nbsp;&nbsp;
                                            </div>
                                            <br />
                                            <br />
                                            <div style="padding-left: 0px; padding-right: 0px;" class="col-xs-12">
                                                <h2 class="text-divider"><span style="background-color: #FFFFFF;">RESUMEN INFORME / GRÁFICA</span></h2>
                                            </div>
                                            <br />
                                            <br />
                                            <p style='color: #003153; font-weight: bold; margin-bottom: 0px;' id="info_municipio"></p>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#resultado_operador_tab" id="tab_resultado_operador" aria-controls="resultado_operador_tab" role="tab" data-toggle="tab">Operadores de Red</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#resultado_comercializador_tab" id="tab_consulta_comercializador" aria-controls="resultado_comercializador_tab" role="tab" data-toggle="tab">Comercializadores</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#resultado_clientes_especiales_tab" id="tab_consulta_clientes_especiales" aria-controls="resultado_clientes_especiales_tab" role="tab" data-toggle="tab">Clientes Especiales</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#resultado_aportes_municipales_tab" id="tab_consulta_aportes_municipales" aria-controls="resultado_aportes_municipales_tab" role="tab" data-toggle="tab">Aportes Municipales</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="resultado_operador_tab">
                                                    <div style="padding-left: 0px; padding-right: 0px;" class="col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-condensed table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th width=52%>OPERADOR RED</th>
                                                                        <th width=12%>FACTURACIÓN</th>
                                                                        <th width=12%>RECAUDO</th>
                                                                        <th width=12%>VAL. ENERGÍA</th>
                                                                        <th width=12%>VAR. A FAVOR</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="resultado_informe_municipio_oper">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <div class='col-xs-12'>
                                                        <div style='text-align: center; font-size: 12px; display: none;' id='pieColumnChartMunicipioOper'></div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="resultado_comercializador_tab">
                                                    <div style="padding-left: 0px; padding-right: 0px;" class="col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-condensed table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th width=52%>COMERCIALIZADORES</th>
                                                                        <th width=12%>FACTURACIÓN</th>
                                                                        <th width=12%>RECAUDO</th>
                                                                        <th width=12%>VAL. ENERGÍA</th>
                                                                        <th width=12%>VAR. A FAVOR</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="resultado_informe_municipio_comer">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <div class='col-xs-12'>
                                                        <div style='text-align: center; font-size: 12px; display: none;' id='pieColumnChartMunicipioComer'></div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="resultado_clientes_especiales_tab">
                                                    <div style="padding-left: 0px; padding-right: 0px;" class="col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-condensed table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th width=52%>CLIENTE ESPECIAL</th>
                                                                        <th width=12%>FACTURACIÓN</th>
                                                                        <th width=12%>RECAUDO</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="resultado_informe_municipio_cliesp">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <div class='col-xs-12'>
                                                        <div style='text-align: center; font-size: 12px; display: none;' id='pieColumnChartMunicipioCliEsp'></div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="resultado_aportes_municipales_tab">
                                                    <div style="padding-left: 0px; padding-right: 0px;" class="col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-condensed table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th width=52%>MUNICIPIO</th>
                                                                        <th width=12%>FACTURACIÓN</th>
                                                                        <th width=12%>RECAUDO</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="resultado_informe_municipio_apor_mun">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <div class='col-xs-12'>
                                                        <div style='text-align: center; font-size: 12px; display: none;' id='pieColumnChartMunicipioAporMun'></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="Javascript/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="Javascript/menu.js"></script>
    <script src="Javascript/moment-with-locales.js"></script>
    <script src="Javascript/bootstrap-datetimepicker.js"></script>
    <script src="Javascript/jquery.twbsPagination.js"></script>
    <script src="Javascript/amcharts4/core.js"></script>
    <script src="Javascript/amcharts4/charts.js"></script>
    <script src="Javascript/amcharts4/themes/material.js"></script>
    <script src="Javascript/amcharts4/themes/animated.js"></script>
    <script>
        function isNumeric(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function isNothing(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 0 && (charCode < 255)) {
                return false;
            }
            return true;
        }
        function resetField(id) {
            switch(id) {
                case 0:
                    $("input[name=periodo_inicio]").focus();
                    $("#resultado_informe_municipio_oper").html("");
                    $("#resultado_informe_municipio_comer").html("");
                    $("#info_municipio").html("");
                    $("#pieColumnChartMunicipioOper").css("display", "none");
                    $("#pieColumnChartMunicipioComer").css("display", "none");
                    break;
            }
        }
        function drawColumnChartInformeOper(datos) {
            am4core.useTheme(am4themes_material);
            am4core.useTheme(am4themes_animated);
            chartO = am4core.create("pieColumnChartMunicipioOper", am4charts.XYChart3D);
            chartO.colors.step = 2;
            chartO.fontSize = 11;
            chartO.fontWeight = 'bold';
            chartO.legend = new am4charts.Legend();
            chartO.legend.position = 'top';
            chartO.legend.paddingBottom = 20;
            chartO.numberFormatter.numberFormat = '$ #,###.##';
            chartO.legend.labels.template.maxWidth = 95;
            chartO.scrollbarX = new am4core.Scrollbar();
            chartO.scrollbarY = new am4core.Scrollbar();
            //chartC.data = datos;
            
            var xAxis = chartO.xAxes.push(new am4charts.CategoryAxis());
            xAxis.dataFields.category = 'category';
            xAxis.renderer.cellStartLocation = 0.1;
            xAxis.renderer.cellEndLocation = 0.8;
            xAxis.renderer.grid.template.location = 0;
            var yAxis = chartO.yAxes.push(new am4charts.ValueAxis());
            yAxis.min = 0;
            function createSeries(value, name) {
                var series = chartO.series.push(new am4charts.ColumnSeries3D());
                series.dataFields.valueY = value;
                series.dataFields.categoryX = 'category';
                series.name = name;
                series.columns.template.tooltipText = "{name}: [bold] {valueY}[/]";
                series.columns.template.tooltipY = "{name}: [bold] {valueY}[/]";
                series.columns.template.width = am4core.percent(95);
                series.events.on("hidden", arrangeColumns);
                series.events.on("shown", arrangeColumns);
                var bullet = series.bullets.push(new am4charts.LabelBullet());
                bullet.interactionsEnabled = false;
                /*bullet.dy = 30;
                bullet.label.text = '{name}';
                bullet.label.fill = am4core.color('#FFFFFF');*/
                return series;
            }
            chartO.data = datos;
            createSeries('facturacion', '[bold]FACTURACIÓN[/]');
            createSeries('recaudo', '[bold]RECAUDO[/]');
            createSeries('valor_energia', '[bold]VALOR ENERGÍA[/]');
            createSeries('valor_favor', '[bold]VALOR A FAVOR[/]');
            function arrangeColumns() {
                var series = chartO.series.getIndex(0);
                var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
                if (series.dataItems.length > 1) {
                    var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
                    var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
                    var delta = ((x1 - x0) / chartO.series.length) * w;
                    if (am4core.isNumber(delta)) {
                        var middle = chartO.series.length / 2;
                        var newIndex = 0;
                        chartO.series.each(function(series) {
                            if (!series.isHidden && !series.isHiding) {
                                series.dummyData = newIndex;
                                newIndex++;
                            }
                            else {
                                series.dummyData = chartO.series.indexOf(series);
                            }
                        });
                        var visibleCount = newIndex;
                        var newMiddle = visibleCount / 2;
                        chartO.series.each(function(series) {
                            var trueIndex = chartO.series.indexOf(series);
                            var newIndex = series.dummyData;
                            var dx = (newIndex - trueIndex + middle - newMiddle) * delta;
                            series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                            series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                        });
                    }
                }
            }
    	}
        function drawColumnChartInformeComer(datos) {
            am4core.useTheme(am4themes_material);
            am4core.useTheme(am4themes_animated);
            chartC = am4core.create("pieColumnChartMunicipioComer", am4charts.XYChart3D);
            chartC.colors.step = 2;
            chartC.fontSize = 11;
            chartC.fontWeight = 'bold';
            chartC.legend = new am4charts.Legend();
            chartC.legend.position = 'top';
            chartC.legend.paddingBottom = 20;
            chartC.numberFormatter.numberFormat = '$ #,###.##';
            chartC.legend.labels.template.maxWidth = 95;
            chartC.scrollbarX = new am4core.Scrollbar();
            chartC.scrollbarY = new am4core.Scrollbar();
            //chartC.data = datos;
            
            var xAxis = chartC.xAxes.push(new am4charts.CategoryAxis());
            xAxis.dataFields.category = 'category';
            xAxis.renderer.cellStartLocation = 0.1;
            xAxis.renderer.cellEndLocation = 0.8;
            xAxis.renderer.grid.template.location = 0;
            var yAxis = chartC.yAxes.push(new am4charts.ValueAxis());
            yAxis.min = 0;
            function createSeries(value, name) {
                var series = chartC.series.push(new am4charts.ColumnSeries3D());
                series.dataFields.valueY = value;
                series.dataFields.categoryX = 'category';
                series.name = name;
                series.columns.template.tooltipText = "{name}: [bold] {valueY}[/]";
                series.columns.template.tooltipY = "{name}: [bold] {valueY}[/]";
                series.columns.template.width = am4core.percent(95);
                series.events.on("hidden", arrangeColumns);
                series.events.on("shown", arrangeColumns);
                var bullet = series.bullets.push(new am4charts.LabelBullet());
                bullet.interactionsEnabled = false;
                /*bullet.dy = 30;
                bullet.label.text = '{name}';
                bullet.label.fill = am4core.color('#FFFFFF');*/
                return series;
            }
            chartC.data = datos;
            createSeries('facturacion', '[bold]FACTURACIÓN[/]');
            createSeries('recaudo', '[bold]RECAUDO[/]');
            createSeries('valor_energia', '[bold]VALOR ENERGÍA[/]');
            createSeries('valor_favor', '[bold]VALOR A FAVOR[/]');
            function arrangeColumns() {
                var series = chartC.series.getIndex(0);
                var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
                if (series.dataItems.length > 1) {
                    var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
                    var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
                    var delta = ((x1 - x0) / chartC.series.length) * w;
                    if (am4core.isNumber(delta)) {
                        var middle = chartC.series.length / 2;
                        var newIndex = 0;
                        chartC.series.each(function(series) {
                            if (!series.isHidden && !series.isHiding) {
                                series.dummyData = newIndex;
                                newIndex++;
                            }
                            else {
                                series.dummyData = chartC.series.indexOf(series);
                            }
                        });
                        var visibleCount = newIndex;
                        var newMiddle = visibleCount / 2;
                        chartC.series.each(function(series) {
                            var trueIndex = chartC.series.indexOf(series);
                            var newIndex = series.dummyData;
                            var dx = (newIndex - trueIndex + middle - newMiddle) * delta;
                            series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                            series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
                        });
                    }
                }
            }
            
    	}
    </script>
    <script>
        $(document).ready(function() {
            var sw = 0;
            $("input[name=periodo_inicio]").focus();
            $("#periodo_inicio").datetimepicker({
                format: 'YYYY-MM'
            });
            $("#periodo_fin").datetimepicker({
                format: 'YYYY-MM'
            });
            $("#tab_consulta_departamento").on("shown.bs.tab", function() {
                var sw = 0;
                $("input[name=periodo_inicio]").focus();
            });
            $("#tab_consulta_departamento").on("click", function() {
                var sw = 0;
                $("input[name=periodo_inicio]").focus();
            });
            $("#periodo_fin").on('dp.update', function() {
                var periodo_inicio = $("input[name=periodo_inicio]").val();
                var periodo_fin = $("input[name=periodo_fin]").val();
                $("#loading-spinner").css("display", "block");
                $.ajax({
                    type: "POST",
                    data: "sw=mpio_gral&periodo_inicio="+periodo_inicio+"&periodo_fin="+periodo_fin,
                    url: "Modelo/Cargar_Departamentos.php",
                    success: function(data) {
                        $("#loading-spinner").css("display", "none");
                        $("#departamento_departamento").html(data);
                        $("#departamento_departamento").focus();
                    }
                });
            });
            $("#departamento_departamento").change(function() {
                var periodo_inicio = $("input[name=periodo_inicio]").val();
                var periodo_fin = $("input[name=periodo_fin]").val();
                var departamento_departamento = $(this).val();
                $("#loading-spinner").css("display", "block");
                $.ajax({
                    type: "POST",
                    data: "sw=mpio_gral&periodo_inicio="+periodo_inicio+"&periodo_fin="+periodo_fin+"&departamento_departamento="+departamento_departamento,
                    url: "Modelo/Cargar_Municipios.php",
                    success: function(data) {
                        $("#loading-spinner").css("display", "none");
                        $("#municipio_departamento").html(data);
                        $("#municipio_departamento").focus();
                    }
                });
            });
            $("#btn_generar_informe").click(function() {
                switch(sw) {
                    case 0:
                        var periodo_inicio = $("input[name=periodo_inicio]").val();
                        var periodo_fin = $("input[name=periodo_fin]").val();
                        var departamento_departamento = $("#departamento_departamento").val();
                        var municipio_departamento = $("#municipio_departamento").val();
                        $("#loading-spinner").css("display", "block");
                        $("#btn_generar_informe").attr("disabled", true);
                        $("#btn_generar_informe").css("pointer-events", "none");
                        $("#btn_generar_informe").html("Generando Informe...");
                        $.ajax({
                            type: "POST",
                            data: "sw="+sw+"&periodo_inicio="+periodo_inicio+"&periodo_fin="+periodo_fin+
                                  "&departamento_departamento="+departamento_departamento+
                                  "&municipio_departamento="+municipio_departamento,
                            dataType: "json",
                            url: "Modelo/Generar_Informe_Municipio_Gral.php",
                            success: function(data) {
                                $("#loading-spinner").css("display", "none");
                                $("#btn_generar_informe").attr("disabled", false);
                                $("#btn_generar_informe").css("pointer-events", "auto");
                                $("#btn_generar_informe").html("<i style='font-size: 14px;' class='fas fa-search'></i>&nbsp;&nbsp;Generar Informe&nbsp;&nbsp;");
                                $("#resultado_informe_municipio_oper").html("");
                                $("#resultado_informe_municipio_oper").html(data[0]);
                                $("#resultado_informe_municipio_comer").html("");
                                $("#resultado_informe_municipio_comer").html(data[3]);
                                $("#pieColumnChartMunicipioOper").css('display', 'block');
                                $("#pieColumnChartMunicipioComer").css('display', 'block');
                                drawColumnChartInformeOper(data[1]);
                                drawColumnChartInformeComer(data[4]);
                                $("#info_municipio").html(data[2]);
                            }
                        });
                        break;
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#periodo_inicio').tooltip({
                container : "body",
                placement : "top"
            });
            $('#periodo_fin').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=periodo_departamento]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=departamento_departamento]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=municipio_departamento]').tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_consultas_grales").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>