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
        <title>AGM - Admin. Tipo Clientes</title>
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
    <!--Eliminar Tipo Cliente Modal-->
    <div class="modal fade" id="modalEliminarTipoCliente" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Tipo Cliente</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Tipo Cliente?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_tipo_cliente" name="eliminar_tipo_cliente"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Tipo Cliente Modal-->
    <!--Eliminar Tipo Cliente Error-->
    <div class="modal fade" id="modalEliminarTipoClienteError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Tipo Cliente</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Tipo Cliente, ya que existen Registros creados con éste. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Tipo Cliente Error-->
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
                                                    <a href="Admin_Tipo_Clientes.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Tipo Clientes</span>
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
                                            <h1>Admin. Tipo Clientes</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_tipo_cliente_tab" id="tab_info_tipo_cliente" aria-controls="informacion_tipo_cliente_tab" role="tab" data-toggle="tab">Información Tipo Clientes</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_tipo_cliente_tab" id="tab_crear_tipo_cliente" aria-controls="crear_tipo_cliente_tab" role="tab" data-toggle="tab">Crear Tipo Cliente</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_tipo_cliente_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Tipo Cliente" name="buscar_tipo_cliente" id="buscar_tipo_cliente" />
                                                    <br />
                                                    <?php
                                                        $query_select_tipo_clientes = "SELECT * FROM tipo_clientes_2 ORDER BY NOMBRE";
                                                        $sql_tipo_clientes = mysqli_query($connection, $query_select_tipo_clientes);
                                                        if (mysqli_num_rows($sql_tipo_clientes) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=90%>TIPO CLIENTE</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_tipo_cliente'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Tipo Clientes Creados.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-tipo_cliente"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_tipo_cliente_tab">
                                                    <?php
                                                        if (isset($_GET['id_tipo_cliente_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_tipo_cliente" name="crear_tipo_cliente" action="<?php echo "Modelo/Crear_Admin_Tipo_Cliente.php?editar=" . $_GET['id_tipo_cliente_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE ID_TIPO_CLIENTE = " . $_GET['id_tipo_cliente_editar']);
                                                            $row_tipo_cliente = mysqli_fetch_array($query_select_tipo_cliente);
                                                        ?>
                                                            <input type="hidden" id="id_tipo_cliente_editar_hidden" name="id_tipo_cliente_editar_hidden" value="<?php echo $row_tipo_cliente['ID_TIPO_CLIENTE']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_tipo_cliente_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_tipo_cliente" name="crear_tipo_cliente" action="<?php echo "Modelo/Crear_Admin_Tipo_Cliente.php?eliminar=" . $_GET['id_tipo_cliente_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE ID_TIPO_CLIENTE = " . $_GET['id_tipo_cliente_eliminar']);
                                                                $row_tipo_cliente = mysqli_fetch_array($query_select_tipo_cliente);
                                                            ?>
                                                                <input type="hidden" id="id_tipo_cliente_eliminar_hidden" name="id_tipo_cliente_eliminar_hidden" value="<?php echo $row_tipo_cliente['ID_TIPO_CLIENTE']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_tipo_cliente" name="crear_tipo_cliente" action="<?php echo "Modelo/Crear_Admin_Tipo_Cliente.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_tipo_cliente_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_tipo_cliente" name="nombre_tipo_cliente" value="<?php echo $row_tipo_cliente['NOMBRE']; ?>" maxlength="100" placeholder="Tipo Cliente" data-toogle="tooltip" title="TIPO CLIENTE" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_tipo_cliente_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_tipo_cliente" name="nombre_tipo_cliente" readonly="readonly" placeholder="Tipo Cliente" value="<?php echo $row_tipo_cliente['NOMBRE']; ?>" data-toogle="tooltip" title="TIPO CLIENTE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_tipo_cliente" name="nombre_tipo_cliente" maxlength="100" placeholder="Tipo Cliente" data-toogle="tooltip" title="TIPO CLIENTE" required />
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
                                                                    if (isset($_GET['id_tipo_cliente_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_tipo_cliente" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Tipo Cliente</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Tipo_Clientes.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_tipo_cliente_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_tipo_cliente" type="button" data-toggle="modal" data-target="#modalEliminarTipoCliente"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Tipo Cliente</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Tipo_Clientes.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_tipo_cliente" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Tipo Cliente</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldTipoCliente();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldTipoCliente() {
            document.getElementById('nombre_tipo_cliente').focus();
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_tipo_cliente").focus();
            var id_tipo_cliente_editar = $("#id_tipo_cliente_editar_hidden").val();
            var id_tipo_cliente_eliminar = $("#id_tipo_cliente_eliminar_hidden").val();
            if (id_tipo_cliente_editar != undefined) {
                $(".nav-pills a[href='#crear_tipo_cliente_tab']").tab("show");
                $(".nav-pills a[href='#crear_tipo_cliente_tab']").text("Actualizar Tipo Cliente");
            } else {
                if (id_tipo_cliente_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_tipo_cliente_tab']").tab("show");
                    $(".nav-pills a[href='#crear_tipo_cliente_tab']").text("Eliminar Tipo Cliente");
                }
            }
            $("#buscar_tipo_cliente").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_tipo_cliente;
                    if ($(this).val() == "") {
                        busqueda_tipo_cliente = " ";
                    } else {
                        busqueda_tipo_cliente = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Tipo_Cliente.php",
                        dataType: "json",
                        data: "sw=1&busqueda_tipo_cliente="+busqueda_tipo_cliente,
                        success: function(data) {
                            $("#pagination-tipo_cliente").twbsPagination('destroy');
                            $("#pagination-tipo_cliente").twbsPagination({
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
                                        url: "Modelo/Cargar_Admin_Tipo_Cliente.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_tipo_cliente="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_tipo_cliente").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_tipo_cliente").on("shown.bs.tab", function() {
                $("#buscar_tipo_cliente").focus();
            });
            $("#tab_crear_tipo_cliente").on("shown.bs.tab", function() {
                $("#nombre_tipo_cliente").focus();
            });
            $("#tab_info_tipo_cliente").on("click", function() {
                $("#buscar_tipo_cliente").focus();
            });
            $("#tab_crear_tipo_cliente").on("click", function() {
                $("#nombre_tipo_cliente").focus();
            });
            if (id_tipo_cliente_editar == undefined && id_tipo_cliente_eliminar == undefined) {
                $("#btn_crear_tipo_cliente").click(function() {
                    var nombre_tipo_cliente = $("#nombre_tipo_cliente").val().toUpperCase();
                    if (nombre_tipo_cliente.length == 0) {
                        $("#nombre_tipo_cliente").focus();
                        return false;
                    }
                    $("#btn_crear_tipo_cliente").attr("disabled", true);
                    $("#btn_crear_tipo_cliente").css("pointer-events", "none");
                    $("#btn_crear_tipo_cliente").html("Creando Tipo Cliente...");
                    $.ajax({
                        type: "POST",
                        data: "nombre_tipo_cliente="+nombre_tipo_cliente,
                        url: "Modelo/Crear_Admin_Tipo_Cliente.php",
                        success: function(data) {
                            document.location.href = 'Admin_Tipo_Clientes.php';
                        }
                    });
                });
            }
            $("#eliminar_tipo_cliente").click(function() {
                var id_tipo_cliente_eliminar = $("#id_tipo_cliente_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Tipo_Cliente_Registros.php",
                    data: "id_tipo_cliente_eliminar="+id_tipo_cliente_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_tipo_cliente").submit();
                        } else {
                            $("#modalEliminarTipoClienteError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Tipo_Cliente.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-tipo_cliente").twbsPagination('destroy');
                    $("#pagination-tipo_cliente").twbsPagination({
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
                                url: "Modelo/Cargar_Admin_Tipo_Cliente.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_tipo_cliente").html(data[0]);
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
            $('input[type=text][name=nombre_tipo_cliente]').tooltip({
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