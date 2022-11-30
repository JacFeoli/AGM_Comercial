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
        <title>AGM - Facturación Operadores</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
        <style type="text/css">
            .text-divider{margin: 2em 0; line-height: 0; text-align: center;}
            .text-divider span{background-color: #D0DEE7; padding: 1em;}
            .text-divider:before{ content: " "; display: block; border-top: 1px solid #A9BDC8;}
        </style>
    </head>
    <!--Eliminar Factura Operadores Modal-->
    <div class="modal fade" id="modalEliminarFactOper" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Factura Operadores</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar la Factura?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_fact_oper" name="eliminar_fact_oper"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Factura Operadores Modal-->
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-lightbulb"></i>
                                                                        <span>Operadores</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Facturacion_Operadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check-alt"></i>
                                                                                    <span>Facturación</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Recaudo_Operadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check"></i>
                                                                                    <span>Recaudo</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Reportes_Operadores.php'>
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
                                            <h1>Facturación Operadores</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_fact_operadores_tab" id="tab_info_fact_operadores" aria-controls="informacion_fact_operadores_tab" role="tab" data-toggle="tab">Información Fact. Operadores</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_fact_operadores_tab" id="tab_crear_fact_operadores" aria-controls="crear_fact_operadores_tab" role="tab" data-toggle="tab">Crear Fact. Operador</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_fact_operadores_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Factura" name="buscar_factura" id="buscar_factura" />
                                                    <br />
                                                    <?php
                                                        $query_select_fact_oper = "SELECT * FROM facturacion_operadores_2 ORDER BY ID_COD_DPTO, ID_COD_MPIO";
                                                        $sql_fact_oper = mysqli_query($connection, $query_select_fact_oper);
                                                        if (mysqli_num_rows($sql_fact_oper) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=4%>ESTADO</th>";
                                                                            echo "<th width=12%>DEPARTAMENTO</th>";
                                                                            echo "<th width=17%>MUNICIPIO</th>";
                                                                            echo "<th width=40%>OPERADOR</th>";
                                                                            echo "<th width=11%>PERIODO</th>";
                                                                            echo "<th width=11%>VALOR</th>";
                                                                            echo "<th width=5%>DETALLE</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_fact_oper'>";
                                                                        
                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                            echo "<p></p>";
                                                            echo "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO.</span>";
                                                            echo "&nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE PAGO.</span>";
                                                            //echo "&nbsp;<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Facturas Operadores Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-fact_oper"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_fact_operadores_tab">
                                                    <?php
                                                        if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_operador" name="crear_fact_operador" action="<?php echo "Modelo/Crear_Fact_Oper.php?editar=" . $_GET['id_fact_operador_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_fact_operador = mysqli_query($connection, "SELECT * FROM facturacion_operadores_2 WHERE ID_FACTURACION = " . $_GET['id_fact_operador_editar']);
                                                            $row_fact_operador = mysqli_fetch_array($query_select_fact_operador);
                                                        ?>
                                                            <input type="hidden" id="id_fact_operador_editar_hidden" name="id_fact_operador_editar_hidden" value="<?php echo $row_fact_operador['ID_FACTURACION']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_operador" name="crear_fact_operador" action="<?php echo "Modelo/Crear_Fact_Oper.php?eliminar=" . $_GET['id_fact_operador_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_fact_operador = mysqli_query($connection, "SELECT * FROM facturacion_operadores_2 WHERE ID_FACTURACION = " . $_GET['id_fact_operador_eliminar']);
                                                                $row_fact_operador = mysqli_fetch_array($query_select_fact_operador);
                                                            ?>
                                                                <input type="hidden" id="id_fact_operador_eliminar_hidden" name="id_fact_operador_eliminar_hidden" value="<?php echo $row_fact_operador['ID_FACTURACION']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_operador" name="crear_fact_operador" action="<?php echo "Modelo/Crear_Fact_Oper.php"; ?>" method="post">
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
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="<?php echo $row_fact_operador['FECHA_FACTURA'] ?>" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="<?php echo $row_fact_operador['FECHA_FACTURA'] ?>" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                            <div class="col-xs-3">
                                                                
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="estado_recaudo">Est. Fact.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="estado_factura" name="estado_factura" data-toggle="tooltip" title="ESTADO FACTURA" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                                <select class="form-control input-text input-sm" id="estado_factura" name="estado_factura" disabled="disabled" data-toggle="tooltip" title="ESTADO FACTURA" required>
                                                                            <?php
                                                                            } else { ?>
                                                                                <select class="form-control input-text input-sm" id="estado_factura" name="estado_factura" data-toggle="tooltip" title="ESTADO FACTURA" required>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                            <option value="" selected="selected">-</option>
                                                                            <option value="1">PAGADO</option>
                                                                            <option value="2">PENDIENTE DE PAGO</option>
                                                                            <?php
                                                                                if (isset($_GET['id_fact_operador_editar']) || isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                                    <input type="hidden" id="estado_factura_hidden" name="estado_factura_hidden" value="<?php echo $row_fact_operador['ESTADO_FACTURA']; ?>" />
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN OPERADOR / CLIENTE</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="operador">Operador:</label>
                                                            <div class="col-xs-7">
                                                                <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) {
                                                                        $query_select_operador = mysqli_query($connection, "SELECT * FROM operadores_2 WHERE ID_OPERADOR = " . $row_fact_operador['ID_OPERADOR']);
                                                                        $row_operador = mysqli_fetch_array($query_select_operador);
                                                                    ?>
                                                                        <input type="hidden" id="id_operador" name="id_operador" value="<?php echo $row_operador['ID_OPERADOR']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="operador" name="operador" value="<?php echo $row_operador['NOMBRE']; ?>" placeholder="Operador" required="required" data-toggle="tooltip" readonly="readonly" title="OPERADOR" onclick="operadorFact()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) {
                                                                            $query_select_operador = mysqli_query($connection, "SELECT * FROM operadores_2 WHERE ID_OPERADOR = " . $row_fact_operador['ID_OPERADOR']);
                                                                            $row_operador = mysqli_fetch_array($query_select_operador);
                                                                        ?>
                                                                            <input type="hidden" id="id_operador" name="id_operador" value="<?php echo $row_operador['ID_OPERADOR']; ?>" />
                                                                            <input type="text" class="form-control input-text input-sm" id="operador" name="operador" value="<?php echo $row_operador['NOMBRE']; ?>" placeholder="Operador" data-toggle="tooltip" readonly="readonly" title="OPERADOR" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_operador" name="id_operador" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="operador" name="operador" placeholder="Operador" required="required" data-toggle="tooltip" readonly="readonly" title="OPERADOR" onclick="operadorFact()" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nit_operador">NIT:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nit_operador" name="nit_operador" value="<?php echo $row_operador['NIT_OPERADOR']; ?>" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_operador" name="nit_operador" value="<?php echo $row_operador['NIT_OPERADOR']; ?>" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_operador" name="nit_operador" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
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
                                                                    if (isset($_GET['id_fact_operador_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_operador['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_operador['ID_COD_DPTO']);
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
                                                                    if (isset($_GET['id_fact_operador_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_operador['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_fact_operador['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_operador['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_fact_operador['ID_COD_MPIO']);
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
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php echo substr($row_fact_operador['PERIODO_FACTURA'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php echo substr($row_fact_operador['PERIODO_FACTURA'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                            $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                    . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                    . " WHERE ANO_FACTURA = " . substr($row_fact_operador['PERIODO_FACTURA'], 0, 4) . " "
                                                                                                                                    . "   AND MES_FACTURA = " . substr($row_fact_operador['PERIODO_FACTURA'], 4, 2));
                                                                            $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_fact_operador['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoFact()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php substr($row_fact_operador['PERIODO_FACTURA'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php substr($row_fact_operador['PERIODO_FACTURA'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                                $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                        . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                        . " WHERE ANO_FACTURA = " . substr($row_fact_operador['PERIODO_FACTURA'], 0, 4) . " "
                                                                                                                                        . "   AND MES_FACTURA = " . substr($row_fact_operador['PERIODO_FACTURA'], 4, 2));
                                                                                $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_fact_operador['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" data-toggle="tooltip" readonly="readonly" title="PERIODO" />
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
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN VALORES / USUARIOS</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_factura">Val. Fact.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="valor_factura" data-toogle="tooltip" title="VALOR FACTURA">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_fact_operador['VALOR_FACTURA'] ?>" maxlength="25" placeholder="Valor Factura" onblur="convertValorFactura(); calcularTotalFactura();" onchange="return convertValorFactura(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_fact_operador['VALOR_FACTURA'] ?>" placeholder="Valor Factura" onblur="convertValorFactura(); calcularTotalFactura();" onchange="return convertValorFactura(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_factura" value="" maxlength="25" placeholder="Valor Factura" required="required" onblur="convertValorFactura(); calcularTotalFactura();" onchange="return convertValorFactura(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="ajuste_fact">Ajus. Fact.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="ajuste_fact" data-toogle="tooltip" title="AJUSTE FACT.">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="ajuste_fact" value="<?php echo $row_fact_operador['AJUSTE_FACT'] ?>" maxlength="25" placeholder="Ajuste Fact." onblur="convertAjusteFact(); calcularTotalFactura();" onchange="return convertAjusteFact(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="ajuste_fact" value="<?php echo $row_fact_operador['AJUSTE_FACT'] ?>" placeholder="Ajuste Fact." onblur="convertAjusteFact(); calcularTotalFactura();" onchange="return convertAjusteFact(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="ajuste_fact" value="" maxlength="25" placeholder="Ajuste Fact." required="required" onblur="convertAjusteFact(); calcularTotalFactura();" onchange="return convertAjusteFact(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="total_factura">Tot. Fact.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="total_factura" data-toogle="tooltip" title="TOTAL FACTURA">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="total_factura" value="<?php echo $row_fact_operador['VALOR_FACTURA'] - $row_fact_operador['AJUSTE_FACT']; ?>" maxlength="25" readonly="readonly" placeholder="Total Factura" onblur="convertTotalFactura();" onchange="return convertTotalFactura()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="total_factura" value="<?php echo $row_fact_operador['VALOR_FACTURA'] - $row_fact_operador['AJUSTE_FACT']; ?>" readonly="readonly" placeholder="Total Factura" onblur="convertTotalFactura();" onchange="return convertTotalFactura()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="total_factura" value="" maxlength="25" readonly="readonly" placeholder="Total Factura" required="required" onblur="convertTotalFactura();" onchange="return convertTotalFactura()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_recaudo">Val. Reca.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="valor_recaudo" data-toogle="tooltip" title="VALOR RECAUDO">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_fact_operador['VALOR_RECAUDO'] ?>" maxlength="25" placeholder="Valor Recaudo" onblur="convertValorRecaudo(); calcularTotalRecaudo();" onchange="return convertValorRecaudo(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_fact_operador['VALOR_RECAUDO'] ?>" placeholder="Valor Recaudo" onblur="convertValorRecaudo(); calcularTotalRecaudo();" onchange="return convertValorRecaudo(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="" maxlength="25" placeholder="Valor Recaudo" required="required" onblur="convertValorRecaudo(); calcularTotalRecaudo();" onchange="return convertValorRecaudo(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="ajuste_reca">Ajus. Reca.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="ajuste_reca" data-toogle="tooltip" title="AJUSTE RECA.">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="ajuste_reca" value="<?php echo $row_fact_operador['AJUSTE_RECA'] ?>" maxlength="25" placeholder="Ajuste Reca." onblur="convertAjusteReca(); calcularTotalRecaudo();" onchange="return convertAjusteReca(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="ajuste_reca" value="<?php echo $row_fact_operador['AJUSTE_RECA'] ?>" placeholder="Ajuste Reca." onblur="convertAjusteReca(); calcularTotalRecaudo();" onchange="return convertAjusteReca(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="ajuste_reca" value="" maxlength="25" placeholder="Ajuste Reca." required="required" onblur="convertAjusteReca(); calcularTotalRecaudo();" onchange="return convertAjusteReca(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="total_recaudo">Tot. Reca.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="total_recaudo" data-toogle="tooltip" title="TOTAL RECAUDO">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="total_recaudo" value="<?php echo $row_fact_operador['VALOR_RECAUDO'] - $row_fact_operador['AJUSTE_RECA']; ?>" maxlength="25" readonly="readonly" placeholder="Total Recaudo" onblur="convertTotalRecaudo();" onchange="return convertTotalRecaudo()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="total_recaudo" value="<?php echo $row_fact_operador['VALOR_RECAUDO'] - $row_fact_operador['AJUSTE_RECA']; ?>" readonly="readonly" placeholder="Total Recaudo" onblur="convertTotalRecaudo();" onchange="return convertTotalRecaudo()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="total_recaudo" value="" maxlength="25" readonly="readonly" placeholder="Total Recaudo" required="required" onblur="convertTotalRecaudo();" onchange="return convertTotalRecaudo()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_energia">Val. Ener.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="valor_energia" data-toogle="tooltip" title="VALOR ENERGÍA">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_energia" value="<?php echo $row_fact_operador['VALOR_ENERGIA'] ?>" maxlength="25" placeholder="Valor Energía" onblur="convertValorEnergia();" onchange="return convertValorEnergia()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_energia" value="<?php echo $row_fact_operador['VALOR_ENERGIA'] ?>" placeholder="Valor Energía" onblur="convertValorEnergia();" onchange="return convertValorEnergia()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_energia" value="" maxlength="25" placeholder="Valor Energía" required="required" onblur="convertValorEnergia();" onchange="return convertValorEnergia()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="cuota_energia">Cuot. Ene.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="cuota_energia" data-toogle="tooltip" title="CUOTA ENERGÍA">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="cuota_energia" value="<?php echo $row_fact_operador['CUOTA_ENERGIA'] ?>" maxlength="25" placeholder="Cuota Energía" onblur="convertCuotaEnergia();" onchange="return convertCuotaEnergia()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="cuota_energia" value="<?php echo $row_fact_operador['CUOTA_ENERGIA'] ?>" placeholder="Cuota Energía" onblur="convertCuotaEnergia();" onchange="return convertCuotaEnergia()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="cuota_energia" value="" maxlength="25" placeholder="Cuota Energía" required="required" onblur="convertCuotaEnergia();" onchange="return convertCuotaEnergia()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="otros_ajustes">Otr. Ajus.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="otros_ajustes" data-toogle="tooltip" title="OTROS AJUSTES">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="otros_ajustes" value="<?php echo $row_fact_operador['OTROS_AJUSTES'] ?>" maxlength="25" placeholder="Otros Ajustes" onblur="convertOtrosAjustes();" onchange="return convertOtrosAjustes()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="otros_ajustes" value="<?php echo $row_fact_operador['OTROS_AJUSTES'] ?>" placeholder="Otros Ajustes" onblur="convertOtrosAjustes();" onchange="return convertOtrosAjustes()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="otros_ajustes" value="" maxlength="25" placeholder="Otros Ajustes" required="required" onblur="convertOtrosAjustes();" onchange="return convertOtrosAjustes()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_favor">Val. Fav.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="valor_favor" data-toogle="tooltip" title="VALOR FAVOR">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_favor" value="<?php echo $row_fact_operador['VALOR_FAVOR'] ?>" maxlength="25" placeholder="Valor Favor" onblur="convertValorFavor();" onchange="return convertValorFavor()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_favor" value="<?php echo $row_fact_operador['VALOR_FAVOR'] ?>" placeholder="Valor Favor" onblur="convertValorFavor();" onchange="return convertValorFavor()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_favor" value="" maxlength="25" placeholder="Valor Favor" required="required" onblur="convertValorFavor();" onchange="return convertValorFavor()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="consumo">Consumo:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="consumo" data-toogle="tooltip" title="CONSUMO">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-hashtag"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="consumo" value="<?php echo $row_fact_operador['CONSUMO'] ?>" maxlength="10" placeholder="Consumo" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="consumo" value="<?php echo $row_fact_operador['CONSUMO'] ?>" readonly="readonly" placeholder="Consumo" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="consumo" value="" maxlength="10" placeholder="Consumo" required="required" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="no_usuarios">No. Usuar.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="no_usuarios" data-toogle="tooltip" title="NO. USUARIOS">
                                                                    <?php
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-hashtag"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="no_usuarios" value="<?php echo $row_fact_operador['NO_USUARIOS'] ?>" maxlength="10" placeholder="No. Usuarios" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="no_usuarios" value="<?php echo $row_fact_operador['NO_USUARIOS'] ?>" readonly="readonly" placeholder="No. Usuarios" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="no_usuarios" value="" maxlength="10" placeholder="No. Usuarios" required="required" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_operador_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_operador" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Factura Oper.</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Facturacion_Operadores.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_operador_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_operador" type="button" data-toggle="modal" data-target="#modalEliminarFactOper"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Factura Oper.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Facturacion_Operadores.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_operador" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Factura Oper.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldFacturaOperador();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
        function resetFieldFacturaOperador() {
            $("#id_departamento").val("");
            $("#id_municipio").val("");
            $("#id_ano_fact").val("");
            $("#id_mes_fact").val("");
            $("#id_operador").val("");
            $("#operador").focus();
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
            var valorFactura = $("input[name=valor_factura]").val();
            var replaceValorFactura = valorFactura.replace(/,/g, '');
            var newValorFactura = replaceValorFactura.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_factura]").val(newValorFactura);
        }
        function convertAjusteFact() {
            var ajusteFact = $("input[name=ajuste_fact]").val();
            var replaceAjusteFact = ajusteFact.replace(/,/g, '');
            var newAjusteFact = replaceAjusteFact.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=ajuste_fact]").val(newAjusteFact);
        }
        function convertTotalFactura() {
            var totalFact = $("input[name=total_factura]").val();
            var replaceTotalFact = totalFact.replace(/,/g, '');
            var newTotalFact = replaceTotalFact.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=total_factura]").val(newTotalFact);
        }
        function calcularTotalFactura() {
            var valor_factura = $("input[name=valor_factura]").val();
            var ajuste_fact = $("input[name=ajuste_fact]").val();
            $("input[name=total_factura]").val(Math.round(valor_factura.replace(/,/g, "") - ajuste_fact.replace(/,/g, "")));
            convertTotalFactura();
        }
        function convertValorRecaudo() {
            var valorRecaudo = $("input[name=valor_recaudo]").val();
            var replaceValorRecaudo = valorRecaudo.replace(/,/g, '');
            var newValorRecaudo = replaceValorRecaudo.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_recaudo]").val(newValorRecaudo);
        }
        function convertAjusteReca() {
            var ajusteReca = $("input[name=ajuste_reca]").val();
            var replaceAjusteReca = ajusteReca.replace(/,/g, '');
            var newAjusteReca = replaceAjusteReca.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=ajuste_reca]").val(newAjusteReca);
        }
        function convertTotalRecaudo() {
            var totalReca = $("input[name=total_recaudo]").val();
            var replaceTotalReca = totalReca.replace(/,/g, '');
            var newTotalReca = replaceTotalReca.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=total_recaudo]").val(newTotalReca);
        }
        function calcularTotalRecaudo() {
            var valor_recaudo = $("input[name=valor_recaudo]").val();
            var ajuste_reca = $("input[name=ajuste_reca]").val();
            $("input[name=total_recaudo]").val(Math.round(valor_recaudo.replace(/,/g, "") - ajuste_reca.replace(/,/g, "")));
            convertTotalRecaudo();
        }
        function convertValorEnergia() {
            var valorEnergia = $("input[name=valor_energia]").val();
            var replaceValorEnergia = valorEnergia.replace(/,/g, '');
            var newValorEnergia = replaceValorEnergia.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_energia]").val(newValorEnergia);
        }
        function convertCuotaEnergia() {
            var cuotaEnergia = $("input[name=cuota_energia]").val();
            var replaceCuotaEnergia = cuotaEnergia.replace(/,/g, '');
            var newCuotaEnergia = replaceCuotaEnergia.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=cuota_energia]").val(newCuotaEnergia);
        }
        function convertOtrosAjustes() {
            var otrosAjustes = $("input[name=otros_ajustes]").val();
            var replaceOtrosAjustes = otrosAjustes.replace(/,/g, '');
            var newOtrosAjustes = replaceOtrosAjustes.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=otros_ajustes]").val(newOtrosAjustes);
        }
        function convertValorFavor() {
            var valorFavor = $("input[name=valor_favor]").val();
            var replaceValorFavor = valorFavor.replace(/,/g, '');
            var newValorFavor = replaceValorFavor.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_favor]").val(newValorFavor);
        }
        //POPUPS
        function infoOperadorFact(id_operador, operador, nit_operador) {
            $("#id_operador").val(id_operador);
            $("#operador").val(operador);
            $("#nit_operador").val(nit_operador);
            $("#id_departamento").val("");
            $("#departamento").val("");
            $("#id_municipio").val("");
            $("#municipio").val("");
            $("#departamento").focus();
        }
        function operadorFact() {
            window.open("Combos/Operador.php", "Popup", "width=500, height=500");
        }
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
            var id_operador = $("#id_operador").val();
            window.open("Combos/Tipo_Departamento_Operador.php?id_consulta="+id_consulta+"&id_operador="+id_operador, "Popup", "width=400, height=500");
        }
        function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
            if (id_consulta == 1) {
                $("#id_municipio").val(id_municipio);
                $("#municipio").val(municipio);
                $("#periodo_factura").focus();
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_operador;
            var id_departamento;
            if (id_consulta == 1) {
                id_operador = $("#id_operador").val();
                id_departamento = $("#id_departamento").val();
            }
            window.open("Combos/Tipo_Municipio_Operador.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta+"&id_operador="+id_operador, "Popup", "width=400, height=500");
        }
        function infoperiodoFact(id_ano, id_mes, periodo) {
            $("#id_ano_fact").val(id_ano);
            $("#id_mes_fact").val(id_mes);
            $("#periodo_factura").val(periodo + " - " + id_ano);
            $("input[name=valor_factura]").focus();
        }
        function periodoFact() {
            window.open("Combos/Periodo_Fact_Esp.php", "Popup", "width=400, height=500");
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            $("#buscar_factura").focus();
            var id_fact_operador_editar = $("#id_fact_operador_editar_hidden").val();
            var id_fact_operador_eliminar = $("#id_fact_operador_eliminar_hidden").val();
            if (id_fact_operador_editar != undefined) {
                convertValorFactura();
                convertAjusteFact();
                convertTotalFactura();
                convertValorRecaudo();
                convertAjusteReca();
                convertTotalRecaudo();
                convertValorEnergia();
                convertCuotaEnergia();
                convertOtrosAjustes();
                convertValorFavor();
                $(".nav-pills a[href='#crear_fact_operadores_tab']").tab("show");
                $(".nav-pills a[href='#crear_fact_operadores_tab']").text("Actualizar Fact. Operador");
                $("#estado_factura").val($("#estado_factura_hidden").val());
            } else {
                if (id_fact_operador_eliminar != undefined) {
                    convertValorFactura();
                    convertAjusteFact();
                    convertTotalFactura();
                    convertValorRecaudo();
                    convertAjusteReca();
                    convertTotalRecaudo();
                    convertValorEnergia();
                    convertCuotaEnergia();
                    convertOtrosAjustes();
                    convertValorFavor();
                    $(".nav-pills a[href='#crear_fact_operadores_tab']").tab("show");
                    $(".nav-pills a[href='#crear_fact_operadores_tab']").text("Eliminar Fact. Operador");
                    $("#estado_factura").val($("#estado_factura_hidden").val());
                }
            }
            $("#buscar_factura").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_factura;
                    if ($(this).val() == "") {
                        busqueda_factura = "";
                    } else {
                        busqueda_factura = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Fact_Oper.php",
                        dataType: "json",
                        data: "sw=1&busqueda_factura="+busqueda_factura,
                        success: function(data) {
                            $("#pagination-fact_oper").twbsPagination('destroy');
                            $("#pagination-fact_oper").twbsPagination({
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
                                        url: "Modelo/Cargar_Fact_Oper.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_factura="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_fact_oper").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#fecha_factura").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#tab_info_fact_operadores").on("shown.bs.tab", function() {
                $("#buscar_factura").focus();
            });
            $("#tab_crear_fact_operadores").on("shown.bs.tab", function() {
                $("#operador").focus();
            });
            $("#tab_info_fact_operadores").on("click", function() {
                $("#buscar_factura").focus();
            });
            $("#tab_crear_fact_operadores").on("click", function() {
                $("#operador").focus();
            });
            if (id_fact_operador_editar == undefined && id_fact_operador_eliminar == undefined) {
                $("#btn_crear_factura_operador").click(function() {
                    var fecha_factura = $("input[name=fecha_factura]").val();
                    var estado_factura = $("#estado_factura").val();
                    var operador = $("#id_operador").val();
                    var departamento = $("#id_departamento").val();
                    var municipio = $("#id_municipio").val();
                    var periodo_factura = $("#id_ano_fact").val() + "" + $("#id_mes_fact").val();
                    var valor_factura = $("input[name=valor_factura]").val();
                    if (valor_factura != "") {
                        valor_factura = valor_factura.replace(/,/g, "");
                    }
                    var ajuste_fact = $("input[name=ajuste_fact]").val();
                    if (ajuste_fact != "") {
                        ajuste_fact = ajuste_fact.replace(/,/g, "");
                    }
                    var valor_recaudo = $("input[name=valor_recaudo]").val();
                    if (valor_recaudo != "") {
                        valor_recaudo = valor_recaudo.replace(/,/g, "");
                    }
                    var ajuste_reca = $("input[name=ajuste_reca]").val();
                    if (ajuste_reca != "") {
                        ajuste_reca = ajuste_reca.replace(/,/g, "");
                    }
                    var valor_energia = $("input[name=valor_energia]").val();
                    if (valor_energia != "") {
                        valor_energia = valor_energia.replace(/,/g, "");
                    }
                    var cuota_energia = $("input[name=cuota_energia]").val();
                    if (cuota_energia != "") {
                        cuota_energia = cuota_energia.replace(/,/g, "");
                    }
                    var otros_ajustes = $("input[name=otros_ajustes]").val();
                    if (otros_ajustes != "") {
                        otros_ajustes = otros_ajustes.replace(/,/g, "");
                    }
                    var valor_favor = $("input[name=valor_favor]").val();
                    if (valor_favor != "") {
                        valor_favor = valor_favor.replace(/,/g, "");
                    }
                    var consumo = $("input[name=consumo]").val();
                    var no_usuarios = $("input[name=no_usuarios]").val();
                    if (fecha_factura.length == 0) {
                        $("input[name=fecha_factura]").focus();
                        return false;
                    }
                    if (estado_factura.length == 0) {
                        $("#estado_factura").focus();
                        return false;
                    }
                    if (operador.length == 0) {
                        $("#operador").focus();
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
                    if (valor_factura.length == 0) {
                        $("input[name=valor_factura]").focus();
                        return false;
                    }
                    if (ajuste_fact.length == 0) {
                        $("input[name=ajuste_fact]").focus();
                        return false;
                    }
                    if (valor_recaudo.length == 0) {
                        $("input[name=valor_recaudo]").focus();
                        return false;
                    }
                    if (ajuste_reca.length == 0) {
                        $("input[name=ajuste_reca]").focus();
                        return false;
                    }
                    if (consumo.length == 0) {
                        $("input[name=consumo]").focus();
                        return false;
                    }
                    if (no_usuarios.length == 0) {
                        $("input[name=no_usuarios]").focus();
                        return false;
                    }
                    $("#btn_crear_factura_operador").attr("disabled", true);
                    $("#btn_crear_factura_operador").css("pointer-events", "none");
                    $("#btn_crear_factura_operador").html("Creando Fact. Operador...");
                    $.ajax({
                        type: "POST",
                        data: "fecha_factura="+fecha_factura+
                              "&estado_factura="+estado_factura+
                              "&operador="+operador+
                              "&departamento="+departamento+
                              "&municipio="+municipio+
                              "&periodo_factura="+periodo_factura+
                              "&valor_factura="+valor_factura+
                              "&ajuste_fact="+ajuste_fact+
                              "&valor_recaudo="+valor_recaudo+
                              "&ajuste_reca="+ajuste_reca+
                              "&valor_energia="+valor_energia+
                              "&cuota_energia="+cuota_energia+
                              "&otros_ajustes="+otros_ajustes+
                              "&valor_favor="+valor_favor+
                              "&consumo="+consumo+
                              "&no_usuarios="+no_usuarios,
                        url: "Modelo/Crear_Fact_Oper.php",
                        success: function(data) {
                            //alert(data);
                            //$("#observaciones").val(data);
                            document.location.href = 'Facturacion_Operadores.php?id_fact_operador_editar='+data;
                        }
                    });
                });
            }
            $("#eliminar_fact_oper").click(function() {
                $("#crear_fact_operador").submit();
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Fact_Oper.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-fact_oper").twbsPagination('destroy');
                    $("#pagination-fact_oper").twbsPagination({
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
                                url: "Modelo/Cargar_Fact_Oper.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_fact_oper").html(data[0]);
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
            $('select[name=estado_factura]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=operador]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nit_operador]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=departamento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=municipio]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=periodo_factura]').tooltip({
                container: "body",
                placement: "top"
            });
            $('#fecha_factura').tooltip({
                container : "body",
                placement : "right"
            });
            $('#valor_factura').tooltip({
                container: "body",
                placement: "top"
            });
            $('#ajuste_fact').tooltip({
                container: "body",
                placement: "top"
            });
            $('#total_factura').tooltip({
                container: "body",
                placement: "top"
            });
            $('#valor_recaudo').tooltip({
                container: "body",
                placement: "top"
            });
            $('#ajuste_reca').tooltip({
                container: "body",
                placement: "top"
            });
            $('#total_recaudo').tooltip({
                container: "body",
                placement: "top"
            });
            $('#valor_energia').tooltip({
                container: "body",
                placement: "top"
            });
            $('#cuota_energia').tooltip({
                container: "body",
                placement: "top"
            });
            $('#otros_ajustes').tooltip({
                container: "body",
                placement: "top"
            });
            $('#valor_favor').tooltip({
                container: "body",
                placement: "top"
            });
            $('#consumo').tooltip({
                container: "body",
                placement: "top"
            });
            $('#no_usuarios').tooltip({
                container: "body",
                placement: "top"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>