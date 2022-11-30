<?php
    session_start();
    require_once('Includes/Config.php');
    if ($_SESSION['rol'] == 'A') {
        if ($_SESSION['timeout'] + 60 * 60 < time()) {
            session_unset();
            session_destroy();
            $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            header("Location:$ruta/Login.php");
        } else {
            $_SESSION['timeout'] = time();
        }
    } else {
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Acceso_Restringido.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Archivos Cargados OYM - RI</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
    </head>
    <!--DETALLE ARCHIVO CARGADO MODAL-->
    <div class="modal fade" id="modalDetalleArchivoOYMRI" role="dialog">
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
    <!--FIN DETALLE ARCHIVO CARGADO MODAL-->
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Home.php");?>
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-hand-holding-usd"></i>
                                                                        <span>OYM - RI</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Archivos_OYM_RI.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-import"></i>
                                                                                    <span>Archivos Cargados</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Cargue_OYM_RI.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-upload"></i>
                                                                                    <span>Cargue OYM - RI</span>
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
                                        <div class="rightcol">
                                            <h1>Archivos Cargados OYM - RI</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_archivos_oym_ri_tab" id="tab_informacion_archivos_oym_ri" aria-controls="informacion_archivos_oym_ri_tab" role="tab" data-toggle="tab">Información Archivos Cargados OYM - RI</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_archivos_oym_ri_tab">
                                                    <ul class="nav nav-pills ulclass_info" role="tablist">
                                                        <?php
                                                            $sw = 0;
                                                            $array_id_ano = array();
                                                            $array_nombre_ano = array();
                                                            $query_select_ano = mysqli_query($connection, "SELECT DISTINCT(AOYM.ANO_FACTURA) AS ANO_FACTURA, AN.ID_ANO AS ID_ANO "
                                                                                                        . "  FROM archivos_cargados_oymri_2 AOYM, anos_2 AN "
                                                                                                        . " WHERE AOYM.ANO_FACTURA = AN.NOMBRE "
                                                                                                        . " ORDER BY AN.NOMBRE");
                                                            if (mysqli_num_rows($query_select_ano) != 0) {
                                                                while ($row_select_ano = mysqli_fetch_assoc($query_select_ano)) {
                                                                    if ($sw == 0) {
                                                                        $sw = 1;
                                                                        $array_id_ano[] = $row_select_ano['ID_ANO'];
                                                                        $array_nombre_ano[] = $row_select_ano['ANO_FACTURA'];
                                                                        $query_select_count_registros = mysqli_query($connection, "SELECT ID_TABLA "
                                                                                                                                . "  FROM archivos_cargados_oymri_2 "
                                                                                                                                . " WHERE ANO_FACTURA = '" . $row_select_ano['ANO_FACTURA'] . "'");
                                                                        $count_registros = mysqli_num_rows($query_select_count_registros);
                                                                        ?>
                                                                        <li role="presentation" class="active">
                                                                            <a tabindex="<?php echo $row_select_ano['ID_ANO']; ?>" href="#informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab" id="tab_info_<?php echo $row_select_ano['ANO_FACTURA']; ?>" aria-controls="informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab" role="tab" data-toggle="tab"><?php echo $row_select_ano['ANO_FACTURA']; ?> <sup id="total_archivos_<?php echo $row_select_ano['ANO_FACTURA']; ?>"><b><?php echo $count_registros; ?></b></sup></a>
                                                                        </li>
                                                                        <?php
                                                                    } else {
                                                                        $array_id_ano[] = $row_select_ano['ID_ANO'];
                                                                        $array_nombre_ano[] = $row_select_ano['ANO_FACTURA'];
                                                                        $query_select_count_registros = mysqli_query($connection, "SELECT ID_TABLA "
                                                                                                                                . "  FROM archivos_cargados_cartera_fallida_2 "
                                                                                                                                . " WHERE ANO_FACTURA = '" . $row_select_ano['ANO_FACTURA'] . "'");
                                                                        $count_registros = mysqli_num_rows($query_select_count_registros);
                                                                        ?>
                                                                        <li role="presentation">
                                                                            <a tabindex="<?php echo $row_select_ano['ID_ANO']; ?>" href="#informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab" id="tab_info_<?php echo $row_select_ano['ANO_FACTURA']; ?>" aria-controls="informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab" role="tab" data-toggle="tab"><?php echo $row_select_ano['ANO_FACTURA']; ?> <sup id="total_archivos_<?php echo $row_select_ano['ANO_FACTURA']; ?>"><b><?php echo $count_registros; ?></b></sup></a>
                                                                        </li>
                                                                        <?php
                                                                    }
                                                                }
                                                            } else {
                                                                echo "<p class='message'>No se encontraron Archivos Cargados de OYM - RI.</p>";
                                                            }
                                                        ?>
                                                    </ul>
                                                    <h2></h2>
                                                    <div class="tab-content">
                                                        <input class="form-control input-text input-sm" type="text" placeholder="Buscar Archivo" name="buscar_archivo_oymri_info" id="buscar_archivo_oymri_info" />
                                                        <br />
                                                        <?php
                                                            $sw = 0;
                                                            $array_pagination = array();
                                                            $query_select_ano = mysqli_query($connection, "SELECT DISTINCT(AOYM.ANO_FACTURA), AN.ID_ANO "
                                                                                                        . "  FROM archivos_cargados_oymri_2 AOYM, anos_2 AN "
                                                                                                        . " WHERE AOYM.ANO_FACTURA = AN.NOMBRE "
                                                                                                        . " ORDER BY AN.NOMBRE");
                                                            while ($row_select_ano = mysqli_fetch_assoc($query_select_ano)) {
                                                                if ($sw == 0) {
                                                                    $sw = 1;
                                                                    $id_ano = $row_select_ano['ID_ANO'];
                                                                    $array_pagination[] = "pagination-" . $row_select_ano['ANO_FACTURA'];
                                                                ?>
                                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab">
                                                                    <?php
                                                                        $query_select_archivos = mysqli_query($connection, "SELECT * FROM archivos_cargados_oymri_2 "
                                                                                                                         . " WHERE ANO_FACTURA = '" . $row_select_ano['ANO_FACTURA'] . "'"
                                                                                                                         . " ORDER BY PERIODO");
                                                                        if (mysqli_num_rows($query_select_archivos) != 0) {
                                                                            echo "<div class='table-responsive'>";
                                                                                echo "<table class='table table-condensed table-hover'>";
                                                                                    echo "<thead>";
                                                                                        echo "<tr>";
                                                                                            echo "<th width=15%>AÑO</th>";
                                                                                            echo "<th width=15%>PERIODO</th>";
                                                                                            echo "<th width=60%>NOMBRE ARCHIVO</th>";
                                                                                            echo "<th width=5%>DETALLE</th>";
                                                                                        echo "</tr>";
                                                                                    echo "</thead>";
                                                                                    echo "<tbody id='resultado_archivos_cargados_" . $row_select_ano['ANO_FACTURA'] . "'>";
                                                                                    
                                                                                    echo "</tbody>";
                                                                                echo "</table>";
                                                                            echo "</div>";
                                                                        } else {
                                                                            echo "<br />";
                                                                            echo "<p class='message'>No se encontraron Archivos Cargados de OYM - RI.</p>";
                                                                        }
                                                                    ?>
                                                                    <div id="div-pagination">
                                                                        <ul id="pagination-<?php echo $row_select_ano['ANO_FACTURA']; ?>"></ul>
                                                                        <span id="loading-spinner-<?php echo $id_ano; ?>" style="display: none; float: right;"><img src="Images/squares.gif" /></span>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                } else {
                                                                    $id_ano = $row_select_ano['ID_ANO'];
                                                                    $array_pagination[] = "pagination-" . $row_select_ano['ANO_FACTURA'];
                                                                    ?>
                                                                    <div role="tabpanel" class="tab-pane fade" id="informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab">
                                                                        <?php
                                                                            $query_select_archivos = mysqli_query($connection, "SELECT * FROM archivos_cargados_oymri_2 "
                                                                                                                             . " WHERE ANO_FACTURA = '" . $row_select_ano['ANO_FACTURA'] . "'"
                                                                                                                             . " ORDER BY PERIODO, RUTA");
                                                                            if (mysqli_num_rows($query_select_archivos) != 0) {
                                                                                echo "<div class='table-responsive'>";
                                                                                    echo "<table class='table table-condensed table-hover'>";
                                                                                        echo "<thead>";
                                                                                            echo "<tr>";
                                                                                                echo "<th width=15%>AÑO</th>";
                                                                                                echo "<th width=15%>PERIODO</th>";
                                                                                                echo "<th width=60%>NOMBRE ARCHIVO</th>";
                                                                                                echo "<th width=5%>DETALLE</th>";
                                                                                            echo "</tr>";
                                                                                        echo "</thead>";
                                                                                        echo "<tbody id='resultado_archivos_cargados_" . $row_select_ano['ANO_FACTURA'] . "'>";

                                                                                        echo "</tbody>";
                                                                                    echo "</table>";
                                                                                echo "</div>";
                                                                            } else {
                                                                                echo "<br />";
                                                                                echo "<p class='message'>No se encontraron Archivos Cargados de OYM - RI.</p>";
                                                                            }
                                                                        ?>
                                                                        <div id="div-pagination">
                                                                            <ul id="pagination-<?php echo $row_select_ano['ANO_FACTURA']; ?>"></ul>
                                                                            <span id="loading-spinner-<?php echo $id_ano; ?>" style="display: none; float: right;"><img src="Images/squares.gif" /></span>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
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
    <script src="Javascript/menu.js"></script>
    <script src="Javascript/jquery.twbsPagination.js"></script>
    <script>
        function paginacionAnos(array_id_ano, array_pagination, array_nombre_ano) {
            //alert("Entra");
            for (var i=0; i<array_id_ano.length; i++) {
                //alert(array_id_ano[i] + " - " + array_pagination[i] + " - " + array_nombre_ano[i]);
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Archivos.php",
                    dataType: "json",
                    data: "archivo=oymri&sw=0&id_ano="+array_id_ano[i]+"&pagination="+array_pagination[i]+"&nombre_ano="+array_nombre_ano[i],
                    success: function(data) {
                        //alert(data[2]);
                        $("#"+data[0]).twbsPagination({
                            totalPages: data[1],
                            visiblePages: 15,
                            first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                            prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                            next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                            last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                            onPageClick: function(event, page) {
                                $("#loading-spinner-"+data[3]).css('display', 'block');
                                $.ajax({
                                    type: "POST",
                                    url: "Modelo/Cargar_Archivos.php",
                                    dataType: "json",
                                    data: "archivo=oymri&sw=0"+"&id_ano="+data[3]+"&nombre_ano="+data[2]+"&page="+page,
                                    success: function(data) {
                                        $("#loading-spinner-"+data[2]).css('display', 'none');
                                        $("#resultado_archivos_cargados_"+data[0]).html(data[1]);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_archivo_oymri_info").focus();
            var array_id_ano = <?php echo json_encode($array_id_ano); ?>;
            var array_pagination = <?php echo json_encode($array_pagination); ?>;
            var array_nombre_ano = <?php echo json_encode($array_nombre_ano); ?>;
            paginacionAnos(array_id_ano, array_pagination, array_nombre_ano);
            $(".ulclass_info").on("click", "a", function() {
                $("#buscar_archivo_oymri_info").focus();
            });
            $("#tab_informacion_archivos_cartera_fallida").on("click", function() {
                $("#buscar_archivo_oymri_info").focus();
            });
            $("#buscar_archivo_oymri_info").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_archivo_oymri;
                    if ($(this).val() == "") {
                        busqueda_archivo_oymri = " ";
                    } else {
                        busqueda_archivo_oymri = $(this).val().toUpperCase();
                    }
                    var id_ano = $(".ulclass_info li.active a").attr("tabindex");
                    $("#loading-spinner-"+id_ano).css('display', 'block');
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Archivos.php",
                        dataType: "json",
                        data: "archivo=oymri&sw=1&id_ano="+id_ano+"&busqueda_archivo_oymri="+busqueda_archivo_oymri,
                        success: function(data) {
                            $("#"+data[0]).twbsPagination('destroy');
                            $("#"+data[0]).twbsPagination({
                                totalPages: data[1],
                                visiblePages: 15,
                                first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                                prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                                next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                                last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                                onPageClick: function(event, page) {
                                    $("#loading-spinner-"+data[3]).css('display', 'block');
                                    $.ajax({
                                        type: "POST",
                                        url: "Modelo/Cargar_Archivos.php",
                                        dataType: "json",
                                        data: "archivo=oymri&sw=1&busqueda_archivo_oymri="+data[4]+"&id_ano="+data[3]+"&nombre_ano="+data[2]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner-"+data[2]).css('display', 'none');
                                            $("#total_archivos_"+data[0]).html('<b>'+data[3]+'</b>');
                                            $("#resultado_archivos_cargados_"+data[0]).html(data[1]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#modalDetalleArchivoOYMRI").on('show.bs.modal', function(e) {
                var detalle_id = e.relatedTarget.id;
                $(".modal-title").html("");
                $(".modal-body").html("<div style='margin-bottom: 30px; margin-top: 0px;'><span id='loading-spinner-detalle' style='display: block; float: left;'><img src='Images/squares.gif' /></span></div>");
                $.ajax ({
                    type: "POST",
                    url: "Modelo/Cargar_Detalle_Archivos.php",
                    dataType: "json",
                    data: "archivo=oymri&detalle_id="+detalle_id,
                    success: function(data) {
                        $(".modal-title").html(data[0]);
                        $(".modal-body").html(data[1]);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>