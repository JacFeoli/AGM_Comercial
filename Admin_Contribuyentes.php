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
        <title>AGM - Admin. Contribuyentes</title>
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
        <style type="text/css">
            .text-divider{margin: 2em 0; line-height: 0; text-align: center;}
            .text-divider span{background-color: #D0DEE7; padding: 1em;}
            .text-divider:before{ content: " "; display: block; border-top: 1px solid #A9BDC8;}
        </style>
    </head>
    <!--Eliminar Contribuyente Modal-->
    <div class="modal fade" id="modalEliminarContribuyente" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Contribuyente</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Contribuyente?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_contribuyente" name="eliminar_contribuyente"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Contribuyente Modal-->
    <!--Eliminar Contribuyente Error-->
    <div class="modal fade" id="modalEliminarContribuyenteError" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Error Eliminar Contribuyente</h4>
                </div>
                <div class="modal-body">
                    <p>No se ha podido Eliminar el Contribuyente, ya que existen Registros creados con éste. Favor revisar la información.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Contribuyente Error-->
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
                                                    <a href="Admin_Contribuyentes.php">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 13px;">
                                                                    <span><i class="fas fa-tags fa-fw"></i></span>
                                                                </td>
                                                                <td>
                                                                    <span>Contribuyentes</span>
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
                                            <h1>Admin. Contribuyentes</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_contribuyente_tab" id="tab_info_contribuyente" aria-controls="informacion_contribuyente_tab" role="tab" data-toggle="tab">Información Contribuyentes</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_contribuyente_tab" id="tab_crear_contribuyente" aria-controls="crear_contribuyente_tab" role="tab" data-toggle="tab">Crear Contribuyente</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_contribuyente_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Contribuyente" name="buscar_contribuyente" id="buscar_contribuyente" />
                                                    <br />
                                                    <?php
                                                        $query_select_contribuyentes = "SELECT * FROM contribuyentes_2 ORDER BY NOMBRE";
                                                        $sql_contribuyentes = mysqli_query($connection, $query_select_contribuyentes);
                                                        if (mysqli_num_rows($sql_contribuyentes) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=13%>NIT</th>";
                                                                            echo "<th width=77%>CONTRIBUYENTE</th>";
                                                                            echo "<th width=5%>EDITAR</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_contribuyente'>";

                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Contribuyentes Creados.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-contribuyente"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_contribuyente_tab">
                                                    <?php
                                                        if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_contribuyente" name="crear_contribuyente" action="<?php echo "Modelo/Crear_Admin_Contribuyente.php?editar=" . $_GET['id_contribuyente_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_contribuyente = mysqli_query($connection, "SELECT * FROM contribuyentes_2 WHERE ID_CONTRIBUYENTE = " . $_GET['id_contribuyente_editar']);
                                                            $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                                                        ?>
                                                            <input type="hidden" id="id_contribuyente_editar_hidden" name="id_contribuyente_editar_hidden" value="<?php echo $row_contribuyente['ID_CONTRIBUYENTE']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_contribuyente" name="crear_contribuyente" action="<?php echo "Modelo/Crear_Admin_Contribuyente.php?eliminar=" . $_GET['id_contribuyente_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_contribuyente = mysqli_query($connection, "SELECT * FROM contribuyentes_2 WHERE ID_CONTRIBUYENTE = " . $_GET['id_contribuyente_eliminar']);
                                                                $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                                                            ?>
                                                                <input type="hidden" id="id_contribuyente_eliminar_hidden" name="id_contribuyente_eliminar_hidden" value="<?php echo $row_contribuyente['ID_CONTRIBUYENTE']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_contribuyente" name="crear_contribuyente" action="<?php echo "Modelo/Crear_Admin_Contribuyente.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_contribuyente" name="nombre_contribuyente" value="<?php echo $row_contribuyente['NOMBRE']; ?>" maxlength="150" placeholder="Contribuyente" data-toogle="tooltip" title="CONTRIBUYENTE" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_contribuyente" name="nombre_contribuyente" readonly="readonly" placeholder="Contribuyente" value="<?php echo $row_contribuyente['NOMBRE']; ?>" data-toogle="tooltip" title="CONTRIBUYENTE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_contribuyente" name="nombre_contribuyente" maxlength="150" placeholder="Contribuyente" data-toogle="tooltip" title="CONTRIBUYENTE" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nit_contribuyente" name="nit_contribuyente" value="<?php echo $row_contribuyente['NIT_CONTRIBUYENTE'] ?>" maxlength="20" placeholder="NIT" data-toggle="tooltip" title="NIT" onkeypress="return isNumeric(event)" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_contribuyente" name="nit_contribuyente" readonly="readonly" placeholder="NIT" value="<?php echo $row_contribuyente['NIT_CONTRIBUYENTE'] ?>" data-toggle="tooltip" title="NIT" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_contribuyente" name="nit_contribuyente" maxlength="20" placeholder="NIT" data-toggle="tooltip" title="NIT" onkeypress="return isNumeric(event)" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_contribuyente['ID_DEPARTAMENTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_contribuyente['ID_DEPARTAMENTO']);
                                                                            $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                        ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_contribuyente['ID_DEPARTAMENTO'] . " AND ID_MUNICIPIO = " . $row_contribuyente['ID_MUNICIPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_contribuyente['ID_DEPARTAMENTO'] . " AND ID_MUNICIPIO = " . $row_contribuyente['ID_MUNICIPIO']);
                                                                            $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                        ?>
                                                                            <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_municipio" name="id_municipio" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="direccion_contribuyente" name="direccion_contribuyente" value="<?php echo $row_contribuyente['DIRECCION_CONTRIBUYENTE']; ?>" maxlength="100" placeholder="Dirección" data-toogle="tooltip" title="DIRECCIÓN" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_contribuyente" name="direccion_contribuyente" readonly="readonly" placeholder="Dirección" value="<?php echo $row_contribuyente['DIRECCION_CONTRIBUYENTE']; ?>" data-toogle="tooltip" title="DIRECCIÓN" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="direccion_contribuyente" name="direccion_contribuyente" maxlength="100" placeholder="Dirección" data-toogle="tooltip" title="DIRECCIÓN" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" value="<?php echo $row_contribuyente['CORREO_ELECTRONICO']; ?>" maxlength="100" placeholder="Correo Electrónico" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="correo_electronico" name="correo_electronico" readonly="readonly" placeholder="Correo Electrónico" value="<?php echo $row_contribuyente['CORREO_ELECTRONICO']; ?>" data-toogle="tooltip" title="CORREO ELECTRÓNICO" />
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
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="sector_contribuyente" name="sector_contribuyente" value="<?php echo $row_contribuyente['SECTOR_CONTRIBUYENTE']; ?>" maxlength="300" placeholder="Sector" data-toogle="tooltip" title="SECTOR" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="sector_contribuyente" name="sector_contribuyente" readonly="readonly" placeholder="Sector" value="<?php echo $row_contribuyente['SECTOR_CONTRIBUYENTE']; ?>" data-toogle="tooltip" title="SECTOR" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="sector_contribuyente" name="sector_contribuyente" maxlength="300" placeholder="Sector" data-toogle="tooltip" title="SECTOR" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-8">
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="responsable_pago" name="responsable_pago" value="<?php echo $row_contribuyente['RESPONSABLE_PAGO']; ?>" maxlength="100" placeholder="Responsable Pago" data-toogle="tooltip" title="RESPONSABLE PAGO" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="responsable_pago" name="responsable_pago" readonly="readonly" placeholder="Responsable Pago" value="<?php echo $row_contribuyente['RESPONSABLE_PAGO']; ?>" data-toogle="tooltip" title="RESPONSABLE PAGO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="responsable_pago" name="responsable_pago" maxlength="100" placeholder="Responsable Pago" data-toogle="tooltip" title="RESPONSABLE PAGO" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="telefono_resp_pago" name="telefono_resp_pago" value="<?php echo $row_contribuyente['TELEFONO_RESP_PAGO'] ?>" maxlength="25" placeholder="Telefono Resp. Pago" data-toggle="tooltip" title="TELEFONO RESP. PAGO" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="telefono_resp_pago" name="telefono_resp_pago" readonly="readonly" placeholder="Telefono Resp. Pago" value="<?php echo $row_contribuyente['TELEFONO_RESP_PAGO'] ?>" data-toggle="tooltip" title="TELEFONO RESP. PAGO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="telefono_resp_pago" name="telefono_resp_pago" maxlength="20" placeholder="Telefono Resp. Pago" data-toggle="tooltip" title="TELEFONO RESP. PAGO" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN TIPO FACTURACIÓN</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" data-toggle="tooltip" title="TIPO FACTURACIÓN" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                                <select class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" disabled="disabled" data-toggle="tooltip" title="TIPO FACTURACIÓN" required>
                                                                            <?php
                                                                            } else { ?>
                                                                                <select class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" data-toggle="tooltip" title="TIPO FACTURACIÓN" required>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                            <option value="" selected="selected">-</option>
                                                                            <option value="1">CONSUMO</option>
                                                                            <option value="2">SALARIOS</option>
                                                                            <option value="3">UVT</option>
                                                                            <option value="4">COMERCIAL</option>
                                                                            <?php
                                                                                if (isset($_GET['id_contribuyente_editar']) || isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                                    <input type="hidden" id="tipo_facturacion_hidden" name="tipo_facturacion_hidden" value="<?php echo $row_contribuyente['ID_TIPO_FACTURACION']; ?>" />
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2">
                                                                <div class="input-group" id="tarifa" data-toogle="tooltip" title="TARIFA">
                                                                    <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="tarifa" value="<?php echo $row_contribuyente['TARIFA'] ?>" maxlength="5" placeholder="Tarifa" onkeypress="return isDouble(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="tarifa" value="<?php echo $row_contribuyente['TARIFA'] ?>" placeholder="Tarifa" onkeypress="return isDouble(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="tarifa" value="" maxlength="5" placeholder="Tarifa" required="required" onkeypress="return isDouble(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-7">
                                                                <?php
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="acuerdo_mcpal" name="acuerdo_mcpal" value="<?php echo $row_contribuyente['ACUERDO_MCPAL']; ?>" maxlength="150" placeholder="Acuerdo Municipal" data-toogle="tooltip" title="ACUERDO MUNICIPAL" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="acuerdo_mcpal" name="acuerdo_mcpal" readonly="readonly" placeholder="Acuerdo Municipal" value="<?php echo $row_contribuyente['ACUERDO_MCPAL']; ?>" data-toogle="tooltip" title="ACUERDO MUNICIPAL" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="acuerdo_mcpal" name="acuerdo_mcpal" maxlength="150" placeholder="Acuerdo Municipal" data-toogle="tooltip" title="ACUERDO MUNICIPAL" required />
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
                                                                    if (isset($_GET['id_contribuyente_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_contribuyente" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Contribuyente</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Contribuyentes.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_contribuyente_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_contribuyente" type="button" data-toggle="modal" data-target="#modalEliminarContribuyente"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Contribuyente</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Contribuyentes.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_contribuyente" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Contribuyente</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldContribuyente();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldContribuyente() {
            $("#nombre_contribuyente").focus();
            $("#id_departamento").val("");
            $("#id_municipio").val("");
            document.getElementById('nombre_contribuyente').focus();
            
        }
        function isNumeric(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function isDouble(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 45 || charCode > 46)) {
                return false;
            }
            return true;
        }
        //POPUPS
        function infoTipoDepartamento(id_consulta, id_departamento, departamento) {
            if (id_consulta == 1) {
                $("#id_departamento").val(id_departamento);
                $("#departamento").val(departamento);
                $("#id_municipio").val("");
                $("#municipio").val("");
                $("#municipio").focus();
            }
        }
        function tipoDepartamento(id_consulta) {
            window.open("Combos/Tipo_Departamento_Visita.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
            if (id_consulta == 1) {
                $("#id_municipio").val(id_municipio);
                $("#municipio").val(municipio);
                $("#nombre_alcalde").focus();
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_departamento;
            if (id_consulta == 1) {
                id_departamento = $("#id_departamento").val();
            }
            window.open("Combos/Tipo_Municipio_Visita.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_contribuyente").focus();
            var id_contribuyente_editar = $("#id_contribuyente_editar_hidden").val();
            var id_contribuyente_eliminar = $("#id_contribuyente_eliminar_hidden").val();
            if (id_contribuyente_editar != undefined) {
                $(".nav-pills a[href='#crear_contribuyente_tab']").tab("show");
                $(".nav-pills a[href='#crear_contribuyente_tab']").text("Actualizar Contribuyente");
                $("#tipo_facturacion").val($("#tipo_facturacion_hidden").val());
                var tipo_facturacion = $("#tipo_facturacion_hidden").val();
                switch (tipo_facturacion) {
                    case '1':
                        $("#tarifa_icon").removeClass('fas fa-hashtag');
                        $("#tarifa_icon").addClass('fas fa-percentage');
                        break;
                    case '2':
                    case '3':
                    case '4':
                        $("#tarifa_icon").removeClass('fas fa-percentage');
                        $("#tarifa_icon").addClass('fas fa-hashtag');
                        break;
                }
            } else {
                if (id_contribuyente_eliminar != undefined) {
                    $(".nav-pills a[href='#crear_contribuyente_tab']").tab("show");
                    $(".nav-pills a[href='#crear_contribuyente_tab']").text("Eliminar Contribuyente");
                    $("#tipo_facturacion").val($("#tipo_facturacion_hidden").val());
                    var tipo_facturacion = $("#tipo_facturacion_hidden").val();
                    switch (tipo_facturacion) {
                        case '1':
                            $("#tarifa_icon").removeClass('fas fa-hashtag');
                            $("#tarifa_icon").addClass('fas fa-percentage');
                            break;
                        case '2':
                        case '3':
                        case '4':
                            $("#tarifa_icon").removeClass('fas fa-percentage');
                            $("#tarifa_icon").addClass('fas fa-hashtag');
                            break;
                    }
                }
            }
            $("#tipo_facturacion").change(function() {
                var tipo_facturacion = $(this).val();
                switch (tipo_facturacion) {
                    case '1':
                        $("#tarifa_icon").removeClass('fas fa-hashtag');
                        $("#tarifa_icon").addClass('fas fa-percentage');
                        break;
                    case '2':
                    case '3':
                    case '4':
                        $("#tarifa_icon").removeClass('fas fa-percentage');
                        $("#tarifa_icon").addClass('fas fa-hashtag');
                        break;
                }
                $("input[name=tarifa]").focus();
            });
            $("#buscar_contribuyente").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_contribuyente;
                    if ($(this).val() == "") {
                        busqueda_contribuyente = " ";
                    } else {
                        busqueda_contribuyente = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Contribuyente.php",
                        dataType: "json",
                        data: "sw=1&busqueda_contribuyente="+busqueda_contribuyente,
                        success: function(data) {
                            $("#pagination-contribuyente").twbsPagination('destroy');
                            $("#pagination-contribuyente").twbsPagination({
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
                                        url: "Modelo/Cargar_Admin_Contribuyente.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_contribuyente="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_contribuyente").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#tab_info_contribuyente").on("shown.bs.tab", function() {
                $("#buscar_contribuyente").focus();
            });
            $("#tab_crear_contribuyente").on("shown.bs.tab", function() {
                $("#nombre_contribuyente").focus();
            });
            $("#tab_info_contribuyente").on("click", function() {
                $("#buscar_contribuyente").focus();
            });
            $("#tab_crear_contribuyente").on("click", function() {
                $("#nombre_contribuyente").focus();
            });
            if (id_contribuyente_editar == undefined && id_contribuyente_eliminar == undefined) {
                $("#btn_crear_contribuyente").click(function() {
                    var nombre_contribuyente = $("#nombre_contribuyente").val().toUpperCase();
                    var nit_contribuyente = $("#nit_contribuyente").val();
                    var departamento = $("#id_departamento").val();
                    var municipio = $("#id_municipio").val();
                    var direccion_contribuyente = $("#direccion_contribuyente").val().toUpperCase();
                    var correo_electronico = $("#correo_electronico").val();
                    var sector_contribuyente = $("#sector_contribuyente").val();
                    var responsable_pago = $("#responsable_pago").val();
                    var telefono_resp_pago = $("#telefono_resp_pago").val();
                    var tipo_facturacion = $("#tipo_facturacion").val();
                    var tarifa = $("input[name=tarifa]").val();
                    var acuerdo_mcpal = $("#acuerdo_mcpal").val();
                    if (nombre_contribuyente.length == 0) {
                        $("#nombre_contribuyente").focus();
                        return false;
                    }
                    if (nit_contribuyente.length == 0) {
                        $("#nit_contribuyente").focus();
                        return false;
                    }
                    if (departamento.length == 0) {
                        $("#departamento").focus();
                        return false;
                    }
                    if (municipio.length == 0) {
                        $("#municipio").focus();
                        return false;
                    }
                    if (sector_contribuyente.length == 0) {
                        $("#sector_contribuyente").focus();
                        return false;
                    }
                    if (tipo_facturacion.length == 0) {
                        $("#tipo_facturacion").focus();
                        return false;
                    }
                    if (tarifa.length == 0) {
                        $("input[name=tarifa]").focus();
                        return false;
                    }
                    $("#btn_crear_contribuyente").attr("disabled", true);
                    $("#btn_crear_contribuyente").css("pointer-events", "none");
                    $("#btn_crear_contribuyente").html("Creando Contribuyente...");
                    $.ajax({
                        type: "POST",
                        data: "nombre_contribuyente="+nombre_contribuyente+
                              "&nit_contribuyente="+nit_contribuyente+
                              "&departamento="+departamento+
                              "&municipio="+municipio+
                              "&direccion_contribuyente="+direccion_contribuyente+
                              "&correo_electronico="+correo_electronico+
                              "&sector_contribuyente="+sector_contribuyente+
                              "&responsable_pago="+responsable_pago+
                              "&telefono_resp_pago="+telefono_resp_pago+
                              "&tipo_facturacion="+tipo_facturacion+
                              "&tarifa="+tarifa+"&acuerdo_mcpal="+acuerdo_mcpal,
                        url: "Modelo/Crear_Admin_Contribuyente.php",
                        success: function(data) {
                            document.location.href = 'Admin_Contribuyentes.php';
                        }
                    });
                });
            }
            $("#eliminar_contribuyente").click(function() {
                var id_contribuyente_eliminar = $("#id_contribuyente_eliminar_hidden").val();
                $.ajax({
                    type: "POST",
                    url: "Verify/Verificar_Admin_Contribuyente_Registros.php",
                    data: "id_contribuyente_eliminar="+id_contribuyente_eliminar,
                    success: function(data) {
                        if (data == 0) {
                            $("#crear_contribuyente").submit();
                        } else {
                            $("#modalEliminarContribuyenteError").modal("show");
                        }
                    }
                });
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Contribuyente.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-contribuyente").twbsPagination('destroy');
                    $("#pagination-contribuyente").twbsPagination({
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
                                url: "Modelo/Cargar_Admin_Contribuyente.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_contribuyente").html(data[0]);
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
            $('input[type=text][name=nombre_contribuyente]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nit_contribuyente]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=departamento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=municipio]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=direccion_contribuyente]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=correo_electronico]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=sector_contribuyente]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=responsable_pago]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=telefono_resp_pago]').tooltip({
                container: "body",
                placement: "top"
            });
            $('select[name=tipo_facturacion]').tooltip({
                container : "body",
                placement : "top"
            });
            $('#tarifa').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=acuerdo_mcpal]').tooltip({
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