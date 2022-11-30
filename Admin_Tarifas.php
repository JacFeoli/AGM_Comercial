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
        <title>AGM - Admin. Tarifas</title>
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
    <!--Eliminar Tarifa Modal-->
    <div class="modal fade" id="modalEliminarTarifa" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Tarifa</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar la Tarifa?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_tarifa" name="eliminar_tarifa"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Tarifa Modal-->
    <!--Eliminar Tarifa Error-->
    <div class="modal fade" id="modalEliminarTarifaError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Tarifa</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar la Tarifa, ya que existen Registros creados con ésta. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Tarifa Error-->
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
                                                    <a href="Admin_Tarifas.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Tarifas</span>
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
                                            <h1>Admin. Tarifas</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_tarifa_tab" id="tab_info_tarifa" aria-controls="informacion_tarifa_tab" role="tab" data-toggle="tab">Información Tarifas</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_tarifa_tab" id="tab_crear_tarifa" aria-controls="crear_tarifa_tab" role="tab" data-toggle="tab">Crear Tarifa</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_tarifa_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Tarifa" name="buscar_tarifa" id="buscar_tarifa" />
                                                    <br />
                                                    <?php
                                                        $query_select_tarifas = "SELECT * FROM tarifas_2 ORDER BY NOMBRE";
                                                        $sql_tarifas = mysqli_query($connection, $query_select_tarifas);
                                                        if (mysqli_num_rows($sql_tarifas) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=10%>COD. TARIFA</th>";
                                                                            echo "<th width=80%>TARIFA</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_tarifa'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Tarifas Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-tarifa"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_tarifa_tab">
                                                    <?php
                                                        if (isset($_GET['id_tarifa_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_tarifa" name="crear_tarifa" action="<?php echo "Modelo/Crear_Admin_Tarifa.php?editar=" . $_GET['id_tarifa_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE ID_TARIFA = " . $_GET['id_tarifa_editar']);
                                                            $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                                        ?>
                                                            <input type="hidden" id="id_tarifa_editar_hidden" name="id_tarifa_editar_hidden" value="<?php echo $row_tarifa['ID_TARIFA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_tarifa_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_tarifa" name="crear_tarifa" action="<?php echo "Modelo/Crear_Admin_Tarifa.php?eliminar=" . $_GET['id_tarifa_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE ID_TARIFA = " . $_GET['id_tarifa_eliminar']);
                                                                $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                                            ?>
                                                                <input type="hidden" id="id_tarifa_eliminar_hidden" name="id_tarifa_eliminar_hidden" value="<?php echo $row_tarifa['ID_TARIFA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_tarifa" name="crear_tarifa" action="<?php echo "Modelo/Crear_Admin_Tarifa.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-2">
                                                                <?php
                                                                    if (isset($_GET['id_tarifa_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="codigo_tarifa" name="codigo_tarifa" value="<?php echo $row_tarifa['COD_TARIFA']; ?>" maxlength="20" placeholder="Cod. Tarifa" data-toogle="tooltip" title="COD. TARIFA" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_tarifa_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="codigo_tarifa" name="codigo_tarifa" readonly="readonly" placeholder="Cod. Tarifa" value="<?php echo $row_tarifa['COD_TARIFA']; ?>" data-toogle="tooltip" title="COD. TARIFA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="codigo_tarifa" name="codigo_tarifa" maxlength="20" placeholder="Cod. Tarifa" data-toogle="tooltip" title="COD. TARIFA" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_tarifa_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_tarifa" name="nombre_tarifa" value="<?php echo $row_tarifa['NOMBRE']; ?>" maxlength="100" placeholder="Tarifa" data-toogle="tooltip" title="TARIFA" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_tarifa_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_tarifa" name="nombre_tarifa" readonly="readonly" placeholder="Tarifa" value="<?php echo $row_tarifa['NOMBRE']; ?>" data-toogle="tooltip" title="TARIFA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_tarifa" name="nombre_tarifa" maxlength="100" placeholder="Tarifa" data-toogle="tooltip" title="TARIFA" required />
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
                                                                    if (isset($_GET['id_tarifa_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_tarifa" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Tarifa</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Tarifas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_tarifa_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_tarifa" type="button" data-toggle="modal" data-target="#modalEliminarTarifa"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Tarifa</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Tarifas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_tarifa" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Tarifa</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldTarifa();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldTarifa() {
            document.getElementById('codigo_tarifa').focus();
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_tarifa").focus();
            var id_tarifa_editar = $("#id_tarifa_editar_hidden").val();
            var id_tarifa_eliminar = $("#id_tarifa_eliminar_hidden").val();
            if (id_tarifa_editar != undefined) {
                $(".nav-pills a[href='#crear_tarifa_tab']").tab("show");
                $(".nav-pills a[href='#crear_tarifa_tab']").text("Actualizar Tarifa");
            } else {
                if (id_tarifa_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_tarifa_tab']").tab("show");
                    $(".nav-pills a[href='#crear_tarifa_tab']").text("Eliminar Tarifa");
                }
            }
            $("#buscar_tarifa").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_tarifa;
                    if ($(this).val() == "") {
                        busqueda_tarifa = " ";
                    } else {
                        busqueda_tarifa = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Tarifa.php",
                        dataType: "json",
                        data: "sw=1&busqueda_tarifa="+busqueda_tarifa,
                        success: function(data) {
                            $("#pagination-tarifa").twbsPagination('destroy');
                            $("#pagination-tarifa").twbsPagination({
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
                                        url: "Modelo/Cargar_Admin_Tarifa.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_tarifa="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_tarifa").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_tarifa").on("shown.bs.tab", function() {
                $("#buscar_tarifa").focus();
            });
            $("#tab_crear_tarifa").on("shown.bs.tab", function() {
                $("#codigo_tarifa").focus();
            });
            $("#tab_info_tarifa").on("click", function() {
                $("#buscar_tarifa").focus();
            });
            $("#tab_crear_tarifa").on("click", function() {
                $("#codigo_tarifa").focus();
            });
            if (id_tarifa_editar == undefined && id_tarifa_eliminar == undefined) {
                $("#btn_crear_tarifa").click(function() {
                    var codigo_tarifa = $("#codigo_tarifa").val();
                    var nombre_tarifa = $("#nombre_tarifa").val().toUpperCase();
                    if (codigo_tarifa.length == 0) {
                        $("#codigo_tarifa").focus();
                        return false;
                    }
                    if (nombre_tarifa.length == 0) {
                        $("#nombre_tarifa").focus();
                        return false;
                    }
                    $("#btn_crear_tarifa").attr("disabled", true);
                    $("#btn_crear_tarifa").css("pointer-events", "none");
                    $("#btn_crear_tarifa").html("Creando Tarifa...");
                    $.ajax({
                        type: "POST",
                        data: "codigo_tarifa="+codigo_tarifa+"&nombre_tarifa="+nombre_tarifa,
                        url: "Modelo/Crear_Admin_Tarifa.php",
                        success: function(data) {
                            document.location.href = 'Admin_Tarifas.php';
                        }
                    });
                });
            }
            $("#eliminar_tarifa").click(function() {
                var id_tarifa_eliminar = $("#id_tarifa_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Tarifa_Registros.php",
                    data: "id_tarifa_eliminar="+id_tarifa_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_tarifa").submit();
                        } else {
                            $("#modalEliminarTarifaError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Tarifa.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-tarifa").twbsPagination('destroy');
                    $("#pagination-tarifa").twbsPagination({
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
                                url: "Modelo/Cargar_Admin_Tarifa.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_tarifa").html(data[0]);
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
            $('input[type=text][name=codigo_tarifa]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nombre_tarifa]').tooltip({
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