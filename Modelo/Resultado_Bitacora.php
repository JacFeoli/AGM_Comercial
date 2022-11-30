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
            <h1>Resultado de la Busqueda por Usuario</h1>
        <?php
            break;
        case 2: ?>
            <h1>Resultado de la Busqueda por Tipo Visita</h1>
        <?php
            break;
        case 4: ?>
            <h1>Resultado de la Busqueda por Periodo</h1>
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
                $query_departamento = " AND ML.ID_DEPARTAMENTO = " . $departamento . " ";
            }
            if ($municipio == "") {
                $query_municipio = "";
            } else {
                $query_municipio = " AND ML.ID_MUNICIPIO = " . $municipio . " ";
            }
            foreach ($myAnos as $index => $ano) {
                if ($sw2 == 0) {
                    $where = " WHERE (BL.ID_MUNICIPIO_LIBRETA = ML.ID_MUNICIPIO_LIBRETA AND YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . $query_departamento . " " . $query_municipio . ") ";
                    $sw2 = 1;
                } else {
                    $or = $or . " OR (BL.ID_MUNICIPIO_LIBRETA = ML.ID_MUNICIPIO_LIBRETA AND YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . $query_departamento . " " . $query_municipio . ") ";
                }
            }
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='departamento' name='departamento' value='" . $departamento . "' />";
            echo "<input type='hidden' id='municipio' name='municipio' value='" . $municipio . "' />";
            echo "<input type='hidden' id='id_ano_municipio' name='id_ano_municipio' value='" . $_POST['id_ano_municipio'] . "' />";
            echo "<input type='hidden' id='id_mes_municipio' name='id_mes_municipio' value='" . $_POST['id_mes_municipio'] . "' />";
            $query_select_info_municipio = mysqli_query($connection, "SELECT * "
                                                                   . "  FROM bitacora_libretas_2 BL, municipios_libreta_2 ML "
                                                                   . $where
                                                                   . $or
                                                                   . " ORDER BY BL.FECHA_VISITA DESC ");
            if (mysqli_num_rows($query_select_info_municipio) != 0) {
                echo "<div id='resultado_municipio' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=12%>DEPARTAMENTO</th>";
                                echo "<th width=18%>MUNICIPIO</th>";
                                echo "<th width=60%>TIPO VISITA</th>";
                                echo "<th width=10%>FECHA VISITA</th>";
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
            $myAnos = explode(', ', $_POST['id_ano_bitacora']);
            $myMes = explode(', ', $_POST['id_mes_bitacora']);
            $usuario_bitacora = $_POST['usuario_bitacora'];
            foreach ($myAnos as $index => $ano) {
                if ($sw2 == 0) {
                    $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_USUARIO = " . $usuario_bitacora . ") ";
                    $sw2 = 1;
                } else {
                    $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_USUARIO = " . $usuario_bitacora . ") ";
                }
            }
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='usuario_bitacora' name='usuario_bitacora' value='" . $usuario_bitacora . "' />";
            echo "<input type='hidden' id='id_ano_bitacora' name='id_ano_bitacora' value='" . $_POST['id_ano_bitacora'] . "' />";
            echo "<input type='hidden' id='id_mes_bitacora' name='id_mes_bitacora' value='" . $_POST['id_mes_bitacora'] . "' />";
            $query_select_info_usuario = mysqli_query($connection, "SELECT * "
                                                                    . "  FROM bitacora_libretas_2 BL "
                                                                    . $where
                                                                    . $or
                                                                    . " ORDER BY BL.FECHA_VISITA DESC ");
            if (mysqli_num_rows($query_select_info_usuario) != 0) {
                echo "<div id='resultado_usuario' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=23%>USUARIO</th>";
                                echo "<th width=12%>DEPARTAMENTO</th>";
                                echo "<th width=18%>MUNICIPIO</th>";
                                echo "<th width=37%>TIPO VISITA</th>";
                                echo "<th width=10%>FECHA VISITA</th>";
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
                echo "<ul id='pagination-usuario'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            break;
        case '2':
            $sw2 = 0;
            $or = "";
            $where = "";
            $myAnos = explode(', ', $_POST['id_ano_tipo_visita']);
            $myMes = explode(', ', $_POST['id_mes_tipo_visita']);
            $tipo_visita = $_POST['tipo_visita'];
            foreach ($myAnos as $index => $ano) {
                if ($sw2 == 0) {
                    $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_TIPO_VISITA = " . $tipo_visita . ") ";
                    $sw2 = 1;
                } else {
                    $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . " AND BL.ID_TIPO_VISITA = " . $tipo_visita . ") ";
                }
            }
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='tipo_visita' name='tipo_visita' value='" . $tipo_visita . "' />";
            echo "<input type='hidden' id='id_ano_tipo_visita' name='id_ano_tipo_visita' value='" . $_POST['id_ano_tipo_visita'] . "' />";
            echo "<input type='hidden' id='id_mes_tipo_visita' name='id_mes_tipo_visita' value='" . $_POST['id_mes_tipo_visita'] . "' />";
            $query_select_info_tipo_visita = mysqli_query($connection, "SELECT * "
                                                                    . "  FROM bitacora_libretas_2 BL "
                                                                    . $where
                                                                    . $or
                                                                    . " ORDER BY BL.FECHA_VISITA DESC ");
            if (mysqli_num_rows($query_select_info_tipo_visita) != 0) {
                echo "<div id='resultado_tipo_visita' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=60%>TIPO VISITA</th>";
                                echo "<th width=12%>DEPARTAMENTO</th>";
                                echo "<th width=18%>MUNICIPIO</th>";                                
                                echo "<th width=10%>FECHA VISITA</th>";
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
                echo "<ul id='pagination-tipo_visita'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
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
                    $where = " WHERE (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . ") ";
                    $sw2 = 1;
                } else {
                    $or = $or . " OR (YEAR(BL.FECHA_VISITA) = " . $ano . " AND MONTH(BL.FECHA_VISITA) = " . $myMes[$index] . ") ";
                }
            }
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='id_ano_mensual' name='id_ano_mensual' value='" . $_POST['id_ano_mensual'] . "' />";
            echo "<input type='hidden' id='id_mes_mensual' name='id_mes_mensual' value='" . $_POST['id_mes_mensual'] . "' />";
            $query_select_info_mensual = mysqli_query($connection, "SELECT * "
                                                                 . "  FROM bitacora_libretas_2 BL "
                                                                 . $where
                                                                 . $or
                                                                 . " ORDER BY BL.FECHA_VISITA DESC ");
            if (mysqli_num_rows($query_select_info_mensual) != 0) {
                echo "<div id='resultado_mensual' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=12%>DEPARTAMENTO</th>";
                                echo "<th width=18%>MUNICIPIO</th>";
                                echo "<th width=60%>TIPO VISITA</th>";
                                echo "<th width=10%>FECHA VISITA</th>";
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
    }
