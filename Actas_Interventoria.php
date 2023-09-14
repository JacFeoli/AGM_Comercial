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
        <title>AGM - Actas de Interventoría</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
        <style type="text/css">
            .text-divider{margin: 2em 0; line-height: 0; text-align: center;}
            .text-divider span{background-color: #D0DEE7; padding: 1em;}
            .text-divider:before{ content: " "; display: block; border-top: 1px solid #A9BDC8;}
        </style>
    </head>
    <!--Eliminar Acta Interventoría Modal-->
    <div class="modal fade" id="modalEliminarActaInterventoria" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Acta Interventoría</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Acta?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_acta_interventoria" name="eliminar_acta_interventoria"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Acta Interventoría Modal-->
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-handshake"></i>
                                                                        <span>Actas Interventoría</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Actas_Interventoria.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-handshake"></i>
                                                                                    <span>Actas Interventoría</span>
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
                                            <h1>Actas Interventoría</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_actas_interventoria_tab" id="tab_info_actas_interventoria" aria-controls="informacion_actas_interventoria_tab" role="tab" data-toggle="tab">Información Actas Interventoría</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_acta_interventoria_tab" id="tab_crear_acta_interventoria" aria-controls="crear_acta_interventoria_tab" role="tab" data-toggle="tab">Crear Acta Interventoría</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_actas_interventoria_tab">
                                                    <h2 class="text-divider"><span style="background-color: #FFFFFF;">FILTROS / BUSQUEDA</span></h2>
                                                    <br />
                                                    <div class="form-group">
                                                        <div class="col-xs-3">
                                                            <select class="form-control input-text input-sm" id="tipo_busqueda_interventoria" name="tipo_busqueda_interventoria" data-toggle="tooltip" title="TIPO DE BUSQUEDA">
                                                                <option value="" selected="selected">-</option>
                                                                <option value="1">MUNICIPIO</option>
                                                                <option value="2">PERIODO</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-9">
                                                            <input class="form-control input-text input-sm" type="text" placeholder="Buscar Acta" name="buscar_acta" id="buscar_acta" data-toggle="tooltip" title="BUSCAR ACTA" />
                                                        </div>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <?php
                                                        $query_select_acta_interventoria = "SELECT * FROM actas_interventoria_2 ORDER BY ID_COD_DPTO, ID_COD_MPIO";
                                                        $sql_acta_interventoria = mysqli_query($connection, $query_select_acta_interventoria);
                                                        if (mysqli_num_rows($sql_acta_interventoria) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=15%>DEPARTAMENTO</th>";
                                                                            echo "<th width=20%>MUNICIPIO</th>";
                                                                            echo "<th width=15%>PERIODO</th>";
                                                                            echo "<th width=5%>DETALLE</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                            echo "<th width=5%>IMPRIMIR</th>";
                                                                            echo "<th width=5%>DESCARGAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_acta_interventoria'>";
                                                                        
                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Actas Interventoría Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-acta_interventoria"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_acta_interventoria_tab">
                                                    <?php
                                                        if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_acta_interventoria" name="crear_acta_interventoria" action="<?php echo "Modelo/Crear_Acta_Interventoria.php?editar=" . $_GET['id_acta_interventoria_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_acta_interventoria = mysqli_query($connection, "SELECT * FROM actas_interventoria_2 WHERE ID_ACTA_INTERVENTORIA = " . $_GET['id_acta_interventoria_editar']);
                                                            $row_acta_interventoria = mysqli_fetch_array($query_select_acta_interventoria);
                                                        ?>
                                                            <input type="hidden" id="id_acta_interventoria_editar_hidden" name="id_acta_interventoria_editar_hidden" value="<?php echo $row_acta_interventoria['ID_ACTA_INTERVENTORIA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_acta_interventoria" name="crear_acta_interventoria" action="<?php echo "Modelo/Crear_Acta_Interventoria.php?eliminar=" . $_GET['id_acta_interventoria_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_acta_interventoria = mysqli_query($connection, "SELECT * FROM actas_interventoria_2 WHERE ID_ACTA_INTERVENTORIA = " . $_GET['id_acta_interventoria_eliminar']);
                                                                $row_acta_interventoria = mysqli_fetch_array($query_select_acta_interventoria);
                                                            ?>
                                                                <input type="hidden" id="id_acta_interventoria_eliminar_hidden" name="id_acta_interventoria_eliminar_hidden" value="<?php echo $row_acta_interventoria['ID_ACTA_INTERVENTORIA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_acta_interventoria" name="crear_acta_interventoria" action="<?php echo "Modelo/Crear_Acta_Interventoria.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_acta">Fech. Acta:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_acta" data-toogle="tooltip" title="FECHA ACTA">
                                                                    <?php
                                                                    if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_acta" value="<?php echo $row_acta_interventoria['FECHA_ACTA'] ?>" placeholder="Fecha Acta" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_acta" value="<?php echo $row_acta_interventoria['FECHA_ACTA'] ?>" placeholder="Fecha Acta" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_acta" value="" placeholder="Fecha Acta" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2"></div>
                                                            <div class="col-xs-3"></div>
                                                            <div style="text-align: right;" class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                        <a onClick="generarActaInterventoria(<?php echo $row_acta_interventoria['ID_ACTA_INTERVENTORIA'] ?>)"><button type="button" style='border: 1px solid;'><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a>
                                                                        <a onClick="generarWordActaInterventoria(<?php echo $row_acta_interventoria['ID_ACTA_INTERVENTORIA'] ?>)"><button type="button" style='border: 1px solid;'><img src='Images/word_2.png' title='Imprimir' width='16' height='16' /></button></a>
                                                                    <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN DPTO. / MUNICIPIO</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento">Dpto:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_acta_interventoria_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_acta_interventoria['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_acta_interventoria_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_acta_interventoria['ID_COD_DPTO']);
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
                                                                    if (isset($_GET['id_acta_interventoria_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_acta_interventoria['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_acta_interventoria['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_acta_interventoria_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_acta_interventoria['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_acta_interventoria['ID_COD_MPIO']);
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
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_acta">Per. Acta:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                        <input type="hidden" id="id_ano_acta" name="id_ano_acta" value="<?php echo substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_acta" name="id_mes_acta" value="<?php echo substr($row_acta_interventoria['PERIODO_ACTA'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                            $query_select_periodo_acta = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                    . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                    . " WHERE ANO_FACTURA = " . substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                                                                                                    . "   AND MES_FACTURA = " . substr($row_acta_interventoria['PERIODO_ACTA'], 4, 2));
                                                                            $row_periodo_acta = mysqli_fetch_array($query_select_periodo_acta);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_acta" name="periodo_acta" value="<?php echo $row_periodo_acta['PERIODO'] . " - " . substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4); ?>" placeholder="Periodo Acta" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO ACTA" onclick="periodoActas()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                            <input type="hidden" id="id_ano_acta" name="id_ano_acta" value="<?php substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_acta" name="id_mes_acta" value="<?php substr($row_acta_interventoria['PERIODO_ACTA'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                                $query_select_periodo_acta = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                        . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                        . " WHERE ANO_FACTURA = " . substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                                                                                                        . "   AND MES_FACTURA = " . substr($row_acta_interventoria['PERIODO_ACTA'], 4, 2));
                                                                                $row_periodo_acta = mysqli_fetch_array($query_select_periodo_acta);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_acta" name="periodo_acta" value="<?php echo $row_periodo_acta['PERIODO'] . " - " . substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4); ?>" placeholder="Periodo Acta" data-toggle="tooltip" readonly="readonly" title="PERIODO ACTA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_ano_acta" name="id_ano_acta" value="" required="required" />
                                                                            <input type="hidden" id="id_mes_acta" name="id_mes_acta" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_acta" name="periodo_acta" placeholder="Periodo Acta" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO ACTA" onclick="periodoActas()" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span>LIQUIDACIÓN OPERADORES DE RED</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_liquidacion">Per. Liq.:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                        <input type="hidden" id="id_ano_liquidacion" name="id_ano_liquidacion" value="<?php echo substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_liquidacion" name="id_mes_liquidacion" value="<?php echo substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                            $query_select_periodo_liquidacion = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                        . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                        . " WHERE ANO_FACTURA = " . substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 0, 4) . " "
                                                                                                                                        . "   AND MES_FACTURA = " . substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 4, 2));
                                                                            $row_periodo_liquidacion = mysqli_fetch_array($query_select_periodo_liquidacion);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_liquidacion" name="periodo_liquidacion" value="<?php echo $row_periodo_liquidacion['PERIODO'] . " - " . substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 0, 4); ?>" placeholder="Periodo Liquidación" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO LIQUIDACIÓN" onclick="periodoLiquidacionesActas()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                            <input type="hidden" id="id_ano_liquidacion" name="id_ano_liquidacion" value="<?php substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_liquidacion" name="id_mes_liquidacion" value="<?php substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                                $query_select_periodo_liquidacion = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                            . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                            . " WHERE ANO_FACTURA = " . substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 0, 4) . " "
                                                                                                                                            . "   AND MES_FACTURA = " . substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 4, 2));
                                                                                $row_periodo_liquidacion = mysqli_fetch_array($query_select_periodo_liquidacion);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_liquidacion" name="periodo_liquidacion" value="<?php echo $row_periodo_liquidacion['PERIODO'] . " - " . substr($row_acta_interventoria['PERIODO_LIQUIDACION'], 0, 4); ?>" placeholder="Periodo Liquidación" data-toggle="tooltip" readonly="readonly" title="PERIODO LIQUIDACIÓN" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="hidden" id="id_ano_liquidacion" name="id_ano_liquidacion" value="" required="required" />
                                                                            <input type="hidden" id="id_mes_liquidacion" name="id_mes_liquidacion" value="" required="required" />
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_liquidacion" name="periodo_liquidacion" placeholder="Periodo Liquidación" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO LIQUIDACIÓN" onclick="periodoLiquidacionesActas()" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class='table-responsive'>
                                                                    <table class='table table-condensed'>
                                                                        <thead>
                                                                            <tr>
                                                                                <th width=20%>VALOR FACTURADO</th>
                                                                                <th width=20%>VALOR RECAUDO</th>
                                                                                <th width=20%>COSTO ENERGÍA</th>
                                                                                <th width=20%>OTRAS DEDUCCIONES</th>
                                                                                <th width=20%>TRASLADO NETO</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style='vertical-align:middle;'>
                                                                                    <div class="input-group" id="valor_facturado" data-toogle="tooltip">
                                                                                        <span class="input-group-addon">
                                                                                            <span class="fas fa-dollar-sign"></span>
                                                                                        </span>
                                                                                        <?php
                                                                                            if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                                                <input type="text" class="form-control input-text input-sm" name="valor_facturado" value="<?php echo $row_acta_interventoria['VALOR_FACTURADO'] ?>" maxlength="25" placeholder="Valor Facturado" required="required" onblur="convertValorFacturado();" onchange="return convertValorFacturado();" onkeypress="return isNumeric(event)" />
                                                                                        <?php
                                                                                            } else {
                                                                                                if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="valor_facturado" value="<?php echo $row_acta_interventoria['VALOR_FACTURADO'] ?>" placeholder="Valor Facturado" onblur="convertValorFacturado();" onchange="return convertValorFacturado();" onkeypress="return isNumeric(event)" />
                                                                                            <?php
                                                                                                } else { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="valor_facturado" value="" maxlength="25" placeholder="Valor Facturado" required="required" onblur="convertValorFacturado();" onchange="return convertValorFacturado();" onkeypress="return isNumeric(event)" />
                                                                                                <?php
                                                                                                }
                                                                                            }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                                <td style='vertical-align:middle;'>
                                                                                    <div class="input-group" id="valor_recaudo" data-toogle="tooltip">
                                                                                        <span class="input-group-addon">
                                                                                            <span class="fas fa-dollar-sign"></span>
                                                                                        </span>
                                                                                        <?php
                                                                                            if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                                                <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_acta_interventoria['VALOR_RECAUDO'] ?>" maxlength="25" placeholder="Valor Recaudo" onblur="convertValorRecaudo();" onchange="return convertValorRecaudo();" onkeypress="return isNumeric(event)" />
                                                                                        <?php
                                                                                            } else {
                                                                                                if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_acta_interventoria['VALOR_RECAUDO'] ?>" placeholder="Valor Recaudo" onblur="convertValorRecaudo();" onchange="return convertValorRecaudo();" onkeypress="return isNumeric(event)" />
                                                                                            <?php
                                                                                                } else { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="" maxlength="25" placeholder="Valor Recaudo" required="required" onblur="convertValorRecaudo();" onchange="return convertValorRecaudo();" onkeypress="return isNumeric(event)" />
                                                                                                <?php
                                                                                                }
                                                                                            }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                                <td style='vertical-align:middle;'>
                                                                                    <div class="input-group" id="costo_energia" data-toogle="tooltip">
                                                                                        <span class="input-group-addon">
                                                                                            <span class="fas fa-dollar-sign"></span>
                                                                                        </span>
                                                                                        <?php
                                                                                            if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                                                <input type="text" class="form-control input-text input-sm" name="costo_energia" value="<?php echo $row_acta_interventoria['COSTO_ENERGIA'] ?>" maxlength="25" placeholder="Costo Energía" onblur="convertCostoEnergia();" onchange="return convertCostoEnergia()" onkeypress="return isNumeric(event)" />
                                                                                        <?php
                                                                                            } else {
                                                                                                if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="costo_energia" value="<?php echo $row_acta_interventoria['COSTO_ENERGIA'] ?>" placeholder="Costo Energía" onblur="convertCostoEnergia();" onchange="return convertCostoEnergia()" onkeypress="return isNumeric(event)" />
                                                                                            <?php
                                                                                                } else { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="costo_energia" value="" maxlength="25" placeholder="Costo Energía" required="required" onblur="convertCostoEnergia();" onchange="return convertCostoEnergia()" onkeypress="return isNumeric(event)" />
                                                                                                <?php
                                                                                                }
                                                                                            }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                                <td style='vertical-align:middle;'>
                                                                                    <div class="input-group" id="otras_deducciones" data-toogle="tooltip">
                                                                                        <span class="input-group-addon">
                                                                                            <span class="fas fa-dollar-sign"></span>
                                                                                        </span>
                                                                                        <?php
                                                                                            if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                                                <input type="text" class="form-control input-text input-sm" name="otras_deducciones" value="<?php echo $row_acta_interventoria['OTRAS_DEDUCCIONES'] ?>" maxlength="25" placeholder="Otras Deducciones" onblur="convertOtrasDeducciones();" onchange="return convertOtrasDeducciones();" onkeypress="return isNumeric(event)" />
                                                                                        <?php
                                                                                            } else {
                                                                                                if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="otras_deducciones" value="<?php echo $row_acta_interventoria['OTRAS_DEDUCCIONES'] ?>" placeholder="Otras Deducciones" onblur="convertOtrasDeducciones();" onchange="return convertOtrasDeducciones();" onkeypress="return isNumeric(event)" />
                                                                                            <?php
                                                                                                } else { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="otras_deducciones" value="" maxlength="25" placeholder="Otras Deducciones" required="required" onblur="convertOtrasDeducciones();" onchange="return convertOtrasDeducciones();" onkeypress="return isNumeric(event)" />
                                                                                                <?php
                                                                                                }
                                                                                            }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                                <td style='vertical-align:middle;'>
                                                                                    <div class="input-group" id="traslado_neto" data-toogle="tooltip">
                                                                                        <span class="input-group-addon">
                                                                                            <span class="fas fa-dollar-sign"></span>
                                                                                        </span>
                                                                                        <?php
                                                                                            if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                                                <input type="text" class="form-control input-text input-sm" name="traslado_neto" value="<?php echo $row_acta_interventoria['TRASLADO_NETO'] ?>" maxlength="25" placeholder="Traslado Neto" onblur="convertTrasladoNeto();" onchange="return convertTrasladoNeto()" onkeypress="return isNumeric(event)" />
                                                                                        <?php
                                                                                            } else {
                                                                                                if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="traslado_neto" value="<?php echo $row_acta_interventoria['TRASLADO_NETO'] ?>" placeholder="Traslado Neto" onblur="convertTrasladoNeto();" onchange="return convertTrasladoNeto()" onkeypress="return isNumeric(event)" />
                                                                                            <?php
                                                                                                } else { ?>
                                                                                                    <input type="text" class="form-control input-text input-sm" name="traslado_neto" value="" maxlength="25" placeholder="Traslado Neto" required="required" onblur="convertTrasladoNeto();" onchange="return convertTrasladoNeto()" onkeypress="return isNumeric(event)" />
                                                                                                <?php
                                                                                                }
                                                                                            }
                                                                                        ?>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span>FACTURACIÓN OTROS AGENTES DE RECAUDO</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class='table-responsive'>
                                                                    <table class='table table-condensed table-hover'>
                                                                        <thead>
                                                                            <tr>
                                                                                <th width=40%>FUENTE DEL RECURSO</th>
                                                                                <th width=20%>VALOR TRASLADO</th>
                                                                                <th width=40%>AGENTE RECAUDADOR</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="resultado_fact_agentes">
                                                                            <?php
                                                                                if (isset($_GET['id_acta_interventoria_editar']) || isset($_GET['id_acta_interventoria_eliminar'])) {
                                                                                    $query_select_fact_munc = mysqli_query($connection, "SELECT * "
                                                                                                                                        . "  FROM facturacion_municipales_2 "
                                                                                                                                        . " WHERE ID_COD_DPTO = '" . $row_acta_interventoria['ID_COD_DPTO'] . "' "
                                                                                                                                        . "   AND ID_COD_MPIO = '" . $row_acta_interventoria['ID_COD_MPIO'] . "' "
                                                                                                                                        . "   AND YEAR(FECHA_FACTURA) = " . substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                                                                                                        . "   AND MONTH(FECHA_FACTURA) = " . substr($row_acta_interventoria['PERIODO_ACTA'], 4, 2) . "");
                                                                                    while ($row_fact_munc = mysqli_fetch_assoc($query_select_fact_munc)) {
                                                                                        echo "<tr>";
                                                                                            echo "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                                                                                            $query_select_reca_munc = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 WHERE ID_FACTURACION = '" . $row_fact_munc['ID_FACTURACION'] . "'");
                                                                                            $row_reca_munc = mysqli_fetch_array($query_select_reca_munc);
                                                                                            echo "<td style='vertical-align:middle;'>$ " . number_format($row_reca_munc['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                                                                            echo "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                                                                                        echo "</tr>";
                                                                                    }
                                                                                    $query_select_fact_esp = mysqli_query($connection, "SELECT * "
                                                                                                                                        . "  FROM facturacion_especiales_2 FE, contribuyentes_2 C "
                                                                                                                                        . " WHERE FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE "
                                                                                                                                        . "   AND FE.ID_COD_DPTO = '" . $row_acta_interventoria['ID_COD_DPTO'] . "' "
                                                                                                                                        . "   AND FE.ID_COD_MPIO = '" . $row_acta_interventoria['ID_COD_MPIO'] . "' "
                                                                                                                                        . "   AND YEAR(FE.FECHA_FACTURA) = " . substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                                                                                                        . "   AND MONTH(FE.FECHA_FACTURA) = " . substr($row_acta_interventoria['PERIODO_ACTA'], 4, 2) . " ");
                                                                                    while ($row_fact_esp = mysqli_fetch_assoc($query_select_fact_esp)) {
                                                                                        echo "<tr>";
                                                                                            echo "<td style='vertical-align:middle;'>" . $row_fact_esp['NOMBRE'] . "</td>";
                                                                                            $query_select_reca_esp = mysqli_query($connection, "SELECT * FROM recaudo_especiales_2 WHERE ID_FACTURACION = '" . $row_fact_esp['ID_FACTURACION'] . "'");
                                                                                            $row_reca_esp = mysqli_fetch_array($query_select_reca_esp);
                                                                                            echo "<td style='vertical-align:middle;'>$ " . number_format($row_reca_esp['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                                                                            echo "<td style='vertical-align:middle;'>MUNICIPIO</td>";
                                                                                        echo "</tr>";
                                                                                    }
                                                                                    $query_select_fact_comer = mysqli_query($connection, "SELECT * FROM facturacion_comercializadores_2 FC, comercializadores_2 C "
                                                                                                                                        . " WHERE FC.ID_COMERCIALIZADOR = C.ID_COMERCIALIZADOR "
                                                                                                                                        . "   AND FC.ID_COD_DPTO = '" . $row_acta_interventoria['ID_COD_DPTO'] . "' "
                                                                                                                                        . "   AND FC.ID_COD_MPIO = '" . $row_acta_interventoria['ID_COD_MPIO'] . "' "
                                                                                                                                        . "   AND YEAR(FC.FECHA_FACTURA) = " . substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                                                                                                        . "   AND MONTH(FC.FECHA_FACTURA) = " . substr($row_acta_interventoria['PERIODO_ACTA'], 4, 2) . " ");
                                                                                    while ($row_fact_comer = mysqli_fetch_assoc($query_select_fact_comer)) {
                                                                                        echo "<tr>";
                                                                                            echo "<td style='vertical-align:middle;'>" . $row_fact_comer['NOMBRE'] . "</td>";
                                                                                            $query_select_reca_comer = mysqli_query($connection, "SELECT * FROM recaudo_comercializadores_2 WHERE ID_FACTURACION = '" . $row_fact_comer['ID_FACTURACION'] . "'");
                                                                                            $row_reca_comer = mysqli_fetch_array($query_select_reca_comer);
                                                                                            echo "<td style='vertical-align:middle;'>$ " . number_format($row_reca_comer['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                                                                                            echo "<td style='vertical-align:middle;'>COMERCIALIZADORA</td>";
                                                                                        echo "</tr>";
                                                                                    }
                                                                                }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span>FACTURA REALIZADA POR EL CONCESIONARIO</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class='table-responsive'>
                                                                    <table class='table table-condensed table-hover'>
                                                                        <thead>
                                                                            <tr>
                                                                                <th width=20%>NO. FACTURA</th>
                                                                                <th width=12%>FECHA FACTURA</th>
                                                                                <th width=36%>CONCEPTO</th>
                                                                                <th width=12%>PERIODO</th>
                                                                                <th width=20%>TOTAL</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="resultado_fact_concesion">
                                                                            <?php
                                                                                if (isset($_GET['id_acta_interventoria_editar']) || isset($_GET['id_acta_interventoria_eliminar'])) {
                                                                                    $query_select_fact_oymri = mysqli_query($connection, "SELECT * FROM facturacion_oymri_2021_2 FO, conceptos_facturacion_2 CF "
                                                                                                                                        . " WHERE FO.ID_CONCEPTO = CF.ID_CONCEPTO_FACT "
                                                                                                                                        . "   AND FO.ID_COD_DPTO = '" . $row_acta_interventoria['ID_COD_DPTO'] . "' "
                                                                                                                                        . "   AND FO.ID_COD_MPIO = '" . $row_acta_interventoria['ID_COD_MPIO'] . "' "
                                                                                                                                        . "   AND YEAR(FO.PERIODO) = " . substr($row_acta_interventoria['PERIODO_ACTA'], 0, 4) . " "
                                                                                                                                        . "   AND MONTH(FO.PERIODO) = " . substr($row_acta_interventoria['PERIODO_ACTA'], 4, 2) . " ");
                                                                                    while ($row_fact_oymri = mysqli_fetch_assoc($query_select_fact_oymri)) {
                                                                                        echo "<tr>";
                                                                                            echo "<td style='vertical-align:middle;'>" . $row_fact_oymri['NO_FACTURA'] . "</td>";
                                                                                            echo "<td style='vertical-align:middle;'>" . $row_fact_oymri['FECHA_FACTURA'] . "</td>";
                                                                                            echo "<td style='vertical-align:middle;'>" . $row_fact_oymri['NOMBRE'] . "</td>";
                                                                                            echo "<td style='vertical-align:middle;'>" . $row_fact_oymri['PERIODO'] . "</td>";
                                                                                            echo "<td style='vertical-align:middle;'>$ " . number_format($row_fact_oymri['VALOR_BRUTO'], 0, ',', '.') . "</td>";
                                                                                        echo "</tr>";
                                                                                    }
                                                                                }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
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
                                                                        if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                            <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" title="OBSERVACIONES"><?php echo trim($row_acta_interventoria['OBSERVACIONES']); ?></textarea>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                                <textarea style="border: 1px solid #A9BDC8; color: #333333; text-transform: uppercase; background-color: #FFFFFF;" class="form-control input-text input-sm" id="observaciones" name="observaciones" rows="4" placeholder="Observaciones" data-toogle="tooltip" readonly="readonly" title="OBSERVACIONES"><?php echo trim($row_acta_interventoria['OBSERVACIONES']); ?></textarea>
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
                                                                    if (isset($_GET['id_acta_interventoria_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_acta_interventoria" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Acta Interventoría</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Actas_Interventoria.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_acta_interventoria_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_acta_interventoria" type="button" data-toggle="modal" data-target="#modalEliminarActaInterventoria"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Acta Interventoría</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Actas_Interventoria.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_acta_interventoria" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Acta Interventoría</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldActa();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
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
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="Javascript/bootstrap.min.js"></script>
    <script src="Javascript/moment-with-locales.js"></script>
    <script src="Javascript/bootstrap-datetimepicker.js"></script>
    <script src="Javascript/jquery.twbsPagination.js"></script>
    <script src="Javascript/menu.js"></script>
    <script>
        function resetFieldActa() {
            $("#id_departamento").val("");
            $("#id_municipio").val("");
            $("#id_ano_acta").val("");
            $("#id_mes_acta").val("");
            $("#id_ano_liquidacion").val("");
            $("#id_mes_liquidacion").val("");
            $("#resultado_fact_agentes").html("");
            $("#resultado_fact_concesion").html("");
            $("input[name=fecha_acta]").focus();
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
        function convertValorFacturado() {
            var valorFacturado = $("input[name=valor_facturado]").val();
            var replaceValorFacturado = valorFacturado.replace(/,/g, '');
            var newValorFacturado = replaceValorFacturado.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_facturado]").val(newValorFacturado);
        }
        function convertValorRecaudo() {
            var valorRecaudo = $("input[name=valor_recaudo]").val();
            var replaceValorRecaudo = valorRecaudo.replace(/,/g, '');
            var newValorRecaudo = replaceValorRecaudo.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_recaudo]").val(newValorRecaudo);
        }
        function convertCostoEnergia() {
            var costoEnergia = $("input[name=costo_energia]").val();
            var replaceCostoEnergia = costoEnergia.replace(/,/g, '');
            var newCostoEnergia = replaceCostoEnergia.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=costo_energia]").val(newCostoEnergia);
        }
        function convertTrasladoNeto() {
            var trasladoNeto = $("input[name=traslado_neto]").val();
            var replaceTrasladoNeto = trasladoNeto.replace(/,/g, '');
            var newTrasladoNeto = replaceTrasladoNeto.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=traslado_neto]").val(newTrasladoNeto);
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
                $("#id_ano_acta").val("");
                $("#id_mes_acta").val("");
                $("#periodo_acta").val("");
                $("#periodo_acta").focus();
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_departamento;
            if (id_consulta == 1) {
                id_departamento = $("#id_departamento").val();
            }
            window.open("Combos/Tipo_Municipio_Visita.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoperiodoActas(id_ano, id_mes, periodo) {
            var id_departamento;
            var id_municipio;
            id_departamento = $("#id_departamento").val();
            id_municipio = $("#id_municipio").val();
            $("#id_ano_acta").val(id_ano);
            $("#id_mes_acta").val(id_mes);
            $("#periodo_acta").val(periodo + " - " + id_ano);
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Fact_Acta.php",
                dataType: "json",
                data: "id_departamento="+id_departamento+"&id_municipio="+id_municipio+"&id_ano="+id_ano+"&id_mes="+id_mes,
                success: function(data) {
                    $("#resultado_fact_agentes").html(data[0]);
                    $("#resultado_fact_concesion").html(data[1]);
                }
            });
            $("#id_ano_liquidacion").val("");
            $("#id_mes_liquidacion").val("");
            $("#periodo_liquidacion").val("");
            $("#periodo_liquidacion").focus();
        }
        function periodoActas() {
            var id_departamento;
            var id_municipio;
            id_departamento = $("#id_departamento").val();
            id_municipio = $("#id_municipio").val();
            window.open("Combos/Periodo_Actas.php?id_departamento="+id_departamento+"&id_municipio="+id_municipio, "Popup", "width=400, height=500");
        }
        function infoperiodoLiquidacionesActas(id_ano, id_mes, periodo, valor_facturado, valor_recaudo, costo_energia, valor_favor) {
            var id_departamento;
            var id_municipio;
            id_departamento = $("#id_departamento").val();
            id_municipio = $("#id_municipio").val();
            $("#id_ano_liquidacion").val(id_ano);
            $("#id_mes_liquidacion").val(id_mes);
            $("#periodo_liquidacion").val(periodo + " - " + id_ano);
            $("input[name=valor_facturado]").val(valor_facturado);
            $("input[name=valor_recaudo]").val(valor_recaudo);
            $("input[name=costo_energia]").val(costo_energia);
            //$("input[name=traslado_neto]").val(valor_recaudo - costo_energia);
            $("input[name=traslado_neto]").val(valor_favor);
            convertValorFacturado();
            convertValorRecaudo();
            convertCostoEnergia();
            convertTrasladoNeto();
            $("input[name=valor_facturado]").focus();
        }
        function periodoLiquidacionesActas() {
            var id_departamento;
            var id_municipio;
            id_departamento = $("#id_departamento").val();
            id_municipio = $("#id_municipio").val();
            window.open("Combos/Periodo_Liquidaciones_Actas.php?id_departamento="+id_departamento+"&id_municipio="+id_municipio, "Popup", "width=400, height=500");
        }
        //END POPUPS
        function generarActaInterventoria(id_acta_interventoria){
            window.open('Combos/Generar_Acta_Interventoria.php?id_acta_interventoria='+id_acta_interventoria, 'Popup', 'width=750, height=600');
        }
        function generarWordActaInterventoria(id_acta_interventoria){
            window.location.href = 'Combos/Generar_Word_Acta_Interventoria.php?id_acta_interventoria='+id_acta_interventoria;
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#tipo_busqueda_interventoria").focus();
            var id_acta_interventoria_editar = $("#id_acta_interventoria_editar_hidden").val();
            var id_acta_interventoria_eliminar = $("#id_acta_interventoria_eliminar_hidden").val();
            if (id_acta_interventoria_editar != undefined) {
                convertValorFacturado();
                convertValorRecaudo();
                convertCostoEnergia();
                convertTrasladoNeto();
                $(".nav-pills a[href='#crear_acta_interventoria_tab']").tab("show");
                $(".nav-pills a[href='#crear_acta_interventoria_tab']").text("Actualizar Acta Interventoría");
            } else {
                if (id_acta_interventoria_eliminar != undefined) {
                    convertValorFacturado();
                    convertValorRecaudo();
                    convertCostoEnergia();
                    convertTrasladoNeto();
                    $(".nav-pills a[href='#crear_acta_interventoria_tab']").tab("show");
                    $(".nav-pills a[href='#crear_acta_interventoria_tab']").text("Eliminar Acta Interventoría");
                }
            }
            $("#tipo_busqueda_interventoria").change(function() {
                $("#buscar_acta").val("");
                $("#buscar_acta").focus();
            });
            $("#buscar_acta").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_acta;
                    if ($(this).val() == "") {
                        busqueda_acta = "";
                    } else {
                        busqueda_acta = $(this).val().toUpperCase();
                    }
                    var tipo_busqueda_interventoria = $('#tipo_busqueda_interventoria').val();
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Acta_Interventoria.php",
                        dataType: "json",
                        data: "sw=1&busqueda_acta="+busqueda_acta+"&tipo_busqueda_interventoria="+tipo_busqueda_interventoria,
                        success: function(data) {
                            $("#pagination-acta_interventoria").twbsPagination('destroy');
                            $("#pagination-acta_interventoria").twbsPagination({
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
                                        url: "Modelo/Cargar_Acta_Interventoria.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_acta="+data[1]+"&page="+page+"&tipo_busqueda_interventoria="+tipo_busqueda_interventoria,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_acta_interventoria").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#fecha_acta").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#tab_info_actas_interventoria").on("shown.bs.tab", function() {
                $("#tipo_busqueda_interventoria").focus();
            });
            $("#tab_crear_acta_interventoria").on("shown.bs.tab", function() {
                $("input[name=fecha_acta]").focus();
            });
            $("#tab_info_actas_interventoria").on("click", function() {
                $("#tipo_busqueda_interventoria").focus();
            });
            $("#tab_crear_acta_interventoria").on("click", function() {
                $("input[name=fecha_acta]").focus();
            });
            if (id_acta_interventoria_editar == undefined && id_acta_interventoria_eliminar == undefined) {
                $("#btn_crear_acta_interventoria").click(function() {
                    var fecha_acta = $("input[name=fecha_acta]").val();
                    var departamento = $("#id_departamento").val();
                    var municipio = $("#id_municipio").val();
                    var periodo_acta = $("#id_ano_acta").val() + "" + $("#id_mes_acta").val();
                    var periodo_liquidacion = $("#id_ano_liquidacion").val() + "" + $("#id_mes_liquidacion").val();
                    var valor_facturado = $("input[name=valor_facturado]").val();
                    if (valor_facturado != "") {
                        valor_facturado = valor_facturado.replace(/,/g, "");
                    }
                    var valor_recaudo = $("input[name=valor_recaudo]").val();
                    if (valor_recaudo != "") {
                        valor_recaudo = valor_recaudo.replace(/,/g, "");
                    }
                    var costo_energia = $("input[name=costo_energia]").val();
                    if (costo_energia != "") {
                        costo_energia = costo_energia.replace(/,/g, "");
                    }
                    var otras_deducciones = $("input[name=otras_deducciones]").val();
                    if (otras_deducciones != "") {
                        otras_deducciones = otras_deducciones.replace(/,/g, "");
                    }
                    var traslado_neto = $("input[name=traslado_neto]").val();
                    if (traslado_neto != "") {
                        traslado_neto = traslado_neto.replace(/,/g, "");
                    }
                    var observaciones = $("#observaciones").val();
                    if (fecha_acta.length == 0) {
                        $("input[name=fecha_acta]").focus();
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
                    if (periodo_acta.length == 0) {
                        $("#periodo_acta").focus();
                        return false;
                    }
                    if (periodo_liquidacion.length == 0) {
                        $("#periodo_liquidacion").focus();
                        return false;
                    }
                    if (valor_facturado.length == 0) {
                        $("input[name=valor_facturado]").focus();
                        return false;
                    }
                    if (valor_recaudo.length == 0) {
                        $("input[name=valor_recaudo]").focus();
                        return false;
                    }
                    if (costo_energia.length == 0) {
                        $("input[name=costo_energia]").focus();
                        return false;
                    }
                    if (otras_deducciones.length == 0) {
                        $("input[name=otras_deducciones]").focus();
                        return false;
                    }
                    if (traslado_neto.length == 0) {
                        $("input[name=traslado_neto]").focus();
                        return false;
                    }
                    $("#btn_crear_acta_interventoria").attr("disabled", true);
                    $("#btn_crear_acta_interventoria").css("pointer-events", "none");
                    $("#btn_crear_acta_interventoria").html("Creando Acta Interventoría...");
                    $.ajax({
                        type: "POST",
                        data: "fecha_acta="+fecha_acta+
                              "&departamento="+departamento+
                              "&municipio="+municipio+
                              "&periodo_acta="+periodo_acta+
                              "&periodo_liquidacion="+periodo_liquidacion+
                              "&valor_facturado="+valor_facturado+
                              "&valor_recaudo="+valor_recaudo+
                              "&costo_energia="+costo_energia+
                              "&otras_deducciones="+otras_deducciones+
                              "&traslado_neto="+traslado_neto+
                              "&observaciones="+observaciones,
                        url: "Modelo/Crear_Acta_Interventoria.php",
                        success: function(data) {
                            document.location.href = 'Actas_Interventoria.php?id_acta_interventoria_editar='+data;
                        }
                    });
                });
            }
            $("#eliminar_acta_interventoria").click(function() {
                $("#crear_acta_interventoria").submit();
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Acta_Interventoria.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-acta_interventoria").twbsPagination('destroy');
                    $("#pagination-acta_interventoria").twbsPagination({
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
                                url: "Modelo/Cargar_Acta_Interventoria.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_acta_interventoria").html(data[0]);
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
            $('select[name=tipo_busqueda_interventoria]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=buscar_acta]').tooltip({
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
            $('input[type=text][name=periodo_acta]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=periodo_liquidacion]').tooltip({
                container: "body",
                placement: "right"
            });
            $('#fecha_acta').tooltip({
                container : "body",
                placement : "right"
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