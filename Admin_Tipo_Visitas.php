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
        <title>AGM - Admin. Tipo Visitas</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <!--<link rel="stylesheet" href="Css/font-awesome.css">-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="Javascript/bootstrap.min.js"></script>
        <script src="Javascript/jquery.twbsPagination.js"></script>
    </head>
    <!--Eliminar Tipo Visita Modal-->
    <div class="modal fade" id="modalEliminarTipoVisita" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Tipo Visita</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Tipo Visita?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_tipo_visita" name="eliminar_tipo_visita"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Tipo Visita Modal-->
    <!--Eliminar Tipo Visita Error-->
    <div class="modal fade" id="modalEliminarTipoVisitaError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Tipo Visita</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Tipo Visita, ya que existen Registros creados con ésta. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Tipo Visita Error-->
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Admin.php"); ?>
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div style="width: 170px;" class="leftcol">
                                            <h3 style="text-align: center;">OPCIONES DISPONIBLES</h3>
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class='active'>
                                                    <a href="Admin_Tipo_Visitas.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Tipo Visitas</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="rightcol">
                                            <h1>Admin. Tipo Visitas</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_tipo_visita_tab" id="tab_info_tipo_visita" aria-controls="informacion_tipo_visita_tab" role="tab" data-toggle="tab">Información Tipo Visitas</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_tipo_visita_tab" id="tab_crear_tipo_visita" aria-controls="crear_tipo_visita_tab" role="tab" data-toggle="tab">Crear Tipo Visita</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_tipo_visita_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Tipo Visita" name="buscar_tipo_visita" id="buscar_tipo_visita" />
                                                    <br />
                                                    <?php
                                                        $query_select_tipo_visitas = "SELECT * FROM tipo_visitas_2 ORDER BY NOMBRE";
                                                        $sql_tipo_visitas = mysqli_query($connection, $query_select_tipo_visitas);
                                                        if (mysqli_num_rows($sql_tipo_visitas) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=90%>TIPO VISITA</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_tipo_visita'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Tipo Visitas Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-tipo_visita"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_tipo_visita_tab">
                                                    <?php
                                                        if (isset($_GET['id_tipo_visita_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_tipo_visita" name="crear_tipo_visita" action="<?php echo "Modelo/Crear_Admin_Tipo_Visita.php?editar=" . $_GET['id_tipo_visita_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_tipo_visita = mysqli_query($connection, "SELECT * FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $_GET['id_tipo_visita_editar']);
                                                            $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                                        ?>
                                                            <input type="hidden" id="id_tipo_visita_editar_hidden" name="id_tipo_visita_editar_hidden" value="<?php echo $row_tipo_visita['ID_TIPO_VISITA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_tipo_visita_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_tipo_visita" name="crear_tipo_visita" action="<?php echo "Modelo/Crear_Admin_Tipo_Visita.php?eliminar=" . $_GET['id_tipo_visita_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_tipo_visita = mysqli_query($connection, "SELECT * FROM tipo_visitas_2 WHERE ID_TIPO_VISITA = " . $_GET['id_tipo_visita_eliminar']);
                                                                $row_tipo_visita = mysqli_fetch_array($query_select_tipo_visita);
                                                            ?>
                                                                <input type="hidden" id="id_tipo_visita_eliminar_hidden" name="id_tipo_visita_eliminar_hidden" value="<?php echo $row_tipo_visita['ID_TIPO_VISITA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_tipo_visita" name="crear_tipo_visita" action="<?php echo "Modelo/Crear_Admin_Tipo_Visita.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_tipo_visita_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_tipo_visita" name="nombre_tipo_visita" value="<?php echo $row_tipo_visita['NOMBRE']; ?>" maxlength="100" placeholder="Tipo Visita" data-toogle="tooltip" title="TIPO VISITA" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_tipo_visita_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_tipo_visita" name="nombre_tipo_visita" readonly="readonly" placeholder="Tipo Visita" value="<?php echo $row_tipo_visita['NOMBRE']; ?>" data-toogle="tooltip" title="TIPO VISITA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_tipo_visita" name="nombre_tipo_visita" maxlength="100" placeholder="Tipo Visita" data-toogle="tooltip" title="TIPO VISITA" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <?php
                                                                    if (isset($_GET['id_tipo_visita_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_tipo_visita" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Tipo Visita</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Tipo_Visitas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_tipo_visita_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_tipo_visita" type="button" data-toggle="modal" data-target="#modalEliminarTipoVisita"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Tipo Visita</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Tipo_Visitas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_tipo_visita" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Tipo Visita</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldTipoVisita();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </form>
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
    <script>
        function resetFieldTipoVisita() {
            document.getElementById('nombre_tipo_visita').focus();
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_tipo_visita").focus();
            var id_tipo_visita_editar = $("#id_tipo_visita_editar_hidden").val();
            var id_tipo_visita_eliminar = $("#id_tipo_visita_eliminar_hidden").val();
            if (id_tipo_visita_editar != undefined) {
                $(".nav-pills a[href='#crear_tipo_visita_tab']").tab("show");
                $(".nav-pills a[href='#crear_tipo_visita_tab']").text("Actualizar Tipo Visita");
            } else {
                if (id_tipo_visita_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_tipo_visita_tab']").tab("show");
                    $(".nav-pills a[href='#crear_tipo_visita_tab']").text("Eliminar Tipo Visita");
                }
            }
            $("#buscar_tipo_visita").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_tipo_visita;
                    if ($(this).val() == "") {
                        busqueda_tipo_visita = " ";
                    } else {
                        busqueda_tipo_visita = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Tipo_Visita.php",
                        dataType: "json",
                        data: "sw=1&busqueda_tipo_visita="+busqueda_tipo_visita,
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
                                        url: "Modelo/Cargar_Admin_Tipo_Visita.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_tipo_visita="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_tipo_visita").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_tipo_visita").on("shown.bs.tab", function() {
                $("#buscar_tipo_visita").focus();
            });
            $("#tab_crear_tipo_visita").on("shown.bs.tab", function() {
                $("#nombre_tipo_visita").focus();
            });
            $("#tab_info_tipo_visita").on("click", function() {
                $("#buscar_tipo_visita").focus();
            });
            $("#tab_crear_tipo_visita").on("click", function() {
                $("#nombre_tipo_visita").focus();
            });
            if (id_tipo_visita_editar == undefined && id_tipo_visita_eliminar == undefined) {
                $("#btn_crear_tipo_visita").click(function() {
                    var nombre_tipo_visita = $("#nombre_tipo_visita").val().toUpperCase();
                    if (nombre_tipo_visita.length == 0) {
                        $("#nombre_tipo_visita").focus();
                        return false;
                    }
                    $("#btn_crear_tipo_visita").attr("disabled", true);
                    $("#btn_crear_tipo_visita").css("pointer-events", "none");
                    $("#btn_crear_tipo_visita").html("Creando Tipo Visita...");
                    $.ajax({
                        type: "POST",
                        data: "nombre_tipo_visita="+nombre_tipo_visita,
                        url: "Modelo/Crear_Admin_Tipo_Visita.php",
                        success: function(data) {
                            document.location.href = 'Admin_Tipo_Visitas.php';
                        }
                    });
                });
            }
            $("#eliminar_tipo_visita").click(function() {
                var id_tipo_visita_eliminar = $("#id_tipo_visita_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Tipo_Visitas_Registros.php",
                    data: "id_tipo_visita_eliminar="+id_tipo_visita_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_tipo_visita").submit();
                        } else {
                            $("#modalEliminarTipoVisitaError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Tipo_Visita.php",
                dataType: "json",
                data: "sw=0",
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
                                url: "Modelo/Cargar_Admin_Tipo_Visita.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_tipo_visita").html(data[0]);
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[type=text][name=nombre_tipo_visita]').tooltip({
                container: "body",
                placement: "right"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_administrador").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>