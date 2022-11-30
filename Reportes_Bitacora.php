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
        <title>AGM - Reportes Bitacora</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
    </head>
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Home.php"); ?>
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-globe-americas"></i>
                                                                        <span>Visitas</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Reportes_Bitacora.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-chart-pie"></i>
                                                                                    <span>Reportes</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Municipios_Libretas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-edit"></i>
                                                                                    <span>Municipios</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Bitacora_Acuerdos.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-book"></i>
                                                                                    <span>Bitacora / Acuerdos</span>
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
                                            <h1>Reportes Bitacora</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#reporte_mensual_tab" id="tab_reporte_mensual" aria-controls="reporte_mensual_tab" role="tab" data-toggle="tab">Mensual</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#reporte_municipio_tab" id="tab_reporte_municipio" aria-controls="reporte_municipio_tab" role="tab" data-toggle="tab">Municipio</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#reporte_usuario_tab" id="tab_reporte_usuario" aria-controls="reporte_usuario_tab" role="tab" data-toggle="tab">Usuario</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#reporte_tipo_visita_tab" id="tab_reporte_tipo_visita" aria-controls="reporte_tipo_visita_tab" role="tab" data-toggle="tab">Tipo Visita</a>
                                                </li>
                                                <!--<li role="presentation">
                                                    <a href="#reporte_graficos_tab" id="tab_reporte_graficos" aria-controls="reporte_graficos_tab" role="tab" data-toggle="tab">Gráficos</a>
                                                </li>-->
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade" id="reporte_municipio_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="reporte_municipio" name="reporte_municipio">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento_municipio">Dpto.:</label>
                                                            <div class="col-xs-3">
								                                <input type="hidden" id="id_departamento_municipio" name="id_departamento_municipio" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="departamento_municipio" name="departamento_municipio" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="municipio_municipio">Mpio.:</label>
                                                            <div class="col-xs-3">
								                                <input type="hidden" id="id_municipio_municipio" name="id_municipio_municipio" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="municipio_municipio" name="municipio_municipio" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_municipio">Periodo:</label>
                                                            <div class="col-xs-3">
								                                <input type="hidden" id="id_ano_municipio" name="id_ano_municipio" value="" required="required" />
                                                                <input type="hidden" id="id_mes_municipio" name="id_mes_municipio" value="" required="required" />
                                                                <input type="hidden" id="mes_municipio" name="mes_municipio" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="periodo_municipio" name="periodo_municipio" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoBitacora(1)" />
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
                                                <div role="tabpanel" class="tab-pane fade" id="reporte_usuario_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="reporte_usuario" name="reporte_usuario">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="usuario_bitacora">Usuario:</label>
                                                            <div class="col-xs-5">
								                                <input type="hidden" id="id_usuario_bitacora" name="id_usuario_bitacora" value="" />
                                                                <input type="text" class="form-control input-text input-sm" id="usuario_bitacora" name="usuario_bitacora" placeholder="Usuario" data-toogle="tooltip" readonly="readonly" title="USUARIO" onclick="usuarioBitacora()" />
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_bitacora">Periodo:</label>
                                                            <div class="col-xs-5">
								                                <input type="hidden" id="id_ano_bitacora" name="id_ano_bitacora" value="" required="required" />
                                                                <input type="hidden" id="id_mes_bitacora" name="id_mes_bitacora" value="" required="required" />
                                                                <input type="hidden" id="mes_bitacora" name="mes_bitacora" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="periodo_bitacora" name="periodo_bitacora" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoBitacora(2)" />
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
                                                <div role="tabpanel" class="tab-pane fade" id="reporte_tipo_visita_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="reporte_tipo_visita" name="reporte_tipo_visita">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="tipo_visita">T. Visita:</label>
                                                            <div class="col-xs-7">
								                                <input type="hidden" id="id_tipo_visita" name="id_tipo_visita" value="" />
                                                                <input type="text" class="form-control input-text input-sm" id="tipo_visita" name="tipo_visita" placeholder="Tipo Visita" data-toogle="tooltip" readonly="readonly" title="TIPO VISITA" onclick="tipoVisita()" />
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_tipo_visita">Periodo:</label>
                                                            <div class="col-xs-3">
								                                <input type="hidden" id="id_ano_tipo_visita" name="id_ano_tipo_visita" value="" required="required" />
                                                                <input type="hidden" id="id_mes_tipo_visita" name="id_mes_tipo_visita" value="" required="required" />
                                                                <input type="hidden" id="mes_tipo_visita" name="mes_tipo_visita" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="periodo_tipo_visita" name="periodo_tipo_visita" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoBitacora(3)" />
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
                                                <div role="tabpanel" class="tab-pane fade in active" id="reporte_mensual_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="reporte_mensual" name="reporte_mensual">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_mensual">Periodo:</label>
                                                            <div class="col-xs-6">
								                    <input type="hidden" id="id_ano_mensual" name="id_ano_mensual" value="" required="required" />
                                                                <input type="hidden" id="id_mes_mensual" name="id_mes_mensual" value="" required="required" />
                                                                <input type="hidden" id="mes_mensual" name="mes_mensual" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="periodo_mensual" name="periodo_mensual" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoBitacora(5)" />
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
        function resetFields(id_menu) {
            switch (id_menu) {
                case 0:
                    $("#id_ano_municipio").val("");
                    $("#id_mes_municipio").val("");
                    $("#mes_municipio").val("");
                    $("#id_departamento_municipio").val("");
                    $("#id_municipio_municipio").val("");
                    $("#departamento_municipio").focus();
                    break;
                case 1:
                    $("#id_ano_bitacora").val("");
                    $("#id_mes_bitacora").val("");
                    $("#mes_bitacora").val("");
                    $("#id_usuario_bitacora").val("");
                    $("#usuario_bitacora").focus();
                    break;
                case 2:
                    $("#id_ano_tipo_visita").val("");
                    $("#id_mes_tipo_visita").val("");
                    $("#mes_tipo_visita").val("");
                    $("#id_tipo_visita").val("");
                    $("#tipo_visita").focus();
                    break;
                case 4:
                    $("#id_ano_mensual").val("");
                    $("#id_mes_mensual").val("");
                    $("#mes_mensual").val("");
                    $("#periodo_mensual").focus();
                    break;
            }
        }
        //POPUPS
        function infoTipoDepartamento(id_consulta, id_departamento, departamento) {
            if (id_consulta == 1) {
                $("#id_departamento_municipio").val(id_departamento);
                $("#departamento_municipio").val(departamento);
                $("#id_municipio_municipio").val("");
                $("#municipio_municipio").val("");
                $("#municipio_municipio").focus();
            }
        }
        function tipoDepartamento(id_consulta) {
            window.open("Combos/Tipo_Departamento_Visita.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
            if (id_consulta == 1) {
                $("#id_municipio_municipio").val(id_municipio);
                $("#municipio_municipio").val(municipio);
                $("#periodo_municipio").focus();
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_departamento;
            if (id_consulta == 1) {
                id_departamento = $("#id_departamento_municipio").val();
            }
            window.open("Combos/Tipo_Municipio_Visita.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoUsuarioBitacora(id_usuario_bitacora, usuario_bitacora) {
            $("#id_usuario_bitacora").val(id_usuario_bitacora);
            $("#usuario_bitacora").val(usuario_bitacora);
            $("#periodo_bitacora").focus();
        }
        function usuarioBitacora() {
            window.open('Combos/Usuarios_Bitacora.php', 'Popup', 'width=500, height=500');
        }
        function infoTipoVisita(id_tipo_visita, tipo_visita) {
            $("#id_tipo_visita").val(id_tipo_visita);
            $("#tipo_visita").val(tipo_visita);
            $("#periodo_tipo_visita").focus();
        }
        function tipoVisita() {
            window.open("Combos/Tipo_Visita.php", "Popup", "width=400, height=500");
        }
        function infoPeriodoBitacora(id_consulta, id_ano, id_mes, mes) {
            switch (id_consulta) {
                case '1':
                    $("#id_ano_municipio").val(id_ano);
                    $("#id_mes_municipio").val(id_mes);
                    $("#mes_municipio").val(mes);
                    $("#periodo_municipio").val(mes + " - " + id_ano);
                    break;
                case '2':
                    $("#id_ano_bitacora").val(id_ano);
                    $("#id_mes_bitacora").val(id_mes);
                    $("#mes_bitacora").val(mes);
                    $("#periodo_bitacora").val(mes + " - " + id_ano);
                    break;
                case '3':
                    $("#id_ano_tipo_visita").val(id_ano);
                    $("#id_mes_tipo_visita").val(id_mes);
                    $("#mes_tipo_visita").val(mes);
                    $("#periodo_tipo_visita").val(mes + " - " + id_ano);
                    break;
                case '5':
                    $("#id_ano_mensual").val(id_ano);
                    $("#id_mes_mensual").val(id_mes);
                    $("#mes_mensual").val(mes);
                    $("#periodo_mensual").val(mes + " - " + id_ano);
                    break;
            }
        }
        function periodoBitacora(id_consulta) {
            window.open("Combos/Periodo_Bitacora.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            var sw = 4;
            $("#periodo_mensual").focus();
            $("#tab_reporte_municipio").on("shown.bs.tab", function() {
                sw = 0;
                $("#departamento_municipio").focus();
            });
            $("#tab_reporte_usuario").on("shown.bs.tab", function() {
                sw = 1;
                $("#usuario_bitacora").focus();
            });
            $("#tab_reporte_tipo_visita").on("shown.bs.tab", function() {
                sw = 2;
                $("#tipo_visita").focus();
            });
            /*$("#reporte_grafico_tab").on("shown.bs.tab", function() {
                sw = 3;
                $("#departamento_departamento").focus();
            });*/
            $("#tab_reporte_mensual").on("shown.bs.tab", function() {
                sw = 4;
                $("#periodo_mensual").focus();
            });
            $("#tab_reporte_municipio").on("click", function() {
                sw = 0;
                $("#departamento_municipio").focus();
            });
            $("#tab_reporte_usuario").on("click", function() {
                sw = 1;
                $("#usuario_bitacora").focus();
            });
            $("#tab_reporte_tipo_visita").on("click", function() {
                sw = 2;
                $("#tipo_visita").focus();
            });
            /*$("#reporte_grafico_tab").on("click", function() {
                sw = 3;
                $("#departamento_departamento").focus();
            });*/
            $("#tab_reporte_mensual").on("click", function() {
                sw = 4;
                $("#periodo_mensual").focus();
            });
            $("#btn_consultar").click(function() {
                switch(sw) {
                    case 0:
                        var departamento = $("#id_departamento_municipio").val();
                        var municipio = $("#id_municipio_municipio").val();
                        var id_ano_municipio = $("#id_ano_municipio").val();
                        var id_mes_municipio = $("#id_mes_municipio").val();
                        var periodo_municipio = $("#periodo_municipio").val();
                        if (departamento.length == 0) {
                            $("#departamento_municipio").focus();
                            return false;
                        }
                        if (municipio.length == 0) {
                            $("#municipio_municipio").focus();
                            return false;
                        }
                        if (periodo_municipio.length == 0) {
                            $("#periodo_municipio").focus();
                            return false;
                        }
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Consulta...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+
                                  '&departamento='+departamento+
                                  '&municipio='+municipio+
                                  '&id_ano_municipio='+id_ano_municipio+
                                  '&id_mes_municipio='+id_mes_municipio+
                                  '&periodo_municipio='+periodo_municipio,
                            url: 'Modelo/Resultado_Bitacora.php',
                            success: function(data) {
                                $("#rightcol").html(data);
                            }
                        });
                        break;
                    case 1:
                        var usuario_bitacora = $("#id_usuario_bitacora").val();
                        var id_ano_bitacora = $("#id_ano_bitacora").val();
                        var id_mes_bitacora = $("#id_mes_bitacora").val();
                        var periodo_bitacora = $("#periodo_bitacora").val();
                        if (usuario_bitacora.length == 0) {
                            $("#usuario_bitacora").focus();
                            return false;
                        }
                        if (periodo_bitacora.length == 0) {
                            $("#periodo_bitacora").focus();
                            return false;
                        }
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Consulta...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+
                                  '&usuario_bitacora='+usuario_bitacora+
                                  '&id_ano_bitacora='+id_ano_bitacora+
                                  '&id_mes_bitacora='+id_mes_bitacora+
                                  '&periodo_bitacora='+periodo_bitacora,
                            url: 'Modelo/Resultado_Bitacora.php',
                            success: function(data) {
                                $("#rightcol").html(data);
                            }
                        });
                        break;
                    case 2:
                        var tipo_visita = $("#id_tipo_visita").val();
                        var id_ano_tipo_visita = $("#id_ano_tipo_visita").val();
                        var id_mes_tipo_visita = $("#id_mes_tipo_visita").val();
                        var periodo_tipo_visita = $("#periodo_tipo_visita").val();
                        if (tipo_visita.length == 0) {
                            $("#tipo_visita").focus();
                            return false;
                        }
                        if (periodo_tipo_visita.length == 0) {
                            $("#periodo_tipo_visita").focus();
                            return false;
                        }
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Consulta...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+
                                  '&tipo_visita='+tipo_visita+
                                  '&id_ano_tipo_visita='+id_ano_tipo_visita+
                                  '&id_mes_tipo_visita='+id_mes_tipo_visita+
                                  '&periodo_tipo_visita='+periodo_tipo_visita,
                            url: 'Modelo/Resultado_Bitacora.php',
                            success: function(data) {
                                $("#rightcol").html(data);
                            }
                        });
                        break;
                    /*case 3:
                        
                        break;*/
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
                            url: 'Modelo/Resultado_Bitacora.php',
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
            $('input[type=text][name=departamento_municipio]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=municipio_municipio]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=periodo_municipio]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=usuario_bitacora]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=periodo_bitacora]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=tipo_visita]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=periodo_tipo_visita]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=periodo_mensual]').tooltip({
                container: "body",
                placement: "top"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>