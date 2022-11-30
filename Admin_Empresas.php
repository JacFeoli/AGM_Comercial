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
        <title>AGM - Admin. Empresas</title>
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
    <!--Eliminar Empresa Modal-->
    <div class="modal fade" id="modalEliminarEmpresa" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Empresa</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar la Empresa?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_empresa" name="eliminar_empresa"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Empresa Modal-->
    <!--Eliminar Empresa Error-->
    <div class="modal fade" id="modalEliminarEmpresaError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Empresa</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar la Empresa, ya que existen Registros creados con ésta. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Empresa Error-->
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
                                                    <a href="Admin_Empresas.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Empresas</span>
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
                                            <h1>Admin. Empresas</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_empresa_tab" id="tab_info_empresa" aria-controls="informacion_empresa_tab" role="tab" data-toggle="tab">Información Empresas</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_empresa_tab" id="tab_crear_empresa" aria-controls="crear_empresa_tab" role="tab" data-toggle="tab">Crear Empresa</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_empresa_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Empresa" name="buscar_empresa" id="buscar_empresa" />
                                                    <br />
                                                    <?php
                                                        $query_select_empresas = "SELECT * FROM empresas_2 ORDER BY NOMBRE";
                                                        $sql_empresas = mysqli_query($connection, $query_select_empresas);
                                                        if (mysqli_num_rows($sql_empresas) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=90%>EMPRESA</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_empresa'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Empresas Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-empresa"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_empresa_tab">
                                                    <?php
                                                        if (isset($_GET['id_empresa_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_empresa" name="crear_empresa" action="<?php echo "Modelo/Crear_Admin_Empresa.php?editar=" . $_GET['id_empresa_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_empresa = mysqli_query($connection, "SELECT * FROM empresas_2 WHERE ID_EMPRESA = " . $_GET['id_empresa_editar']);
                                                            $row_empresa = mysqli_fetch_array($query_select_empresa);
                                                        ?>
                                                            <input type="hidden" id="id_empresa_editar_hidden" name="id_empresa_editar_hidden" value="<?php echo $row_empresa['ID_EMPRESA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_empresa_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_empresa" name="crear_empresa" action="<?php echo "Modelo/Crear_Admin_Empresa.php?eliminar=" . $_GET['id_empresa_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_empresa = mysqli_query($connection, "SELECT * FROM empresas_2 WHERE ID_EMPRESA = " . $_GET['id_empresa_eliminar']);
                                                                $row_empresa = mysqli_fetch_array($query_select_empresa);
                                                            ?>
                                                                <input type="hidden" id="id_empresa_eliminar_hidden" name="id_empresa_eliminar_hidden" value="<?php echo $row_empresa['ID_EMPRESA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_empresa" name="crear_empresa" action="<?php echo "Modelo/Crear_Admin_Empresa.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-9">
                                                                <?php
                                                                    if (isset($_GET['id_empresa_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_empresa" name="nombre_empresa" value="<?php echo $row_empresa['NOMBRE']; ?>" maxlength="100" placeholder="Empresa" data-toogle="tooltip" title="EMPRESA" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_empresa_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_empresa" name="nombre_empresa" readonly="readonly" placeholder="Empresa" value="<?php echo $row_empresa['NOMBRE']; ?>" data-toogle="tooltip" title="EMPRESA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_empresa" name="nombre_empresa" maxlength="100" placeholder="Empresa" data-toogle="tooltip" title="EMPRESA" required />
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
                                                                    if (isset($_GET['id_empresa_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_empresa" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Empresa</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Empresas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_empresa_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_empresa" type="button" data-toggle="modal" data-target="#modalEliminarEmpresa"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Empresa</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Empresas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_empresa" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Empresa</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldEmpresa();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldEmpresa() {
            document.getElementById('nombre_empresa').focus();
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_empresa").focus();
            var id_empresa_editar = $("#id_empresa_editar_hidden").val();
            var id_empresa_eliminar = $("#id_empresa_eliminar_hidden").val();
            if (id_empresa_editar != undefined) {
                $(".nav-pills a[href='#crear_empresa_tab']").tab("show");
                $(".nav-pills a[href='#crear_empresa_tab']").text("Actualizar Empresa");
            } else {
                if (id_empresa_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_empresa_tab']").tab("show");
                    $(".nav-pills a[href='#crear_empresa_tab']").text("Eliminar Empresa");
                }
            }
            $("#buscar_empresa").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_empresa;
                    if ($(this).val() == "") {
                        busqueda_empresa = " ";
                    } else {
                        busqueda_empresa = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Empresa.php",
                        dataType: "json",
                        data: "sw=1&busqueda_empresa="+busqueda_empresa,
                        success: function(data) {
                            $("#pagination-empresa").twbsPagination('destroy');
                            $("#pagination-empresa").twbsPagination({
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
                                        url: "Modelo/Cargar_Admin_Empresa.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_empresa="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_empresa").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_empresa").on("shown.bs.tab", function() {
                $("#buscar_empresa").focus();
            });
            $("#tab_crear_empresa").on("shown.bs.tab", function() {
                $("#nombre_empresa").focus();
            });
            $("#tab_info_empresa").on("click", function() {
                $("#buscar_empresa").focus();
            });
            $("#tab_crear_empresa").on("click", function() {
                $("#nombre_empresa").focus();
            });
            if (id_empresa_editar == undefined && id_empresa_eliminar == undefined) {
                $("#btn_crear_empresa").click(function() {
                    var nombre_empresa = $("#nombre_empresa").val().toUpperCase();
                    if (nombre_empresa.length == 0) {
                        $("#nombre_empresa").focus();
                        return false;
                    }
                    $("#btn_crear_empresa").attr("disabled", true);
                    $("#btn_crear_empresa").css("pointer-events", "none");
                    $("#btn_crear_empresa").html("Creando Empresa...");
                    $.ajax({
                        type: "POST",
                        data: "nombre_empresa="+nombre_empresa,
                        url: "Modelo/Crear_Admin_Empresa.php",
                        success: function(data) {
                            document.location.href = 'Admin_Empresas.php';
                        }
                    });
                });
            }
            $("#eliminar_empresa").click(function() {
                var id_empresa_eliminar = $("#id_empresa_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Empresa_Registros.php",
                    data: "id_empresa_eliminar="+id_empresa_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_empresa").submit();
                        } else {
                            $("#modalEliminarEmpresaError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Empresa.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-empresa").twbsPagination('destroy');
                    $("#pagination-empresa").twbsPagination({
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
                                url: "Modelo/Cargar_Admin_Empresa.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_empresa").html(data[0]);
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
            $('input[type=text][name=nombre_empresa]').tooltip({
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