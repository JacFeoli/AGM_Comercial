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
        <title>AGM - Estado de Cuentas</title>
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
            table {
                margin-bottom: 0;
                background-color: #D0DEE7;
                color: #333333;
                cursor: default;
                font-size: 14px;
                font-family: 'Cabin';
            }
            table th {
                /*background-image: linear-gradient(#2FB3FB, #0676C0);*/
                background-image: linear-gradient(#2FB3FB, #003153);
                color: #FFFFFF;
                vertical-align: middle;
                text-align: center;
                font-size: 11px;
                border-right: 1px solid #FFFFFF;
            }
            table th:last-child {
                border-right: none;
            }
            table thead tr th {
                vertical-align: middle;
            }
            table td {
                color: #333333;
                text-align: center;
                font-size: 11px;
            }
        </style>
    </head>
    <!--Eliminar Estado de Cuenta Modal-->
    <div class="modal fade" id="modalEliminarEstadoCuenta" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Estado de Cuenta</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Estado de Cuenta?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_estado_cuenta" name="eliminar_estado_cuenta"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Estado de Cuenta Modal-->
    <!--Historial NIC Estado de Cuenta Modal-->
    <div class="modal fade" id="modalHistorialNIC" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title" id="modal-title-histNIC"></h4>
                </div>
                <div class="modal-body" id="modal-body-hist">
                    <div class='table-responsive'>
                        <table id="table_nic_ano" class='table table-condensed table-hover'>
                            <thead>
                                <tr>
                                    <th width=10%>PERIODO</th>
                                    <th width=10%>SV</th>
                                    <th width=10%>VALOR FACTURA</th>
                                    <th width=10%>FECHA FACTURA</th>
                                    <th width=10%>VALOR RECAUDO</th>
                                    <th width=10%>FECHA RECAUDO</th>
                                    <th width=10%>DEUDA MES</th>
                                    <th width=10%>DETALLE</th>
                                </tr>
                            </thead>
                            <div style='margin-top: 0px;'>
                                <span id='loading-spinner-historial-ano' style='display: none; float: left;'><img src='Images/squares.gif' /></span>
                            </div>
                            <tbody id='resultado_nic_ano'>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Historial NIC Estado de Cuenta Modal-->
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-newspaper"></i>
                                                                        <span>Estado Cuentas</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Estado_Cuentas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-newspaper"></i>
                                                                                    <span>Estado Cuentas</span>
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
                                            <h1>Estado de Cuentas</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <!--<li role="presentation">
                                                    <a href="#informacion_estado_cuentas_tab" id="tab_info_estado_cuentas" aria-controls="informacion_estado_cuentas_tab" role="tab" data-toggle="tab">Información Estado Cuentas</a>
                                                </li>-->
                                                <li role="presentation" class="active">
                                                    <a href="#crear_acta_interventoria_tab" id="tab_crear_acta_interventoria" aria-controls="crear_acta_interventoria_tab" role="tab" data-toggle="tab">Generar Estado de Cuenta</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade" id="informacion_estado_cuentas_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Estado Cuenta" name="buscar_estado_cuenta" id="buscar_estado_cuenta" />
                                                    <br />
                                                    <?php
                                                        $query_select_estado_cuentas = mysqli_query($connection, "SELECT * FROM estado_cuentas_2 ORDER BY ID_COD_DPTO, ID_COD_MPIO");
                                                        if (mysqli_num_rows($query_select_estado_cuentas) != 0) {
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
                                                                    echo "<tbody id='resultado_estado_cuentas'>";
                                                                        
                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Estado de Cuentas Generadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-estado_cuentas"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade in active" id="crear_acta_interventoria_tab">
                                                    <?php
                                                        if (isset($_GET['id_estado_cuenta_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="generar_estado_cuenta" name="generar_estado_cuenta" action="<?php echo "Modelo/Generar_Estado_Cuenta.php?editar=" . $_GET['id_estado_cuenta_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_estado_cuenta = mysqli_query($connection, "SELECT * FROM estado_cuentas_2 WHERE ID_ESTADO_CUENTA = " . $_GET['id_estado_cuenta_editar']);
                                                            $row_estado_cuenta = mysqli_fetch_array($query_select_estado_cuenta);
                                                        ?>
                                                            <input type="hidden" id="id_estado_cuenta_editar_hidden" name="id_estado_cuenta_editar_hidden" value="<?php echo $row_estado_cuenta['ID_ESTADO_CUENTA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_estado_cuenta_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="generar_estado_cuenta" name="generar_estado_cuenta" action="<?php echo "Modelo/Generar_Estado_Cuenta.php?eliminar=" . $_GET['id_estado_cuenta_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_estado_cuenta = mysqli_query($connection, "SELECT * FROM estado_cuentas_2 WHERE ID_ESTADO_CUENTA = " . $_GET['id_estado_cuenta_eliminar']);
                                                                $row_estado_cuenta = mysqli_fetch_array($query_select_estado_cuenta);
                                                            ?>
                                                                <input type="hidden" id="id_estado_cuenta_eliminar_hidden" name="id_estado_cuenta_eliminar_hidden" value="<?php echo $row_estado_cuenta['ID_ESTADO_CUENTA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="generar_estado_cuenta" name="generar_estado_cuenta" action="<?php echo "Modelo/Generar_Estado_Cuenta.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_estado_cuenta">Fecha E.C.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_estado_cuenta" data-toogle="tooltip" title="FECHA ESTADO CUENTA">
                                                                    <?php
                                                                    if (isset($_GET['id_estado_cuenta_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_estado_cuenta" value="<?php echo $row_estado_cuenta['FECHA_ESTADO_CUENTA'] ?>" placeholder="F. Estado Cuenta" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_estado_cuenta_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_estado_cuenta" value="<?php echo $row_estado_cuenta['FECHA_ESTADO_CUENTA'] ?>" placeholder="F. Estado Cuenta" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                            <span class="input-group-addon">
                                                                                <span class="far fa-calendar-alt"></span>
                                                                            </span>
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_estado_cuenta" value="" placeholder="F. Estado Cuenta" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN DPTO. / MUNICIPIO</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="departamento">Dpto:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_estado_cuenta_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_2 WHERE ID_DEPARTAMENTO = " . $row_estado_cuenta['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_estado_cuenta_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_2 WHERE ID_DEPARTAMENTO = " . $row_estado_cuenta['ID_COD_DPTO']);
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
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="municipio">Mpio:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_estado_cuenta_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_2 WHERE ID_DEPARTAMENTO = " . $row_estado_cuenta['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_estado_cuenta['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_estado_cuenta_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_2 WHERE ID_DEPARTAMENTO = " . $row_estado_cuenta['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_estado_cuenta['ID_COD_MPIO']);
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
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="periodo_estado_cuenta">Periodo:</label>
                                                            <div class="col-xs-7">
                                                                <?php
                                                                    if (isset($_GET['id_estado_cuenta_editar'])) { ?>
                                                                        <input type="hidden" id="id_ano_estado_cuenta" name="id_ano_estado_cuenta" value="<?php echo substr($row_estado_cuenta['PERIODO_ACTA'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_estado_cuenta" name="id_mes_estado_cuenta" value="<?php echo substr($row_estado_cuenta['PERIODO_ACTA'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                            $query_select_periodo_estado_cuenta = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                          . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                          . " WHERE ANO_FACTURA = " . substr($row_estado_cuenta['PERIODO_ACTA'], 0, 4) . " "
                                                                                                                                          . "   AND MES_FACTURA = " . substr($row_estado_cuenta['PERIODO_ACTA'], 4, 2));
                                                                            $row_periodo_acta = mysqli_fetch_array($query_select_periodo_estado_cuenta);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_estado_cuenta" name="periodo_estado_cuenta" value="<?php echo $row_periodo_acta['PERIODO'] . " - " . substr($row_estado_cuenta['PERIODO_ACTA'], 0, 4); ?>" placeholder="Periodo Acta" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO ACTA" onclick="periodoActas()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_estado_cuenta_eliminar'])) { ?>
                                                                            <input type="hidden" id="id_ano_estado_cuenta" name="id_ano_estado_cuenta" value="<?php substr($row_estado_cuenta['PERIODO_ACTA'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_estado_cuenta" name="id_mes_estado_cuenta" value="<?php substr($row_estado_cuenta['PERIODO_ACTA'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                                $query_select_periodo_estado_cuenta = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                        . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                        . " WHERE ANO_FACTURA = " . substr($row_estado_cuenta['PERIODO_ACTA'], 0, 4) . " "
                                                                                                                                        . "   AND MES_FACTURA = " . substr($row_estado_cuenta['PERIODO_ACTA'], 4, 2));
                                                                                $row_periodo_acta = mysqli_fetch_array($query_select_periodo_estado_cuenta);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_estado_cuenta" name="periodo_estado_cuenta" value="<?php echo $row_periodo_acta['PERIODO'] . " - " . substr($row_estado_cuenta['PERIODO_ACTA'], 0, 4); ?>" placeholder="Periodo Acta" data-toggle="tooltip" readonly="readonly" title="PERIODO ACTA" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_estado_cuenta" name="periodo_estado_cuenta" placeholder="Periodo Estado Cuenta" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO ESTADO CUENTA" onclick="anoEstadoCuenta(5)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span>RANGO DEUDA</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="deuda_desde">Desde:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="deuda_desde" data-toogle="tooltip" title="DEUDA DESDE">
                                                                    <span class="input-group-addon">
                                                                        <span class="fas fa-dollar-sign"></span>
                                                                    </span>
                                                                    <?php
                                                                        if (isset($_GET['id_estado_cuenta_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="deuda_desde" value="<?php echo $row_estado_cuenta['DEUDA_DESDE'] ?>" maxlength="25" placeholder="Deuda Desde" required="required" onblur="convertDeudaDesde();" onchange="return convertDeudaDesde();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                        } else {
                                                                            if (isset($_GET['id_estado_cuenta_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="deuda_desde" value="<?php echo $row_estado_cuenta['DEUDA_DESDE'] ?>" placeholder="Deuda Desde" onblur="convertDeudaDesde();" onchange="return convertDeudaDesde();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="deuda_desde" value="" maxlength="25" placeholder="Deuda Desde" required="required" onblur="convertDeudaDesde();" onchange="return convertDeudaDesde();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="deuda_hasta">Hasta:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="deuda_hasta" data-toogle="tooltip" title="DEUDA HASTA">
                                                                    <span class="input-group-addon">
                                                                        <span class="fas fa-dollar-sign"></span>
                                                                    </span>
                                                                    <?php
                                                                        if (isset($_GET['id_estado_cuenta_editar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="deuda_hasta" value="<?php echo $row_estado_cuenta['DEUDA_HASTA'] ?>" maxlength="25" placeholder="Deuda Hasta" required="required" onblur="convertDeudaHasta();" onchange="return convertDeudaHasta();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                        } else {
                                                                            if (isset($_GET['id_estado_cuenta_eliminar'])) { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="deuda_hasta" value="<?php echo $row_estado_cuenta['DEUDA_HASTA'] ?>" placeholder="Deuda Hasta" onblur="convertDeudaHasta();" onchange="return convertDeudaHasta();" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                            } else { ?>
                                                                                <input type="text" class="form-control input-text input-sm" name="deuda_hasta" value="" maxlength="25" placeholder="Deuda Hasta" required="required" onblur="convertDeudaHasta();" onchange="return convertDeudaHasta();" onkeypress="return isNumeric(event)" />
                                                                            <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-1">
                                                                <span id="loading-spinner_estado_cuenta" style="display: none; float: left;"><img src="Images/squares.gif" /></span>
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
                                                                    if (isset($_GET['id_estado_cuenta_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_consultar_estado_cuenta" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Estado Cuenta</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Estado_Cuentas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_estado_cuenta_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_consultar_estado_cuenta" type="button" data-toggle="modal" data-target="#modalEliminarEstadoCuenta"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Estado Cuenta</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Estado_Cuentas.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldEstadoCuenta();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div style="margin-bottom: 15px; margin-top: 15px;" class="divider"></div>
                                                    <div style="text-align: right; margin-bottom: 10px;" class="col-xs-12">
                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_consultar_estado_cuenta" type="submit"><i style="font-size: 14px;" class="fas fa-search"></i>&nbsp;&nbsp;Consultar Estado Cuenta</button>&nbsp;&nbsp;
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <div style="padding-left: 0px; padding-right: 0px;" class="col-xs-12">
                                                        <h2 class="text-divider"><span style="background-color: #FFFFFF;">RESUMEN ESTADO DE CUENTA</span></h2>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <div style="padding-left: 0px; padding-right: 0px;" class="col-xs-12">
                                                        <div id="resultado_consulta_estado_cuenta" class="table-responsive">
                                                            <table class="table table-condensed table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th width=5%>#</th>
                                                                        <th width=5%>PERIODO</th>
                                                                        <th width=10%>NIC</th>
                                                                        <th width=50%>NOMBRE</th>
                                                                        <th width=10%>DEUDA COR.</th>
                                                                        <th width=10%>CART. FAL.</th>
                                                                        <th width=4%>EXP.</th>
                                                                        <th width=5%>SELEC.</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
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
        function resetFieldEstadoCuenta() {
            $("#id_departamento").val("");
            $("#id_municipio").val("");
            $("#id_ano_estado_cuenta").val("");
            $("#id_mes_estado_cuenta").val("");
            $("#resultado_consulta_estado_cuenta").html("");
            $("input[name=fecha_estado_cuenta]").focus();
            $("#btn_generar_cobros").css("display", "none");
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
        function convertDeudaDesde() {
            var deudaDesde = $("input[name=deuda_desde]").val();
            var replaceDeudaDesde = deudaDesde.replace(/,/g, '');
            var newDeudaDesde = replaceDeudaDesde.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=deuda_desde]").val(newDeudaDesde);
        }
        function convertDeudaHasta() {
            var deudaHasta = $("input[name=deuda_hasta]").val();
            var replaceDeudaHasta = deudaHasta.replace(/,/g, '');
            var newDeudaHasta = replaceDeudaHasta.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=deuda_hasta]").val(newDeudaHasta);
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
            window.open("Combos/Tipo_Departamento.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
            if (id_consulta == 1) {
                $("#id_municipio").val(id_municipio);
                $("#municipio").val(municipio);
                $("#id_ano_estado_cuenta").val("");
                $("#id_mes_estado_cuenta").val("");
                $("#periodo_estado_cuenta").val("");
                $("#periodo_estado_cuenta").focus();
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_departamento;
            if (id_consulta == 1) {
                id_departamento = $("#id_departamento").val();
            }
            window.open("Combos/Tipo_Municipio.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function infoAnoEstadoCuenta(id_consulta, id_ano) {
            switch (id_consulta) {
                case '5':
                    $("#periodo_estado_cuenta").val(id_ano);
                    break;
            }
        }
        function anoEstadoCuenta(id_consulta) {
            window.open("Combos/Anos_Estado_Cuenta.php?id_consulta="+id_consulta, "Popup", "width=400, height=500");
        }
        function generarWordCobros(){
            var nics = "";
            var fecha_estado_cuenta = $("input[name=fecha_estado_cuenta]").val();
            var departamento = $("#id_departamento").val();
            var municipio = $("#id_municipio").val();
            var checkBoxes = document.getElementsByName("nic");
            for (var i=0;i<checkBoxes.length;i++) {
                if (checkBoxes[i].checked === true) {
                    nics = nics + checkBoxes[i].value + ",";
                }
            }
            var allNics =  nics.slice(0, -1);
            window.location.href = 'Combos/Generar_Word_Cobros.php?nics='+allNics+'&departamento='+departamento+'&municipio='+municipio+'&fecha_estado_cuenta='+fecha_estado_cuenta;
        }
        //END POPUPS
    </script>
    <script>
        $(document).ready(function() {
            var sw = 5;
            $("input[name=fecha_estado_cuenta]").focus();
            $("#tab_info_estado_cuentas").on("shown.bs.tab", function() {
                $("#buscar_estado_cuenta").focus();
            });
            $("#tab_info_estado_cuentas").on("click", function() {
                $("#buscar_estado_cuenta").focus();
            });
            $("#tab_crear_acta_interventoria").on("shown.bs.tab", function() {
                sw = 5;
                $("input[name=fecha_estado_cuenta]").focus();
            });
            $("#tab_crear_acta_interventoria").on("click", function() {
                sw = 5;
                $("input[name=fecha_estado_cuenta]").focus();
            });
            $("#fecha_estado_cuenta").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#btn_consultar_estado_cuenta").click(function() {
                switch(sw) {
                    case 5:
                        var fecha_estado_cuenta = $("input[name=fecha_estado_cuenta]").val();
                        var departamento = $("#id_departamento").val();
                        var municipio = $("#id_municipio").val();
                        var deuda_desde = $("input[name=deuda_desde]").val();
                        if (deuda_desde !== "") {
                            deuda_desde = deuda_desde.replace(/,/g, "");
                        }
                        var deuda_hasta = $("input[name=deuda_hasta]").val();
                        if (deuda_hasta !== "") {
                            deuda_hasta = deuda_hasta.replace(/,/g, "");
                        }
                        var periodo_estado_cuenta = $("#periodo_estado_cuenta").val().slice(0, -1);
                        if (fecha_estado_cuenta.length == 0) {
                            $("input[name=fecha_estado_cuenta]").focus();
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
                        if (periodo_estado_cuenta.length == 0) {
                            $("#periodo_estado_cuenta").focus();
                            return false;
                        }
                        if (deuda_desde.length == 0) {
                            $("input[name=deuda_desde]").focus();
                            return false;
                        }
                        if (deuda_hasta.length == 0) {
                            $("input[name=deuda_hasta]").focus();
                            return false;
                        }
                        $("#loading-spinner_estado_cuenta").css("display", "block");
                        $("#btn_consultar_estado_cuenta").attr("disabled", true);
                        $("#btn_consultar_estado_cuenta").css("pointer-events", "none");
                        $("#btn_consultar_estado_cuenta").html("Generando Estado Cuenta...");
                        $.ajax({
                            type: "POST",
                            data: "departamento="+departamento+
                                  "&municipio="+municipio+
                                  "&periodo_estado_cuenta="+periodo_estado_cuenta+
                                  "&deuda_desde="+deuda_desde+
                                  "&deuda_hasta="+deuda_hasta,
                            dataType: "json",
                            url: "Modelo/Generar_Informe_Estado_Cuenta.php",
                            success: function(data) {
                                $("#loading-spinner_estado_cuenta").css("display", "none");
                                $("#btn_consultar_estado_cuenta").attr("disabled", false);
                                $("#btn_consultar_estado_cuenta").css("pointer-events", "auto");
                                $("#btn_consultar_estado_cuenta").html("<i style='font-size: 14px;' class='fas fa-search'></i>&nbsp;&nbsp;Consultar Estado Cuenta&nbsp;&nbsp;");
                                $("#resultado_consulta_estado_cuenta").html("");
                                $("#resultado_consulta_estado_cuenta").html(data[0]);
                                $("#btn_generar_cobros").css("display", "inline-block");
                            }
                        });
                        break;
                }
            });
            $("#btn_generar_cobros").click(function() {
                var nics = "";
                var fecha_estado_cuenta = $("input[name=fecha_estado_cuenta]").val();
                var departamento = $("#id_departamento").val();
                var municipio = $("#id_municipio").val();
                var checkBoxes = document.getElementsByName("nic");
                for (var i=0;i<checkBoxes.length;i++) {
                    if (checkBoxes[i].checked === true) {
                        nics = nics + checkBoxes[i].value + ",";
                    }
                }
                //alert(departamento + " - " + municipio + " - " + nics);
                var allNics =  nics.slice(0, -1);
                window.location.href = 'Combos/Generar_Word_Cobros.php?nics='+allNics+'&departamento='+departamento+'&municipio='+municipio+'&fecha_estado_cuenta='+fecha_estado_cuenta;
            });
            $("#modalHistorialNIC").on('show.bs.modal', function(e) {
                $("#modal-title-histNIC").html("");
                $("#resultado_nic_periodo").html("");
                var nic_ano_factura = e.relatedTarget.id;
                var nic = nic_ano_factura.substr(0, 7);
                var ano_periodo = nic_ano_factura.substr(7, 4);
                $("#loading-spinner-historial-ano").css('display', 'block');
                $("#modal-title-histNIC").html("");
                $("#table_nic_ano").css('display', 'none');
                $("#resultado_nic_ano").html("");
                $.ajax ({
                    type: "POST",
                    url: "Modelo/Cargar_Historial_Factura_Recaudo.php",
                    dataType: "json",
                    data: "detalle_id="+nic+"&ano_factura="+ano_periodo+"&hist=ano",
                    success: function(data) {
                        $("#loading-spinner-historial-ano").css('display', 'none');
                        $("#modal-title-histNIC").html(data[0]);
                        $("#table_nic_ano").css('display', 'block');
                        $("#resultado_nic_ano").html(data[1]);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#fecha_estado_cuenta').tooltip({
                container : "body",
                placement : "right"
            });
            $('input[type=text][name=departamento]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=municipio]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=periodo_estado_cuenta]').tooltip({
                container: "body",
                placement: "top"
            });
            $('#deuda_desde').tooltip({
                container : "body",
                placement : "top"
            });
            $('#deuda_hasta').tooltip({
                container : "body",
                placement : "right"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>