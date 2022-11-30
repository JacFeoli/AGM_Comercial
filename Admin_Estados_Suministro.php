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
        <title>AGM - Admin. Estados Suministro</title>
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
    <!--Eliminar Estado Suministro Modal-->
    <div class="modal fade" id="modalEliminarEstadoSuministro" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Estado Suministro</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Estado Suministro?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_estado_suministro" name="eliminar_estado_suministro"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Estado Suministro Modal-->
    <!--Eliminar Estado Suministro Error-->
    <div class="modal fade" id="modalEliminarEstadoSuministroError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Estado Suministro</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Estado Suministro, ya que existen Registros creados con ésta. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Estado Suministro Error-->
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
                                        <div style="width: 177px;" class="leftcol">
                                            <h3 style="text-align: center;">OPCIONES DISPONIBLES</h3>
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class='active'>
                                                    <a href="Admin_Estados_Suministro.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Estados Suministro</span>
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
                                            <h1>Admin. Estados Suministro</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_estado_suministro_tab" id="tab_info_estado_suministro" aria-controls="informacion_estado_suministro_tab" role="tab" data-toggle="tab">Información Estados Suministro</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_estado_suministro_tab" id="tab_crear_estado_suministro" aria-controls="crear_estado_suministro_tab" role="tab" data-toggle="tab">Crear Estado Suministro</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_estado_suministro_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Estado Suministro" name="buscar_estado_suministro" id="buscar_estado_suministro" />
                                                    <br />
                                                    <?php
                                                        $query_select_estados_suministro = "SELECT * FROM estados_suministro_2 ORDER BY NOMBRE";
                                                        $sql_estados_suministro = mysqli_query($connection, $query_select_estados_suministro);
                                                        if (mysqli_num_rows($sql_estados_suministro) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=90%>ESTADO SUMINISTRO</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_estado_suministro'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Estados Suministro Creados.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-estado_suministro"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_estado_suministro_tab">
                                                    <?php
                                                        if (isset($_GET['id_estado_suministro_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_estado_suministro" name="crear_estado_suministro" action="<?php echo "Modelo/Crear_Admin_Estado_Suministro.php?editar=" . $_GET['id_estado_suministro_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_estado_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE ID_ESTADO_SUMINISTRO = " . $_GET['id_estado_suministro_editar']);
                                                            $row_estado_suministro = mysqli_fetch_array($query_select_estado_suministro);
                                                        ?>
                                                            <input type="hidden" id="id_estado_suministro_editar_hidden" name="id_estado_suministro_editar_hidden" value="<?php echo $row_estado_suministro['ID_ESTADO_SUMINISTRO']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_estado_suministro_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_estado_suministro" name="crear_estado_suministro" action="<?php echo "Modelo/Crear_Admin_Estado_Suministro.php?eliminar=" . $_GET['id_estado_suministro_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_estado_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE ID_ESTADO_SUMINISTRO = " . $_GET['id_estado_suministro_eliminar']);
                                                                $row_estado_suministro = mysqli_fetch_array($query_select_estado_suministro);
                                                            ?>
                                                                <input type="hidden" id="id_estado_suministro_eliminar_hidden" name="id_estado_suministro_eliminar_hidden" value="<?php echo $row_estado_suministro['ID_ESTADO_SUMINISTRO']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_estado_suministro" name="crear_estado_suministro" action="<?php echo "Modelo/Crear_Admin_Estado_Suministro.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_estado_suministro_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_estado_suministro" name="nombre_estado_suministro" value="<?php echo $row_estado_suministro['NOMBRE']; ?>" maxlength="100" placeholder="Estado Suministro" data-toogle="tooltip" title="ESTADO SUMINISTRO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_estado_suministro_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_estado_suministro" name="nombre_estado_suministro" readonly="readonly" placeholder="Estado Suministro" value="<?php echo $row_estado_suministro['NOMBRE']; ?>" data-toogle="tooltip" title="ESTADO SUMINISTRO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_estado_suministro" name="nombre_estado_suministro" maxlength="100" placeholder="Estado Suministro" data-toogle="tooltip" title="ESTADO SUMINISTRO" required />
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
                                                                    if (isset($_GET['id_estado_suministro_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_estado_suministro" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Estado Suministro</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Estados_Suministro.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_estado_suministro_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_estado_suministro" type="button" data-toggle="modal" data-target="#modalEliminarEstadoSuministro"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Estado Suministro</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Estados_Suministro.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_estado_suministro" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Estado Suministro</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldEstadoSuministro();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldEstadoSuministro() {
            document.getElementById('nombre_estado_suministro').focus();
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_estado_suministro").focus();
            var id_estado_suministro_editar = $("#id_estado_suministro_editar_hidden").val();
            var id_estado_suministro_eliminar = $("#id_estado_suministro_eliminar_hidden").val();
            if (id_estado_suministro_editar != undefined) {
                $(".nav-pills a[href='#crear_estado_suministro_tab']").tab("show");
                $(".nav-pills a[href='#crear_estado_suministro_tab']").text("Actualizar Estado Suministro");
            } else {
                if (id_estado_suministro_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_estado_suministro_tab']").tab("show");
                    $(".nav-pills a[href='#crear_estado_suministro_tab']").text("Eliminar Estado Suministro");
                }
            }
            $("#buscar_estado_suministro").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_estado_suministro;
                    if ($(this).val() == "") {
                        busqueda_estado_suministro = " ";
                    } else {
                        busqueda_estado_suministro = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Estado_Suministro.php",
                        dataType: "json",
                        data: "sw=1&busqueda_estado_suministro="+busqueda_estado_suministro,
                        success: function(data) {
                            $("#pagination-estado_suministro").twbsPagination('destroy');
                            $("#pagination-estado_suministro").twbsPagination({
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
                                        url: "Modelo/Cargar_Admin_Estado_Suministro.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_estado_suministro="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_estado_suministro").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_estado_suministro").on("shown.bs.tab", function() {
                $("#buscar_estado_suministro").focus();
            });
            $("#tab_crear_estado_suministro").on("shown.bs.tab", function() {
                $("#nombre_estado_suministro").focus();
            });
            $("#tab_info_estado_suministro").on("click", function() {
                $("#buscar_estado_suministro").focus();
            });
            $("#tab_crear_estado_suministro").on("click", function() {
                $("#nombre_estado_suministro").focus();
            });
            if (id_estado_suministro_editar == undefined && id_estado_suministro_eliminar == undefined) {
                $("#btn_crear_estado_suministro").click(function() {
                    var nombre_estado_suministro = $("#nombre_estado_suministro").val().toUpperCase();
                    if (nombre_estado_suministro.length == 0) {
                        $("#nombre_estado_suministro").focus();
                        return false;
                    }
                    $("#btn_crear_estado_suministro").attr("disabled", true);
                    $("#btn_crear_estado_suministro").css("pointer-events", "none");
                    $("#btn_crear_estado_suministro").html("Creando Estado Suministro...");
                    $.ajax({
                        type: "POST",
                        data: "nombre_estado_suministro="+nombre_estado_suministro,
                        url: "Modelo/Crear_Admin_Estado_Suministro.php",
                        success: function(data) {
                            document.location.href = 'Admin_Estados_Suministro.php';
                        }
                    });
                });
            }
            $("#eliminar_estado_suministro").click(function() {
                var id_estado_suministro_eliminar = $("#id_estado_suministro_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Estado_Suministro_Registros.php",
                    data: "id_estado_suministro_eliminar="+id_estado_suministro_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_estado_suministro").submit();
                        } else {
                            $("#modalEliminarEstadoSuministroError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Estado_Suministro.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-estado_suministro").twbsPagination('destroy');
                    $("#pagination-estado_suministro").twbsPagination({
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
                                url: "Modelo/Cargar_Admin_Estado_Suministro.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_estado_suministro").html(data[0]);
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
            $('input[type=text][name=nombre_estado_suministro]').tooltip({
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