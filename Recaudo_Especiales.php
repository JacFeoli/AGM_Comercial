<?php
session_start();
require_once('Includes/Config.php');
if ($_SESSION['timeout'] + 60 * 60 < time()) {
    session_unset();
    session_destroy();
    $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location:$ruta/Login.php");
} else {
    $_SESSION['timeout'] = time();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>AGM - Recaudo Municipio</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="Css/AGM_Style.css" />
    <link rel="stylesheet" href="Css/bootstrap.min.css" />
    <link rel="stylesheet" href="Css/menu_style.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
    <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
    <style type="text/css">
        .text-divider {
            margin: 2em 0;
            line-height: 0;
            text-align: center;
        }

        .text-divider span {
            background-color: #D0DEE7;
            padding: 1em;
        }

        .text-divider:before {
            content: " ";
            display: block;
            border-top: 1px solid #A9BDC8;
        }

        .inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .inputfile+label {
            max-width: 80%;
            /*font-size: 1.25rem;*/
            /* 20px */
            font-weight: normal;
            font-size: 12px;
            font-family: 'Cabin';
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            display: inline-block;
            overflow: hidden;
            padding: 0.625rem 1.25rem;
            border-radius: 3px;
            /* 10px 20px */
        }

        .inputfile:focus+label,
        .inputfile.has-focus+label {
            outline: 1px dotted #000;
            outline: -webkit-focus-ring-color auto 5px;
        }

        .inputfile-1+label {
            border-color: #2C592C;
            color: #FFFFFF;
            background-image: linear-gradient(#6CBF6C, #408040);
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
        }

        .inputfile-1:focus+label,
        .inputfile-1.has-focus+label,
        .inputfile-1+label:hover {
            border-color: #2C592C;
            background-image: linear-gradient(#61AB61, #397339);
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
        }
    </style>
</head>
<!--Upload Modal-->
<div class="modal fade" id="modalUpload" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Carga Exitosa</h4>
            </div>
            <div class="modal-body">
                <p>El Archivo, se cargo de forma Exitosa.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--Fin Upload Modal-->
<!--Eliminar Recaudo Especiales Modal-->
<div class="modal fade" id="modalEliminarRecaEsp" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Eliminar Recaudo Especiales</h4>
            </div>
            <div class="modal-body">
                <p>¿Desea eliminar el Recaudo?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_reca_esp" name="eliminar_reca_esp"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>
<!--Fin Eliminar Recaudo Especiales Modal-->

<body>
    <div class="wrapper">
        <?php include("Top Pages/Top_Page_Home.php"); ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="content">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="leftcol">
                                        <div class="page-wrapper chiller-theme toggled">
                                            <nav id="sidebar" class="sidebar-wrapper">
                                                <div class="sidebar-content">
                                                    <div class="sidebar-menu">
                                                        <ul>
                                                            <li class="header-menu">
                                                                <h3 style="text-align: center;">OPCIONES DISPONIBLES</h3>
                                                            </li>
                                                            <li class="sidebar-dropdown active">
                                                                <a href='#'>
                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-user-tie"></i>
                                                                    <span>Fact. Municipio</span>
                                                                </a>
                                                                <div style="display: block;" class="sidebar-submenu">
                                                                    <ul class="nav nav-pills nav-stacked">
                                                                        <li>
                                                                            <a href='Recaudo_Especiales.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check"></i>
                                                                                <span>Recaudo</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href='Facturacion_Especiales.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check-alt"></i>
                                                                                <span>Facturación</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href='Reportes_Especiales.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-chart-pie"></i>
                                                                                <span>Reportes</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href='Admin_Especiales.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-address-card"></i>
                                                                                <span>Admin. Clientes Espc.</span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="rightcol">
                                        <h1>Recaudo Municipio</h1>
                                        <h2></h2>
                                        <ul class="nav nav-pills" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#informacion_reca_especiales_tab" id="tab_info_reca_especiales" aria-controls="informacion_reca_especiales_tab" role="tab" data-toggle="tab">Información Reca. Municipio</a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#crear_reca_especiales_tab" id="tab_crear_reca_especiales" aria-controls="crear_reca_especiales_tab" role="tab" data-toggle="tab">Crear Reca. Municipio</a>
                                            </li>
                                            <?php
                                            if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                <li role="presentation">
                                                    <a href="#cargar_soporte_recaudo_tab" id="tab_cargar_soporte_recaudo" aria-controls="cargar_soporte_recaudo_tab" role="tab" data-toggle="tab">Cargar Soporte Recaudo</a>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                        <h2></h2>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade in active" id="informacion_reca_especiales_tab">
                                                <input class="form-control input-text input-sm" type="text" placeholder="Buscar Recaudo" name="buscar_recaudo" id="buscar_recaudo" />
                                                <br />
                                                <?php
                                                $query_select_reca_esp = "SELECT * FROM recaudo_especiales_2 ORDER BY ID_FACTURACION DESC";
                                                $sql_reca_esp = mysqli_query($connection, $query_select_reca_esp);
                                                if (mysqli_num_rows($sql_reca_esp) != 0) {
                                                    echo "<div class='table-responsive'>";
                                                    echo "<table class='table table-condensed table-hover'>";
                                                    echo "<thead>";
                                                    echo "<tr>";
                                                    echo "<th width=4%>ESTADO</th>";
                                                    echo "<th width=13%>DEPARTAMENTO</th>";
                                                    echo "<th width=17%>MUNICIPIO</th>";
                                                    echo "<th width=34%>CONTRIBUYENTE</th>";
                                                    echo "<th width=11%>FACTURA</th>";
                                                    echo "<th width=11%>VALOR RECA.</th>";
                                                    echo "<th width=5%>DETALLE</th>";
                                                    echo "<th width=5%>ELIMINAR</th>";
                                                    echo "</tr>";
                                                    echo "</thead>";
                                                    echo "<tbody id='resultado_reca_esp'>";

                                                    echo "</tbody>";
                                                    echo "</table>";
                                                    echo "</div>";
                                                    echo "<p></p>";
                                                    echo "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = ENTREGADO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGO PARCIAL.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #FFC107;' class='label label-success'><b>PE</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE ENVIO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span>";
                                                } else {
                                                    echo "<p class='message'>No se encontraron Recaudos Cliente Especiales Creados.</p>";
                                                }
                                                ?>
                                                <div id="div-pagination">
                                                    <ul id="pagination-reca_esp"></ul>
                                                    <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="crear_reca_especiales_tab">
                                                <?php
                                                if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_reca_especial" name="crear_reca_especial" action="<?php echo "Modelo/Crear_Reca_Esp.php?editar=" . $_GET['id_reca_especial_editar']; ?>" method="post">
                                                        <?php
                                                        $query_select_reca_especial = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 WHERE ID_RECAUDO = " . $_GET['id_reca_especial_editar']);
                                                        $row_reca_especial = mysqli_fetch_array($query_select_reca_especial);
                                                        ?>
                                                        <input type="hidden" id="id_reca_especial_editar_hidden" name="id_reca_especial_editar_hidden" value="<?php echo $row_reca_especial['ID_RECAUDO']; ?>" />
                                                        <?php
                                                    } else {
                                                        if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_reca_especial" name="crear_reca_especial" action="<?php echo "Modelo/Crear_Reca_Esp.php?eliminar=" . $_GET['id_reca_especial_eliminar']; ?>" method="post">
                                                                <?php
                                                                $query_select_reca_especial = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 WHERE ID_RECAUDO = " . $_GET['id_reca_especial_eliminar']);
                                                                $row_reca_especial = mysqli_fetch_array($query_select_reca_especial);
                                                                ?>
                                                                <input type="hidden" id="id_reca_especial_eliminar_hidden" name="id_reca_especial_eliminar_hidden" value="<?php echo $row_reca_especial['ID_RECAUDO']; ?>" />
                                                            <?php
                                                        } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_reca_especial" name="crear_reca_especial" action="<?php echo "Modelo/Crear_Reca_Esp.php"; ?>" method="post">
                                                                <?php
                                                            }
                                                                ?>
                                                            <?php
                                                        }
                                                            ?>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_pago_soporte">Fecha Sop.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_pago_soporte" data-toogle="tooltip" title="FECHA PAGO SOPORTE">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_pago_soporte" value="<?php echo $row_reca_especial['FECHA_PAGO_SOPORTE'] ?>" placeholder="Fecha Pago Soporte" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_soporte" value="<?php echo $row_reca_especial['FECHA_PAGO_SOPORTE'] ?>" placeholder="Fecha Pago Soporte" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_soporte" value="" placeholder="Fecha Pago Soporte" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_pago_bitacora">Fecha Bit.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_pago_bitacora" data-toogle="tooltip" title="FECHA PAGO BITACORA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_pago_bitacora" value="<?php echo $row_reca_especial['FECHA_PAGO_BITACORA'] ?>" placeholder="Fecha Pago Bitacora" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_bitacora" value="<?php echo $row_reca_especial['FECHA_PAGO_BITACORA'] ?>" placeholder="Fecha Pago Bitacora" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_bitacora" value="" placeholder="Fecha Pago Bitacora" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="estado_recaudo">Estd. Reca.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="styled-select">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="estado_recaudo" name="estado_recaudo" data-toggle="tooltip" title="ESTADO RECAUDO" required>
                                                                                <?php
                                                                            } else {
                                                                                if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                    <select class="form-control input-text input-sm" id="estado_recaudo" name="estado_recaudo" disabled="disabled" data-toggle="tooltip" title="ESTADO RECAUDO" required>
                                                                                    <?php
                                                                                } else { ?>
                                                                                        <select class="form-control input-text input-sm" id="estado_recaudo" name="estado_recaudo" data-toggle="tooltip" title="ESTADO RECAUDO" required>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                                    ?>
                                                                                    <option value="" selected="selected">-</option>
                                                                                    <option value="1">ENTREGADO</option>
                                                                                    <option value="4">PAGADA</option>
                                                                                    <option value="5">PAGO PARCIAL</option>
                                                                                    <option value="2">PENDIENTE DE ENVÍO</option>
                                                                                    <option value="3">RECLAMADA</option>
                                                                                    <?php
                                                                                    if (isset($_GET['id_reca_especial_editar']) || isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                        <input type="hidden" id="estado_recaudo_hidden" name="estado_recaudo_hidden" value="<?php echo $row_reca_especial['ESTADO_RECAUDO']; ?>" />
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN MUNICIPIO / CLIENTE ESPECIAL</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="contribuyente">Contribuy.:</label>
                                                                <div class="col-xs-7">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_especial_editar'])) {
                                                                        $query_select_contribuyente = mysqli_query($connection, "SELECT * "
                                                                            . "  FROM contribuyentes_2 C, facturacion_especiales_2 FE, recaudo_especiales_2 RE "
                                                                            . " WHERE RE.ID_FACTURACION = FE.ID_FACTURACION "
                                                                            . "   AND FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE "
                                                                            . "   AND RE.ID_FACTURACION = " . $row_reca_especial['ID_FACTURACION']);
                                                                        $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                                                                    ?>
                                                                        <input type="hidden" id="id_contribuyente" name="id_contribuyente" value="<?php echo $row_contribuyente['ID_CONTRIBUYENTE']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="contribuyente" name="contribuyente" value="<?php echo $row_contribuyente['NOMBRE']; ?>" placeholder="Contribuyente" required="required" data-toggle="tooltip" readonly="readonly" title="CONTRIBUYENTE" onclick="contribuyenteReca()" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_especial_eliminar'])) {
                                                                            $query_select_contribuyente = mysqli_query($connection, "SELECT * "
                                                                                . "  FROM contribuyentes_2 C, facturacion_especiales_2 FE, recaudo_especiales_2 RE "
                                                                                . " WHERE RE.ID_FACTURACION = FE.ID_FACTURACION "
                                                                                . "   AND FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE "
                                                                                . "   AND RE.ID_FACTURACION = " . $row_reca_especial['ID_FACTURACION']);
                                                                            $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                                                                        ?>
                                                                            <input type="hidden" id="id_contribuyente" name="id_contribuyente" value="<?php echo $row_contribuyente['ID_CONTRIBUYENTE']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="contribuyente" name="contribuyente" value="<?php echo $row_contribuyente['NOMBRE']; ?>" placeholder="Contribuyente" data-toggle="tooltip" readonly="readonly" title="CONTRIBUYENTE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_contribuyente" name="id_contribuyente" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="contribuyente" name="contribuyente" placeholder="Contribuyente" required="required" data-toggle="tooltip" readonly="readonly" title="CONTRIBUYENTE" onclick="contribuyenteReca()" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nit_operador">NIT:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nit_contribuyente" name="nit_contribuyente" value="<?php echo $row_contribuyente['NIT_CONTRIBUYENTE']; ?>" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_contribuyente" name="nit_contribuyente" value="<?php echo $row_contribuyente['NIT_CONTRIBUYENTE']; ?>" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_contribuyente" name="nit_contribuyente" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento">Dpto:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_especial_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_contribuyente['ID_DEPARTAMENTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_especial_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_contribuyente['ID_DEPARTAMENTO']);
                                                                            $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                        ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_departamento" name="id_departamento" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="municipio">Mpio:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_especial_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_contribuyente['ID_DEPARTAMENTO'] . " AND ID_MUNICIPIO = " . $row_contribuyente['ID_MUNICIPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_especial_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_contribuyente['ID_DEPARTAMENTO'] . " AND ID_MUNICIPIO = " . $row_contribuyente['ID_MUNICIPIO']);
                                                                            $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                        ?>
                                                                            <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_municipio" name="id_municipio" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN TIPO FACTURACIÓN / VALORES FACTURA</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="consecutivo_fact">Cons. Fact:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_especial_editar'])) {
                                                                        $query_select_info_facturacion = mysqli_query($connection, "SELECT * FROM facturacion_especiales_2 WHERE ID_FACTURACION = " . $row_reca_especial['ID_FACTURACION']);
                                                                        $row_info_facturacion = mysqli_fetch_array($query_select_info_facturacion);
                                                                    ?>
                                                                        <input type="hidden" id="id_facturacion" name="id_facturacion" value="<?php echo $row_info_facturacion['ID_FACTURACION']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="consecutivo_factura" name="consecutivo_factura" value="<?php echo $row_info_facturacion['CONSECUTIVO_FACT']; ?>" placeholder="Consecutivo Fact." required="required" data-toggle="tooltip" readonly="readonly" title="CONSECUTIVO FACT." onclick="infoFact()" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_especial_eliminar'])) {
                                                                            $query_select_info_facturacion = mysqli_query($connection, "SELECT * FROM facturacion_especiales_2 WHERE ID_FACTURACION = " . $row_reca_especial['ID_FACTURACION']);
                                                                            $row_info_facturacion = mysqli_fetch_array($query_select_info_facturacion);
                                                                        ?>
                                                                            <input type="hidden" id="id_facturacion" name="id_facturacion" value="<?php echo $row_info_facturacion['ID_FACTURACION']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="consecutivo_factura" name="consecutivo_factura" value="<?php echo $row_info_facturacion['CONSECUTIVO_FACT']; ?>" placeholder="Consecutivo Fact." data-toggle="tooltip" readonly="readonly" title="CONSECUTIVO FACT." />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_facturacion" name="id_facturacion" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="consecutivo_factura" name="consecutivo_factura" placeholder="Consecutivo Fact." required="required" data-toggle="tooltip" readonly="readonly" title="CONSECUTIVO FACT." onclick="infoFact()" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <div class="col-xs-4"></div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_factura">Periodo:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                        <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php echo substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php echo substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                        $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                            . "  FROM periodos_facturacion_especiales_2 "
                                                                            . " WHERE ANO_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4) . " "
                                                                            . "   AND MES_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2));
                                                                        $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoFact()" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                            $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                . "  FROM periodos_facturacion_especiales_2 "
                                                                                . " WHERE ANO_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4) . " "
                                                                                . "   AND MES_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2));
                                                                            $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" data-toggle="tooltip" readonly="readonly" title="PERIODO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" placeholder="Periodo" data-toggle="tooltip" readonly="readonly" title="PERIODO" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="no_liq_vencidas">Liq. Venc.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="no_liq_vencidas" data-toogle="tooltip" title="NO. LIQ. VENCIDAS">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="no_liq_vencidas" value="<?php echo $row_info_facturacion['NO_LIQ_VENCIDAS'] ?>" readonly="readonly" maxlength="2" placeholder="No. Liq. Vencidas" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="no_liq_vencidas" value="<?php echo $row_info_facturacion['NO_LIQ_VENCIDAS'] ?>" readonly="readonly" placeholder="No. Liq. Vencidas" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="no_liq_vencidas" value="" readonly="readonly" maxlength="2" placeholder="No. Liq. Vencidas" required="required" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="no_liq_vencidas">Valor Liq.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_liq_vencidas" data-toogle="tooltip" title="VALOR LIQ. VENCIDAS">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_liq_vencidas" value="<?php echo $row_info_facturacion['VALOR_LIQ_VENCIDAS'] ?>" readonly="readonly" maxlength="25" placeholder="Valor Liq. Vencidas" onblur="convertValorLiqVencidas();" onchange="return convertValorLiqVencidas()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_liq_vencidas" value="<?php echo $row_info_facturacion['VALOR_LIQ_VENCIDAS'] ?>" readonly="readonly" placeholder="Valor Liq. Vencidas" onblur="convertValorLiqVencidas();" onchange="return convertValorLiqVencidas()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_liq_vencidas" value="" readonly="readonly" maxlength="25" placeholder="Valor Liq. Vencidas" required="required" onblur="convertValorLiqVencidas();" onchange="return convertValorLiqVencidas()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="tipo_facturacion">Tipo Fact.:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_especial_editar'])) {
                                                                        if ($row_info_facturacion['ID_TIPO_FACTURACION'] == 1) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "CONSUMO"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "SALARIOS"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_especial_eliminar'])) {
                                                                            if ($row_info_facturacion['ID_TIPO_FACTURACION'] == 1) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "CONSUMO"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "SALARIOS"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="tarifa">Tarifa:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="tarifa" data-toogle="tooltip" title="TARIFA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <?php
                                                                                if ($row_info_facturacion['ID_TIPO_FACTURACION'] == 1) { ?>
                                                                                    <span id="tarifa_icon" class="fas fa-percentage"></span>
                                                                                <?php
                                                                                } else { ?>
                                                                                    <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="tarifa" value="<?php echo $row_info_facturacion['TARIFA'] ?>" maxlength="5" readonly="readonly" placeholder="Tarifa" onkeypress="return isDouble(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <?php
                                                                                    if ($row_info_facturacion['ID_TIPO_FACTURACION'] == 1) { ?>
                                                                                        <span id="tarifa_icon" class="fas fa-percentage"></span>
                                                                                    <?php
                                                                                    } else { ?>
                                                                                        <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="tarifa" value="<?php echo $row_info_facturacion['TARIFA'] ?>" readonly="readonly" placeholder="Tarifa" onkeypress="return isDouble(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="tarifa" value="" maxlength="5" placeholder="Tarifa" readonly="readonly" required="required" onkeypress="return isDouble(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_tarifa">Valor Tar.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_tarifa" data-toogle="tooltip" title="VALOR TARIFA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_tarifa" value="<?php echo $row_info_facturacion['VALOR_TARIFA'] ?>" maxlength="25" placeholder="Valor Tarifa" onblur="convertValorTarifa(); calcularValorFacturaConsumo();" onchange="return convertValorTarifa()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_tarifa" value="<?php echo $row_info_facturacion['VALOR_TARIFA'] ?>" placeholder="Valor Tarifa" onblur="convertValorTarifa(); calcularValorFacturaConsumo();" onchange="return convertValorTarifa()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_tarifa" value="" maxlength="25" placeholder="Valor Tarifa" required="required" onblur="convertValorTarifa(); calcularValorFacturaConsumo();" onchange="return convertValorTarifa()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-3"></div>
                                                                <div class="col-xs-3"></div>
                                                                <div class="col-xs-2"></div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_factura">Valor Fact.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_factura" data-toogle="tooltip" title="VALOR FACTURA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Factura" onblur="return convertValorFactura()" onchange="return convertValorFactura()" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] ?>" readonly="readonly" placeholder="Valor Factura" onblur="return convertValorFactura()" onchange="return convertValorFactura()" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_factura" value="" maxlength="25" placeholder="Valor Factura" readonly="readonly" required="required" onblur="return convertValorFactura()" onchange="return convertValorFactura()" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN VALORES RECAUDO</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nota_fiducia">Nota Fid.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="nota_fiducia" data-toogle="tooltip" title="NOTA FIDUCIA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="nota_fiducia" value="<?php echo $row_reca_especial['NOTA_FIDUCIA'] ?>" maxlength="10" required="required" placeholder="Nota Fiducia" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="nota_fiducia" value="<?php echo $row_reca_especial['NOTA_FIDUCIA'] ?>" placeholder="Nota Fiducia" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="nota_fiducia" value="" maxlength="10" placeholder="Nota Fiducia" required="required" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_fiducia">Fecha Fid.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_fiducia" data-toogle="tooltip" title="FECHA FIDUCIA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_fiducia" value="<?php echo $row_reca_especial['FECHA_FIDUCIA'] ?>" placeholder="Fecha Fiducia" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_fiducia" value="<?php echo $row_reca_especial['FECHA_FIDUCIA'] ?>" placeholder="Fecha Fiducia" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_fiducia" value="" placeholder="Fecha Fiducia" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_aplicacion_encargo">Fecha Apl.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_aplicacion_encargo" data-toogle="tooltip" title="FECHA APL. ENCARGO">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_aplicacion_encargo" value="<?php echo $row_reca_especial['FECHA_APL_ENCARGO'] ?>" placeholder="Fecha Apl. Encargo" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_aplicacion_encargo" value="<?php echo $row_reca_especial['FECHA_APL_ENCARGO'] ?>" placeholder="Fecha Apl. Encargo" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_aplicacion_encargo" value="" placeholder="Fecha Apl. Encargo" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_recaudo">Valor Reca.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_recaudo" data-toogle="tooltip" title="VALOR RECAUDO">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_reca_especial['VALOR_RECAUDO'] ?>" maxlength="25" placeholder="Valor Recaudo" required="required" onblur="convertValorRecaudo(); calcularValorSaldoFecha();" onchange="return convertValorRecaudo()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_reca_especial['VALOR_RECAUDO'] ?>" readonly="readonly" placeholder="Valor Recaudo" onblur="convertValorRecaudo(); calcularValorSaldoFecha();" onchange="return convertValorRecaudo()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="" maxlength="25" placeholder="Valor Recaudo" required="required" onblur="convertValorRecaudo(); calcularValorSaldoFecha();" onchange="return convertValorRecaudo()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-4"></div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="saldo_fecha">Saldo:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="saldo_fecha" data-toogle="tooltip" title="SALDO A LA FECHA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="saldo_fecha" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] - $row_reca_especial['VALOR_RECAUDO']; ?>" maxlength="25" readonly="readonly" placeholder="Saldo Fecha" onblur="return convertSaldoFecha()" onchange="return convertSaldoFecha()" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="saldo_fecha" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] - $row_reca_especial['VALOR_RECAUDO']; ?>" readonly="readonly" placeholder="Saldo Fecha" onblur="return convertSaldoFecha()" onchange="return convertSaldoFecha()" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="saldo_fecha" value="" maxlength="25" placeholder="Saldo Fecha" readonly="readonly" required="required" onblur="return convertSaldoFecha()" onchange="return convertSaldoFecha()" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <div class="divider"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <div class="styled-select">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"><?php echo trim($row_reca_especial['OBSERVACIONES']); ?></textarea>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase; background-color: #FFFFFF;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" readonly="readonly" title="OBSERVACIONES"><?php echo trim($row_reca_especial['OBSERVACIONES']); ?></textarea>
                                                                            <?php
                                                                            } else { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"></textarea>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
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
                                                                    if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_recaudo_especial" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Recaudo Esp.</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Recaudo_Especiales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_especial_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_recaudo_especial" type="button" data-toggle="modal" data-target="#modalEliminarRecaEsp"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Recaudo Esp.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Recaudo_Especiales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_recaudo_especial" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Recaudo Esp.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldRecaudoEspecial();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                                </form>
                                            </div>
                                            <?php
                                            if (isset($_GET['id_reca_especial_editar'])) { ?>
                                                <div role="tabpanel" class="tab-pane fade" id="cargar_soporte_recaudo_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_soporte_recaudo" name="cargar_soporte_recaudo" action="Modelo/Subir_Archivos.php?archivo=recaudo" method="post" enctype="multipart/form-data">
                                                        <input type="hidden" name="id_tabla_archivo" id="id_tabla_archivo" value="<?php echo $_GET['id_reca_especial_editar']; ?>" />
                                                        <div style="margin-bottom: 5px;" class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="styled-select">
                                                                    <input type="file" name="files" id="files" class="inputfile inputfile-1" data-multiple-caption="{count} Archivos Seleccionados" />
                                                                    <label for="files"><i class="fas fa-folder-open"></i> <span>Seleccionar Archivo(s)&hellip;</span></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <button class="btn btn-primary btn-sm font background cursor" type="submit" name="upload_files" id="upload_files"><i style="font-size: 14px;" class="fas fa-upload"></i>&nbsp;&nbsp;Subir Soporte</button>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-9">
                                                                <div class="progress">
                                                                    <div id="progressBarFile" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div style="margin-bottom: 15px; margin-top: 15px;" class="divider"></div>
                                                    <?php
                                                    function file_size($url)
                                                    {
                                                        $size = filesize($url);
                                                        if ($size >= 1073741824) {
                                                            $fileSize = round($size / 1024 / 1024 / 1024, 1) . ' GB';
                                                        } elseif ($size >= 1048576) {
                                                            $fileSize = round($size / 1024 / 1024, 1) . ' MB';
                                                        } elseif ($size >= 1024) {
                                                            $fileSize = round($size / 1024, 1) . ' KB';
                                                        } else {
                                                            $fileSize = $size . ' bytes';
                                                        }
                                                        return $fileSize;
                                                    }
                                                    $theList = "";
                                                    $tag = "";
                                                    $total_size = 0;
                                                    $total_files = 0;
                                                    $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                        . " WHERE ID_DEPARTAMENTO = " . $row_contribuyente['ID_DEPARTAMENTO'] . ""
                                                        . "   AND ID_MUNICIPIO = " . $row_contribuyente['ID_MUNICIPIO']);
                                                    $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                                                    $path = "Files/" . $row_nombre_municipio['NOMBRE'] . "/";
                                                    $query_select_files_recaudo_especiales = mysqli_query($connection, "SELECT * "
                                                        . "  FROM recaudo_especiales_archivos_2 "
                                                        . " WHERE ID_TABLA_RECAUDO = " . $_GET['id_reca_especial_editar']);
                                                    if (mysqli_num_rows($query_select_files_recaudo_especiales) != 0) {
                                                        while ($row_files = mysqli_fetch_assoc($query_select_files_recaudo_especiales)) {
                                                            $info_recaudo[] = $row_files['NOMBRE_ARCHIVO'];
                                                            $info_id_recaudo[] = $row_files['ID_TABLA'];
                                                        }
                                                        if ($handle = opendir($path)) {
                                                            foreach (array_combine($info_id_recaudo, $info_recaudo) as $id_files => $files) {
                                                                if (pathinfo($path . $files, PATHINFO_EXTENSION) == "pdf" || pathinfo($path . $files, PATHINFO_EXTENSION) == "PDF") {
                                                                    $tag = "<i title='PDF' class='fas fa-file-pdf fa-lg' aria-hidden='true'></i>";
                                                                }
                                                                if (pathinfo($path . $files, PATHINFO_EXTENSION) == "png" || pathinfo($path . $files, PATHINFO_EXTENSION) == "PNG" || pathinfo($path . $files, PATHINFO_EXTENSION) == "jpg" || pathinfo($path . $files, PATHINFO_EXTENSION) == "JPG") {
                                                                    $tag = "<i title='IMAGE' class='fas fa-file-image fa-lg' aria-hidden='true'></i>";
                                                                }
                                                                if (pathinfo($path . $files, PATHINFO_EXTENSION) == "zip" || pathinfo($path . $files, PATHINFO_EXTENSION) == "ZIP" || pathinfo($path . $files, PATHINFO_EXTENSION) == "rar" || pathinfo($path . $files, PATHINFO_EXTENSION) == "RAR") {
                                                                    $tag = "<i title='ZIP - RAR' class='fas fa-file-archive fa-lg' aria-hidden='true'></i>";
                                                                }
                                                                if (pathinfo($path . $files, PATHINFO_EXTENSION) == "doc" || pathinfo($path . $files, PATHINFO_EXTENSION) == "DOC" || pathinfo($path . $files, PATHINFO_EXTENSION) == "docx" || pathinfo($path . $files, PATHINFO_EXTENSION) == "DOCX") {
                                                                    $tag = "<i title='WORD' class='fas fa-file-word fa-lg' aria-hidden='true'></i>";
                                                                }
                                                                if (pathinfo($path . $files, PATHINFO_EXTENSION) == "xls" || pathinfo($path . $files, PATHINFO_EXTENSION) == "XLS" || pathinfo($path . $files, PATHINFO_EXTENSION) == "xlsx" || pathinfo($path . $files, PATHINFO_EXTENSION) == "XLSX") {
                                                                    $tag = "<i title='EXCEL' class='fas fa-file-excel fa-lg' aria-hidden='true'></i>";
                                                                }
                                                                if (pathinfo($path . $files, PATHINFO_EXTENSION) == "ppt" || pathinfo($path . $files, PATHINFO_EXTENSION) == "PPT" || pathinfo($path . $files, PATHINFO_EXTENSION) == "pptx" || pathinfo($path . $files, PATHINFO_EXTENSION) == "PPTX") {
                                                                    $tag = "<i title='POWERPOINT' class='fas fa-file-powerpoint fa-lg' aria-hidden='true'></i>";
                                                                }
                                                                $theList .= "<tr>"
                                                                    . "   <td style='vertical-align: middle;'><a href='" . $path . $files . "' target='_blank' title='" . $files . "'>" . $files . "</a></td>"
                                                                    . "   <td style='vertical-align: middle;'>" . file_size($path . $files) . "</td>"
                                                                    . "   <td style='vertical-align: middle;'>" . $tag . " - " . strtoupper(pathinfo($path . $files, PATHINFO_EXTENSION)) . "</td>"
                                                                    . "   <td style='vertical-align: middle;'><a href='Modelo/Eliminar_Archivos.php?file=" . $files . "&id_tabla_archivo=" . $_GET['id_reca_especial_editar'] . "&file_id=" . $id_files . "&archivo=recaudo'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16' /></button></a></td>"
                                                                    . "</tr>";
                                                            }
                                                            closedir($handle);
                                                        }
                                                    }
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-condensed table-hover">
                                                            <thead>
                                                                <th style="width: 40%;">NOMBRE ARCHIVO</th>
                                                                <th style="width: 12%;">TAMAÑO</th>
                                                                <th style="width: 12%;">EXTENSIÓN</th>
                                                                <th style='width: 5%;'>ELIMINAR</th>
                                                            </thead>
                                                            <tbody>
                                                                <?php echo $theList; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="Javascript/bootstrap.min.js"></script>
<script src="Javascript/menu.js"></script>
<script src="Javascript/moment-with-locales.js"></script>
<script src="Javascript/bootstrap-datetimepicker.js"></script>
<script src="Javascript/jquery.twbsPagination.js"></script>
<script src="Javascript/custom-file-input.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script>
    function resetFieldRecaudoEspecial() {
        $("#id_contribuyente").val("");
        $("#id_facturacion").val("");
        $("#contribuyente").focus();
    }

    function isNumeric(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function isNothing(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 0 && (charCode < 255)) {
            return false;
        }
        return true;
    }

    function convertValorLiqVencidas() {
        var liqVencidas = $("input[name=valor_liq_vencidas]").val();
        var replaceLiqVencidas = liqVencidas.replace(/,/g, '');
        var newLiqVencidas = replaceLiqVencidas.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_liq_vencidas]").val(newLiqVencidas);
    }

    function convertValorTarifa() {
        var tarifa = $("input[name=valor_tarifa]").val();
        var replaceTarifa = tarifa.replace(/,/g, '');
        var newTarifa = replaceTarifa.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_tarifa]").val(newTarifa);
    }

    function convertValorFactura() {
        var factura = $("input[name=valor_factura]").val();
        var replaceFactura = factura.replace(/,/g, '');
        var newFactura = replaceFactura.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_factura]").val(newFactura);
    }

    function convertValorRecaudo() {
        var recaudo = $("input[name=valor_recaudo]").val();
        var replaceRecaudo = recaudo.replace(/,/g, '');
        var newRecaudo = replaceRecaudo.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_recaudo]").val(newRecaudo);
    }

    function convertSaldoFecha() {
        var saldoFecha = $("input[name=saldo_fecha]").val();
        var replaceSaldoFecha = saldoFecha.replace(/,/g, '');
        var newSaldoFecha = replaceSaldoFecha.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=saldo_fecha]").val(newSaldoFecha);
    }

    function calcularValorSaldoFecha() {
        var valor_factura = $("input[name=valor_factura]").val();
        var valor_recaudo = $("input[name=valor_recaudo]").val();
        $("input[name=saldo_fecha]").val(Math.round(valor_factura.replace(/,/g, "") - valor_recaudo.replace(/,/g, "")));
        convertSaldoFecha();
    }

    function calcularValorFacturaConsumo() {
        var tarifa = $("input[name=tarifa]").val();
        var valor_tarifa = $("input[name=valor_tarifa]").val();
        //alert("Tarifa: " + tarifa + ". Valor: " + valor_tarifa + ". Total: " + Math.round((tarifa / 100) * valor_tarifa.replace(/,/g, "")));
        $("input[name=valor_factura]").val(Math.round((tarifa / 100) * valor_tarifa.replace(/,/g, "")));
        convertValorFactura();
    }
    //POPUPS
    function infoContribuyenteReca(id_contribuyente, contribuyente, nit_contribuyente, departamento, municipio) {
        $("#id_contribuyente").val(id_contribuyente);
        $("#contribuyente").val(contribuyente);
        $("#nit_contribuyente").val(nit_contribuyente);
        $("#departamento").val(departamento);
        $("#municipio").val(municipio);
        $("#id_facturacion").val("");
        $("#consecutivo_factura").val("");
        $("#periodo_factura").val("");
        $("input[name=no_liq_vencidas]").val("");
        $("input[name=valor_liq_vencidas]").val("");
        $("#tipo_facturacion").val("");
        $("input[name=tarifa]").val("");
        $("input[name=valor_tarifa]").val("");
        $("input[name=valor_factura]").val("");
        $("input[name=valor_recaudo]").val("");
        $("input[name=saldo_fecha]").val("");
        $("#consecutivo_factura").focus();
    }

    function contribuyenteReca() {
        window.open("Combos/Contribuyente_Recaudo.php", "Popup", "width=700, height=500");
    }

    function infoFacturacion(id_facturacion, consecutivo_factura, periodo_factura, no_liq_vencidas, valor_liq_vencidas, id_tipo_facturacion, tarifa, valor_tarifa, valor_factura) {
        $("#id_facturacion").val(id_facturacion);
        $("#consecutivo_factura").val(consecutivo_factura);
        $("#periodo_factura").val(periodo_factura);
        $("input[name=no_liq_vencidas]").val(no_liq_vencidas);
        $("input[name=valor_liq_vencidas]").val(valor_liq_vencidas);
        switch (id_tipo_facturacion) {
            case '1':
                $("#tipo_facturacion").val("CONSUMO");
                $("#tarifa_icon").removeClass('fas fa-hashtag');
                $("#tarifa_icon").addClass('fas fa-percentage');
                break;
            case '2':
                $("#tipo_facturacion").val("SALARIOS");
                $("#tarifa_icon").removeClass('fas fa-percentage');
                $("#tarifa_icon").addClass('fas fa-hashtag');
                break;
        }
        $("input[name=tarifa]").val(tarifa);
        $("input[name=valor_tarifa]").val(valor_tarifa);
        $("input[name=valor_factura]").val(valor_factura);
        convertValorTarifa();
        convertValorFactura();
        convertValorLiqVencidas();
        $("input[name=nota_fiducia]").focus();
    }

    function infoFact() {
        var id_contribuyente = $("#id_contribuyente").val();
        window.open("Combos/Consecutivo_Factura.php?id_contribuyente=" + id_contribuyente, "Popup", "width=500, height=500");
    }
    //END POPUPS
