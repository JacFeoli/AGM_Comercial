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
        <title>AGM - Admin. Concesiones</title>
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
    <!--Eliminar Concesión Modal-->
    <div class="modal fade" id="modalEliminarConcesion" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Concesión</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar la Concesión?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_concesion" name="eliminar_concesion"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Concesión Modal-->
    <!--Eliminar Concesión Error-->
    <div class="modal fade" id="modalEliminarConcesionError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Concesión</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar la Concesión, ya que existen Registros creados con ésta. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Concesión Error-->
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
                                                    <a href="Admin_Concesiones.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Concesiones</span>
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
                                            <h1>Admin. Concesiones</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_concesion_tab" id="tab_info_concesion" aria-controls="informacion_concesion_tab" role="tab" data-toggle="tab">Información Concesiones</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_concesion_tab" id="tab_crear_concesion" aria-controls="crear_concesion_tab" role="tab" data-toggle="tab">Crear Concesión</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_concesion_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Concesión" name="buscar_concesion" id="buscar_concesion" />
                                                    <br />
                                                    <?php
                                                        $query_select_concesiones = "SELECT * FROM concesiones_2 ORDER BY NOMBRE";
                                                        $sql_concesiones = mysqli_query($connection, $query_select_concesiones);
                                                        if (mysqli_num_rows($sql_concesiones) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=90%>CONCESIÓN</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_concesion'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Concesiones Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-concesion"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_concesion_tab">
                                                    <?php
                                                        if (isset($_GET['id_concesion_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_concesion" name="crear_concesion" action="<?php echo "Modelo/Crear_Admin_Concesion.php?editar=" . $_GET['id_concesion_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_concesion = mysqli_query($connection, "SELECT * FROM concesiones_2 WHERE ID_CONCESION = " . $_GET['id_concesion_editar']);
                                                            $row_concesion = mysqli_fetch_array($query_select_concesion);
                                                        ?>
                                                            <input type="hidden" id="id_concesion_editar_hidden" name="id_concesion_editar_hidden" value="<?php echo $row_concesion['ID_CONCESION']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_concesion_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_concesion" name="crear_concesion" action="<?php echo "Modelo/Crear_Admin_Concesion.php?eliminar=" . $_GET['id_concesion_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_concesion = mysqli_query($connection, "SELECT * FROM concesiones_2 WHERE ID_CONCESION = " . $_GET['id_concesion_eliminar']);
                                                                $row_concesion = mysqli_fetch_array($query_select_concesion);
                                                            ?>
                                                                <input type="hidden" id="id_concesion_eliminar_hidden" name="id_concesion_eliminar_hidden" value="<?php echo $row_concesion['ID_CONCESION']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_concesion" name="crear_concesion" action="<?php echo "Modelo/Crear_Admin_Concesion.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_concesion_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_concesion" name="nombre_concesion" value="<?php echo $row_concesion['NOMBRE']; ?>" maxlength="100" placeholder="Concesión" data-toogle="tooltip" title="CONCESIÓN" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_concesion_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_concesion" name="nombre_concesion" readonly="readonly" placeholder="Concesión" value="<?php echo $row_concesion['NOMBRE']; ?>" data-toogle="tooltip" title="CONCESIÓN" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_concesion" name="nombre_concesion" maxlength="100" placeholder="Concesión" data-toogle="tooltip" title="CONCESIÓN" required />
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
                                                                    if (isset($_GET['id_concesion_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_concesion" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Concesión</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Concesiones.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_concesion_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_concesion" type="button" data-toggle="modal" data-target="#modalEliminarConcesion"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Concesión</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Concesiones.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_concesion" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Concesión</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldConcesion();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldConcesion() {
            document.getElementById('nombre_concesion').focus();
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_concesion").focus();
            var id_concesion_editar = $("#id_concesion_editar_hidden").val();
            var id_concesion_eliminar = $("#id_concesion_eliminar_hidden").val();
            if (id_concesion_editar != undefined) {
                $(".nav-pills a[href='#crear_concesion_tab']").tab("show");
                $(".nav-pills a[href='#crear_concesion_tab']").text("Actualizar Concesión");
            } else {
                if (id_concesion_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_concesion_tab']").tab("show");
                    $(".nav-pills a[href='#crear_concesion_tab']").text("Eliminar Concesión");
                }
            }
            $("#buscar_concesion").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_concesion;
                    if ($(this).val() == "") {
                        busqueda_concesion = " ";
                    } else {
                        busqueda_concesion = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Concesion.php",
                        dataType: "json",
                        data: "sw=1&busqueda_concesion="+busqueda_concesion,
                        success: function(data) {
                            $("#pagination-concesion").twbsPagination('destroy');
                            $("#pagination-concesion").twbsPagination({
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
                                        url: "Modelo/Cargar_Admin_Concesion.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_concesion="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_concesion").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_concesion").on("shown.bs.tab", function() {
                $("#buscar_concesion").focus();
            });
            $("#tab_crear_concesion").on("shown.bs.tab", function() {
                $("#nombre_concesion").focus();
            });
            $("#tab_info_concesion").on("click", function() {
                $("#buscar_concesion").focus();
            });
            $("#tab_crear_concesion").on("click", function() {
                $("#nombre_concesion").focus();
            });
            if (id_concesion_editar == undefined && id_concesion_eliminar == undefined) {
                $("#btn_crear_concesion").click(function() {
                    var nombre_concesion = $("#nombre_concesion").val().toUpperCase();
                    if (nombre_concesion.length == 0) {
                        $("#nombre_concesion").focus();
                        return false;
                    }
                    $("#btn_crear_concesion").attr("disabled", true);
                    $("#btn_crear_concesion").css("pointer-events", "none");
                    $("#btn_crear_concesion").html("Creando Concesión...");
                    $.ajax({
                        type: "POST",
                        data: "nombre_concesion="+nombre_concesion,
                        url: "Modelo/Crear_Admin_Concesion.php",
                        success: function(data) {
                            document.location.href = 'Admin_Concesiones.php';
                        }
                    });
                });
            }
            $("#eliminar_concesion").click(function() {
                var id_concesion_eliminar = $("#id_concesion_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Concesion_Registros.php",
                    data: "id_concesion_eliminar="+id_concesion_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_concesion").submit();
                        } else {
                            $("#modalEliminarConcesionError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Concesion.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-concesion").twbsPagination('destroy');
                    $("#pagination-concesion").twbsPagination({
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
                                url: "Modelo/Cargar_Admin_Concesion.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_concesion").html(data[0]);
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
            $('input[type=text][name=nombre_concesion]').tooltip({
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