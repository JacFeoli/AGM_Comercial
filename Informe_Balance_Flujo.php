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
        <title>AGM - Informe Balance Flujo</title>
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-chart-bar"></i>
                                                                        <span>Inf. Balance Flujo</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Informe_Balance_Flujo.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-chart-bar"></i>
                                                                                    <span>Inf. Balance Flujo</span>
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
                                            <h1>Informe Histórico Balance Flujo</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#reporte_historico_tab" id="tab_reporte_historico" aria-controls="reporte_historico_tab" role="tab" data-toggle="tab">Histórico Balance Flujo</a>
                                                </li>
                                                <!--<li role="presentation">
                                                    <a href="#reporte_rango_tab" id="tab_reporte_rango" aria-controls="reporte_rango_tab" role="tab" data-toggle="tab">Rango Fechas</a>
                                                </li>-->
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="reporte_historico_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="reporte_historico" name="reporte_historico">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento_historico">Dpto.:</label>
                                                            <div class="col-xs-3">
								<input type="hidden" id="id_departamento_historico" name="id_departamento_historico" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="departamento_historico" name="departamento_historico" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="municipio_historico">Mpio.:</label>
                                                            <div class="col-xs-3">
								<input type="hidden" id="id_municipio_historico" name="id_municipio_historico" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="municipio_historico" name="municipio_historico" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
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
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
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
                    $("#id_departamento_historico").val("");
                    $("#id_municipio_historico").val("");
                    $("#departamento_historico").focus();
                    break;
                case 5:
                    $("input[name=fecha_inicio]").focus();
                    break;
            }
        }
        //POPUPS
        function infoTipoDepartamento(id_consulta, id_departamento, departamento) {
            if (id_consulta == 1) {
                $("#id_departamento_historico").val(id_departamento);
                $("#departamento_historico").val(departamento);
                $("#id_municipio_historico").val("");
                $("#municipio_historico").val("");
                $("#municipio_historico").focus();
            }
        }
        function tipoDepartamento(id_consulta) {
            window.open("Combos/Tipo_Departamento_Visita.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
            if (id_consulta == 1) {
                $("#id_municipio_historico").val(id_municipio);
                $("#municipio_historico").val(municipio);
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_departamento;
            if (id_consulta == 1) {
                id_departamento = $("#id_departamento_historico").val();
            }
            window.open("Combos/Tipo_Municipio_Visita.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            var sw = 4;
            $("#departamento_historico").focus();
            $("#tab_reporte_historico").on("shown.bs.tab", function() {
                sw = 4;
                $("#departamento_historico").focus();
            });
            $("#tab_reporte_historico").on("click", function() {
                sw = 4;
                $("#departamento_historico").focus();
            });
            $("#btn_consultar").click(function() {
                switch(sw) {
                    case 4:
                        var id_departamento_historico = $("#id_departamento_historico").val();
                        var id_municipio_historico = $("#id_municipio_historico").val();
                        if (id_departamento_historico.length == 0) {
                            $("#departamento_historico").focus();
                            return false;
                        }
                        if (id_municipio_historico.length == 0) {
                            $("#municipio_historico").focus();
                            return false;
                        }
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Consulta...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+
                                  '&id_departamento_historico='+id_departamento_historico+
                                  '&id_municipio_historico='+id_municipio_historico,
                            url: 'Modelo/Resultado_Informe_BalanceF.php',
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
            $('input[type=text][name=departamento_historico]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=municipio_historico]').tooltip({
                container: "body",
                placement: "right"
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