</script>
<script>
    $(document).ready(function() {
        $("#buscar_recaudo").focus();
        var id_reca_especial_editar = $("#id_reca_especial_editar_hidden").val();
        var id_reca_especial_eliminar = $("#id_reca_especial_eliminar_hidden").val();
        if (id_reca_especial_editar != undefined) {
            convertValorRecaudo();
            convertValorTarifa();
            convertValorFactura();
            convertValorLiqVencidas();
            $(".nav-pills a[href='#crear_reca_especiales_tab']").tab("show");
            $(".nav-pills a[href='#crear_reca_especiales_tab']").text("Actualizar Reca. Cliente Especial");
            $("#estado_recaudo").val($("#estado_recaudo_hidden").val());
        } else {
            if (id_reca_especial_eliminar != undefined) {
                convertValorRecaudo();
                convertValorTarifa();
                convertValorFactura();
                convertValorLiqVencidas();
                $(".nav-pills a[href='#crear_reca_especiales_tab']").tab("show");
                $(".nav-pills a[href='#crear_reca_especiales_tab']").text("Eliminar Reca. Cliente Especial");
                $("#estado_recaudo").val($("#estado_recaudo_hidden").val());
            }
        }
        $("#buscar_recaudo").keypress(function(e) {
            if (e.which == 13) {
                var busqueda_recaudo;
                if ($(this).val() == "") {
                    busqueda_recaudo = "";
                } else {
                    busqueda_recaudo = $(this).val().toUpperCase();
                }
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Reca_Esp.php",
                    dataType: "json",
                    data: "sw=1&busqueda_recaudo=" + busqueda_recaudo,
                    success: function(data) {
                        $("#pagination-reca_esp").twbsPagination('destroy');
                        $("#pagination-reca_esp").twbsPagination({
                            totalPages: data[0],
                            visiblePages: 15,
                            first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                            prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                            next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                            last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                            onPageClick: function(event, page) {
                                $("#loading-spinner").css('display', 'block');
                                $.ajax({
                                    type: "POST",
                                    url: "Modelo/Cargar_Reca_Esp.php",
                                    dataType: "json",
                                    data: "sw=1&busqueda_recaudo=" + data[1] + "&page=" + page,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_reca_esp").html(data[0]);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
        $("#fecha_pago_soporte").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_pago_bitacora").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_fiducia").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_aplicacion_encargo").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#tab_info_reca_especiales").on("shown.bs.tab", function() {
            $("#buscar_recaudo").focus();
        });
        $("#tab_crear_reca_especiales").on("shown.bs.tab", function() {
            $("#contribuyente").focus();
        });
        $("#tab_info_reca_especiales").on("click", function() {
            $("#buscar_recaudo").focus();
        });
        $("#tab_crear_reca_especiales").on("click", function() {
            $("#contribuyente").focus();
        });
        if (id_reca_especial_editar == undefined && id_reca_especial_eliminar == undefined) {
            $("#btn_crear_recaudo_especial").click(function() {
                var fecha_pago_soporte = $("input[name=fecha_pago_soporte]").val();
                var fecha_pago_bitacora = $("input[name=fecha_pago_bitacora]").val();
                var estado_recaudo = $("#estado_recaudo").val();
                var contribuyente = $("#id_contribuyente").val();
                var id_facturacion = $("#id_facturacion").val();
                var nota_fiducia = $("input[name=nota_fiducia]").val();
                var fecha_fiducia = $("input[name=fecha_fiducia]").val();
                var fecha_aplicacion_encargo = $("input[name=fecha_aplicacion_encargo]").val();
                var valor_recaudo = $("input[name=valor_recaudo]").val();
                if (valor_recaudo != "") {
                    valor_recaudo = valor_recaudo.replace(/,/g, "");
                }
                var observaciones = $("#observaciones").val().toUpperCase();
                if (fecha_pago_soporte.length == 0) {
                    $("input[name=fecha_pago_soporte]").focus();
                    return false;
                }
                if (fecha_pago_bitacora.length == 0) {
                    $("input[name=fecha_pago_bitacora]").focus();
                    return false;
                }
                if (estado_recaudo.length == 0) {
                    $("#estado_recaudo").focus();
                    return false;
                }
                if (contribuyente.length == 0) {
                    $("#contribuyente").focus();
                    return false;
                }
                if (id_facturacion.length == 0) {
                    $("#consecutivo_factura").focus();
                    return false;
                }
                if (nota_fiducia.length == 0) {
                    $("input[name=nota_fiducia]").focus();
                    return false;
                }
                if (fecha_fiducia.length == 0) {
                    $("input[name=fecha_fiducia]").focus();
                    return false;
                }
                if (fecha_aplicacion_encargo.length == 0) {
                    $("input[name=fecha_aplicacion_encargo]").focus();
                    return false;
                }
                if (valor_recaudo.length == 0) {
                    $("input[name=valor_recaudo]").focus();
                    return false;
                }
                $("#btn_crear_recaudo_especial").attr("disabled", true);
                $("#btn_crear_recaudo_especial").css("pointer-events", "none");
                $("#btn_crear_recaudo_especial").html("Creando Reca. Cliente Especial...");
                $.ajax({
                    type: "POST",
                    data: "fecha_pago_soporte=" + fecha_pago_soporte +
                          "&fecha_pago_bitacora=" + fecha_pago_bitacora +
                          "&estado_recaudo=" + estado_recaudo +
                          "&id_facturacion=" + id_facturacion +
                          "&nota_fiducia=" + nota_fiducia +
                          "&fecha_fiducia=" + fecha_fiducia +
                          "&fecha_aplicacion_encargo=" + fecha_aplicacion_encargo +
                          "&valor_recaudo=" + valor_recaudo +
                          "&observaciones=" + observaciones,
                    url: "Modelo/Crear_Reca_Esp.php",
                    success: function(data) {
                        //alert(data);
                        //$("#observaciones").val(data);
                        document.location.href = 'Recaudo_Especiales.php?id_reca_especial_editar=' + data;
                    }
                });
            });
        }
        $.ajax({
            type: "POST",
            url: "Modelo/Cargar_Paginacion_Reca_Esp.php",
            dataType: "json",
            data: "sw=0",
            success: function(data) {
                $("#pagination-reca_esp").twbsPagination('destroy');
                $("#pagination-reca_esp").twbsPagination({
                    totalPages: data[0],
                    visiblePages: 15,
                    first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                    prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                    next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                    last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                    onPageClick: function(event, page) {
                        $("#loading-spinner").css('display', 'block');
                        $.ajax({
                            type: "POST",
                            url: "Modelo/Cargar_Reca_Esp.php",
                            dataType: "json",
                            data: "sw=0&page=" + page,
                            success: function(data) {
                                $("#loading-spinner").css('display', 'none');
                                $("#resultado_reca_esp").html(data[0]);
                            }
                        });
                    }
                });
            }
        });
        $("#upload_files").click(function() {
            var files = $("#files").val();
            if (files.length == 0) {
                $("#files").focus();
                return false;
            }
            $("#upload_files").attr("disabled", true);
            $("#upload_files").css("pointer-events", "none");
            $("#upload_files").html("Subiendo archivo...");
            $("#cargar_soporte_recaudo").ajaxSubmit({
                beforeSend: function() {
                    $("#progressBarFile").css("display", "block");
                    $("#progressBarFile").width("100%");
                    $("#progressBarFile").text("100%");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    $("#progressBarFile").width(percentComplete + "%");
                    $("#progressBarFile").text(percentComplete + "%");
                },
                success: function() {
                    $("#modalUpload").modal("show");
                }
            });
            return false;
        });
        $("#modalUpload").on('hidden.bs.modal', function() {
            location.reload();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('input[type=text][name=consecutivo_factura]').tooltip({
            container: "body",
            placement: "right"
        });
        $('input[type=text][name=contribuyente]').tooltip({
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
        $('input[type=text][name=periodo_factura]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=tipo_facturacion]').tooltip({
            container: "body",
            placement: "top"
        });
        $('#no_liq_vencidas').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_liq_vencidas').tooltip({
            container: "body",
            placement: "right"
        });
        $('#tarifa').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_tarifa').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_factura').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_pago_soporte').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_pago_bitacora').tooltip({
            container: "body",
            placement: "top"
        });
        $('#nota_fiducia').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_fiducia').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_aplicacion_encargo').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_recaudo').tooltip({
            container: "body",
            placement: "right"
        });
        $('#saldo_fecha').tooltip({
            container: "body",
            placement: "top"
        });
        $('select[name=estado_recaudo]').tooltip({
            container: "body",
            placement: "top"
        });
        $('textarea[name=observaciones]').tooltip({
            container: "body",
            placement: "top"
        });
        $("#menu_home").tooltip({
            container: "body",
            placement: "top"
        });
    });
</script>

</html>