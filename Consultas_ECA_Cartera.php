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
        <title>AGM - Consultas Cartera Fallida</title>
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
            <?php include("Top Pages/Top_Page_Electricaribe.php"); ?>
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
                                                                    <a href="#">
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-search"></i>
                                                                        <span>Consultas</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Consultas_ECA_Cartera.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class='fas fa-filter'></i>
                                                                                    <span>Cartera Fallida</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Consultas_ECA.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class='fas fa-filter'></i>
                                                                                    <span>Fact. & Reca.</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Consultas_ECA_Graficos.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class='fas fas fa-chart-pie'></i>
                                                                                    <span>Con Graficos</span>
                                                                                </a>
                                                                            </li>
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
                                            <h1>Consultas Cartera Fallida</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#consulta_nic_tab" id="tab_consulta_nic" aria-controls="consulta_nic_tab" role="tab" data-toggle="tab">Por NIC</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#consulta_propietario_tab" id="tab_consulta_propietario" aria-controls="consulta_propietario_tab" role="tab" data-toggle="tab">Por Propietario</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#consulta_departamento_tab" id="tab_consulta_departamento" aria-controls="consulta_departamento_tab" role="tab" data-toggle="tab">Por Departamento</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="consulta_nic_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="consultar_nic" name="consultar_nic">
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
								<input type="text" class="form-control input-text input-sm" id="nic" name="nic" maxlength="8" placeholder="NIC" data-toogle="tooltip" title="NIC" onkeypress="return isNumeric(event)" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFields(0);"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="consulta_propietario_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="consultar_propietario" name="consultar_propietario">
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
								<input type="text" class="form-control input-text input-sm" id="propietario" name="propietario" maxlength="70" placeholder="Propietario" data-toogle="tooltip" title="PROPIETARIO" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFields(1);"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="consulta_departamento_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="consultar_departamento" name="consultar_departamento">
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
								<input type="hidden" id="id_departamento_departamento" name="id_departamento_departamento" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="departamento_departamento" name="departamento_departamento" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                            </div>
                                                            <div class="col-xs-6">
								<input type="hidden" id="id_municipio_departamento" name="id_municipio_departamento" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="municipio_departamento" name="municipio_departamento" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                            </div>
                                                            <div class="col-xs-3">
								<input type="hidden" id="id_ano_factura_departamento" name="id_ano_factura_departamento" value="" required="required" />
                                                                <input type="hidden" id="id_mes_factura_departamento" name="id_mes_factura_departamento" value="" required="required" />
                                                                <input type="hidden" id="mes_factura_departamento" name="mes_factura_departamento" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="periodo_departamento" name="periodo_departamento" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoNic(1)" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFields(2);"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
    <script src="Javascript/amcharts4/core.js"></script>
    <script src="Javascript/amcharts4/charts.js"></script>
    <script src="Javascript/amcharts4/themes/animated.js"></script>
    <script src="Javascript/amcharts4/themes/dataviz.js"></script>
    <script>
        function isNumeric(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function resetFields(id_menu) {
            switch (id_menu) {
                case 0:
                    $("#nic").focus();
                    break;
                case 1:
                    $("#propietario").focus();
                    break;
                case 2:
                    $("#id_ano_factura_departamento").val("");
                    $("#id_mes_factura_departamento").val("");
                    $("#mes_factura_departamento").val("");
                    $("#id_departamento_departamento").val("");
                    $("#id_municipio_departamento").val("");
                    $("#departamento_departamento").focus();
                    break;
            }
        }
        //POPUPS
        function infoTipoDepartamento(id_consulta, id_departamento, departamento) {
            if (id_consulta == 1) {
                $("#id_departamento_departamento").val(id_departamento);
                $("#departamento_departamento").val(departamento);
                $("#id_municipio_departamento").val("");
                $("#municipio_departamento").val("");
                $("#municipio_departamento").focus();
            } else {
                $("#id_departamento_tarifa").val(id_departamento);
                $("#departamento_tarifa").val(departamento);
                $("#id_municipio_tarifa").val("");
                $("#municipio_tarifa").val("");
                $("#municipio_tarifa").focus();
            }
        }
        function tipoDepartamento(id_consulta) {
            window.open("Combos/Tipo_Departamento.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
            if (id_consulta == 1) {
                $("#id_municipio_departamento").val(id_municipio);
                $("#municipio_departamento").val(municipio);
                $("#periodo_departamento").focus();
            } else {
                $("#id_municipio_tarifa").val(id_municipio);
                $("#municipio_tarifa").val(municipio);
                $("#periodo_tarifa").focus();
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_departamento;
            if (id_consulta == 1) {
                id_departamento = $("#id_departamento_departamento").val();
            } else {
                id_departamento = $("#id_departamento_tarifa").val();
            }
            window.open("Combos/Tipo_Municipio.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoPeriodoNic(id_consulta, id_ano_factura, id_mes_factura, mes_factura) {
            switch (id_consulta) {
                case '1':
                    $("#id_ano_factura_departamento").val(id_ano_factura);
                    $("#id_mes_factura_departamento").val(id_mes_factura);
                    $("#mes_factura_departamento").val(mes_factura);
                    $("#periodo_departamento").val(mes_factura + " - " + id_ano_factura);
                    break;
                case '2':
                    $("#id_ano_factura_tarifa").val(id_ano_factura);
                    $("#id_mes_factura_tarifa").val(id_mes_factura);
                    $("#mes_factura_tarifa").val(mes_factura);
                    $("#periodo_tarifa").val(mes_factura + " - " + id_ano_factura);
                    break;
                case '3':
                    $("#id_ano_cambio_tarifa").val(id_ano_factura);
                    $("#id_mes_cambio_tarifa").val(id_mes_factura);
                    $("#mes_cambio_tarifa").val(mes_factura);
                    $("#periodo_cambio_tarifa").val(mes_factura + " - " + id_ano_factura);
                    break;
            }
        }
        function periodoNic(id_consulta) {
            window.open("Combos/Periodo_Nic.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            var sw = 0;
            $("#nic").focus();
            $("#tab_consulta_nic").on("shown.bs.tab", function() {
                sw = 0;
                $("#nic").focus();
            });
            $("#tab_consulta_propietario").on("shown.bs.tab", function() {
                sw = 1;
                $("#propietario").focus();
            });
            $("#tab_consulta_departamento").on("shown.bs.tab", function() {
                sw = 2;
                $("#departamento_departamento").focus();
            });
            $("#tab_consulta_nic").on("click", function() {
                sw = 0;
                $("#nic").focus();
            });
            $("#tab_consulta_propietario").on("click", function() {
                sw = 1;
                $("#propietario").focus();
            });
            $("#tab_consulta_departamento").on("click", function() {
                sw = 2;
                $("#departamento_departamento").focus();
            });
            $("#btn_consultar").click(function() {
                switch(sw) {
                    case 0:
                        var nic = $("#nic").val();
                        if (nic.length == 0) {
                            $("#nic").focus();
                            return false;
                        }
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Consulta...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+'&nic='+nic,
                            url: 'Modelo/Resultado_Busqueda_Cartera.php',
                            success: function(data) {
                                $("#rightcol").html(data);
                            }
                        });
                        break;
                    case 1:
                        var propietario = $("#propietario").val();
                        if (propietario.length == 0) {
                            $("#propietario").focus();
                            return false;
                        }
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Consulta...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+'&propietario='+propietario,
                            url: 'Modelo/Resultado_Busqueda_Cartera.php',
                            success: function(data) {
                                $("#rightcol").html(data);
                            }
                        });
                        break;
                    case 2:
                        var departamento = $("#id_departamento_departamento").val();
                        var municipio = $("#id_municipio_departamento").val();
                        var id_ano_factura = $("#id_ano_factura_departamento").val();
                        var id_mes_factura = $("#id_mes_factura_departamento").val();
                        var mes_factura = $("#mes_factura_departamento").val();
                        if (departamento.length == 0) {
                            $("#departamento_departamento").focus();
                            return false;
                        }
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Consulta...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+'&departamento='+departamento+'&municipio='+municipio+
                                  '&id_ano_factura='+id_ano_factura+'&id_mes_factura='+id_mes_factura+
                                  '&mes_factura='+mes_factura,
                            url: 'Modelo/Resultado_Busqueda_Cartera.php',
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
        $(document).ready(function(){
            $('input[type=text][name=nic]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=propietario]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=departamento_departamento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=municipio_departamento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=periodo_departamento]').tooltip({
                container: "body",
                placement: "top"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_electricaribe").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>