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
        <title>AGM - Admin. Municipios Cargue</title>
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
    <!--Eliminar Municipio Modal-->
    <div class="modal fade" id="modalEliminarMunicipio" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Municipio</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Municipio?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_municipio" name="eliminar_municipio"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Municipio Modal-->
    <!--Eliminar Municipio Error-->
    <div class="modal fade" id="modalEliminarMunicipioError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Municipio</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Municipio, ya que existen Registros creados con éste. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Municipio Error-->
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
                                                    <a href="Admin_Municipios.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Mpios. Cargue</span>
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
                                            <h1>Admin. Municipios Cargues</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_municipio_tab" id="tab_info_municipio" aria-controls="informacion_municipio_tab" role="tab" data-toggle="tab">Información Municipios</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_municipio_tab" id="tab_crear_municipio" aria-controls="crear_municipio_tab" role="tab" data-toggle="tab">Crear Municipio</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_municipio_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Municipío" name="buscar_municipio" id="buscar_municipio" />
                                                    <br />
                                                    <?php
                                                        $query_select_municipios = "SELECT * "
                                                                                   . "FROM municipios_2 MUN, departamentos_2 DEP "
                                                                                   . "WHERE DEP.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO "
                                                                                   . "ORDER BY DEP.NOMBRE, MUN.NOMBRE";
                                                        $sql_municipios = mysqli_query($connection, $query_select_municipios);
                                                        if (mysqli_num_rows($sql_municipios) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=5%>ID.</th>";
                                                                            echo "<th width=50%>MUNICIPIO</th>";
                                                                            echo "<th width=5%>ID.</th>";
                                                                            echo "<th width=30%>DEPARTAMENTO</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_municipio'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Municipios Creados.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-municipio"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_municipio_tab">
                                                    <?php
                                                        if (isset($_GET['id_municipio_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_municipio" name="crear_municipio" action="<?php echo "Modelo/Crear_Admin_Municipio.php?editar=" . $_GET['id_municipio_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_2 WHERE ID_TABLA = " . $_GET['id_municipio_editar']);
                                                            $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                        ?>
                                                            <input type="hidden" id="id_municipio_editar_hidden" name="id_municipio_editar_hidden" value="<?php echo $row_municipio['ID_TABLA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_municipio_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_municipio" name="crear_municipio" action="<?php echo "Modelo/Crear_Admin_Municipio.php?eliminar=" . $_GET['id_municipio_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_2 WHERE ID_TABLA = " . $_GET['id_municipio_eliminar']);
                                                                $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                            ?>
                                                                <input type="hidden" id="id_municipio_eliminar_hidden" name="id_municipio_eliminar_hidden" value="<?php echo $row_municipio['ID_TABLA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_municipio" name="crear_municipio" action="<?php echo "Modelo/Crear_Admin_Municipio.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_2 WHERE ID_DEPARTAMENTO = " . $row_municipio['ID_DEPARTAMENTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(0)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_2 WHERE ID_DEPARTAMENTO = " . $row_municipio['ID_DEPARTAMENTO']);
                                                                            $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                        ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(0)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" maxlength="5" placeholder="ID. Municipio" data-toogle="tooltip" title="ID. MUNICIPIO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="id_municipio" name="id_municipio" readonly="readonly" placeholder="ID. Municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" data-toogle="tooltip" title="ID. MUNICIPIO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="id_municipio" name="id_municipio" maxlength="5" placeholder="ID. Municipio" data-toogle="tooltip" title="ID. MUNICIPIO" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_municipio_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_municipio" name="nombre_municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" maxlength="50" placeholder="Municipio" data-toogle="tooltip" title="MUNICIPIO" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_municipio" name="nombre_municipio" readonly="readonly" placeholder="Municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" data-toogle="tooltip" title="MUNICIPIO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_municipio" name="nombre_municipio" maxlength="50" placeholder="Municipio" data-toogle="tooltip" title="MUNICIPIO" required />
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
                                                                    if (isset($_GET['id_municipio_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_municipio" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Municipio</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Municipios.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_municipio_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_municipio" type="button" data-toggle="modal" data-target="#modalEliminarMunicipio"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Municipio</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Municipios.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_municipio" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Municipio</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldMunicipio();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldMunicipio() {
            $("#id_documento").val("");
            document.getElementById('departamento').focus();
        }
        function infoTipoDepartamento(id_consulta, id_departamento, departamento) {
            if (id_consulta == 0) {
                $("#id_departamento").val(id_departamento);
                $("#departamento").val(departamento);
                $("#id_municipio").val("");
                $("#municipio").val("");
                $("#id_municipio").focus();
            }
        }
        function tipoDepartamento(id_consulta) {
            window.open("Combos/Tipo_Departamento.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_municipio").focus();
            var id_municipio_editar = $("#id_municipio_editar_hidden").val();
            var id_municipio_eliminar = $("#id_municipio_eliminar_hidden").val();
            if (id_municipio_editar != undefined) {
                $(".nav-pills a[href='#crear_municipio_tab']").tab("show");
                $(".nav-pills a[href='#crear_municipio_tab']").text("Actualizar Municipio");
            } else {
                if (id_municipio_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_municipio_tab']").tab("show");
                    $(".nav-pills a[href='#crear_municipio_tab']").text("Eliminar Municipio");
                }
            }
            $("#buscar_municipio").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_municipio;
                    if ($(this).val() == "") {
                        busqueda_municipio = "";
                    } else {
                        busqueda_municipio = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Municipio.php",
                        dataType: "json",
                        data: "sw=1&busqueda_municipio="+busqueda_municipio,
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
                                        url: "Modelo/Cargar_Admin_Municipio.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_municipio="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_municipio").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_municipio").on("shown.bs.tab", function() {
                $("#buscar_municipio").focus();
            });
            $("#tab_crear_municipio").on("shown.bs.tab", function() {
                $("#departamento").focus();
            });
            $("#tab_info_municipio").on("click", function() {
                $("#buscar_municipio").focus();
            });
            $("#tab_crear_municipio").on("click", function() {
                $("#departamento").focus();
            });
            if (id_municipio_editar == undefined && id_municipio_eliminar == undefined) {
                $("#btn_crear_municipio").click(function() {
                    var id_departamento = $("#id_departamento").val();
                    var id_municipio = $("#id_municipio").val();
                    var nombre_municipio = $("#nombre_municipio").val().toUpperCase();
                    if (id_departamento.length == 0) {
                        $("#id_departamento").focus();
                        return false;
                    }
                    if (id_municipio.length == 0) {
                        $("#id_municipio").focus();
                        return false;
                    }
                    if (nombre_municipio.length == 0) {
                        $("#nombre_municipio").focus();
                        return false;
                    }
                    $("#btn_crear_municipio").attr("disabled", true);
                    $("#btn_crear_municipio").css("pointer-events", "none");
                    $("#btn_crear_municipio").html("Creando Municipio...");
                    $.ajax({
                        type: "POST",
                        data: "id_departamento="+id_departamento+"&id_municipio="+id_municipio+"&nombre_municipio="+nombre_municipio,
                        url: "Modelo/Crear_Admin_Municipio.php",
                        success: function(data) {
                            document.location.href = 'Admin_Municipios.php';
                        }
                    });
                });
            }
            $("#eliminar_municipio").click(function() {
                var id_municipio_eliminar = $("#id_municipio_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Municipio_Registros.php",
                    data: "id_municipio_eliminar="+id_municipio_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_municipio").submit();
                        } else {
                            $("#modalEliminarMunicipioError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Municipio.php",
                dataType: "json",
                data: "sw=0",
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
                                url: "Modelo/Cargar_Admin_Municipio.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_municipio").html(data[0]);
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
            $('input[type=text][name=departamento]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=id_municipio]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nombre_municipio]').tooltip({
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