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
        <title>AGM - Admin. Departamentos Cargue</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <!--<link rel="stylesheet" href="Css/font-awesome.css">-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="Javascript/bootstrap.min.js"></script>
        <script src="Javascript/jquery.twbsPagination.js"></script>
    </head>
    <!--Eliminar Departamento Modal-->
    <div class="modal fade" id="modalEliminarDepartamento" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Departamento</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Departamento?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_departamento" name="eliminar_departamento"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Departamento Modal-->
    <!--Eliminar Departamento Error-->
    <div class="modal fade" id="modalEliminarDepartamentoError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Departamento</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Departamento, ya que existen Registros creados con éste. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Departamento Error-->
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
                                                    <a href="Admin_Departamentos.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Dptos. Cargue</span>
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
                                            <h1>Admin. Departamentos Cargues</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_departamento_tab" id="tab_info_departamento" aria-controls="informacion_departamento_tab" role="tab" data-toggle="tab">Información Departamentos</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_departamento_tab" id="tab_crear_departamento" aria-controls="crear_departamento_tab" role="tab" data-toggle="tab">Crear Departamento</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_departamento_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Departamento" name="buscar_departamento" id="buscar_departamento" />
                                                    <br />
                                                    <?php
                                                        $query_select_departamentos = "SELECT * FROM departamentos_2 ORDER BY NOMBRE";
                                                        $sql_departamentos = mysqli_query($connection, $query_select_departamentos);
                                                        if (mysqli_num_rows($sql_departamentos) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=5%>ID.</th>";
                                                                            echo "<th width=85%>DEPARTAMENTO</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_departamento'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Departamentos Creados.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-departamento"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_departamento_tab">
                                                    <?php
                                                        if (isset($_GET['id_departamento_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_departamento" name="crear_departamento" action="<?php echo "Modelo/Crear_Admin_Departamento.php?editar=" . $_GET['id_departamento_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_2 WHERE ID_TABLA = " . $_GET['id_departamento_editar']);
                                                            $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                        ?>
                                                            <input type="hidden" id="id_departamento_editar_hidden" name="id_departamento_editar_hidden" value="<?php echo $row_departamento['ID_TABLA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_departamento_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_departamento" name="crear_departamento" action="<?php echo "Modelo/Crear_Admin_Departamento.php?eliminar=" . $_GET['id_departamento_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_2 WHERE ID_TABLA = " . $_GET['id_departamento_eliminar']);
                                                                $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                            ?>
                                                                <input type="hidden" id="id_departamento_eliminar_hidden" name="id_departamento_eliminar_hidden" value="<?php echo $row_departamento['ID_TABLA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_departamento" name="crear_departamento" action="<?php echo "Modelo/Crear_Admin_Departamento.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_departamento_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" maxlength="3" placeholder="ID. Departamento" data-toogle="tooltip" title="ID. DEPARTAMENTO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_departamento_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="id_departamento" name="id_departamento" readonly="readonly" placeholder="ID. Departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" data-toogle="tooltip" title="ID. DEPARTAMENTO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="id_departamento" name="id_departamento" maxlength="3" placeholder="ID. Departamento" data-toogle="tooltip" title="ID. DEPARTAMENTO" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_departamento_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_departamento" name="nombre_departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" maxlength="30" placeholder="Departamento" data-toogle="tooltip" title="DEPARTAMENTO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_departamento_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_departamento" name="nombre_departamento" readonly="readonly" placeholder="Departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" data-toogle="tooltip" title="DEPARTAMENTO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_departamento" name="nombre_departamento" maxlength="30" placeholder="Departamento" data-toogle="tooltip" title="DEPARTAMENTO" required />
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
                                                                    if (isset($_GET['id_departamento_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_departamento" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Departamento</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Departamentos.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_departamento_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_departamento" type="button" data-toggle="modal" data-target="#modalEliminarDepartamento"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Departamento</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Departamentos.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_departamento" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Departamento</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldDepartamento();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldDepartamento() {
            document.getElementById('id_departamento').focus();
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_departamento").focus();
            var id_departamento_editar = $("#id_departamento_editar_hidden").val();
            var id_departamento_eliminar = $("#id_departamento_eliminar_hidden").val();
            if (id_departamento_editar != undefined) {
                $(".nav-pills a[href='#crear_departamento_tab']").tab("show");
                $(".nav-pills a[href='#crear_departamento_tab']").text("Actualizar Departamento");
            } else {
                if (id_departamento_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_departamento_tab']").tab("show");
                    $(".nav-pills a[href='#crear_departamento_tab']").text("Eliminar Departamento");
                }
            }
            $("#buscar_departamento").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_departamento;
                    if ($(this).val() == "") {
                        busqueda_departamento = "";
                    } else {
                        busqueda_departamento = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Departamento.php",
                        dataType: "json",
                        data: "sw=1&busqueda_departamento="+busqueda_departamento,
                        success: function(data) {
                            $("#pagination-departamento").twbsPagination('destroy');
                            $("#pagination-departamento").twbsPagination({
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
                                        url: "Modelo/Cargar_Admin_Departamento.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_departamento="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_departamento").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_departamento").on("shown.bs.tab", function() {
                $("#buscar_departamento").focus();
            });
            $("#tab_crear_departamento").on("shown.bs.tab", function() {
                $("#id_departamento").focus();
            });
            $("#tab_info_departamento").on("click", function() {
                $("#buscar_departamento").focus();
            });
            $("#tab_crear_departamento").on("click", function() {
                $("#id_departamento").focus();
            });
            if (id_departamento_editar == undefined && id_departamento_eliminar == undefined) {
                $("#btn_crear_departamento").click(function() {
                    var id_departamento = $("#id_departamento").val();
                    var nombre_departamento = $("#nombre_departamento").val().toUpperCase();
                    if (id_departamento.length == 0) {
                        $("#id_departamento").focus();
                        return false;
                    }
                    if (nombre_departamento.length == 0) {
                        $("#nombre_departamento").focus();
                        return false;
                    }
                    $("#btn_crear_departamento").attr("disabled", true);
                    $("#btn_crear_departamento").css("pointer-events", "none");
                    $("#btn_crear_departamento").html("Creando Departamento...");
                    $.ajax({
                        type: "POST",
                        data: "id_departamento="+id_departamento+"&nombre_departamento="+nombre_departamento,
                        url: "Modelo/Crear_Admin_Departamento.php",
                        success: function(data) {
                            document.location.href = 'Admin_Departamentos.php';
                        }
                    });
                });
            }
            $("#eliminar_departamento").click(function() {
                var id_departamento_eliminar = $("#id_departamento_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Departamento_Registros.php",
                    data: "id_departamento_eliminar="+id_departamento_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_departamento").submit();
                        } else {
                            $("#modalEliminarDepartamentoError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Departamento.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-departamento").twbsPagination('destroy');
                    $("#pagination-departamento").twbsPagination({
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
                                url: "Modelo/Cargar_Admin_Departamento.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_departamento").html(data[0]);
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
            $('input[type=text][name=id_departamento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nombre_departamento]').tooltip({
                container: "body",
                placement: "top"
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