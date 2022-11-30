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
        <title>AGM - Historiales</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <!--<link rel="stylesheet" href="Css/font-awesome.css">-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
    </head>
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Electricaribe.php");?>
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div style="width: 176px;" class="leftcol">
                                            <h3 style="text-align: center;">OPCIONES DISPONIBLES</h3>
                                            <nav id="nav" class="accordion">
                                                <ul class="nav nav-pills nav-stacked">
                                                    <li class='active'>
                                                        <a href='Historiales.php'>
                                                            <table>
                                                                <tr>
                                                                    <td style="padding-right: 13px;">
                                                                        <span><i class='fas fa-history fa-fw'></i></span>
                                                                    </td>
                                                                    <td>
                                                                        <span>Historiales</span>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div id="rightcol" class="rightcol">
                                            <h1>Historiales</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#historial_catastro_tab" id="tab_historial_catastro" aria-controls="historial_catastro_tab" role="tab" data-toggle="tab">Por Catastro</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="historial_catastro_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="historial_catastro" name="historial_catastro">
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
								<input type="hidden" id="id_ano_factura_inicial" name="id_ano_factura_inicial" value="" required="required" />
                                                                <input type="hidden" id="id_mes_factura_inicial" name="id_mes_factura_inicial" value="" required="required" />
                                                                <input type="hidden" id="mes_factura_inicial" name="mes_factura_inicial" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="periodo_inicial" name="periodo_inicial" placeholder="Periodo Inicial" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO INICIAL" onclick="periodoCatastro(1)" />
                                                            </div>
                                                            <div class="col-xs-3">
								<input type="hidden" id="id_ano_factura_final" name="id_ano_factura_final" value="" required="required" />
                                                                <input type="hidden" id="id_mes_factura_final" name="id_mes_factura_final" value="" required="required" />
                                                                <input type="hidden" id="mes_factura_final" name="mes_factura_final" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="periodo_final" name="periodo_final" placeholder="Periodo Final" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO FINAL" onclick="periodoCatastro(2)" />
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
                                            </div>
                                            <div style="margin-bottom: 15px; margin-top: 15px;" class="divider"></div>
                                            <div style="text-align: right; margin-bottom: 10px;" class="col-xs-12">
                                                <button class="btn btn-primary btn-sm font background cursor" id="btn_consultar" type="submit"><i style="font-size: 14px;" class="fas fa-search"></i>&nbsp;&nbsp;Consultar</button>&nbsp;&nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="Javascript/bootstrap.min.js"></script>
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
                    $("#id_ano_factura_inicial").val("");
                    $("#id_mes_factura_inicial").val("");
                    $("#mes_factura_inicial").val("");
                    $("#id_ano_factura_final").val("");
                    $("#id_mes_factura_final").val("");
                    $("#mes_factura_final").val("");
                    $("#periodo_inicial").focus();
                    break;
            }
        }
        //POPUPS
        function infoPeriodoCatastro(id_consulta, id_ano_factura, id_mes_factura, mes_factura) {
            if (id_consulta == 1) {
                $("#id_ano_factura_inicial").val(id_ano_factura);
                $("#id_mes_factura_inicial").val(id_mes_factura);
                $("#mes_factura_inicial").val(mes_factura);
                $("#periodo_inicial").val(mes_factura + " - " + id_ano_factura);
            } else {
                $("#id_ano_factura_final").val(id_ano_factura);
                $("#id_mes_factura_final").val(id_mes_factura);
                $("#mes_factura_final").val(mes_factura);
                $("#periodo_final").val(mes_factura + " - " + id_ano_factura);
            }
        }
        function periodoCatastro(id_consulta) {
            window.open("Combos/Periodo_Catastro.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function(){
            var sw = 0;
            $("#periodo_inicial").focus();
            $("#tab_historial_catastro").on("shown.bs.tab", function() {
                sw = 0;
                $("#periodo_inicial").focus();
            });
            $("#tab_historial_catastro").on("click", function() {
                sw = 0;
                $("#periodo_inicial").focus();
            });
            $("#btn_consultar").click(function() {
                switch(sw) {
                    case 0:
                        var id_ano_factura_inicial = $("#id_ano_factura_inicial").val();
                        var id_mes_factura_inicial = $("#id_mes_factura_inicial").val();
                        var mes_factura_inicial = $("#mes_factura_inicial").val();
                        var id_ano_factura_final = $("#id_ano_factura_final").val();
                        var id_mes_factura_final = $("#id_mes_factura_final").val();
                        var mes_factura_final = $("#mes_factura_final").val();
                        if (id_ano_factura_inicial.length == 0) {
                            $("#periodo_inicial").focus();
                            return false;
                        }
                        if (id_ano_factura_final.length == 0) {
                            $("#periodo_final").focus();
                            return false;
                        }
                        $("#btn_consultar").attr("disabled", true);
                        $("#btn_consultar").css("pointer-events", "none");
                        $("#btn_consultar").html("Generando Historial...");
                        $.ajax({
                            type: 'POST',
                            data: 'sw='+sw+'&id_ano_factura_inicial='+id_ano_factura_inicial+
                                  '&id_mes_factura_inicial='+id_mes_factura_inicial+
                                  '&mes_factura_inicial='+mes_factura_inicial+
                                  '&id_ano_factura_final='+id_ano_factura_final+
                                  '&id_mes_factura_final='+id_mes_factura_final+
                                  '&mes_factura_final='+mes_factura_final,
                            url: 'Modelo/Resultado_Historial.php',
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
            $('input[type=text][name=periodo_inicial]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=periodo_final]').tooltip({
                container : "body",
                placement : "top"
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