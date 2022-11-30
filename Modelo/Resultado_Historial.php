<?php
    session_start();
?>
<div class="modal fade" id="modalDetalleHistorial" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php
    switch ($_POST['sw']) {
        case 0: ?>
            <h1>Resultado del Historial por Catastro</h1>
        <?php
            break;
    }
?>
<div style="display: none;" class="alert alert-success alert-dismissible text-center" role="alert" id="alert-historial">
    Resultado Generado Satisfactoriamente. <i class="fas fa-check" aria-hidden="true"></i>
</div>
<h2></h2>
<?php
    require_once('../Includes/Config.php');
    $sw = $_POST['sw'];
    switch ($sw) {
        case 0:
            $id_ano_factura_inicial = $_POST['id_ano_factura_inicial'];
            $id_mes_factura_inicial = $_POST['id_mes_factura_inicial'];
            $mes_factura_inicial = strtolower($_POST['mes_factura_inicial']);
            $id_ano_factura_final = $_POST['id_ano_factura_final'];
            $id_mes_factura_final = $_POST['id_mes_factura_final'];
            $mes_factura_final = strtolower($_POST['mes_factura_final']);
            $bd_tabla_catastro_inicial = "catastro_" . $mes_factura_inicial . $id_ano_factura_inicial . "_2";
            $bd_tabla_catastro_final = "catastro_" . $mes_factura_final . $id_ano_factura_final . "_2";
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='id_ano_factura_inicial' name='id_ano_factura_inicial' value='" . $id_ano_factura_inicial . "' />";
            echo "<input type='hidden' id='id_mes_factura_inicial' name='id_mes_factura_inicial' value='" . $id_mes_factura_inicial . "' />";
            echo "<input type='hidden' id='mes_factura_inicial' name='mes_factura_inicial' value='" . $mes_factura_inicial . "' />";
            echo "<input type='hidden' id='id_ano_factura_final' name='id_ano_factura_final' value='" . $id_ano_factura_final . "' />";
            echo "<input type='hidden' id='id_mes_factura_final' name='id_mes_factura_final' value='" . $id_mes_factura_final . "' />";
            echo "<input type='hidden' id='mes_factura_final' name='mes_factura_final' value='" . $mes_factura_final . "' />";
            echo "<input type='hidden' id='bd_tabla_catastro_inicial' name='bd_tabla_catastro_inicial' value='" . $bd_tabla_catastro_inicial . "' />";
            echo "<input type='hidden' id='bd_tabla_catastro_final' name='bd_tabla_catastro_final' value='" . $bd_tabla_catastro_final . "' />";
            $query_select_historial_catastro = mysqli_query($connection, "SELECT TAR.ID_TARIFA, TAR.COD_TARIFA, TAR.NOMBRE, COUNT(CATA1.ID_TABLA) "
                                                                       . "  FROM $bd_tabla_catastro_inicial CATA1, tarifas_2 TAR "
                                                                       . " WHERE NIC NOT IN (SELECT NIC FROM $bd_tabla_catastro_final) "
                                                                       . "   AND CATA1.ID_TARIFA = TAR.ID_TARIFA "
                                                                       . " GROUP BY TAR.NOMBRE "
                                                                       . "HAVING COUNT(1) >= 1 "
                                                                       . " ORDER BY TAR.NOMBRE");
            if (mysqli_num_rows($query_select_historial_catastro) != 0) {
                echo "<div id='resultado_historial_catastro' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=10%>COD. TARIFA</th>";
                                echo "<th width=70%>TARIFA</th>";
                                echo "<th width=10%>NUEVOS SUM.</th>";
                                echo "<th width=5%>DETALLE</th>";
                                echo "<th width=5%>EXPORTAR</th>";
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
                echo "<ul id='pagination-historial_catastro'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            
            break;
    }
?>
    <script>
        $(document).ready(function() {
            $("#alert-historial").attr("display", "block");
            $("#alert-historial").fadeTo(3000, 500).fadeOut(500, function() {
                $("#alert-historial").fadeOut();
            });
            var sw = $("#sw").val();
            switch (sw) {
                case '0':
                    var id_ano_factura_inicial = $("#id_ano_factura_inicial").val();
                    var id_mes_factura_inicial = $("#id_mes_factura_inicial").val();
                    var mes_factura_inicial = $("#mes_factura_inicial").val();
                    var id_ano_factura_final = $("#id_ano_factura_final").val();
                    var id_mes_factura_final = $("#id_mes_factura_final").val();
                    var mes_factura_final = $("#mes_factura_final").val();
                    var bd_tabla_catastro_inicial = $("#bd_tabla_catastro_inicial").val();
                    var bd_tabla_catastro_final = $("#bd_tabla_catastro_final").val();
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Historiales.php",
                        dataType: "json",
                        data: "bd_tabla_catastro_inicial="+bd_tabla_catastro_inicial+
                              "&bd_tabla_catastro_final="+bd_tabla_catastro_final+"&sw="+sw,
                        success: function(data) {
                            $("#pagination-historial_catastro").twbsPagination('destroy');
                            $("#pagination-historial_catastro").twbsPagination({
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
                                        url: "Modelo/Cargar_Historiales.php",
                                        data: "id_ano_factura_inicial="+id_ano_factura_inicial+
                                              "&id_mes_factura_inicial="+id_mes_factura_inicial+
                                              "&mes_factura_inicial="+mes_factura_inicial+
                                              "&id_ano_factura_final="+id_ano_factura_final+
                                              "&id_mes_factura_final="+id_mes_factura_final+
                                              "&mes_factura_final="+mes_factura_final+
                                              "&bd_tabla_catastro_inicial="+bd_tabla_catastro_inicial+
                                              "&bd_tabla_catastro_final="+bd_tabla_catastro_final+"&sw="+sw+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_historial_catastro").html(data);
                                        }
                                    });
                                }
                            });
                        }
                    });
                    $("#modalDetalleHistorial").on('show.bs.modal', function(e) {
                        var historial_id = e.relatedTarget.id;
                        $(".modal-title").html("");
                        $(".modal-body").html("<div style='margin-bottom: 30px; margin-top: 0px;'><span id='loading-spinner-historial' style='display: block; float: left;'><img src='Images/squares.gif' /></span></div>");
                        $.ajax ({
                            type: "POST",
                            url: "Modelo/Cargar_Detalle_Historial_Catastro.php",
                            dataType: "json",
                            data: "historial_id="+historial_id,
                            success: function(data) {
                                $(".modal-title").html(data[0]);
                                $(".modal-body").html(data[1]);
                            }
                        });
                    });
                    break;
            }
        });
    </script>