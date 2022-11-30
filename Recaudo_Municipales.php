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
    <title>AGM - Recaudo Aportes Municipales</title>
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
<!--Eliminar Recaudo Municipales Modal-->
<div class="modal fade" id="modalEliminarRecaMun" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Eliminar Recaudo Aportes Municipales</h4>
            </div>
            <div class="modal-body">
                <p>¿Desea eliminar el Recaudo?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_reca_mun" name="eliminar_reca_mun"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>
<!--Fin Eliminar Recaudo Municipales Modal-->

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
                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-coins"></i>
                                                                    <span>Aportes Municipales</span>
                                                                </a>
                                                                <div style="display: block;" class="sidebar-submenu">
                                                                    <ul class="nav nav-pills nav-stacked">
                                                                        <li>
                                                                            <a href='Recaudo_Municipales.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check"></i>
                                                                                <span>Recaudo</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href='Facturacion_Municipales.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check-alt"></i>
                                                                                <span>Facturación</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href='Reportes_Municipales.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-chart-pie"></i>
                                                                                <span>Reportes</span>
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
                                        <h1>Recaudo Aportes Municipales</h1>
                                        <h2></h2>
                                        <ul class="nav nav-pills" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#informacion_reca_municipales_tab" id="tab_info_reca_municipales" aria-controls="informacion_reca_municipales_tab" role="tab" data-toggle="tab">Información Reca. Aportes Municipales</a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#crear_reca_municipales_tab" id="tab_crear_reca_municipales" aria-controls="crear_reca_municipales_tab" role="tab" data-toggle="tab">Crear Reca. Aportes Municipales</a>
                                            </li>
                                            <?php
                                            if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                <li role="presentation">
                                                    <a href="#cargar_soporte_recaudo_tab" id="tab_cargar_soporte_recaudo" aria-controls="cargar_soporte_recaudo_tab" role="tab" data-toggle="tab">Cargar Soporte Recaudo</a>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                        <h2></h2>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade in active" id="informacion_reca_municipales_tab">
                                                <input class="form-control input-text input-sm" type="text" placeholder="Buscar Recaudo" name="buscar_recaudo" id="buscar_recaudo" />
                                                <br />
                                                <?php
                                                $query_select_reca_mun = "SELECT * FROM recaudo_municipales_2 ORDER BY ID_FACTURACION DESC";
                                                $sql_reca_mun = mysqli_query($connection, $query_select_reca_mun);
                                                if (mysqli_num_rows($sql_reca_mun) != 0) {
                                                    echo "<div class='table-responsive'>";
                                                    echo "<table class='table table-condensed table-hover'>";
                                                    echo "<thead>";
                                                    echo "<tr>";
                                                    echo "<th width=4%>ESTADO</th>";
                                                    echo "<th width=13%>DEPARTAMENTO</th>";
                                                    echo "<th width=17%>MUNICIPIO</th>";
                                                    echo "<th width=11%>C. COBRO</th>";
                                                    echo "<th width=11%>VALOR</th>";
                                                    echo "<th width=5%>DETALLE</th>";
                                                    echo "<th width=5%>ELIMINAR</th>";
                                                    echo "</tr>";
                                                    echo "</thead>";
                                                    echo "<tbody id='resultado_reca_mun'>";

                                                    echo "</tbody>";
                                                    echo "</table>";
                                                    echo "</div>";
                                                    echo "<p></p>";
                                                    echo "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = ENTREGADO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADA.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO POR ACUERDO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGO PARCIAL.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE ENVIO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span>";
                                                } else {
                                                    echo "<p class='message'>No se encontraron Recaudos Aportes Municipales Creados.</p>";
                                                }
                                                ?>
                                                <div id="div-pagination">
                                                    <ul id="pagination-reca_mun"></ul>
                                                    <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="crear_reca_municipales_tab">
                                                <?php
                                                if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_reca_municipal" name="crear_reca_municipal" action="<?php echo "Modelo/Crear_Reca_Mun.php?editar=" . $_GET['id_reca_municipal_editar']; ?>" method="post">
                                                        <?php
                                                        $query_select_reca_municipal = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 WHERE ID_RECAUDO = " . $_GET['id_reca_municipal_editar']);
                                                        $row_reca_municipal = mysqli_fetch_array($query_select_reca_municipal);
                                                        ?>
                                                        <input type="hidden" id="id_reca_municipal_editar_hidden" name="id_reca_municipal_editar_hidden" value="<?php echo $row_reca_municipal['ID_RECAUDO']; ?>" />
                                                        <?php
                                                    } else {
                                                        if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_reca_municipal" name="crear_reca_municipal" action="<?php echo "Modelo/Crear_Reca_Mun.php?eliminar=" . $_GET['id_reca_municipal_eliminar']; ?>" method="post">
                                                                <?php
                                                                $query_select_reca_municipal = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 WHERE ID_RECAUDO = " . $_GET['id_reca_municipal_eliminar']);
                                                                $row_reca_municipal = mysqli_fetch_array($query_select_reca_municipal);
                                                                ?>
                                                                <input type="hidden" id="id_reca_municipal_eliminar_hidden" name="id_reca_municipal_eliminar_hidden" value="<?php echo $row_reca_municipal['ID_RECAUDO']; ?>" />
                                                            <?php
                                                        } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_reca_municipal" name="crear_reca_municipal" action="<?php echo "Modelo/Crear_Reca_Mun.php"; ?>" method="post">
                                                                <?php
                                                            }
                                                                ?>
                                                            <?php
                                                        }
                                                            ?>
                                                            <div class="form-group">
                                                                <!--<div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_pago_soporte" data-toogle="tooltip" title="FECHA PAGO SOPORTE">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_pago_soporte" value="<?php echo $row_reca_municipal['FECHA_PAGO_SOPORTE'] ?>" placeholder="Fecha Pago Soporte" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_soporte" value="<?php echo $row_reca_municipal['FECHA_PAGO_SOPORTE'] ?>" placeholder="Fecha Pago Soporte" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                            </div>-->
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_pago_bitacora">Fecha Bit.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_pago_bitacora" data-toogle="tooltip" title="FECHA PAGO BITACORA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_pago_bitacora" value="<?php echo $row_reca_municipal['FECHA_PAGO_BITACORA'] ?>" placeholder="Fecha Pago Bitacora" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_bitacora" value="<?php echo $row_reca_municipal['FECHA_PAGO_BITACORA'] ?>" placeholder="Fecha Pago Bitacora" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_pago_municipio">Fecha Mun.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_pago_municipio" data-toogle="tooltip" title="FECHA PAGO MUNICIPIO">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_pago_municipio" value="<?php echo $row_reca_municipal['FECHA_PAGO_MUNICIPIO'] ?>" placeholder="Fecha Pago Municipio" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_municipio" value="<?php echo $row_reca_municipal['FECHA_PAGO_MUNICIPIO'] ?>" placeholder="Fecha Pago Municipio" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_pago_municipio" value="" placeholder="Fecha Pago Municipio" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="estado_recaudo" name="estado_recaudo" data-toggle="tooltip" title="ESTADO RECAUDO" required>
                                                                                <?php
                                                                            } else {
                                                                                if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
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
                                                                                    <option value="6">PAGADO POR ACUERDO</option>
                                                                                    <option value="5">PAGO PARCIAL</option>
                                                                                    <option value="2">PENDIENTE DE ENVÍO</option>
                                                                                    <option value="3">RECLAMADA</option>
                                                                                    <?php
                                                                                    if (isset($_GET['id_reca_municipal_editar']) || isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                        <input type="hidden" id="estado_recaudo_hidden" name="estado_recaudo_hidden" value="<?php echo $row_reca_municipal['ESTADO_RECAUDO']; ?>" />
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN MUNICIPIO</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento">Dpto:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_municipal_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * "
                                                                            . "  FROM departamentos_visitas_2 DV, facturacion_municipales_2 FM, recaudo_municipales_2 RM "
                                                                            . " WHERE FM.ID_FACTURACION = RM.ID_FACTURACION "
                                                                            . "   AND FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                            . "   AND RM.ID_FACTURACION = " . $row_reca_municipal['ID_FACTURACION']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_municipal_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * "
                                                                                . "  FROM departamentos_visitas_2 DV, facturacion_municipales_2 FM, recaudo_municipales_2 RM "
                                                                                . " WHERE FM.ID_FACTURACION = RM.ID_FACTURACION "
                                                                                . "   AND FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                                . "   AND RM.ID_FACTURACION = " . $row_reca_municipal['ID_FACTURACION']);
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="municipio">Mpio:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_municipal_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * "
                                                                            . "  FROM departamentos_visitas_2 DV, municipios_visitas_2 MV, facturacion_municipales_2 FM, recaudo_municipales_2 RM "
                                                                            . " WHERE FM.ID_FACTURACION = RM.ID_FACTURACION "
                                                                            . "   AND FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                            . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                            . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                            . "   AND RM.ID_FACTURACION = " . $row_reca_municipal['ID_FACTURACION']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_municipal_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * "
                                                                                . "  FROM departamentos_visitas_2 DV, municipios_visitas_2 MV, facturacion_municipales_2 FM, recaudo_municipales_2 RM "
                                                                                . " WHERE FM.ID_FACTURACION = RM.ID_FACTURACION "
                                                                                . "   AND FM.ID_COD_DPTO = DV.ID_DEPARTAMENTO "
                                                                                . "   AND FM.ID_COD_MPIO = MV.ID_MUNICIPIO "
                                                                                . "   AND DV.ID_DEPARTAMENTO = MV.ID_DEPARTAMENTO "
                                                                                . "   AND RM.ID_FACTURACION = " . $row_reca_municipal['ID_FACTURACION']);
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
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN VALORES FACTURA</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="consecutivo_fact">C.C. Fact:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_municipal_editar'])) {
                                                                        $query_select_info_facturacion = mysqli_query($connection, "SELECT * FROM facturacion_municipales_2 WHERE ID_FACTURACION = " . $row_reca_municipal['ID_FACTURACION']);
                                                                        $row_info_facturacion = mysqli_fetch_array($query_select_info_facturacion);
                                                                    ?>
                                                                        <input type="hidden" id="id_facturacion" name="id_facturacion" value="<?php echo $row_info_facturacion['ID_FACTURACION']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="consecutivo_factura" name="consecutivo_factura" value="<?php echo $row_info_facturacion['CONSECUTIVO_FACT']; ?>" placeholder="Consecutivo Fact." required="required" data-toggle="tooltip" readonly="readonly" title="CONSECUTIVO FACT." onclick="infoFact()" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_municipal_eliminar'])) {
                                                                            $query_select_info_facturacion = mysqli_query($connection, "SELECT * FROM facturacion_municipales_2 WHERE ID_FACTURACION = " . $row_reca_municipal['ID_FACTURACION']);
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_factura">Periodo:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                        <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php echo substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php echo substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                        $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                            . "  FROM periodos_facturacion_municipales_2 "
                                                                            . " WHERE ANO_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4) . " "
                                                                            . "   AND MES_FACTURA = " . substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2));
                                                                        $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoFact()" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php substr($row_info_facturacion['PERIODO_FACTURA'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php substr($row_info_facturacion['PERIODO_FACTURA'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                            $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                . "  FROM periodos_facturacion_municipales_2 "
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_factura">Valor Fact.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_factura" data-toogle="tooltip" title="VALOR FACTURA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Factura" onblur="return convertValorFactura()" onchange="return convertValorFactura()" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
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
                                                            <div style="margin-bottom: 0;" class="form-group">
                                                                <div class="col-xs-3"></div>
                                                                <div class="col-xs-3"></div>
                                                                <div class="col-xs-2"></div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_cartera">Valor Cart.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="valor_cartera" data-toogle="tooltip" title="VALOR CARTERA">
                                                                        <?php
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <?php
                                                                            $query_select_valor_cartera = mysqli_query($connection, "SELECT VALOR_CARTERA "
                                                                                . "  FROM alcaldias_2 "
                                                                                . " WHERE ID_COD_DPTO = " . $row_info_facturacion['ID_COD_DPTO'] . " "
                                                                                . "   AND ID_COD_MPIO = " . $row_info_facturacion['ID_COD_MPIO'] . " ");
                                                                            $row_valor_cartera = mysqli_fetch_array($query_select_valor_cartera);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_cartera" value="<?php echo $row_valor_cartera['VALOR_CARTERA'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Cartera" onblur="return convertValorCartera()" onchange="return convertValorCartera()" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <?php
                                                                                $query_select_valor_cartera = mysqli_query($connection, "SELECT VALOR_CARTERA "
                                                                                    . "  FROM alcaldias_2 "
                                                                                    . " WHERE ID_COD_DPTO = " . $row_info_facturacion['ID_COD_DPTO'] . " "
                                                                                    . "   AND ID_COD_MPIO = " . $row_info_facturacion['ID_COD_MPIO'] . " ");
                                                                                $row_valor_cartera = mysqli_fetch_array($query_select_valor_cartera);
                                                                                ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_cartera" value="<?php echo $row_valor_cartera['VALOR_CARTERA'] ?>" readonly="readonly" placeholder="Valor Cartera" onblur="return convertValorCartera()" onchange="return convertValorCartera()" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_cartera" value="" maxlength="25" placeholder="Valor Cartera" readonly="readonly" required="required" onblur="return convertValorCartera()" onchange="return convertValorCartera()" />
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: right; padding-top: 4px; white-space: nowrap; color: red;" class="col-xs-12 control-label row-label" for="">Valor Cartera incluye el Periodo Actual Facturado.</label>
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
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="nota_fiducia" value="<?php echo $row_reca_municipal['NOTA_FIDUCIA'] ?>" maxlength="10" required="required" placeholder="Nota Fiducia" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="nota_fiducia" value="<?php echo $row_reca_municipal['NOTA_FIDUCIA'] ?>" placeholder="Nota Fiducia" onkeypress="return isNumeric(event)" />
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
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_fiducia" value="<?php echo $row_reca_municipal['FECHA_FIDUCIA'] ?>" placeholder="Fecha Fiducia" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_fiducia" value="<?php echo $row_reca_municipal['FECHA_FIDUCIA'] ?>" placeholder="Fecha Fiducia" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_aplicacion_encargo" value="<?php echo $row_reca_municipal['FECHA_APL_ENCARGO'] ?>" placeholder="Fecha Apl. Encargo" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_aplicacion_encargo" value="<?php echo $row_reca_municipal['FECHA_APL_ENCARGO'] ?>" placeholder="Fecha Apl. Encargo" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_reca_municipal['VALOR_RECAUDO'] ?>" maxlength="25" placeholder="Valor Recaudo" required="required" onblur="convertValorRecaudo(); calcularValorSaldoFecha();" onchange="return convertValorRecaudo()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_reca_municipal['VALOR_RECAUDO'] ?>" readonly="readonly" placeholder="Valor Recaudo" onblur="convertValorRecaudo(); calcularValorSaldoFecha();" onchange="return convertValorRecaudo()" onkeypress="return isNumeric(event)" />
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
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="saldo_fecha" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] - $row_reca_municipal['VALOR_RECAUDO']; ?>" maxlength="25" readonly="readonly" placeholder="Saldo Fecha" onblur="return convertSaldoFecha()" onchange="return convertSaldoFecha()" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="saldo_fecha" value="<?php echo $row_info_facturacion['VALOR_FACTURA'] - $row_reca_municipal['VALOR_RECAUDO']; ?>" readonly="readonly" placeholder="Saldo Fecha" onblur="return convertSaldoFecha()" onchange="return convertSaldoFecha()" />
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
                                                                <div class="col-xs-3"></div>
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
                                                                        if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"><?php echo trim($row_reca_municipal['OBSERVACIONES']); ?></textarea>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase; background-color: #FFFFFF;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" readonly="readonly" title="OBSERVACIONES"><?php echo trim($row_reca_municipal['OBSERVACIONES']); ?></textarea>
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
                                                                    if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_recaudo_municipal" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Recaudo Mun.</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Recaudo_Municipales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_reca_municipal_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_recaudo_municipal" type="button" data-toggle="modal" data-target="#modalEliminarRecaMun"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Recaudo Mun.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Recaudo_Municipales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_recaudo_municipal" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Recaudo Mun.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldRecaudoMunicipal();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                                </form>
                                            </div>
                                            <?php
                                            if (isset($_GET['id_reca_municipal_editar'])) { ?>
                                                <div role="tabpanel" class="tab-pane fade" id="cargar_soporte_recaudo_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_soporte_recaudo" name="cargar_soporte_recaudo" action="Modelo/Subir_Archivos.php?archivo=recaudo_municipal" method="post" enctype="multipart/form-data">
                                                        <input type="hidden" name="id_tabla_archivo" id="id_tabla_archivo" value="<?php echo $_GET['id_reca_municipal_editar']; ?>" />
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
                                                        . " WHERE ID_DEPARTAMENTO = " . $row_municipio['ID_DEPARTAMENTO'] . ""
                                                        . "   AND ID_MUNICIPIO = " . $row_municipio['ID_MUNICIPIO']);
                                                    $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                                                    $path = "Files/" . $row_nombre_municipio['NOMBRE'] . "/";
                                                    $query_select_files_recaudo_municipales = mysqli_query($connection, "SELECT * "
                                                        . "  FROM recaudo_municipales_archivos_2 "
                                                        . " WHERE ID_TABLA_RECAUDO = " . $_GET['id_reca_municipal_editar']);
                                                    if (mysqli_num_rows($query_select_files_recaudo_municipales) != 0) {
                                                        while ($row_files = mysqli_fetch_assoc($query_select_files_recaudo_municipales)) {
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
                                                                    . "   <td style='vertical-align: middle;'><a href='Modelo/Eliminar_Archivos.php?file=" . $files . "&id_tabla_archivo=" . $_GET['id_reca_municipal_editar'] . "&file_id=" . $id_files . "&archivo=recaudo_municipal'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16' /></button></a></td>"
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
    function resetFieldRecaudoMunicipal() {
        $("#id_departamento").val("");
        $("#id_municipio").val("");
        $("#id_facturacion").val("");
        $("#departamento").focus();
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

    function convertValorFactura() {
        var factura = $("input[name=valor_factura]").val();
        var replaceFactura = factura.replace(/,/g, '');
        var newFactura = replaceFactura.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_factura]").val(newFactura);
    }

    function convertValorCartera() {
        var cartera = $("input[name=valor_cartera]").val();
        var replaceCartera = cartera.replace(/,/g, '');
        var newCartera = replaceCartera.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        $("input[name=valor_cartera]").val(newCartera);
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
        var valor_cartera = $("input[name=valor_cartera]").val();
        var valor_recaudo = $("input[name=valor_recaudo]").val();
        $("input[name=saldo_fecha]").val(Math.round(valor_cartera.replace(/,/g, "") - valor_recaudo.replace(/,/g, "")));
        convertSaldoFecha();
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
        window.open("Combos/Tipo_Departamento_Recaudo.php?id_consulta=" + id_consulta, "Popup", "width=400, height=500");
    }

    function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
        if (id_consulta == 1) {
            $("#id_municipio").val(id_municipio);
            $("#municipio").val(municipio);
            $("#consecutivo_factura").focus();
        }
    }

    function tipoMunicipio(id_consulta) {
        var id_departamento;
        if (id_consulta == 1) {
            id_departamento = $("#id_departamento").val();
        }
        window.open("Combos/Tipo_Municipio_Recaudo.php?id_departamento=" + id_departamento + "&id_consulta=" + id_consulta, "Popup", "width=400, height=500");
    }

    function infoFacturacion(id_facturacion, consecutivo_factura, periodo_factura, valor_factura, valor_cartera) {
        $("#id_facturacion").val(id_facturacion);
        $("#consecutivo_factura").val(consecutivo_factura);
        $("#periodo_factura").val(periodo_factura);
        $("input[name=valor_factura]").val(valor_factura);
        $("input[name=valor_cartera]").val(valor_cartera);
        convertValorFactura();
        convertValorCartera();
        $("input[name=nota_fiducia]").focus();
    }

    function infoFact() {
        var id_departamento = $("#id_departamento").val();
        var id_municipio = $("#id_municipio").val();
        window.open("Combos/Consecutivo_Factura_Mun.php?id_departamento=" + id_departamento + "&id_municipio=" + id_municipio, "Popup", "width=500, height=500");
    }
    //END POPUPS
</script>
<script>
    $(document).ready(function() {
        $("#buscar_recaudo").focus();
        var id_reca_municipal_editar = $("#id_reca_municipal_editar_hidden").val();
        var id_reca_municipal_eliminar = $("#id_reca_municipal_eliminar_hidden").val();
        if (id_reca_municipal_editar != undefined) {
            convertValorRecaudo();
            convertValorFactura();
            convertValorCartera();
            convertSaldoFecha();
            $(".nav-pills a[href='#crear_reca_municipales_tab']").tab("show");
            $(".nav-pills a[href='#crear_reca_municipales_tab']").text("Actualizar Reca. Aportes Municipales");
            $("#estado_recaudo").val($("#estado_recaudo_hidden").val());
        } else {
            if (id_reca_municipal_eliminar != undefined) {
                convertValorRecaudo();
                convertValorFactura();
                convertValorCartera();
                convertSaldoFecha();
                $(".nav-pills a[href='#crear_reca_municipales_tab']").tab("show");
                $(".nav-pills a[href='#crear_reca_municipales_tab']").text("Eliminar Reca. Aportes Municipales");
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
                    url: "Modelo/Cargar_Paginacion_Reca_Mun.php",
                    dataType: "json",
                    data: "sw=1&busqueda_recaudo=" + busqueda_recaudo,
                    success: function(data) {
                        $("#pagination-reca_mun").twbsPagination('destroy');
                        $("#pagination-reca_mun").twbsPagination({
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
                                    url: "Modelo/Cargar_Reca_Mun.php",
                                    dataType: "json",
                                    data: "sw=1&busqueda_recaudo=" + data[1] + "&page=" + page,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_reca_mun").html(data[0]);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
        $("#fecha_pago_bitacora").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_pago_municipio").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_fiducia").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_aplicacion_encargo").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#tab_info_reca_municipales").on("shown.bs.tab", function() {
            $("#buscar_recaudo").focus();
        });
        $("#tab_crear_reca_municipales").on("shown.bs.tab", function() {
            $("#departamento").focus();
        });
        $("#tab_info_reca_municipales").on("click", function() {
            $("#buscar_recaudo").focus();
        });
        $("#tab_crear_reca_municipales").on("click", function() {
            $("#departamento").focus();
        });
        if (id_reca_municipal_editar == undefined && id_reca_municipal_eliminar == undefined) {
            $("#btn_crear_recaudo_municipal").click(function() {
                var fecha_pago_bitacora = $("input[name=fecha_pago_bitacora]").val();
                var fecha_pago_municipio = $("input[name=fecha_pago_municipio]").val();
                var estado_recaudo = $("#estado_recaudo").val();
                var departamento = $("#id_departamento").val();
                var municipio = $("#id_municipio").val();
                var id_facturacion = $("#id_facturacion").val();
                var nota_fiducia = $("input[name=nota_fiducia]").val();
                var fecha_fiducia = $("input[name=fecha_fiducia]").val();
                var fecha_aplicacion_encargo = $("input[name=fecha_aplicacion_encargo]").val();
                var valor_recaudo = $("input[name=valor_recaudo]").val();
                if (valor_recaudo != "") {
                    valor_recaudo = valor_recaudo.replace(/,/g, "");
                }
                var observaciones = $("#observaciones").val().toUpperCase();
                if (fecha_pago_bitacora.length == 0) {
                    $("input[name=fecha_pago_bitacora]").focus();
                    return false;
                }
                if (fecha_pago_municipio.length == 0) {
                    $("input[name=fecha_pago_municipio]").focus();
                    return false;
                }
                if (estado_recaudo.length == 0) {
                    $("#estado_recaudo").focus();
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
                $("#btn_crear_recaudo_municipal").attr("disabled", true);
                $("#btn_crear_recaudo_municipal").css("pointer-events", "none");
                $("#btn_crear_recaudo_municipal").html("Creando Reca. Aportes Municipales...");
                $.ajax({
                    type: "POST",
                    data: "fecha_pago_bitacora=" + fecha_pago_bitacora +
                          "&fecha_pago_municipio=" + fecha_pago_municipio +
                          "&estado_recaudo=" + estado_recaudo +
                          "&id_facturacion=" + id_facturacion +
                          "&nota_fiducia=" + nota_fiducia +
                          "&fecha_fiducia=" + fecha_fiducia +
                          "&fecha_aplicacion_encargo=" + fecha_aplicacion_encargo +
                          "&valor_recaudo=" + valor_recaudo +
                          "&observaciones=" + observaciones,
                    url: "Modelo/Crear_Reca_Mun.php",
                    success: function(data) {
                        //alert(data);
                        //$("#observaciones").val(data);
                        document.location.href = 'Recaudo_Municipales.php?id_reca_municipal_editar=' + data;
                    }
                });
            });
        }
        $.ajax({
            type: "POST",
            url: "Modelo/Cargar_Paginacion_Reca_Mun.php",
            dataType: "json",
            data: "sw=0",
            success: function(data) {
                $("#pagination-reca_mun").twbsPagination('destroy');
                $("#pagination-reca_mun").twbsPagination({
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
                            url: "Modelo/Cargar_Reca_Mun.php",
                            dataType: "json",
                            data: "sw=0&page=" + page,
                            success: function(data) {
                                $("#loading-spinner").css('display', 'none');
                                $("#resultado_reca_mun").html(data[0]);
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
        $('#valor_factura').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_cartera').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_pago_bitacora').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_pago_municipio').tooltip({
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