?>
<script>
    function generarReporteMunicipio(sw, departamento, municipio, id_ano, id_mes) {
        window.open('Combos/Generar_Reporte_Bitacora.php?sw='+sw+'&departamento='+departamento+'&municipio='+municipio+'&id_ano='+id_ano+'&id_mes='+id_mes, 'Popup', 'width=750, height=600');
    }
    function generarReporteUsuario(sw, usuario, id_ano, id_mes) {
        window.open('Combos/Generar_Reporte_Bitacora.php?sw='+sw+'&usuario='+usuario+'&id_ano='+id_ano+'&id_mes='+id_mes, 'Popup', 'width=750, height=600');
    }
    function generarReporteTipoVisita(sw, tipo_visita, id_ano, id_mes) {
        window.open('Combos/Generar_Reporte_Bitacora.php?sw='+sw+'&tipo_visita='+tipo_visita+'&id_ano='+id_ano+'&id_mes='+id_mes, 'Popup', 'width=750, height=600');
    }
    function generarReporteMensual(sw, id_ano, id_mes) {
        window.open('Combos/Generar_Reporte_Bitacora.php?sw='+sw+'&id_ano='+id_ano+'&id_mes='+id_mes, 'Popup', 'width=750, height=600');
    }
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
                    url: "Modelo/Cargar_Paginacion_Resultados_Bitacora.php",
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
                                    url: "Modelo/Cargar_Resultados_Bitacora.php",
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
                var usuario_bitacora = $("#usuario_bitacora").val();
                var id_ano_bitacora = $("#id_ano_bitacora").val();
                var id_mes_bitacora = $("#id_mes_bitacora").val();
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Resultados_Bitacora.php",
                    dataType: "json",
                    data: "sw="+sw+
                          "&usuario_bitacora="+usuario_bitacora+
                          "&id_ano_bitacora="+id_ano_bitacora+
                          "&id_mes_bitacora="+id_mes_bitacora,
                    success: function(data) {
                        $("#pagination-usuario").twbsPagination('destroy');
                        $("#pagination-usuario").twbsPagination({
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
                                    url: "Modelo/Cargar_Resultados_Bitacora.php",
                                    data: "sw="+sw+
                                          "&usuario_bitacora="+usuario_bitacora+
                                          "&page="+page+
                                          "&id_ano_bitacora="+id_ano_bitacora+
                                          "&id_mes_bitacora="+id_mes_bitacora,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_usuario").html(data);
                                    }
                                });
                            }
                        });
                    }
                });
                break;
            case '2':
                var tipo_visita = $("#tipo_visita").val();
                var id_ano_tipo_visita = $("#id_ano_tipo_visita").val();
                var id_mes_tipo_visita = $("#id_mes_tipo_visita").val();
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Resultados_Bitacora.php",
                    dataType: "json",
                    data: "sw="+sw+
                          "&tipo_visita="+tipo_visita+
                          "&id_ano_tipo_visita="+id_ano_tipo_visita+
                          "&id_mes_tipo_visita="+id_mes_tipo_visita,
                    success: function(data) {
                        $("#pagination-tipo_visita").twbsPagination('destroy');
                        $("#pagination-tipo_visita").twbsPagination({
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
                                    url: "Modelo/Cargar_Resultados_Bitacora.php",
                                    data: "sw="+sw+
                                          "&tipo_visita="+tipo_visita+
                                          "&page="+page+
                                          "&id_ano_tipo_visita="+id_ano_tipo_visita+
                                          "&id_mes_tipo_visita="+id_mes_tipo_visita,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_tipo_visita").html(data);
                                    }
                                });
                            }
                        });
                    }
                });
                break;
            case '3':
                
                break;
            case '4':
                var id_ano_mensual = $("#id_ano_mensual").val();
                var id_mes_mensual = $("#id_mes_mensual").val();
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Resultados_Bitacora.php",
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
                                    url: "Modelo/Cargar_Resultados_Bitacora.php",
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
        }
    });
</script>