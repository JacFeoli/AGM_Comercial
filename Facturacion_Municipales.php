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
        <title>AGM - Facturación Aportes Municipales</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
        <style type="text/css">
            .text-divider{margin: 2em 0; line-height: 0; text-align: center;}
            .text-divider span{background-color: #D0DEE7; padding: 1em;}
            .text-divider:before{ content: " "; display: block; border-top: 1px solid #A9BDC8;}
        </style>
    </head>
    <!--Seleccionar Factura Municipales Modal-->
    <div class="modal fade animate__animated animate__zoomIn animate__faster" id="modalSeleccionarFactMun" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Seleccionar Factura Aportes Municipales</h4>
                </div>
                <div class="modal-body">
                    <form style="background-color: #D0DEE7; border: 1px solid #A9BDC8;" class="form-horizontal row-bottom-buffer row-top-buffer" id="seleccionar_fact_municipal" name="seleccionar_fact_municipal">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <?php
                                    $query_select_fact_mun = mysqli_query($connection, "SELECT VALOR_CONCEPTO, VALOR_CARTERA "
                                                                                     . "  FROM alcaldias_2 "
                                                                                     . " WHERE ID_COD_DPTO = 1 "
                                                                                     . "   AND ID_COD_MPIO = 13");
                                    while ($row_fact_mun = mysqli_fetch_assoc($query_select_fact_mun)) { ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="fact_mun" id="fact_mun_<?php echo $row_fact_mun['VALOR_CONCEPTO']; ?>" value="<?php echo $row_fact_mun['VALOR_CONCEPTO']; ?>" />
                                            <input type="hidden" name="fact_mun_hidden_<?php echo $row_fact_mun['VALOR_CONCEPTO'] ?>" id="fact_mun_hidden_<?php echo $row_fact_mun['VALOR_CONCEPTO']; ?>" value="<?php echo $row_fact_mun['VALOR_CARTERA']; ?>" />
                                            <label class="form-check-label" for="fact_mun_<?php echo $row_fact_mun['VALOR_CONCEPTO']; ?>"><b style="font-size: 15px;">$ </b><?php echo number_format($row_fact_mun['VALOR_CONCEPTO'],0, ',', '.') ?></label>
                                        </div>
                                    <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>-->
                    <button type="button" class="btn btn-primary btn-sm font background cursor" id="seleccionar_fact_mun" name="seleccionar_fact_mun" data-dismiss="modal"><i class="fas fa-check fa-fw"></i> Seleccionar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Seleccionar Factura Municipales Modal-->
    <!--Eliminar Factura Municipales Modal-->
    <div class="modal fade" id="modalEliminarFactMun" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Factura Aportes Municipales</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar la Factura?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_fact_mun" name="eliminar_fact_mun"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Factura Municipales Modal-->
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Home.php");?>
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
                                                                                <a href='Facturacion_Municipales.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check-alt"></i>
                                                                                    <span>Facturación</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Recaudo_Municipales.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check"></i>
                                                                                    <span>Recaudo</span>
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
                                            <h1>Facturación Aportes Municipales</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_fact_municipales_tab" id="tab_info_fact_municipales" aria-controls="informacion_fact_municipales_tab" role="tab" data-toggle="tab">Información Fact. Aportes Municipales</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_fact_municipales_tab" id="tab_crear_fact_municipales" aria-controls="crear_fact_municipales_tab" role="tab" data-toggle="tab">Crear Fact. Aporte Municipal</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_fact_municipales_tab">
                                                    <h2 class="text-divider"><span style="background-color: #FFFFFF;">FILTROS / BUSQUEDA</span></h2>
                                                    <br />
                                                    <div class="form-group">
                                                        <div class="col-xs-3">
                                                            <select class="form-control input-text input-sm" id="estado_factura_busqueda" name="estado_factura_busqueda" data-toggle="tooltip" title="ESTADO">
                                                                <option value="" selected="selected">-</option>
                                                                <option value="1">ENTREGADO</option>
                                                                <!--<option value="6">PAGADO POR ACUERDO</option>-->
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
                                                        $query_select_fact_mun = "SELECT * FROM facturacion_municipales_2 ORDER BY ID_COD_DPTO, ID_COD_MPIO, PERIODO_FACTURA DESC";
                                                        $sql_fact_mun = mysqli_query($connection, $query_select_fact_mun);
                                                        if (mysqli_num_rows($sql_fact_mun) != 0) {
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
                                                                            echo "<th width=5%>IMPRIMIR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_fact_mun'>";
                                                                        
                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                            echo "<p></p>";
                                                            echo "<span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = ENTREGADO.</span>";
                                                            //echo "&nbsp;<span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO POR ACUERDO.</span>";
                                                            echo "&nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE ENVIO.</span>";
                                                            echo "&nbsp;<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Facturas Aportes Municipales Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-fact_mun"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_fact_municipales_tab">
                                                    <?php
                                                        if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_municipal" name="crear_fact_municipal" action="<?php echo "Modelo/Crear_Fact_Mun.php?editar=" . $_GET['id_fact_municipal_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_fact_municipal = mysqli_query($connection, "SELECT * FROM facturacion_municipales_2 WHERE ID_FACTURACION = " . $_GET['id_fact_municipal_editar']);
                                                            $row_fact_municipal = mysqli_fetch_array($query_select_fact_municipal);
                                                        ?>
                                                            <input type="hidden" id="id_fact_municipal_editar_hidden" name="id_fact_municipal_editar_hidden" value="<?php echo $row_fact_municipal['ID_FACTURACION']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_municipal" name="crear_fact_municipal" action="<?php echo "Modelo/Crear_Fact_Mun.php?eliminar=" . $_GET['id_fact_municipal_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_fact_municipal = mysqli_query($connection, "SELECT * FROM facturacion_municipales_2 WHERE ID_FACTURACION = " . $_GET['id_fact_municipal_eliminar']);
                                                                $row_fact_municipal = mysqli_fetch_array($query_select_fact_municipal);
                                                            ?>
                                                                <input type="hidden" id="id_fact_municipal_eliminar_hidden" name="id_fact_municipal_eliminar_hidden" value="<?php echo $row_fact_municipal['ID_FACTURACION']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_municipal" name="crear_fact_municipal" action="<?php echo "Modelo/Crear_Fact_Mun.php"; ?>" method="post">
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
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="<?php echo $row_fact_municipal['FECHA_FACTURA'] ?>" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="<?php echo $row_fact_municipal['FECHA_FACTURA'] ?>" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for=""></label>
                                                            <div class="col-xs-2"></div>
                                                            <?php
                                                                if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                    <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="revisado_por">Revis. Por:</label>
                                                                    <div class="col-xs-4">
                                                                        <div class="styled-select">
                                                                            <select class="form-control input-text input-sm" id="revisado_por" name="revisado_por" data-toggle="tooltip" title="REVISADO POR" required>
                                                                                <option value="" selected="selected">-</option>
                                                                                <option value="2">MELISSA ESCORCIA VARELA</option>
                                                                                <?php
                                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                                        <input type="hidden" id="revisado_por_hidden" name="revisado_por_hidden" value="<?php echo $row_fact_municipal['ID_USUARIO_REVISADO']; ?>" />
                                                                                    <?php
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_entrega">Fecha Entr.</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_entrega" data-toogle="tooltip" title="FECHA ENTREGA">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_entrega" value="<?php echo $row_fact_municipal['FECHA_ENTREGA'] ?>" placeholder="Fecha Entrega" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_entrega" value="<?php echo $row_fact_municipal['FECHA_ENTREGA'] ?>" placeholder="Fecha Entrega" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_vencimiento" value="<?php echo $row_fact_municipal['FECHA_VENCIMIENTO'] ?>" placeholder="Fecha Vencimiento" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_vencimiento" value="<?php echo $row_fact_municipal['FECHA_VENCIMIENTO'] ?>" placeholder="Fecha Vencimiento" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                                        if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="estado_factura" name="estado_factura" data-toggle="tooltip" title="ESTADO FACTURA" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
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
                                                                            <!--<option value="6">PAGADO POR ACUERDO</option>-->
                                                                            <option value="2">PENDIENTE DE ENVÍO</option>
                                                                            <option value="3">RECLAMADA</option>
                                                                            <?php
                                                                                if (isset($_GET['id_fact_municipal_editar']) || isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                                    <input type="hidden" id="estado_factura_hidden" name="estado_factura_hidden" value="<?php echo $row_fact_municipal['ESTADO_FACTURA']; ?>" />
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
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="consecutivo_fact">C.C. Fact:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <input style="text-align: center; color: red; font-style: italic; font-size: 13px;" type="text" class="form-control input-text input-sm" id="consecutivo_fact" name="consecutivo_fact" value="<?php echo $row_fact_municipal['CONSECUTIVO_FACT']; ?>" readonly="readonly" placeholder="Consecutivo Fact." data-toogle="tooltip" title="CONSECUTIVO FACT." />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                            <input style="text-align: center; color: red; font-style: italic; font-size: 13px;" type="text" class="form-control input-text input-sm" id="consecutivo_fact" name="consecutivo_fact" value="<?php echo $row_fact_municipal['CONSECUTIVO_FACT']; ?>" readonly="readonly" placeholder="Consecutivo Fact." data-toogle="tooltip" title="CONSECUTIVO FACT." />
                                                                        <?php
                                                                        } else {
                                                                            $query_select_consecutivo_fact = mysqli_query($connection, "SELECT MAX(ID_FACTURACION) AS ID_FACTURACION FROM facturacion_municipales_2");
                                                                            $row_consecutivo_fact = mysqli_fetch_array($query_select_consecutivo_fact);
                                                                            if ($row_consecutivo_fact['ID_FACTURACION'] == null) {
                                                                                $consecutivo = "CC-" . 1;
                                                                            } else {
                                                                                $consecutivo = "CC-" . ($row_consecutivo_fact['ID_FACTURACION'] + 1);
                                                                            }
                                                                            ?>
                                                                            <input style="text-align: center; color: red; font-style: italic; font-size: 13px;" type="text" class="form-control input-text input-sm" id="consecutivo_fact" name="consecutivo_fact" value="<?php echo $consecutivo; ?>" readonly="readonly" placeholder="Consecutivo Fact." data-toogle="tooltip" title="CONSECUTIVO FACT." />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-xs-4"></div>
                                                            <div style="text-align: right;" class="col-xs-4">
                                                                <?php
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <a onClick="generarFacturaAportesMun(<?php echo $_GET['id_fact_municipal_editar'] ?>)"><button class="btn_print" type="button" data-tooltip='tooltip' title="IMPRIMIR FACTURA"><img src="Images/print_2.png" width="16" height="16" /></button></a>
                                                                    <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento">Dpto:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_fact_municipal_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_municipal['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_municipal['ID_COD_DPTO']);
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
                                                                    if (isset($_GET['id_fact_municipal_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_municipal['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_fact_municipal['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_municipal['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_fact_municipal['ID_COD_MPIO']);
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
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php echo substr($row_fact_municipal['PERIODO_FACTURA'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php echo substr($row_fact_municipal['PERIODO_FACTURA'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                            $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                    . "  FROM periodos_facturacion_municipales_2 "
                                                                                                                                    . " WHERE ANO_FACTURA = " . substr($row_fact_municipal['PERIODO_FACTURA'], 0, 4) . " "
                                                                                                                                    . "   AND MES_FACTURA = " . substr($row_fact_municipal['PERIODO_FACTURA'], 4, 2));
                                                                            $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_fact_municipal['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoFact()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php substr($row_fact_municipal['PERIODO_FACTURA'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php substr($row_fact_municipal['PERIODO_FACTURA'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                                $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                        . "  FROM periodos_facturacion_municipales_2 "
                                                                                                                                        . " WHERE ANO_FACTURA = " . substr($row_fact_municipal['PERIODO_FACTURA'], 0, 4) . " "
                                                                                                                                        . "   AND MES_FACTURA = " . substr($row_fact_municipal['PERIODO_FACTURA'], 4, 2));
                                                                                $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_fact_municipal['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" data-toggle="tooltip" readonly="readonly" title="PERIODO" />
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
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN VALORES FACTURA</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="no_cc_vencidas">C.C. Venc.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="no_cc_vencidas" data-toogle="tooltip" title="NO. CUENTAS VENCIDAS">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-hashtag"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="no_cc_vencidas" value="<?php echo $row_fact_municipal['NO_CC_VENCIDAS'] ?>" maxlength="5" placeholder="No. Cuentas Vencidas" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="no_cc_vencidas" value="<?php echo $row_fact_municipal['NO_CC_VENCIDAS'] ?>" readonly="readonly" placeholder="No. Cuentas Vencidas" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="no_cc_vencidas" value="" maxlength="5" placeholder="No. Cuentas Vencidas" required="required" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 0;" class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_factura">Valor Fact.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="valor_factura" data-toogle="tooltip" title="VALOR FACTURA">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_fact_municipal['VALOR_FACTURA'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Factura" onblur="return convertValorFactura()" onchange="return convertValorFactura()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_fact_municipal['VALOR_FACTURA'] ?>" readonly="readonly" placeholder="Valor Factura" onblur="return convertValorFactura()" onchange="return convertValorFactura()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_factura" value="" maxlength="25" readonly="readonly" placeholder="Valor Factura" required="required" onblur="return convertValorFactura()" onchange="return convertValorFactura()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-4"></div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_cartera">Valor Cart.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="valor_cartera" data-toogle="tooltip" title="VALOR CARTERA">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <?php
                                                                            /*$query_select_valor_cartera = mysqli_query($connection, "SELECT VALOR_CARTERA "
                                                                                                                                  . "  FROM alcaldias_2 "
                                                                                                                                  . " WHERE VALOR_CONCEPTO = " . $row_fact_municipal['VALOR_FACTURA'] . " "
                                                                                                                                  . "   AND ID_COD_DPTO = " . $row_fact_municipal['ID_COD_DPTO'] . " "
                                                                                                                                  . "   AND ID_COD_MPIO = " . $row_fact_municipal['ID_COD_MPIO']);*/
                                                                            $query_select_valor_cartera = mysqli_query($connection, "SELECT VALOR_CARTERA "
                                                                                                                                  . "  FROM alcaldias_2 "
                                                                                                                                  . " WHERE ID_COD_DPTO = " . $row_fact_municipal['ID_COD_DPTO'] . " "
                                                                                                                                  . "   AND ID_COD_MPIO = " . $row_fact_municipal['ID_COD_MPIO']);
                                                                            $row_valor_cartera = mysqli_fetch_array($query_select_valor_cartera);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_cartera" value="<?php echo $row_valor_cartera['VALOR_CARTERA'] ?>" maxlength="25" readonly="readonly" placeholder="Valor Cartera" onblur="return convertValorCartera()" onchange="return convertValorCartera()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <?php
                                                                                $query_select_valor_cartera = mysqli_query($connection, "SELECT VALOR_CARTERA "
                                                                                                                                      . "  FROM alcaldias_2 "
                                                                                                                                      . " WHERE VALOR_CONCEPTO = " . $row_fact_municipal['VALOR_FACTURA'] . " "
                                                                                                                                      . "   AND ID_COD_DPTO = " . $row_fact_municipal['ID_COD_DPTO'] . " "
                                                                                                                                      . "   AND ID_COD_MPIO = " . $row_fact_municipal['ID_COD_MPIO']);
                                                                                $row_valor_cartera = mysqli_fetch_array($query_select_valor_cartera);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_cartera" value="<?php echo $row_valor_cartera['VALOR_CARTERA'] ?>" readonly="readonly" placeholder="Valor Cartera" onblur="return convertValorCartera()" onchange="return convertValorCartera()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_cartera" value="" maxlength="25" readonly="readonly" placeholder="Valor Cartera" required="required" onblur="return convertValorCartera()" onchange="return convertValorCartera()" onkeypress="return isNumeric(event)" />
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
                                                                <div class="divider"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"><?php echo trim($row_fact_municipal['OBSERVACIONES']); ?></textarea>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase; background-color: #FFFFFF;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" readonly="readonly" title="OBSERVACIONES"><?php echo trim($row_fact_municipal['OBSERVACIONES']); ?></textarea>
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
                                                                    if (isset($_GET['id_fact_municipal_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_municipal" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Factura Mun.</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Facturacion_Municipales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_municipal_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_municipal" type="button" data-toggle="modal" data-target="#modalEliminarFactMun"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Factura Mun.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Facturacion_Municipales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_municipal" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Factura Mun.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldFacturaMunicipal();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldFacturaMunicipal() {
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
        function convertValorFactura() {
            var factura = $("input[name=valor_factura]").val();
            var replaceFactura = factura.replace(/,/g, '');
            var newFactura = replaceFactura.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_factura]").val(newFactura);
        }
        function convertValorCartera() {
            var valor_cartera = $("input[name=valor_cartera]").val();
            var replaceValor_cartera = valor_cartera.replace(/,/g, '');
            var newValor_cartera = replaceValor_cartera.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_cartera]").val(newValor_cartera);
        }
        function generarFacturaAportesMun(id_fact_municipal){
            window.open('Combos/Generar_Factura_Aporte_Municipal.php?id_fact_municipal='+id_fact_municipal, 'Popup', 'width=750, height=600');
        }
        function calcularValorConcepto(id_departamento, id_municipio) {
            $.ajax({
                type: "POST",
                data: "id_departamento="+id_departamento+
                      "&id_municipio="+id_municipio,
                dataType: "json",
                url: "Modelo/Calcular_Valor_Concepto.php",
                success: function(data) {
                    $("input[name=valor_factura]").val(data[0]);
                    $("input[name=valor_cartera]").val(data[1]);
                    convertValorFactura();
                    convertValorCartera();
                }
            });
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
                if (id_municipio == 13) {
                    $("#modalSeleccionarFactMun").addClass("animate__zoomIn");
                    $("#modalSeleccionarFactMun").removeClass("animate__zoomOut");
                    $("#modalSeleccionarFactMun").modal("show");
                    $("#id_municipio").val(id_municipio);
                    $("#municipio").val(municipio);
                    $("#periodo_factura").focus();
                } else {
                    $("#id_municipio").val(id_municipio);
                    $("#municipio").val(municipio);
                    $("#periodo_factura").focus();
                    var id_departamento = $("#id_departamento").val();
                    calcularValorConcepto(id_departamento, id_municipio);
                }
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_departamento;
            if (id_consulta == 1) {
                id_departamento = $("#id_departamento").val();
            }
            window.open("Combos/Tipo_Municipio_Visita.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoperiodoFact(id_ano, id_mes, periodo) {
            $("#id_ano_fact").val(id_ano);
            $("#id_mes_fact").val(id_mes);
            $("#periodo_factura").val(periodo + " - " + id_ano);
            $("input[name=no_cc_vencidas]").focus();
        }
        function periodoFact() {
            window.open("Combos/Periodo_Fact_Esp.php", "Popup", "width=400, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            $("#estado_factura_busqueda").focus();
            var id_fact_municipal_editar = $("#id_fact_municipal_editar_hidden").val();
            var id_fact_municipal_eliminar = $("#id_fact_municipal_eliminar_hidden").val();
            if (id_fact_municipal_editar != undefined) {
                convertValorFactura();
                convertValorCartera();
                $(".nav-pills a[href='#crear_fact_municipales_tab']").tab("show");
                $(".nav-pills a[href='#crear_fact_municipales_tab']").text("Actualizar Fact. Aportes Municipales");
                $("#estado_factura").val($("#estado_factura_hidden").val());
                $("#revisado_por").val($("#revisado_por_hidden").val());
            } else {
                if (id_fact_municipal_eliminar != undefined) {
                    convertValorFactura();
                    convertValorCartera();
                    $(".nav-pills a[href='#crear_fact_municipales_tab']").tab("show");
                    $(".nav-pills a[href='#crear_fact_municipales_tab']").text("Eliminar Fact. Aportes Municipales");
                    $("#estado_factura").val($("#estado_factura_hidden").val());
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
                        url: "Modelo/Cargar_Paginacion_Fact_Mun.php",
                        dataType: "json",
                        data: "sw=1&busqueda_factura="+busqueda_factura+"&estado_factura_busqueda="+estado_factura_busqueda,
                        success: function(data) {
                            $("#pagination-fact_mun").twbsPagination('destroy');
                            $("#pagination-fact_mun").twbsPagination({
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
                                        url: "Modelo/Cargar_Fact_Mun.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_factura="+data[1]+"&estado_factura_busqueda="+estado_factura_busqueda+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_fact_mun").html(data[0]);
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
            $("#tab_info_fact_municipales").on("shown.bs.tab", function() {
                $("#estado_factura_busqueda").focus();
            });
            $("#tab_crear_fact_municipales").on("shown.bs.tab", function() {
                $("#departamento").focus();
            });
            $("#tab_info_fact_municipales").on("click", function() {
                $("#estado_factura_busqueda").focus();
            });
            $("#tab_crear_fact_municipales").on("click", function() {
                $("#departamento").focus();
            });
            if (id_fact_municipal_editar == undefined && id_fact_municipal_eliminar == undefined) {
                $("#btn_crear_factura_municipal").click(function() {
                    var fecha_factura = $("input[name=fecha_factura]").val();
                    var fecha_entrega = $("input[name=fecha_entrega]").val();
                    var fecha_vencimiento = $("input[name=fecha_vencimiento]").val();
                    var estado_factura = $("#estado_factura").val();
                    var consecutivo_fact = $("#consecutivo_fact").val();
                    var departamento = $("#id_departamento").val();
                    var municipio = $("#id_municipio").val();
                    var periodo_factura = $("#id_ano_fact").val() + "" + $("#id_mes_fact").val();
                    var no_cc_vencidas = $("input[name=no_cc_vencidas]").val();
                    var valor_factura = $("input[name=valor_factura]").val();
                    if (valor_factura != "") {
                        valor_factura = valor_factura.replace(/,/g, "");
                    }
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
                    if (no_cc_vencidas.length == 0) {
                        $("input[name=no_cc_vencidas]").focus();
                        return false;
                    }
                    if (valor_factura.length == 0) {
                        $("input[name=valor_factura]").focus();
                        return false;
                    }
                    $("#btn_crear_factura_municipal").attr("disabled", true);
                    $("#btn_crear_factura_municipal").css("pointer-events", "none");
                    $("#btn_crear_factura_municipal").html("Creando Fact. Aportes Municipales...");
                    $.ajax({
                        type: "POST",
                        data: "fecha_factura="+fecha_factura+
                              "&fecha_entrega="+fecha_entrega+
                              "&fecha_vencimiento="+fecha_vencimiento+
                              "&estado_factura="+estado_factura+
                              "&consecutivo_fact="+consecutivo_fact+
                              "&departamento="+departamento+
                              "&municipio="+municipio+
                              "&periodo_factura="+periodo_factura+
                              "&no_cc_vencidas="+no_cc_vencidas+
                              "&valor_factura="+valor_factura+
                              "&observaciones="+observaciones,
                        url: "Modelo/Crear_Fact_Mun.php",
                        success: function(data) {
                            //alert(data);
                            //$("#observaciones").val(data);
                            document.location.href = 'Facturacion_Municipales.php?id_fact_municipal_editar='+data;
                        }
                    });
                });
            }
            $("#seleccionar_fact_mun").click(function() {
                var valor_concepto = $("input[name=fact_mun]:checked").val();
                $("input[name=valor_factura]").val(valor_concepto);
                $("input[name=valor_cartera]").val($("#fact_mun_hidden_"+valor_concepto).val());
                convertValorFactura();
                convertValorCartera();
                $("#modalSeleccionarFactMun").removeClass("animate__zoomIn");
                $("#modalSeleccionarFactMun").addClass("animate__zoomOut");
                $("#periodo_factura").focus();
            });
            $("#eliminar_fact_mun").click(function() {
                $("#crear_fact_municipal").submit();
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Fact_Mun.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-fact_mun").twbsPagination('destroy');
                    $("#pagination-fact_mun").twbsPagination({
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
                                url: "Modelo/Cargar_Fact_Mun.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_fact_mun").html(data[0]);
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
                container : "body",
                placement : "top"
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
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=consecutivo_fact]').tooltip({
                container: "body",
                placement: "right"
            });
            $('#fecha_factura').tooltip({
                container : "body",
                placement : "right"
            });
            $('select[name=revisado_por]').tooltip({
                container : "body",
                placement : "top"
            });
            $('#fecha_entrega').tooltip({
                container : "body",
                placement : "top"
            });
            $('#no_cc_vencidas').tooltip({
                container: "body",
                placement: "right"
            });
            $('#valor_factura').tooltip({
                container: "body",
                placement: "right"
            });
            $('#valor_cartera').tooltip({
                container: "body",
                placement: "top"
            });
            $('#fecha_vencimiento').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=periodo_factura]').tooltip({
                container: "body",
                placement: "top"
            });
            $('select[name=estado_factura]').tooltip({
                container : "body",
                placement : "top"
            });
            $('textarea[name=observaciones]').tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>