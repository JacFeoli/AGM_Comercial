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
        <title>AGM - Informe Recaudo General</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-invoice-dollar"></i>
                                                                        <span>Inf. Recaudo Gral.</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Informe_Recaudo_Gral.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-invoice-dollar"></i>
                                                                                    <span>Inf. Recaudo Gral.</span>
                                                                                </a>
                                                                            </li>
                                                                            <!--<li>
                                                                                <a href='Informe_Municipio_Gral.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="far fa-chart-bar"></i>
                                                                                    <span>Inf. Municipio Gral.</span>
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
                                        <div id="rightcol" class="rightcol">
                                            <h1>Informe Recaudo General</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#reporte_mensual_tab" id="tab_reporte_mensual" aria-controls="reporte_mensual_tab" role="tab" data-toggle="tab">Mensual</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#reporte_rango_tab" id="tab_reporte_rango" aria-controls="reporte_rango_tab" role="tab" data-toggle="tab">Rango Fechas</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="reporte_mensual_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="reporte_mensual" name="reporte_mensual">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_mensual">Periodo:</label>
                                                            <div class="col-xs-3">
								<input type="hidden" id="id_ano_mensual" name="id_ano_mensual" value="" required="required" />
                                                                <input type="hidden" id="id_mes_mensual" name="id_mes_mensual" value="" required="required" />
                                                                <input type="hidden" id="mes_mensual" name="mes_mensual" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="periodo_mensual" name="periodo_mensual" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoRecaudo(5)" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFields(4);"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="reporte_rango_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="reporte_rango" name="reporte_rango">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_inicio">Fecha Ini.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_inicio" data-toogle="tooltip" title="FECHA INICIO">
                                                                    <input type="text" class="form-control input-text input-sm" name="fecha_inicio" value="<?php echo date('Y-m-d'); ?>" placeholder="Fecha Inicio" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                    <span class="input-group-addon">
                                                                        <span class="far fa-calendar-alt"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_fin">Fecha Fin:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_fin" data-toogle="tooltip" title="FECHA FIN">
                                                                    <input type="text" class="form-control input-text input-sm" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" placeholder="Fecha Fin" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                    <span class="input-group-addon">
                                                                        <span class="far fa-calendar-alt"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFields(5);"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div style="margin-bottom: 15px; margin-top: 15px;" class="divider"></div>
                                            <div style="text-align: right; margin-bottom: 10px;" class="col-xs-12">
                                                <button class="btn btn-primary btn-sm font background cursor" id="btn_consultar" type="submit"><i style="font-size: 14px;" class="fas fa-search"></i>&nbsp;&nbsp;Consultar</button>&nbsp;&nbsp;
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
    <script src="Javascript/menu.js"></script>
    <script src="Javascript/moment-with-locales.js"></script>
    <script src="Javascript/bootstrap-datetimepicker.js"></script>
    <script src="Javascript/jquery.twbsPagination.js"></script>
    <script>
        function isNumeric(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function isNothing(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 0 && (charCode < 255)) {
                return false;
            }
            return true;
        }
        function resetFields(id_menu) {
            switch (id_menu) {
                case 4:
                    $("#id_ano_mensual").val("");
                    $("#id_mes_mensual").val("");
                    $("#mes_mensual").val("");
                    $("#periodo_mensual").focus();
                    break;
                case 5:
                    $("input[name=fecha_inicio]").focus();
                    break;
            }
        }
        //POPUPS
        function infoPeriodoRecaudo(id_consulta, id_ano, id_mes, mes) {
            switch (id_consulta) {
                case '5':
                    $("#id_ano_mensual").val(id_ano);
                    $("#id_mes_mensual").val(id_mes);
                    $("#mes_mensual").val(mes);
                    $("#periodo_mensual").val(mes + " - " + id_ano);
                    break;
            }
        }
        function periodoRecaudo(id_consulta) {
            window.open("Combos/Periodo_Informe_Reca.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            var sw = 4;
            $("#periodo_mensual").focus();
            $("#fecha_inicio").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#fecha_fin").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#tab_reporte_mensual").on("shown.bs.tab", function() {
                sw = 4;
                $("#periodo_mensual").focus();
            });
            $("#tab_reporte_rango").on("shown.bs.tab", function() {
                sw = 5;
                $("input[name=fecha_inicio]").focus();
            });
            $("#tab_reporte_mensual").on("click", function() {
                sw = 4;
                $("#periodo_mensual").focus();
            });
            $("#tab_reporte_rango").on("click", function() {
                sw = 5;
                $("input[name=fecha_inicio]").focus();
            });
            $("#btn_consultar").click(function() {
                switch(sw) {
                    case 4:
                        var id_ano_mensual = $("#id_ano_mensual").val();
                        var id_mes_mensual = $("#id_mes_mensual").val();
                        var periodo_mensual = $("#periodo_mensual").val();
                        if (periodo_mensual.length == 0) {
                            $("#periodo_mensual").focus();
                            return false;
                        }
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Consulta...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+
                                  '&id_ano_mensual='+id_ano_mensual+
                                  '&id_mes_mensual='+id_mes_mensual+
                                  '&periodo_mensual='+periodo_mensual,
                            url: 'Modelo/Resultado_Informe_Reca.php',
                            success: function(data) {
                                $("#rightcol").html(data);
                            }
                        });
                        break;
                    case 5:
                        var fecha_inicio = $("input[name=fecha_inicio]").val();
                        var fecha_fin = $("input[name=fecha_fin]").val();
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Consulta...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+
                                  '&fecha_inicio='+fecha_inicio+
                                  '&fecha_fin='+fecha_fin,
                            url: 'Modelo/Resultado_Informe_Reca.php',
                            success: function(data) {
                                $("#rightcol").html(data);
                            }
                        });
                        break;
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[type=text][name=periodo_mensual]').tooltip({
                container: "body",
                placement: "right"
            });
            $('#fecha_inicio').tooltip({
                container : "body",
                placement : "top"
            });
            $('#fecha_fin').tooltip({
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