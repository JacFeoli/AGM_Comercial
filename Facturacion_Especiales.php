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
    <title>AGM - Facturación Municipio</title>
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
    </style>
</head>
<!--Eliminar Factura Especiales Modal-->
<div class="modal fade" id="modalEliminarFactEsp" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Eliminar Factura Especiales</h4>
            </div>
            <div class="modal-body">
                <p>¿Desea eliminar la Factura?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_fact_esp" name="eliminar_fact_esp"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
            </div>
        </div>
    </div>
</div>
<!--Fin Eliminar Factura Especiales Modal-->

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
                                                                            <a href='Facturacion_Especiales.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check-alt"></i>
                                                                                <span>Facturación</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href='Recaudo_Especiales.php'>
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check"></i>
                                                                                <span>Recaudo</span>
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
                                        <h1>Facturación Municipio</h1>
                                        <h2></h2>
                                        <ul class="nav nav-pills" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#informacion_fact_especiales_tab" id="tab_info_fact_especiales" aria-controls="informacion_fact_especiales_tab" role="tab" data-toggle="tab">Información Fact. Municipio</a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#crear_fact_especiales_tab" id="tab_crear_fact_especiales" aria-controls="crear_fact_especiales_tab" role="tab" data-toggle="tab">Crear Fact. Municipio</a>
                                            </li>
                                        </ul>
                                        <h2></h2>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade in active" id="informacion_fact_especiales_tab">
                                                <h2 class="text-divider"><span style="background-color: #FFFFFF;">FILTROS / BUSQUEDA</span></h2>
                                                <br />
                                                <div class="form-group">
                                                    <div class="col-xs-3">
                                                        <select class="form-control input-text input-sm" id="estado_factura_busqueda" name="estado_factura_busqueda" data-toggle="tooltip" title="ESTADO">
                                                            <option value="" selected="selected">-</option>
                                                            <option value="1">ENTREGADO</option>
                                                            <option value="2">PENDIENTE DE ENVÍO</option>
                                                            <option value="3">RECLAMADA</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <input class="form-control input-text input-sm" type="text" placeholder="Buscar Factura" name="buscar_factura" id="buscar_factura" data-toggle="tooltip" title="BUSCAR FACTURA" />
                                                    </div>
                                                </div>
                                                <br />
                                                <br />
                                                <?php
                                                $query_select_fact_esp = "SELECT * FROM facturacion_especiales_2 ORDER BY ID_COD_DPTO, ID_COD_MPIO, PERIODO_FACTURA DESC";
                                                $sql_fact_esp = mysqli_query($connection, $query_select_fact_esp);
                                                if (mysqli_num_rows($sql_fact_esp) != 0) {
                                                    echo "<div class='table-responsive'>";
                                                    echo "<table class='table table-condensed table-hover'>";
                                                    echo "<thead>";
                                                    echo "<tr>";
                                                    echo "<th width=4%>ESTADO</th>";
                                                    echo "<th width=13%>DEPARTAMENTO</th>";
                                                    echo "<th width=17%>MUNICIPIO</th>";
                                                    echo "<th width=34%>CONTRIBUYENTE</th>";
                                                    echo "<th width=11%>FACTURA</th>";
                                                    echo "<th width=11%>VALOR</th>";
                                                    echo "<th width=5%>DETALLE</th>";
                                                    echo "<th width=5%>ELIMINAR</th>";
                                                    echo "<th width=5%>IMPRIMIR</th>";
                                                    echo "</tr>";
                                                    echo "</thead>";
                                                    echo "<tbody id='resultado_fact_esp'>";

                                                    echo "</tbody>";
                                                    echo "</table>";
                                                    echo "</div>";
                                                    echo "<p></p>";
                                                    echo "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = ENTREGADO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE ENVIO.</span>";
                                                    echo "&nbsp;<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span>";
                                                } else {
                                                    echo "<p class='message'>No se encontraron Facturas Cliente Especiales Creadas.</p>";
                                                }
                                                ?>
                                                <div id="div-pagination">
                                                    <ul id="pagination-fact_esp"></ul>
                                                    <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="crear_fact_especiales_tab">
                                                <?php
                                                if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_especial" name="crear_fact_especial" action="<?php echo "Modelo/Crear_Fact_Esp.php?editar=" . $_GET['id_fact_especial_editar']; ?>" method="post">
                                                        <?php
                                                        $query_select_fact_especial = mysqli_query($connection, "SELECT * FROM facturacion_especiales_2 WHERE ID_FACTURACION = " . $_GET['id_fact_especial_editar']);
                                                        $row_fact_especial = mysqli_fetch_array($query_select_fact_especial);
                                                        ?>
                                                        <input type="hidden" id="id_fact_especial_editar_hidden" name="id_fact_especial_editar_hidden" value="<?php echo $row_fact_especial['ID_FACTURACION']; ?>" />
                                                        <?php
                                                    } else {
                                                        if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_especial" name="crear_fact_especial" action="<?php echo "Modelo/Crear_Fact_Esp.php?eliminar=" . $_GET['id_fact_especial_eliminar']; ?>" method="post">
                                                                <?php
                                                                $query_select_fact_especial = mysqli_query($connection, "SELECT * FROM facturacion_especiales_2 WHERE ID_FACTURACION = " . $_GET['id_fact_especial_eliminar']);
                                                                $row_fact_especial = mysqli_fetch_array($query_select_fact_especial);
                                                                ?>
                                                                <input type="hidden" id="id_fact_especial_eliminar_hidden" name="id_fact_especial_eliminar_hidden" value="<?php echo $row_fact_especial['ID_FACTURACION']; ?>" />
                                                            <?php
                                                        } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_especial" name="crear_fact_especial" action="<?php echo "Modelo/Crear_Fact_Esp.php"; ?>" method="post">
                                                                <?php
                                                            }
                                                                ?>
                                                            <?php
                                                        }
                                                            ?>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_factura">Fecha Fact.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_factura" data-toogle="tooltip" title="FECHA FACTURA">
                                                                        <?php
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="<?php echo $row_fact_especial['FECHA_FACTURA'] ?>" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="<?php echo $row_fact_especial['FECHA_FACTURA'] ?>" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="<?php echo date('Y-m-d'); ?>" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_entrega">Fecha Entr.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_entrega" data-toogle="tooltip" title="FECHA ENTREGA">
                                                                        <?php
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_entrega" value="<?php echo $row_fact_especial['FECHA_ENTREGA'] ?>" placeholder="Fecha Entrega" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_entrega" value="<?php echo $row_fact_especial['FECHA_ENTREGA'] ?>" placeholder="Fecha Entrega" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_entrega" value="" placeholder="Fecha Entrega" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_vencimiento">Fecha Vto.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group date" id="fecha_vencimiento" data-toogle="tooltip" title="FECHA VENCIMIENTO">
                                                                        <?php
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_vencimiento" value="<?php echo $row_fact_especial['FECHA_VENCIMIENTO'] ?>" placeholder="Fecha Vencimiento" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_vencimiento" value="<?php echo $row_fact_especial['FECHA_VENCIMIENTO'] ?>" placeholder="Fecha Vencimiento" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                            <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="fecha_vencimiento" value="" placeholder="Fecha Vencimiento" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="far fa-calendar-alt"></span>
                                                                                </span>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="estado_factura">Estd. Fact.</label>
                                                                <div class="col-xs-3">
                                                                    <div class="styled-select">
                                                                        <?php
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="estado_factura" name="estado_factura" data-toggle="tooltip" title="ESTADO FACTURA" required>
                                                                                <?php
                                                                            } else {
                                                                                if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                    <select class="form-control input-text input-sm" id="estado_factura" name="estado_factura" disabled="disabled" data-toggle="tooltip" title="ESTADO FACTURA" required>
                                                                                    <?php
                                                                                } else { ?>
                                                                                        <select class="form-control input-text input-sm" id="estado_factura" name="estado_factura" data-toggle="tooltip" title="ESTADO FACTURA" required>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                                    ?>
                                                                                    <option value="" selected="selected">-</option>
                                                                                    <option value="1">ENTREGADO</option>
                                                                                    <option value="2">PENDIENTE DE ENVÍO</option>
                                                                                    <option value="3">RECLAMADA</option>
                                                                                    <?php
                                                                                    if (isset($_GET['id_fact_especial_editar']) || isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                        <input type="hidden" id="estado_factura_hidden" name="estado_factura_hidden" value="<?php echo $row_fact_especial['ESTADO_FACTURA']; ?>" />
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="consecutivo_fact">Cons. Fact:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="consecutivo_fact" name="consecutivo_fact" value="<?php echo $row_fact_especial['CONSECUTIVO_FACT']; ?>" readonly="readonly" placeholder="Consecutivo Fact." data-toogle="tooltip" title="CONSECUTIVO FACT." />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="consecutivo_fact" name="consecutivo_fact" value="<?php echo $row_fact_especial['CONSECUTIVO_FACT']; ?>" readonly="readonly" placeholder="Consecutivo Fact." data-toogle="tooltip" title="CONSECUTIVO FACT." />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="consecutivo_fact" name="consecutivo_fact" readonly="readonly" placeholder="Consecutivo Fact." data-toogle="tooltip" title="CONSECUTIVO FACT." />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <div class="col-xs-4"></div>
                                                                <div style="text-align: right;" class="col-xs-4">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                        <a onClick="generarFacturaClienteEsp(<?php echo $_GET['id_fact_especial_editar'] ?>)"><button class="btn_print" type="button" data-tooltip='tooltip' title="IMPRIMIR FACTURA"><img src="Images/print_2.png" width="16" height="16" /></button></a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento">Dpto:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_especial_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_especial['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_especial['ID_COD_DPTO']);
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
                                                                    if (isset($_GET['id_fact_especial_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_especial['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_fact_especial['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_especial['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_fact_especial['ID_COD_MPIO']);
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_factura">Periodo:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                        <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php echo substr($row_fact_especial['PERIODO_FACTURA'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php echo substr($row_fact_especial['PERIODO_FACTURA'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                        $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                            . "  FROM periodos_facturacion_especiales_2 "
                                                                            . " WHERE ANO_FACTURA = " . substr($row_fact_especial['PERIODO_FACTURA'], 0, 4) . " "
                                                                            . "   AND MES_FACTURA = " . substr($row_fact_especial['PERIODO_FACTURA'], 4, 2));
                                                                        $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_fact_especial['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoFact()" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php substr($row_fact_especial['PERIODO_FACTURA'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php substr($row_fact_especial['PERIODO_FACTURA'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                            $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                . "  FROM periodos_facturacion_especiales_2 "
                                                                                . " WHERE ANO_FACTURA = " . substr($row_fact_especial['PERIODO_FACTURA'], 0, 4) . " "
                                                                                . "   AND MES_FACTURA = " . substr($row_fact_especial['PERIODO_FACTURA'], 4, 2));
                                                                            $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_fact_especial['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" data-toggle="tooltip" readonly="readonly" title="PERIODO" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoFact()" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="contribuyente">Contribuy.:</label>
                                                                <div class="col-xs-7">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_especial_editar'])) {
                                                                        $query_select_contribuyente = mysqli_query($connection, "SELECT * FROM contribuyentes_2 WHERE ID_CONTRIBUYENTE = " . $row_fact_especial['ID_CONTRIBUYENTE']);
                                                                        $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                                                                    ?>
                                                                        <input type="hidden" id="id_contribuyente" name="id_contribuyente" value="<?php echo $row_contribuyente['ID_CONTRIBUYENTE']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="contribuyente" name="contribuyente" value="<?php echo $row_contribuyente['NOMBRE']; ?>" placeholder="Contribuyente" required="required" data-toggle="tooltip" readonly="readonly" title="CONTRIBUYENTE" onclick="contribuyenteFact()" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) {
                                                                            $query_select_contribuyente = mysqli_query($connection, "SELECT * FROM contribuyentes_2 WHERE ID_CONTRIBUYENTE = " . $row_fact_especial['ID_CONTRIBUYENTE']);
                                                                            $row_contribuyente = mysqli_fetch_array($query_select_contribuyente);
                                                                        ?>
                                                                            <input type="hidden" id="id_contribuyente" name="id_contribuyente" value="<?php echo $row_contribuyente['ID_CONTRIBUYENTE']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="contribuyente" name="contribuyente" value="<?php echo $row_contribuyente['NOMBRE']; ?>" placeholder="Contribuyente" data-toggle="tooltip" readonly="readonly" title="CONTRIBUYENTE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_contribuyente" name="id_contribuyente" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="contribuyente" name="contribuyente" placeholder="Contribuyente" required="required" data-toggle="tooltip" readonly="readonly" title="CONTRIBUYENTE" onclick="contribuyenteFact()" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nit_operador">NIT:</label>
                                                                <div class="col-xs-3">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nit_contribuyente" name="nit_contribuyente" value="<?php echo $row_contribuyente['NIT_CONTRIBUYENTE']; ?>" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) { ?>
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="acuerdo_municipal">Acuerdo:</label>
                                                                <div class="col-xs-11">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="acuerdo_municipal" name="acuerdo_municipal" value="<?php echo $row_fact_especial['ACUERDO_MCPAL']; ?>" readonly="readonly" placeholder="Acuerdo Municipal" data-toogle="tooltip" title="ACUERDO MUNICIPAL" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="acuerdo_municipal" name="acuerdo_municipal" value="<?php echo $row_fact_especial['ACUERDO_MCPAL']; ?>" readonly="readonly" placeholder="Acuerdo Municipal" data-toogle="tooltip" title="ACUERDO MUNICIPAL" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="acuerdo_municipal" name="acuerdo_municipal" readonly="readonly" placeholder="Acuerdo Municipal" data-toogle="tooltip" title="ACUERDO MUNICIPAL" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-10">
                                                                    <div class="btn-group" data-toggle="buttons">
                                                                        <?php
                                                                        if (isset($_GET['id_fact_especial_editar']) || isset($_GET['id_fact_especial_eliminar'])) {
                                                                            if ($row_fact_especial['ID_TIPO_CLIENTE'] == 1) { ?>
                                                                                <label class="btn btn-primary cursor font background active" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - ANTIGUO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" checked="checked" value="1" required />Tipo Cliente - Antiguo
                                                                                </label>
                                                                                <label class="btn btn-primary cursor font background" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - NUEVO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" value="2" required />Tipo Cliente - Nuevo
                                                                                </label>
                                                                            <?php
                                                                            } else { ?>
                                                                                <label class="btn btn-primary cursor font background" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - ANTIGUO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" value="1" required />Tipo Cliente - Antiguo
                                                                                </label>
                                                                                <label class="btn btn-primary cursor font background active" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - NUEVO">
                                                                                    <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" checked="checked" value="2" required />Tipo Cliente - Nuevo
                                                                                </label>
                                                                            <?php
                                                                            }
                                                                        } else { ?>
                                                                            <label class="btn btn-primary cursor font background" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - ANTIGUO">
                                                                                <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" value="1" required />Tipo Cliente - Antiguo
                                                                            </label>
                                                                            <label class="btn btn-primary cursor font background" name="label_tipo_cliente" data-toogle="tooltip" title="TIPO CLIENTE - NUEVO">
                                                                                <input type="radio" class="form-control input-text input-sm" id="tipo_cliente" name="tipo_cliente" value="2" required />Tipo Cliente - Nuevo
                                                                            </label>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-12">
                                                                    <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN TIPO FACTURACIÓN / VALORES</span></h2>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="no_liq_vencidas">Liq. Venc.:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="no_liq_vencidas" data-toogle="tooltip" title="NO. LIQ. VENCIDAS">
                                                                        <?php
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="no_liq_vencidas" value="<?php echo $row_fact_especial['NO_LIQ_VENCIDAS'] ?>" maxlength="2" placeholder="No. Liq. Vencidas" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="no_liq_vencidas" value="<?php echo $row_fact_especial['NO_LIQ_VENCIDAS'] ?>" readonly="readonly" placeholder="No. Liq. Vencidas" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-hashtag"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="no_liq_vencidas" value="" maxlength="2" placeholder="No. Liq. Vencidas" required="required" onkeypress="return isNumeric(event)" />
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
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_liq_vencidas" value="<?php echo $row_fact_especial['VALOR_LIQ_VENCIDAS'] ?>" maxlength="25" placeholder="Valor Liq. Vencidas" onblur="convertValorLiqVencidas();" onchange="return convertValorLiqVencidas()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_liq_vencidas" value="<?php echo $row_fact_especial['VALOR_LIQ_VENCIDAS'] ?>" placeholder="Valor Liq. Vencidas" onblur="convertValorLiqVencidas();" onchange="return convertValorLiqVencidas()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            } else { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_liq_vencidas" value="" maxlength="25" placeholder="Valor Liq. Vencidas" required="required" onblur="convertValorLiqVencidas();" onchange="return convertValorLiqVencidas()" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_especial_editar'])) {
                                                                        switch ($row_fact_especial['ID_TIPO_FACTURACION']) {
                                                                            case '1': ?>
                                                                                <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "CONSUMO"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                            <?php
                                                                                break;
                                                                            case '2': ?>
                                                                                <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "SALARIOS"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                            <?php
                                                                                break;
                                                                            case '3': ?>
                                                                                <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "UVT"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                        <?php
                                                                                break;
                                                                        }
                                                                        ?>
                                                                        <input type="hidden" id="id_tipo_facturacion" name="id_tipo_facturacion" value="<?php echo $row_fact_especial['ID_TIPO_FACTURACION']; ?>" required="required" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) {
                                                                            switch ($row_fact_especial['ID_TIPO_FACTURACION']) {
                                                                                case '1': ?>
                                                                                    <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "CONSUMO"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                                <?php
                                                                                    break;
                                                                                case '2': ?>
                                                                                    <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "SALARIOS"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                                <?php
                                                                                    break;
                                                                                case '3': ?>
                                                                                    <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" value="<?php echo "UVT"; ?>" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                            <?php
                                                                                    break;
                                                                            }
                                                                            ?>
                                                                            <input type="hidden" id="id_tipo_facturacion" name="id_tipo_facturacion" value="<?php echo $row_fact_especial['ID_TIPO_FACTURACION']; ?>" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_tipo_facturacion" name="id_tipo_facturacion" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="tipo_facturacion" name="tipo_facturacion" readonly="readonly" placeholder="Tipo Facturación" data-toogle="tooltip" title="TIPO FACTURACIÓN" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="tarifa">Tarifa:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="input-group" id="tarifa" data-toogle="tooltip" title="TARIFA">
                                                                        <?php
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <?php
                                                                                if ($row_fact_especial['ID_TIPO_FACTURACION'] == 1) { ?>
                                                                                    <span id="tarifa_icon" class="fas fa-percentage"></span>
                                                                                <?php
                                                                                } else { ?>
                                                                                    <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="tarifa" value="<?php echo $row_fact_especial['TARIFA'] ?>" maxlength="5" readonly="readonly" placeholder="Tarifa" onkeypress="return isDouble(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <?php
                                                                                    if ($row_fact_especial['ID_TIPO_FACTURACION'] == 1) { ?>
                                                                                        <span id="tarifa_icon" class="fas fa-percentage"></span>
                                                                                    <?php
                                                                                    } else { ?>
                                                                                        <span id="tarifa_icon" class="fas fa-hashtag"></span>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="tarifa" value="<?php echo $row_fact_especial['TARIFA'] ?>" readonly="readonly" placeholder="Tarifa" onkeypress="return isDouble(event)" />
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
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_tarifa" value="<?php echo $row_fact_especial['VALOR_TARIFA'] ?>" maxlength="25" placeholder="Valor Tarifa" onblur="convertValorTarifa(); calcularValorFacturaConsumo();" onchange="return convertValorTarifa()" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_tarifa" value="<?php echo $row_fact_especial['VALOR_TARIFA'] ?>" placeholder="Valor Tarifa" onblur="convertValorTarifa(); calcularValorFacturaConsumo();" onchange="return convertValorTarifa()" onkeypress="return isNumeric(event)" />
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
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_fact_especial['VALOR_FACTURA'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Factura" onblur="return convertValorFactura()" onchange="return convertValorFactura()" />
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                <span class="input-group-addon">
                                                                                    <span class="fas fa-dollar-sign"></span>
                                                                                </span>
                                                                                <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_fact_especial['VALOR_FACTURA'] ?>" readonly="readonly" placeholder="Valor Factura" onblur="return convertValorFactura()" onchange="return convertValorFactura()" />
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
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="comercializador">Comercial.:</label>
                                                                <div class="col-xs-7">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_especial_editar'])) {
                                                                        $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = " . $row_fact_especial['ID_COMERCIALIZADOR']);
                                                                        $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                                                    ?>
                                                                        <input type="hidden" id="id_comercializador" name="id_comercializador" value="<?php echo $row_comercializador['ID_COMERCIALIZADOR']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="comercializador" name="comercializador" value="<?php echo $row_comercializador['NOMBRE']; ?>" placeholder="Comercializador" required="required" data-toggle="tooltip" readonly="readonly" title="COMERCIALIZADOR" onclick="comercializadorFact(0)" />
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) {
                                                                            $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = " . $row_fact_especial['ID_COMERCIALIZADOR']);
                                                                            $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                                                        ?>
                                                                            <input type="hidden" id="id_comercializador" name="id_comercializador" value="<?php echo $row_comercializador['ID_COMERCIALIZADOR']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="comercializador" name="comercializador" value="<?php echo $row_comercializador['NOMBRE']; ?>" placeholder="Comercializador" data-toggle="tooltip" readonly="readonly" title="COMERCIALIZADOR" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_comercializador" name="id_comercializador" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="comercializador" name="comercializador" placeholder="Comercializador" required="required" data-toggle="tooltip" readonly="readonly" title="COMERCIALIZADOR" onclick="comercializadorFact(0)" />
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="facturado_por">Fact. Por:</label>
                                                                <div class="col-xs-3">
                                                                    <div class="styled-select">
                                                                        <?php
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="facturado_por" name="facturado_por" data-toggle="tooltip" title="FACTURADO POR" required>
                                                                                <?php
                                                                            } else {
                                                                                if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                    <select class="form-control input-text input-sm" id="facturado_por" name="facturado_por" disabled="disabled" data-toggle="tooltip" title="FACTURADO POR" required>
                                                                                    <?php
                                                                                } else { ?>
                                                                                        <select class="form-control input-text input-sm" id="facturado_por" name="facturado_por" data-toggle="tooltip" title="FACTURADO POR" required>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                                    ?>
                                                                                    <option value="" selected="selected">-</option>
                                                                                    <option value="1">COMERCIALIZADOR</option>
                                                                                    <option value="2">CUENTA DE COBRO</option>
                                                                                    <option value="3">RESOLUCIÓN</option>
                                                                                    <?php
                                                                                    if (isset($_GET['id_fact_especial_editar']) || isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                        <input type="hidden" id="facturado_por_hidden" name="facturado_por_hidden" value="<?php echo $row_fact_especial['ID_FACTURADO_POR']; ?>" />
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                        </select>
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
                                                                        if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"><?php echo trim($row_fact_especial['OBSERVACIONES']); ?></textarea>
                                                                            <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase; background-color: #FFFFFF;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" readonly="readonly" title="OBSERVACIONES"><?php echo trim($row_fact_especial['OBSERVACIONES']); ?></textarea>
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
                                                                    if (isset($_GET['id_fact_especial_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_especial" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Factura Esp.</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Facturacion_Especiales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_especial_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_especial" type="button" data-toggle="modal" data-target="#modalEliminarFactEsp"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Factura Esp.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Facturacion_Especiales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_especial" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Factura Esp.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldFacturaEspecial();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
<script>
    function resetFieldFacturaEspecial() {
        $('label[name=label_tipo_cliente]').attr("disabled", false);
        $('label[name=label_tipo_cliente]').attr("readonly", false);
        $('label[name=label_tipo_cliente]').removeClass("active");
        $("#id_departamento").val("");
        $("#id_municipio").val("");
        $("#id_ano_fact").val("");
        $("#id_mes_fact").val("");
        $("#id_contribuyente").val("");
        $("#id_comercializador").val("");
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

    function calcularValorFacturaSalarios(tarifa, salario_minimo) {
        $("input[name=valor_factura]").val(Math.round(tarifa * salario_minimo));
    }

    function calcularValorFacturaConsumo() {
        var tarifa = $("input[name=tarifa]").val();
        var valor_tarifa = $("input[name=valor_tarifa]").val();
        //alert("Tarifa: " + tarifa + ". Valor: " + valor_tarifa + ". Total: " + Math.round((tarifa / 100) * valor_tarifa.replace(/,/g, "")));
        $("input[name=valor_factura]").val(Math.round((tarifa / 100) * valor_tarifa.replace(/,/g, "")));
        convertValorFactura();
    }

    function generarFacturaClienteEsp(id_fact_especial) {
        window.open('Combos/Generar_Factura_Cliente_Especial.php?id_fact_especial=' + id_fact_especial, 'Popup', 'width=750, height=600');
    }
    //POPUPS
    function infoTipoDepartamento(id_consulta, id_departamento, departamento) {
        if (id_consulta == 1) {
            $("#id_departamento").val(id_departamento);
            $("#departamento").val(departamento);
            $("#id_municipio").val("");
            $("#municipio").val("");
            $("#id_ano_fact").val("");
            $("#id_mes_fact").val("");
            $("#periodo_factura").val("");
            $("#id_contribuyente").val("");
            $("#contribuyente").val("");
            $("#nit_contribuyente").val("");
            $("#acuerdo_municipal").val("");
            $("#municipio").focus();
        }
    }

    function tipoDepartamento(id_consulta) {
        window.open("Combos/Tipo_Departamento_Visita.php?id_consulta=" + id_consulta, "Popup", "width=400, height=500");
    }

    function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
        if (id_consulta == 1) {
            $("#id_municipio").val(id_municipio);
            $("#municipio").val(municipio);
            $("#id_ano_fact").val("");
            $("#id_mes_fact").val("");
            $("#periodo_factura").val("");
            $("#id_contribuyente").val("");
            $("#contribuyente").val("");
            $("#nit_contribuyente").val("");
            $("#acuerdo_municipal").val("");
            $("#periodo_factura").focus();
        }
    }

    function tipoMunicipio(id_consulta) {
        var id_departamento;
        if (id_consulta == 1) {
            id_departamento = $("#id_departamento").val();
        }
        window.open("Combos/Tipo_Municipio_Visita.php?id_departamento=" + id_departamento + "&id_consulta=" + id_consulta, "Popup", "width=400, height=500");
    }

    function infoperiodoFact(id_ano, id_mes, periodo) {
        $("#id_ano_fact").val(id_ano);
        $("#id_mes_fact").val(id_mes);
        $("#periodo_factura").val(periodo + " - " + id_ano);
        $("#id_contribuyente").val("");
        $("#contribuyente").val("");
        $("#nit_contribuyente").val("");
        $("#acuerdo_municipal").val("");
        $("#contribuyente").focus();
        $("#consecutivo_fact").val("00" + id_mes + id_ano);
    }

    function periodoFact() {
        window.open("Combos/Periodo_Fact_Esp.php", "Popup", "width=400, height=500");
    }

    function infoContribuyenteFact(id_contribuyente, contribuyente, nit_contribuyente, acuerdo_municipal, tipo_facturacion, tarifa, salario_minimo, consecutivo) {
        $("#id_contribuyente").val(id_contribuyente);
        $("#contribuyente").val(contribuyente);
        $("#nit_contribuyente").val(nit_contribuyente);
        $("#acuerdo_municipal").val(acuerdo_municipal);
        if (id_contribuyente.length == 1) {
            $("#consecutivo_fact").val("0" + id_contribuyente + $("#id_mes_fact").val() + $("#id_ano_fact").val() + "-" + consecutivo);
        } else {
            $("#consecutivo_fact").val(id_contribuyente + $("#id_mes_fact").val() + $("#id_ano_fact").val() + "-" + consecutivo);
        }
        switch (tipo_facturacion) {
            case '0':
                $("#id_tipo_facturacion").val("");
                $("#tipo_facturacion").val("");
                $("input[name=valor_tarifa]").val("");
                $("#tarifa_icon").removeClass('fas fa-percentage');
                $("#tarifa_icon").addClass('fas fa-hashtag');
                $("input[name=valor_tarifa]").attr("readonly", false);
                $("input[name=valor_factura]").attr("readonly", false);
                $("input[name=tarifa]").val(tarifa);
                $("input[name=no_liq_vencidas]").focus();
                break;
            case '1':
                $("#id_tipo_facturacion").val(1);
                $("#tipo_facturacion").val("CONSUMO");
                $("input[name=valor_tarifa]").val("");
                $("input[name=valor_factura]").val("");
                $("#tarifa_icon").removeClass('fas fa-hashtag');
                $("#tarifa_icon").addClass('fas fa-percentage');
                $("input[name=valor_tarifa]").attr("readonly", false);
                $("input[name=valor_factura]").attr("readonly", false);
                $("input[name=tarifa]").val(tarifa);
                $("input[name=no_liq_vencidas]").focus();
                break;
            case '2':
                $("#id_tipo_facturacion").val(2);
                $("#tipo_facturacion").val("SALARIOS");
                $("input[name=valor_tarifa]").val(salario_minimo);
                convertValorTarifa();
                calcularValorFacturaSalarios(tarifa, salario_minimo);
                convertValorFactura();
                $("#tarifa_icon").removeClass('fas fa-percentage');
                $("#tarifa_icon").addClass('fas fa-hashtag');
                $("input[name=valor_tarifa]").attr("readonly", true);
                $("input[name=valor_factura]").attr("readonly", true);
                $("input[name=tarifa]").val(tarifa);
                $("input[name=no_liq_vencidas]").focus();
                break;
            case '3':
                $("#id_tipo_facturacion").val(3);
                $("#tipo_facturacion").val("UVT");
                $("input[name=valor_tarifa]").val(salario_minimo);
                convertValorTarifa();
                calcularValorFacturaSalarios(tarifa, salario_minimo);
                convertValorFactura();
                $("#tarifa_icon").removeClass('fas fa-percentage');
                $("#tarifa_icon").addClass('fas fa-hashtag');
                $("input[name=valor_tarifa]").attr("readonly", true);
                $("input[name=valor_factura]").attr("readonly", true);
                $("input[name=tarifa]").val(tarifa);
                $("input[name=no_liq_vencidas]").focus();
                break;
            case '4':
                $("#id_tipo_facturacion").val(4);
                $("#tipo_facturacion").val("COMERCIAL");
                $("input[name=valor_tarifa]").val(salario_minimo);
                convertValorTarifa();
                calcularValorFacturaSalarios(tarifa, salario_minimo);
                convertValorFactura();
                $("#tarifa_icon").removeClass('fas fa-percentage');
                $("#tarifa_icon").addClass('fas fa-hashtag');
                $("input[name=valor_tarifa]").attr("readonly", false);
                $("input[name=valor_factura]").attr("readonly", false);
                $("input[name=tarifa]").val(tarifa);
                $("input[name=no_liq_vencidas]").focus();
                break;
        }
    }

    function contribuyenteFact() {
        var id_departamento = $("#id_departamento").val();
        var id_municipio = $("#id_municipio").val();
        window.open("Combos/Contribuyente.php?id_departamento=" + id_departamento + "&id_municipio=" + id_municipio, "Popup", "width=700, height=500");
    }

    function infoComercializadorFact(id_comercializador, comercializador, nit_comercializador, consulta) {
        if (consulta == 0) {
            $("#id_comercializador").val(id_comercializador);
            $("#comercializador").val(comercializador);
        }
    }

    function comercializadorFact(consulta) {
        window.open("Combos/Comercializador.php?consulta=" + consulta, "Popup", "width=550, height=500");
    }
    //END POPUPS
</script>
<script>
    $(document).ready(function() {
        $("#estado_factura_busqueda").focus();
        var id_fact_especial_editar = $("#id_fact_especial_editar_hidden").val();
        var id_fact_especial_eliminar = $("#id_fact_especial_eliminar_hidden").val();
        if (id_fact_especial_editar != undefined) {
            convertValorLiqVencidas();
            convertValorTarifa();
            convertValorFactura();
            $(".nav-pills a[href='#crear_fact_especiales_tab']").tab("show");
            $(".nav-pills a[href='#crear_fact_especiales_tab']").text("Actualizar Fact. Cliente Especial");
            $("#estado_factura").val($("#estado_factura_hidden").val());
            $("#facturado_por").val($("#facturado_por_hidden").val());
        } else {
            if (id_fact_especial_eliminar != undefined) {
                convertValorLiqVencidas();
                convertValorTarifa();
                convertValorFactura();
                $(".nav-pills a[href='#crear_fact_especiales_tab']").tab("show");
                $(".nav-pills a[href='#crear_fact_especiales_tab']").text("Eliminar Fact. Cliente Especial");
                $("#estado_factura").val($("#estado_factura_hidden").val());
                $("#facturado_por").val($("#facturado_por_hidden").val());
            }
        }
        $("#estado_factura_busqueda").change(function() {
            $("#buscar_factura").focus();
        });
        $("#buscar_factura").keypress(function(e) {
            if (e.which == 13) {
                var busqueda_factura;
                var estado_factura_busqueda = $("#estado_factura_busqueda").val();
                if ($(this).val() == "") {
                    busqueda_factura = "";
                } else {
                    busqueda_factura = $(this).val().toUpperCase();
                }
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Fact_Esp.php",
                    dataType: "json",
                    data: "sw=1&busqueda_factura=" + busqueda_factura + "&estado_factura_busqueda=" + estado_factura_busqueda,
                    success: function(data) {
                        $("#pagination-fact_esp").twbsPagination('destroy');
                        $("#pagination-fact_esp").twbsPagination({
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
                                    url: "Modelo/Cargar_Fact_Esp.php",
                                    dataType: "json",
                                    data: "sw=1&busqueda_factura=" + data[1] + "&estado_factura_busqueda=" + estado_factura_busqueda + "&page=" + page,
                                    success: function(data) {
                                        $("#loading-spinner").css('display', 'none');
                                        $("#resultado_fact_esp").html(data[0]);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
        $("#fecha_entrega").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#fecha_vencimiento").datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $("#tab_info_fact_especiales").on("shown.bs.tab", function() {
            $("#estado_factura_busqueda").focus();
        });
        $("#tab_crear_fact_especiales").on("shown.bs.tab", function() {
            $("#departamento").focus();
        });
        $("#tab_info_fact_especiales").on("click", function() {
            $("#estado_factura_busqueda").focus();
        });
        $("#tab_crear_fact_especiales").on("click", function() {
            $("#departamento").focus();
        });
        if (id_fact_especial_editar == undefined && id_fact_especial_eliminar == undefined) {
            $("#btn_crear_factura_especial").click(function() {
                var fecha_factura = $("input[name=fecha_factura]").val();
                var fecha_entrega = $("input[name=fecha_entrega]").val();
                var fecha_vencimiento = $("input[name=fecha_vencimiento]").val();
                var estado_factura = $("#estado_factura").val();
                var consecutivo_fact = $("#consecutivo_fact").val();
                var departamento = $("#id_departamento").val();
                var municipio = $("#id_municipio").val();
                var periodo_factura = $("#id_ano_fact").val() + "" + $("#id_mes_fact").val();
                var contribuyente = $("#id_contribuyente").val();
                var acuerdo_municipal = $("#acuerdo_municipal").val();
                var tipo_cliente = $("input[name=tipo_cliente]:checked").val();
                var no_liq_vencidas = $("input[name=no_liq_vencidas]").val();
                var valor_liq_vencidas = $("input[name=valor_liq_vencidas]").val();
                if (valor_liq_vencidas != "") {
                    valor_liq_vencidas = valor_liq_vencidas.replace(/,/g, "");
                }
                var tipo_facturacion = $("#id_tipo_facturacion").val();
                var tarifa = $("input[name=tarifa]").val();
                var valor_tarifa = $("input[name=valor_tarifa]").val();
                if (valor_tarifa != "") {
                    valor_tarifa = valor_tarifa.replace(/,/g, "");
                }
                var valor_factura = $("input[name=valor_factura]").val();
                if (valor_factura != "") {
                    valor_factura = valor_factura.replace(/,/g, "");
                }
                var comercializador = $("#id_comercializador").val();
                var facturado_por = $("#facturado_por").val();
                var observaciones = $("#observaciones").val();
                if (fecha_entrega.length == 0) {
                    $("input[name=fecha_entrega]").focus();
                    return false;
                }
                if (estado_factura.length == 0) {
                    $("#estado_factura").focus();
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
                if (periodo_factura.length == 0) {
                    $("#periodo_factura").focus();
                    return false;
                }
                if (contribuyente.length == 0) {
                    $("#contribuyente").focus();
                    return false;
                }
                if (tipo_cliente == undefined) {
                    $("#tipo_cliente").focus();
                    return false;
                }
                if (tipo_facturacion.length == 0) {
                    $("#tipo_facturacion").focus();
                    return false;
                }
                if (no_liq_vencidas.length == 0) {
                    $("input[name=no_liq_vencidas]").focus();
                    return false;
                }
                if (valor_liq_vencidas.length == 0) {
                    $("input[name=valor_liq_vencidas]").focus();
                    return false;
                }
                if (tarifa.length == 0) {
                    $("input[name=tarifa]").focus();
                    return false;
                }
                if (valor_tarifa.length == 0) {
                    $("input[name=valor_tarifa]").focus();
                    return false;
                }
                if (comercializador.length == 0) {
                    $("#comercializador").focus();
                    return false;
                }
                if (facturado_por.length == 0) {
                    $("#facturado_por").focus();
                    return false;
                }
                $("#btn_crear_factura_especial").attr("disabled", true);
                $("#btn_crear_factura_especial").css("pointer-events", "none");
                $("#btn_crear_factura_especial").html("Creando Fact. Cliente Especial...");
                $.ajax({
                    type: "POST",
                    data: "fecha_factura=" + fecha_factura +
                        "&fecha_entrega=" + fecha_entrega +
                        "&fecha_vencimiento=" + fecha_vencimiento +
                        "&estado_factura=" + estado_factura +
                        "&consecutivo_fact=" + consecutivo_fact +
                        "&departamento=" + departamento +
                        "&municipio=" + municipio +
                        "&periodo_factura=" + periodo_factura +
                        "&contribuyente=" + contribuyente +
                        "&acuerdo_municipal=" + acuerdo_municipal +
                        "&tipo_cliente=" + tipo_cliente +
                        "&no_liq_vencidas=" + no_liq_vencidas +
                        "&valor_liq_vencidas=" + valor_liq_vencidas +
                        "&tipo_facturacion=" + tipo_facturacion +
                        "&tarifa=" + tarifa +
                        "&valor_tarifa=" + valor_tarifa +
                        "&valor_factura=" + valor_factura +
                        "&comercializador=" + comercializador +
                        "&facturado_por=" + facturado_por +
                        "&observaciones=" + observaciones,
                    url: "Modelo/Crear_Fact_Esp.php",
                    success: function(data) {
                        //alert(data);
                        //$("#observaciones").val(data);
                        document.location.href = 'Facturacion_Especiales.php?id_fact_especial_editar=' + data;
                    }
                });
            });
        }
        $("#eliminar_fact_esp").click(function() {
            $("#crear_fact_especial").submit();
        });
        $.ajax({
            type: "POST",
            url: "Modelo/Cargar_Paginacion_Fact_Esp.php",
            dataType: "json",
            data: "sw=0",
            success: function(data) {
                $("#pagination-fact_esp").twbsPagination('destroy');
                $("#pagination-fact_esp").twbsPagination({
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
                            url: "Modelo/Cargar_Fact_Esp.php",
                            dataType: "json",
                            data: "sw=0&page=" + page,
                            success: function(data) {
                                $("#loading-spinner").css('display', 'none');
                                $("#resultado_fact_esp").html(data[0]);
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
        $('select[name=estado_factura_busqueda]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=buscar_factura]').tooltip({
            container: "body",
            placement: "top"
        });
        $('.btn_print').tooltip({
            container: "body",
            placement: "left"
        });
        $('input[type=text][name=departamento]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=municipio]').tooltip({
            container: "body",
            placement: "top"
        });
        $('label[name=label_tipo_cliente]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=consecutivo_fact]').tooltip({
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
        $('input[type=text][name=acuerdo_municipal]').tooltip({
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
        $('#fecha_factura').tooltip({
            container: "body",
            placement: "right"
        });
        $('#fecha_entrega').tooltip({
            container: "body",
            placement: "top"
        });
        $('#valor_factura').tooltip({
            container: "body",
            placement: "top"
        });
        $('#fecha_vencimiento').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=periodo_factura]').tooltip({
            container: "body",
            placement: "top"
        });
        $('input[type=text][name=comercializador]').tooltip({
            container: "body",
            placement: "top"
        });
        $('select[name=estado_factura]').tooltip({
            container: "body",
            placement: "top"
        });
        $('select[name=facturado_por]').tooltip({
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