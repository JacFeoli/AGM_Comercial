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
        <title>AGM - Admin. Comercializadores</title>
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
        <style type="text/css">
            .text-divider{margin: 2em 0; line-height: 0; text-align: center;}
            .text-divider span{background-color: #D0DEE7; padding: 1em;}
            .text-divider:before{ content: " "; display: block; border-top: 1px solid #A9BDC8;}
        </style>
    </head>
    <!--Eliminar Comercializador Modal-->
    <div class="modal fade" id="modalEliminarComercializador" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Comercializador</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Comercializador?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_comercializador" name="eliminar_comercializador"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Comercializador Modal-->
    <!--Eliminar Comercializador Error-->
    <div class="modal fade" id="modalEliminarComercializadorError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Comercializador</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Comercializador, ya que existen Registros creados con éste. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Comercializador Error-->
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
                                                    <a href="Admin_Comercializadores.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Comercializadores</span>
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
                                            <h1>Admin. Comercializadores</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_comercializador_tab" id="tab_info_comercializador" aria-controls="informacion_comercializador_tab" role="tab" data-toggle="tab">Información Comercializadores</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_comercializador_tab" id="tab_crear_comercializador" aria-controls="crear_comercializador_tab" role="tab" data-toggle="tab">Crear Comercializador</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_comercializador_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Comercializador" name="buscar_comercializador" id="buscar_comercializador" />
                                                    <br />
                                                    <?php
                                                        $query_select_comercializadores = "SELECT * FROM comercializadores_2 ORDER BY NOMBRE";
                                                        $sql_comercializadores = mysqli_query($connection, $query_select_comercializadores);
                                                        if (mysqli_num_rows($sql_comercializadores) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=13%>NIT</th>";
                                                                            echo "<th width=77%>COMERCIALIZADOR</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_comercializador'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Comercializadores Creados.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-comercializador"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_comercializador_tab">
                                                    <?php
                                                        if (isset($_GET['id_comercializador_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_comercializador" name="crear_comercializador" action="<?php echo "Modelo/Crear_Admin_Comercializador.php?editar=" . $_GET['id_comercializador_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = " . $_GET['id_comercializador_editar']);
                                                            $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                                        ?>
                                                            <input type="hidden" id="id_comercializador_editar_hidden" name="id_comercializador_editar_hidden" value="<?php echo $row_comercializador['ID_COMERCIALIZADOR']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_comercializador" name="crear_comercializador" action="<?php echo "Modelo/Crear_Admin_Comercializador.php?eliminar=" . $_GET['id_comercializador_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = " . $_GET['id_comercializador_eliminar']);
                                                                $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                                            ?>
                                                                <input type="hidden" id="id_comercializador_eliminar_hidden" name="id_comercializador_eliminar_hidden" value="<?php echo $row_comercializador['ID_COMERCIALIZADOR']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_comercializador" name="crear_comercializador" action="<?php echo "Modelo/Crear_Admin_Comercializador.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_comercializador" name="nombre_comercializador" value="<?php echo $row_comercializador['NOMBRE']; ?>" maxlength="150" placeholder="Comercializador" data-toogle="tooltip" title="COMERCIALIZADOR" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_comercializador" name="nombre_comercializador" readonly="readonly" placeholder="Comercializador" value="<?php echo $row_comercializador['NOMBRE']; ?>" data-toogle="tooltip" title="COMERCIALIZADOR" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_comercializador" name="nombre_comercializador" maxlength="150" placeholder="Comercializador" data-toogle="tooltip" title="COMERCIALIZADOR" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nit_comercializador" name="nit_comercializador" value="<?php echo $row_comercializador['NIT_COMERCIALIZADOR'] ?>" maxlength="20" placeholder="NIT" data-toggle="tooltip" title="NIT" onkeypress="return isNumeric(event)" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_comercializador" name="nit_comercializador" readonly="readonly" placeholder="NIT" value="<?php echo $row_comercializador['NIT_COMERCIALIZADOR'] ?>" data-toggle="tooltip" title="NIT" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_comercializador" name="nit_comercializador" maxlength="20" placeholder="NIT" data-toggle="tooltip" title="NIT" onkeypress="return isNumeric(event)" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="representante_legal" name="representante_legal" value="<?php echo $row_comercializador['REPR_LEGAL']; ?>" maxlength="100" placeholder="Repr. Legal" data-toogle="tooltip" title="REPR. LEGAL" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="representante_legal" name="representante_legal" readonly="readonly" placeholder="Repr. Legal" value="<?php echo $row_comercializador['REPR_LEGAL']; ?>" data-toogle="tooltip" title="REPR. LEGAL" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="representante_legal" name="representante_legal" maxlength="100" placeholder="Repr. Legal" data-toogle="tooltip" title="REPR. LEGAL" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="direccion_comercializador" name="direccion_comercializador" value="<?php echo $row_comercializador['DIRECCION_COMERCIALIZADOR']; ?>" maxlength="100" placeholder="Dirección" data-toogle="tooltip" title="DIRECCIÓN" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_comercializador" name="direccion_comercializador" readonly="readonly" placeholder="Dirección" value="<?php echo $row_comercializador['DIRECCION_COMERCIALIZADOR']; ?>" data-toogle="tooltip" title="DIRECCIÓN" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_comercializador" name="direccion_comercializador" maxlength="100" placeholder="Dirección" data-toogle="tooltip" title="DIRECCIÓN" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" value="<?php echo $row_comercializador['CORREO_ELECTRONICO']; ?>" maxlength="100" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" readonly="readonly" placeholder="Correo Electrónico" value="<?php echo $row_comercializador['CORREO_ELECTRONICO']; ?>" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" maxlength="100" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN RESPONSABLE</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="responsable_acargo" name="responsable_acargo" value="<?php echo $row_comercializador['RESPONSABLE_ACARGO']; ?>" maxlength="100" placeholder="Responsable" data-toogle="tooltip" title="RESPONSABLE" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="responsable_acargo" name="responsable_acargo" readonly="readonly" placeholder="Responsable" value="<?php echo $row_comercializador['RESPONSABLE_ACARGO']; ?>" data-toogle="tooltip" title="RESPONSABLE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="responsable_acargo" name="responsable_acargo" maxlength="100" placeholder="Responsable" data-toogle="tooltip" title="RESPONSABLE" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="cargo_responsable" name="cargo_responsable" value="<?php echo $row_comercializador['CARGO_RESPONSABLE']; ?>" maxlength="150" placeholder="Cargo Responsable" data-toogle="tooltip" title="CARGO RESPONSABLE" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="cargo_responsable" name="cargo_responsable" readonly="readonly" placeholder="Cargo Responsable" value="<?php echo $row_comercializador['CARGO_RESPONSABLE']; ?>" data-toogle="tooltip" title="CARGO RESPONSABLE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="cargo_responsable" name="cargo_responsable" maxlength="150" placeholder="Cargo Responsable" data-toogle="tooltip" title="CARGO RESPONSABLE" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="telefono_responsable" name="telefono_responsable" value="<?php echo $row_comercializador['TELEFONO_RESPONSABLE']; ?>" maxlength="100" placeholder="Telefono Responsable" data-toogle="tooltip" title="TELEFONO RESPONSABLE" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="telefono_responsable" name="telefono_responsable" readonly="readonly" placeholder="Telefono Responsable" value="<?php echo $row_comercializador['TELEFONO_RESPONSABLE']; ?>" data-toogle="tooltip" title="TELEFONO RESPONSABLE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="telefono_responsable" name="telefono_responsable" maxlength="100" placeholder="Telefono Responsable" data-toogle="tooltip" title="TELEFONO RESPONSABLE" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="correo_electronico_responsable" name="correo_electronico_responsable" value="<?php echo $row_comercializador['CORREO_RESPONSABLE']; ?>" maxlength="80" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico_responsable" name="correo_electronico_responsable" readonly="readonly" placeholder="Correo Electrónico" value="<?php echo $row_comercializador['CORREO_RESPONSABLE']; ?>" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico_responsable" name="correo_electronico_responsable" maxlength="80" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
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
                                                                    if (isset($_GET['id_comercializador_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_comercializador" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Comercializador</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Comercializadores.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_comercializador_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_comercializador" type="button" data-toggle="modal" data-target="#modalEliminarComercializador"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Comercializador</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Comercializadores.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_comercializador" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Comercializador</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldComercializador();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldComercializador() {
            document.getElementById('nombre_comercializador').focus();
        }
        function isNumeric(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_comercializador").focus();
            var id_comercializador_editar = $("#id_comercializador_editar_hidden").val();
            var id_comercializador_eliminar = $("#id_comercializador_eliminar_hidden").val();
            if (id_comercializador_editar != undefined) {
                $(".nav-pills a[href='#crear_comercializador_tab']").tab("show");
                $(".nav-pills a[href='#crear_comercializador_tab']").text("Actualizar Comercializador");
            } else {
                if (id_comercializador_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_comercializador_tab']").tab("show");
                    $(".nav-pills a[href='#crear_comercializador_tab']").text("Eliminar Comercializador");
                }
            }
            $("#buscar_comercializador").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_comercializador;
                    if ($(this).val() == "") {
                        busqueda_comercializador = " ";
                    } else {
                        busqueda_comercializador = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Comercializador.php",
                        dataType: "json",
                        data: "sw=1&busqueda_comercializador="+busqueda_comercializador,
                        success: function(data) {
                            $("#pagination-comercializador").twbsPagination('destroy');
                            $("#pagination-comercializador").twbsPagination({
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
                                        url: "Modelo/Cargar_Admin_Comercializador.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_comercializador="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_comercializador").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_comercializador").on("shown.bs.tab", function() {
                $("#buscar_comercializador").focus();
            });
            $("#tab_crear_comercializador").on("shown.bs.tab", function() {
                $("#nombre_comercializador").focus();
            });
            $("#tab_info_comercializador").on("click", function() {
                $("#buscar_comercializador").focus();
            });
            $("#tab_crear_comercializador").on("click", function() {
                $("#nombre_comercializador").focus();
            });
            if (id_comercializador_editar == undefined && id_comercializador_eliminar == undefined) {
                $("#btn_crear_comercializador").click(function() {
                    var nombre_comercializador = $("#nombre_comercializador").val().toUpperCase();
                    var nit_comercializador = $("#nit_comercializador").val();
                    var representante_legal = $("#representante_legal").val();
                    var direccion_comercializador = $("#direccion_comercializador").val().toUpperCase();
                    var correo_electronico = $("#correo_electronico").val();
                    var responsable_acargo = $("#responsable_acargo").val();
                    var cargo_responsable = $("#cargo_responsable").val();
                    var telefono_responsable = $("#telefono_responsable").val();
                    var correo_electronico_responsable = $("#correo_electronico_responsable").val();
                    if (nombre_comercializador.length == 0) {
                        $("#nombre_comercializador").focus();
                        return false;
                    }
                    if (nit_comercializador.length == 0) {
                        $("#nit_comercializador").focus();
                        return false;
                    }
                    $("#btn_crear_comercializador").attr("disabled", true);
                    $("#btn_crear_comercializador").css("pointer-events", "none");
                    $("#btn_crear_comercializador").html("Creando Comercializador...");
                    $.ajax({
                        type: "POST",
                        data: "nombre_comercializador="+nombre_comercializador+
                              "&nit_comercializador="+nit_comercializador+
                              "&representante_legal="+representante_legal+
                              "&direccion_comercializador="+direccion_comercializador+
                              "&correo_electronico="+correo_electronico+
                              "&responsable_acargo="+responsable_acargo+
                              "&cargo_responsable="+cargo_responsable+
                              "&telefono_responsable="+telefono_responsable+
                              "&correo_electronico_responsable="+correo_electronico_responsable,
                        url: "Modelo/Crear_Admin_Comercializador.php",
                        success: function(data) {
                            document.location.href = 'Admin_Comercializadores.php';
                        }
                    });
                });
            }
            $("#eliminar_comercializador").click(function() {
                var id_comercializador_eliminar = $("#id_comercializador_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Comercializador_Registros.php",
                    data: "id_comercializador_eliminar="+id_comercializador_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_comercializador").submit();
                        } else {
                            $("#modalEliminarComercializadorError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Comercializador.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-comercializador").twbsPagination('destroy');
                    $("#pagination-comercializador").twbsPagination({
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
                                url: "Modelo/Cargar_Admin_Comercializador.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_comercializador").html(data[0]);
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
            $('input[type=text][name=nombre_comercializador]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nit_comercializador]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=representante_legal]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=direccion_comercializador]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=correo_electronico]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=responsable_acargo]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=cargo_responsable]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=telefono_responsable]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=correo_electronico_responsable]').tooltip({
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