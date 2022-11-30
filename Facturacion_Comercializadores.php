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
        <title>AGM - Facturación Comercializadores</title>
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
            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }
            .inputfile + label {
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
            .inputfile:focus + label,
            .inputfile.has-focus + label {
                outline: 1px dotted #000;
                outline: -webkit-focus-ring-color auto 5px;
            }
            .inputfile-1 + label {
                border-color: #2C592C;
                color: #FFFFFF;
                background-image: linear-gradient(#6CBF6C, #408040);
                box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
            }
            .inputfile-1:focus + label,
            .inputfile-1.has-focus + label,
            .inputfile-1 + label:hover {
                border-color: #2C592C;
                background-image: linear-gradient(#61AB61, #397339);
                box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
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
                    <p>El (Los) archivo(s) se cargaron de forma Exitosa.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Upload Modal-->
    <!--DETALLE ARCHIVO CARGADO MODAL-->
    <div class="modal fade" id="modalDetalleArchivoFacturacion" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--FIN DETALLE ARCHIVO CARGADO MODAL-->
    <!--Eliminar Factura Comercializadores Modal-->
    <div class="modal fade" id="modalEliminarFactComer" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Factura Comercializadores</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar la Factura?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_fact_comer" name="eliminar_fact_comer"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Factura Comercializadores Modal-->
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-industry"></i>
                                                                        <span>Comercializadores</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Facturacion_Comercializadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check-alt"></i>
                                                                                    <span>Facturación</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Recaudo_Comercializadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check"></i>
                                                                                    <span>Recaudo</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Reportes_Comercializadores.php'>
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
                                            <h1>Facturación Comercializadores</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_fact_comercializadores_tab" id="tab_info_fact_comercializadores" aria-controls="informacion_fact_comercializadores_tab" role="tab" data-toggle="tab">Información Fact. Comer.</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_fact_comercializadores_tab" id="tab_crear_fact_comercializadores" aria-controls="crear_fact_comercializadores_tab" role="tab" data-toggle="tab">Crear Fact. Comer.</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#subir_fact_comercializadores_tab" id="tab_subir_fact_comercializadores" aria-controls="subir_fact_comercializadores_tab" role="tab" data-toggle="tab">Subir Fact. Comer.</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#archivos_fact_comercializadores_tab" id="tab_archivos_fact_comercializadores" aria-controls="archivos_fact_comercializadores_tab" role="tab" data-toggle="tab">Arch. Cargados Fact. Comer.</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_fact_comercializadores_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Factura" name="buscar_factura" id="buscar_factura" />
                                                    <br />
                                                    <?php
                                                        $query_select_fact_comer = "SELECT * FROM facturacion_comercializadores_2 ORDER BY ID_COD_DPTO, ID_COD_MPIO";
                                                        $sql_fact_comer = mysqli_query($connection, $query_select_fact_comer);
                                                        if (mysqli_num_rows($sql_fact_comer) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=4%>ESTADO</th>";
                                                                            echo "<th width=12%>DEPARTAMENTO</th>";
                                                                            echo "<th width=17%>MUNICIPIO</th>";
                                                                            echo "<th width=40%>COMERCIALIZADOR</th>";
                                                                            //echo "<th width=11%>FACTURA</th>";
                                                                            echo "<th width=11%>VALOR</th>";
                                                                            echo "<th width=5%>DETALLE</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_fact_comer'>";
                                                                        
                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                            echo "<p></p>";
                                                            echo "<span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PAGADO.</span>";
                                                            echo "&nbsp;<span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PP</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = PENDIENTE PAGO.</span>";
                                                            //echo "&nbsp;<span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span><span style='color: #003153; font-size: 12px; font-family: Cabin; font-weight: bold;'> = RECLAMADA.</span>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Facturas Comercializadores Creadas.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-fact_comer"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_fact_comercializadores_tab">
                                                    <?php
                                                        if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_comercializador" name="crear_fact_comercializador" action="<?php echo "Modelo/Crear_Fact_Comer.php?editar=" . $_GET['id_fact_comercializador_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_fact_comercializador = mysqli_query($connection, "SELECT * FROM facturacion_comercializadores_2 WHERE ID_FACTURACION = " . $_GET['id_fact_comercializador_editar']);
                                                            $row_fact_comercializador = mysqli_fetch_array($query_select_fact_comercializador);
                                                        ?>
                                                            <input type="hidden" id="id_fact_comercializador_editar_hidden" name="id_fact_comercializador_editar_hidden" value="<?php echo $row_fact_comercializador['ID_FACTURACION']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_comercializador" name="crear_fact_comercializador" action="<?php echo "Modelo/Crear_Fact_Comer.php?eliminar=" . $_GET['id_fact_comercializador_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_fact_comercializador = mysqli_query($connection, "SELECT * FROM facturacion_comercializadores_2 WHERE ID_FACTURACION = " . $_GET['id_fact_comercializador_eliminar']);
                                                                $row_fact_comercializador = mysqli_fetch_array($query_select_fact_comercializador);
                                                            ?>
                                                                <input type="hidden" id="id_fact_comercializador_eliminar_hidden" name="id_fact_comercializador_eliminar_hidden" value="<?php echo $row_fact_comercializador['ID_FACTURACION']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_fact_comercializador" name="crear_fact_comercializador" action="<?php echo "Modelo/Crear_Fact_Comer.php"; ?>" method="post">
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="<?php echo $row_fact_comercializador['FECHA_FACTURA'] ?>" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                        <span class="input-group-addon">
                                                                            <span class="far fa-calendar-alt"></span>
                                                                        </span>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" name="fecha_factura" value="<?php echo $row_fact_comercializador['FECHA_FACTURA'] ?>" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
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
                                                                        if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="estado_factura" name="estado_factura" data-toggle="tooltip" title="ESTADO FACTURA" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
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
                                                                                if (isset($_GET['id_fact_comercializador_editar']) || isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                                    <input type="hidden" id="estado_factura_hidden" name="estado_factura_hidden" value="<?php echo $row_fact_comercializador['ESTADO_FACTURA']; ?>" />
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN COMERCIALIZADOR / CLIENTE</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="comercializador">Comercial.:</label>
                                                            <div class="col-xs-7">
                                                                <?php
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) {
                                                                        $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = " . $row_fact_comercializador['ID_COMERCIALIZADOR']);
                                                                        $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                                                                    ?>
                                                                        <input type="hidden" id="id_comercializador" name="id_comercializador" value="<?php echo $row_comercializador['ID_COMERCIALIZADOR']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="comercializador" name="comercializador" value="<?php echo $row_comercializador['NOMBRE']; ?>" placeholder="Comercializador" required="required" data-toggle="tooltip" readonly="readonly" title="COMERCIALIZADOR" onclick="comercializadorFact(0)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) {
                                                                            $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = " . $row_fact_comercializador['ID_COMERCIALIZADOR']);
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
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nit_comercializador">NIT:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nit_comercializador" name="nit_comercializador" value="<?php echo $row_comercializador['NIT_COMERCIALIZADOR']; ?>" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_comercializador" name="nit_comercializador" value="<?php echo $row_comercializador['NIT_COMERCIALIZADOR']; ?>" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nit_comercializador" name="nit_comercializador" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_comercializador['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_comercializador['ID_COD_DPTO']);
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_comercializador['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_fact_comercializador['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_fact_comercializador['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_fact_comercializador['ID_COD_MPIO']);
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php echo substr($row_fact_comercializador['PERIODO_FACTURA'], 0, 4); ?>" required="required" />
                                                                        <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php echo substr($row_fact_comercializador['PERIODO_FACTURA'], 4, 2); ?>" required="required" />
                                                                        <?php
                                                                            $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                    . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                    . " WHERE ANO_FACTURA = " . substr($row_fact_comercializador['PERIODO_FACTURA'], 0, 4) . " "
                                                                                                                                    . "   AND MES_FACTURA = " . substr($row_fact_comercializador['PERIODO_FACTURA'], 4, 2));
                                                                            $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                        ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_fact_comercializador['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" required="required" data-toggle="tooltip" readonly="readonly" title="PERIODO" onclick="periodoFact()" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <input type="hidden" id="id_ano_fact" name="id_ano_fact" value="<?php substr($row_fact_comercializador['PERIODO_FACTURA'], 0, 4) ?>" required="required" />
                                                                            <input type="hidden" id="id_mes_fact" name="id_mes_fact" value="<?php substr($row_fact_comercializador['PERIODO_FACTURA'], 4, 2) ?>" required="required" />
                                                                            <?php
                                                                                $query_select_periodo_factura = mysqli_query($connection, "SELECT PERIODO "
                                                                                                                                        . "  FROM periodos_facturacion_especiales_2 "
                                                                                                                                        . " WHERE ANO_FACTURA = " . substr($row_fact_comercializador['PERIODO_FACTURA'], 0, 4) . " "
                                                                                                                                        . "   AND MES_FACTURA = " . substr($row_fact_comercializador['PERIODO_FACTURA'], 4, 2));
                                                                                $row_periodo_factura = mysqli_fetch_array($query_select_periodo_factura);
                                                                            ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" value="<?php echo $row_periodo_factura['PERIODO'] . " - " . substr($row_fact_comercializador['PERIODO_FACTURA'], 0, 4); ?>" placeholder="Periodo" data-toggle="tooltip" readonly="readonly" title="PERIODO" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_fact_comercializador['VALOR_FACTURA'] ?>" maxlength="25" placeholder="Valor Factura" onblur="convertValorFactura(); calcularTotalFactura();" onchange="return convertValorFactura(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_factura" value="<?php echo $row_fact_comercializador['VALOR_FACTURA'] ?>" placeholder="Valor Factura" onblur="convertValorFactura(); calcularTotalFactura();" onchange="return convertValorFactura(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="ajuste_fact" value="<?php echo $row_fact_comercializador['AJUSTE_FACT'] ?>" maxlength="25" placeholder="Ajuste Fact." onblur="convertAjusteFact(); calcularTotalFactura();" onchange="return convertAjusteFact(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="ajuste_fact" value="<?php echo $row_fact_comercializador['AJUSTE_FACT'] ?>" placeholder="Ajuste Fact." onblur="convertAjusteFact(); calcularTotalFactura();" onchange="return convertAjusteFact(); calcularTotalFactura();" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="total_factura" value="<?php echo $row_fact_comercializador['VALOR_FACTURA'] + $row_fact_comercializador['AJUSTE_FACT']; ?>" maxlength="25" readonly="readonly" placeholder="Total Factura" onblur="convertTotalFactura();" onchange="return convertTotalFactura()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="total_factura" value="<?php echo $row_fact_comercializador['VALOR_FACTURA'] + $row_fact_comercializador['AJUSTE_FACT']; ?>" readonly="readonly" placeholder="Total Factura" onblur="convertTotalFactura();" onchange="return convertTotalFactura()" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_fact_comercializador['VALOR_RECAUDO'] ?>" maxlength="25" placeholder="Valor Recaudo" onblur="convertValorRecaudo(); calcularTotalRecaudo();" onchange="return convertValorRecaudo(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_recaudo" value="<?php echo $row_fact_comercializador['VALOR_RECAUDO'] ?>" placeholder="Valor Recaudo" onblur="convertValorRecaudo(); calcularTotalRecaudo();" onchange="return convertValorRecaudo(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="ajuste_reca" value="<?php echo $row_fact_comercializador['AJUSTE_RECA'] ?>" maxlength="25" placeholder="Ajuste Reca." onblur="convertAjusteReca(); calcularTotalRecaudo();" onchange="return convertAjusteReca(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="ajuste_reca" value="<?php echo $row_fact_comercializador['AJUSTE_RECA'] ?>" placeholder="Ajuste Reca." onblur="convertAjusteReca(); calcularTotalRecaudo();" onchange="return convertAjusteReca(); calcularTotalRecaudo();" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="total_recaudo" value="<?php echo $row_fact_comercializador['VALOR_RECAUDO'] - $row_fact_comercializador['AJUSTE_RECA']; ?>" maxlength="25" readonly="readonly" placeholder="Total Recaudo" onblur="convertTotalRecaudo();" onchange="return convertTotalRecaudo()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="total_recaudo" value="<?php echo $row_fact_comercializador['VALOR_RECAUDO'] - $row_fact_comercializador['AJUSTE_RECA']; ?>" readonly="readonly" placeholder="Total Recaudo" onblur="convertTotalRecaudo();" onchange="return convertTotalRecaudo()" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_energia" value="<?php echo round($row_fact_comercializador['VALOR_ENERGIA'], 1); ?>" maxlength="25" placeholder="Valor Energía" onblur="convertValorEnergia();" onchange="return convertValorEnergia()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_energia" value="<?php echo round($row_fact_comercializador['VALOR_ENERGIA'], 1); ?>" placeholder="Valor Energía" onblur="convertValorEnergia();" onchange="return convertValorEnergia()" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="cuota_energia" value="<?php echo $row_fact_comercializador['CUOTA_ENERGIA'] ?>" maxlength="25" placeholder="Cuota Energía" onblur="convertCuotaEnergia();" onchange="return convertCuotaEnergia()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="cuota_energia" value="<?php echo $row_fact_comercializador['CUOTA_ENERGIA'] ?>" placeholder="Cuota Energía" onblur="convertCuotaEnergia();" onchange="return convertCuotaEnergia()" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="otros_ajustes" value="<?php echo $row_fact_comercializador['OTROS_AJUSTES'] ?>" maxlength="25" placeholder="Otros Ajustes" onblur="convertOtrosAjustes();" onchange="return convertOtrosAjustes()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="otros_ajustes" value="<?php echo $row_fact_comercializador['OTROS_AJUSTES'] ?>" placeholder="Otros Ajustes" onblur="convertOtrosAjustes();" onchange="return convertOtrosAjustes()" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_favor" value="<?php echo $row_fact_comercializador['VALOR_FAVOR'] ?>" maxlength="25" placeholder="Valor Favor" onblur="convertValorFavor();" onchange="return convertValorFavor()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_favor" value="<?php echo $row_fact_comercializador['VALOR_FAVOR'] ?>" placeholder="Valor Favor" onblur="convertValorFavor();" onchange="return convertValorFavor()" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-hashtag"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="consumo" value="<?php echo round($row_fact_comercializador['CONSUMO'], 2); ?>" maxlength="10" placeholder="Consumo" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="consumo" value="<?php echo round($row_fact_comercializador['CONSUMO'], 2); ?>" readonly="readonly" placeholder="Consumo" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-hashtag"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="no_usuarios" value="<?php echo $row_fact_comercializador['NO_USUARIOS'] ?>" maxlength="10" placeholder="No. Usuarios" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-hashtag"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="no_usuarios" value="<?php echo $row_fact_comercializador['NO_USUARIOS'] ?>" readonly="readonly" placeholder="No. Usuarios" onkeypress="return isNumeric(event)" />
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
                                                                    if (isset($_GET['id_fact_comercializador_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_comercializador" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Factura Comer.</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Facturacion_Comercializadores.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_fact_comercializador_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_comercializador" type="button" data-toggle="modal" data-target="#modalEliminarFactComer"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Factura Comer.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Facturacion_Comercializadores.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_factura_comercializador" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Factura Comer.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldFacturaComercializador();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="subir_fact_comercializadores_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_fact_comer" name="cargar_fact_comer" action="Modelo/Subir_Archivos_Mes.php?archivo=fact_comer" method="post" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_factura_comer">Fecha Fact.</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_factura_comer" data-toogle="tooltip" title="FECHA FACTURA">
                                                                    <input type="text" class="form-control input-text input-sm" name="fecha_factura_comer" value="" placeholder="Fecha Factura" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                    <span class="input-group-addon">
                                                                        <span class="far fa-calendar-alt"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="ano_factura_comer">Periodo:</label>
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="ano_factura_comer" name="ano_factura_comer" data-toggle="tooltip" title="AÑO FACTURACIÓN">
                                                                        <option value="" selected="selected">-</option>
                                                                        <!--<option value="2017">2017</option>
                                                                        <option value="2018">2018</option>
                                                                        <option value="2019">2019</option>
                                                                        <option value="2020">2020</option>-->
                                                                        <option value="2021">2021</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="estado_factura_comer">Est. Fact.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="estado_factura_comer" name="estado_factura_comer" data-toggle="tooltip" title="ESTADO FACTURA" required>
                                                                        <option value="" selected="selected">-</option>
                                                                        <option value="1">PAGADO</option>
                                                                        <option value="2">PENDIENTE DE PAGO</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN COMERCIALIZADOR / CLIENTE</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="comercializador_comer">Comercial.:</label>
                                                            <div class="col-xs-7">
                                                                <input type="hidden" id="id_comercializador_comer" name="id_comercializador_comer" value="" required="required" />
                                                                <input type="text" class="form-control input-text input-sm" id="comercializador_comer" name="comercializador_comer" placeholder="Comercializador" required="required" data-toggle="tooltip" readonly="readonly" title="COMERCIALIZADOR" onclick="comercializadorFact(1)" />
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nit_comercializador_comer">NIT:</label>
                                                            <div class="col-xs-3">
                                                                <input type="text" class="form-control input-text input-sm" id="nit_comercializador_comer" name="nit_comercializador_comer" readonly="readonly" placeholder="NIT" data-toogle="tooltip" title="NIT" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">ARCHIVO COMERCIALIZADOR / CLIENTE</span></h2>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 5px; margin-top: 14px;" class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="styled-select">
                                                                    <input type="file" name="files[]" id="files" class="inputfile inputfile-1" data-multiple-caption="{count} Archivos Seleccionados" multiple />
                                                                    <label id="label_files" for="files"><i class="fas fa-folder-open"></i> <span>Seleccionar Archivo(s)&hellip;</span></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 5px" class="form-group">
                                                            <div class="col-xs-11">
                                                                <div style="margin-bottom: 10px;" class="progress">
                                                                    <div id="progressBarFile" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-1">
                                                                <span id="loading-spinner-progressBar" style="display: none; float: left;"><img src="Images/squares.gif" /></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-2">
                                                                <div class="styled-select">
                                                                    <button class="btn btn-primary btn-sm font background cursor" type="submit" name="upload_files" id="upload_files"><i style="font-size: 14px;" class="fas fa-upload"></i>&nbsp;&nbsp;Subir Archivo</button>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2">
                                                                <div class="styled-select">
                                                                    <button class="btn btn-primary btn-sm font background cursor" type="button" name="detail_process" id="detail_process"><i style="font-size: 14px;" class="fas fa-upload"></i>&nbsp;&nbsp;Procesar Detalle</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN DEL ARCHIVO CARGADO</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div id="informacion-cargada"></div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="archivos_fact_comercializadores_tab">
                                                    <ul class="nav nav-pills ulclass_info" role="tablist">
                                                        <?php
                                                            $sw = 0;
                                                            $array_id_ano = array();
                                                            $array_nombre_ano = array();
                                                            $query_select_ano = mysqli_query($connection, "SELECT DISTINCT(ACC.ANO_FACTURA) AS ANO_FACTURA, AN.ID_ANO AS ID_ANO "
                                                                                                        . "  FROM archivos_cargados_fact_comer_2 ACC, anos_2 AN "
                                                                                                        . " WHERE ACC.ANO_FACTURA = AN.NOMBRE "
                                                                                                        . " ORDER BY AN.NOMBRE");
                                                            if (mysqli_num_rows($query_select_ano) != 0) {
                                                                while ($row_select_ano = mysqli_fetch_assoc($query_select_ano)) {
                                                                    if ($sw == 0) {
                                                                        $sw = 1;
                                                                        $array_id_ano[] = $row_select_ano['ID_ANO'];
                                                                        $array_nombre_ano[] = $row_select_ano['ANO_FACTURA'];
                                                                        $query_select_count_registros = mysqli_query($connection, "SELECT ID_TABLA "
                                                                                                                                . "  FROM archivos_cargados_fact_comer_2 "
                                                                                                                                . " WHERE ANO_FACTURA = '" . $row_select_ano['ANO_FACTURA'] . "'");
                                                                        $count_registros = mysqli_num_rows($query_select_count_registros);
                                                                        ?>
                                                                        <li role="presentation" class="active">
                                                                            <a tabindex="<?php echo $row_select_ano['ID_ANO']; ?>" href="#informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab" id="tab_info_<?php echo $row_select_ano['ANO_FACTURA']; ?>" aria-controls="informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab" role="tab" data-toggle="tab"><?php echo $row_select_ano['ANO_FACTURA']; ?> <sup id="total_archivos_<?php echo $row_select_ano['ANO_FACTURA']; ?>"><b><?php echo $count_registros; ?></b></sup></a>
                                                                        </li>
                                                                        <?php
                                                                    } else {
                                                                        $array_id_ano[] = $row_select_ano['ID_ANO'];
                                                                        $array_nombre_ano[] = $row_select_ano['ANO_FACTURA'];
                                                                        $query_select_count_registros = mysqli_query($connection, "SELECT ID_TABLA "
                                                                                                                                . "  FROM archivos_cargados_fact_comer_2 "
                                                                                                                                . " WHERE ANO_FACTURA = '" . $row_select_ano['ANO_FACTURA'] . "'");
                                                                        $count_registros = mysqli_num_rows($query_select_count_registros);
                                                                        ?>
                                                                        <li role="presentation">
                                                                            <a tabindex="<?php echo $row_select_ano['ID_ANO']; ?>" href="#informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab" id="tab_info_<?php echo $row_select_ano['ANO_FACTURA']; ?>" aria-controls="informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab" role="tab" data-toggle="tab"><?php echo $row_select_ano['ANO_FACTURA']; ?> <sup id="total_archivos_<?php echo $row_select_ano['ANO_FACTURA']; ?>"><b><?php echo $count_registros; ?></b></sup></a>
                                                                        </li>
                                                                        <?php
                                                                    }
                                                                }
                                                            } else {
                                                                echo "<p class='message'>No se encontraron Archivos Cargados de Facturación.</p>";
                                                            }
                                                        ?>
                                                    </ul>
                                                    <h2></h2>
                                                    <div class="tab-content">
                                                        <input class="form-control input-text input-sm" type="text" placeholder="Buscar Archivo" name="buscar_archivo_facturacion_info" id="buscar_archivo_facturacion_info" />
                                                        <br />
                                                        <?php
                                                            $sw = 0;
                                                            $array_pagination = array();
                                                            $query_select_ano = mysqli_query($connection, "SELECT DISTINCT(ACC.ANO_FACTURA), AN.ID_ANO "
                                                                                                        . "  FROM archivos_cargados_fact_comer_2 ACC, anos_2 AN "
                                                                                                        . " WHERE ACC.ANO_FACTURA = AN.NOMBRE "
                                                                                                        . " ORDER BY AN.NOMBRE");
                                                            while ($row_select_ano = mysqli_fetch_assoc($query_select_ano)) {
                                                                if ($sw == 0) {
                                                                    $sw = 1;
                                                                    $id_ano = $row_select_ano['ID_ANO'];
                                                                    $array_pagination[] = "pagination-" . $row_select_ano['ANO_FACTURA'];
                                                                ?>
                                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab">
                                                                    <?php
                                                                        $query_select_archivos = mysqli_query($connection, "SELECT * FROM archivos_cargados_fact_comer_2 "
                                                                                                                         . " WHERE ANO_FACTURA = '" . $row_select_ano['ANO_FACTURA'] . "'"
                                                                                                                         . " ORDER BY PERIODO, ID_COMERCIALIZADOR");
                                                                        if (mysqli_num_rows($query_select_archivos) != 0) {
                                                                            echo "<div class='table-responsive'>";
                                                                                echo "<table class='table table-condensed table-hover'>";
                                                                                    echo "<thead>";
                                                                                        echo "<tr>";
                                                                                            echo "<th width=12%>MES</th>";
                                                                                            echo "<th width=43%>COMERCIALIZADOR</th>";
                                                                                            echo "<th width=40%>NOMBRE ARCHIVO</th>";
                                                                                            echo "<th width=5%>DETALLE</th>";
                                                                                        echo "</tr>";
                                                                                    echo "</thead>";
                                                                                    echo "<tbody id='resultado_archivos_cargados_" . $row_select_ano['ANO_FACTURA'] . "'>";
                                                                                    
                                                                                    echo "</tbody>";
                                                                                echo "</table>";
                                                                            echo "</div>";
                                                                        } else {
                                                                            echo "<br />";
                                                                            echo "<p class='message'>No se encontraron Archivos Cargados de Facturación.</p>";
                                                                        }
                                                                    ?>
                                                                    <div id="div-pagination">
                                                                        <ul id="pagination-<?php echo $row_select_ano['ANO_FACTURA']; ?>"></ul>
                                                                        <span id="loading-spinner-<?php echo $id_ano; ?>" style="display: none; float: right;"><img src="Images/squares.gif" /></span>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                } else {
                                                                    $id_ano = $row_select_ano['ID_ANO'];
                                                                    $array_pagination[] = "pagination-" . $row_select_ano['ANO_FACTURA'];
                                                                    ?>
                                                                    <div role="tabpanel" class="tab-pane fade" id="informacion_<?php echo $row_select_ano['ANO_FACTURA']; ?>_tab">
                                                                        <?php
                                                                            $query_select_archivos = mysqli_query($connection, "SELECT * FROM archivos_cargados_fact_comer_2 "
                                                                                                                             . " WHERE ANO_FACTURA = '" . $row_select_ano['ANO_FACTURA'] . "'"
                                                                                                                             . " ORDER BY PERIODO, ID_COMERCIALIZADOR, RUTA");
                                                                            if (mysqli_num_rows($query_select_archivos) != 0) {
                                                                                echo "<div class='table-responsive'>";
                                                                                    echo "<table class='table table-condensed table-hover'>";
                                                                                        echo "<thead>";
                                                                                            echo "<tr>";
                                                                                                echo "<th width=12%>MES</th>";
                                                                                                echo "<th width=43%>COMERCIALIZADOR</th>";
                                                                                                echo "<th width=40%>NOMBRE ARCHIVO</th>";
                                                                                                echo "<th width=5%>DETALLE</th>";
                                                                                            echo "</tr>";
                                                                                        echo "</thead>";
                                                                                        echo "<tbody id='resultado_archivos_cargados_" . $row_select_ano['ANO_FACTURA'] . "'>";

                                                                                        echo "</tbody>";
                                                                                    echo "</table>";
                                                                                echo "</div>";
                                                                            } else {
                                                                                echo "<br />";
                                                                                echo "<p class='message'>No se encontraron Archivos Cargados de Facturación.</p>";
                                                                            }
                                                                        ?>
                                                                        <div id="div-pagination">
                                                                            <ul id="pagination-<?php echo $row_select_ano['ANO_FACTURA']; ?>"></ul>
                                                                            <span id="loading-spinner-<?php echo $id_ano; ?>" style="display: none; float: right;"><img src="Images/squares.gif" /></span>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                }
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
    <script src="http://malsup.github.io/jquery.form.js"></script><!--Progress Bar Script with Form-->
    <script>
        function paginacionAnos(array_id_ano, array_pagination, array_nombre_ano) {
            //alert("Entra");
            for (var i=0; i<array_id_ano.length; i++) {
                //alert(array_id_ano[i] + " - " + array_pagination[i] + " - " + array_nombre_ano[i]);
                $.ajax({
                    type: "POST",
                    url: "Modelo/Cargar_Paginacion_Archivos.php",
                    dataType: "json",
                    data: "archivo=fact_comer&sw=0&id_ano="+array_id_ano[i]+"&pagination="+array_pagination[i]+"&nombre_ano="+array_nombre_ano[i],
                    success: function(data) {
                        //alert(data[2]);
                        $("#"+data[0]).twbsPagination({
                            totalPages: data[1],
                            visiblePages: 15,
                            first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                            prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                            next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                            last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                            onPageClick: function(event, page) {
                                $("#loading-spinner-"+data[3]).css('display', 'block');
                                $.ajax({
                                    type: "POST",
                                    url: "Modelo/Cargar_Archivos.php",
                                    dataType: "json",
                                    data: "archivo=fact_comer&sw=0"+"&id_ano="+data[3]+"&nombre_ano="+data[2]+"&page="+page,
                                    success: function(data) {
                                        $("#loading-spinner-"+data[2]).css('display', 'none');
                                        $("#resultado_archivos_cargados_"+data[0]).html(data[1]);
                                    }
                                });
                            }
                        });
                    }
                });
            }
        }
        function resetFieldFacturaComercializador() {
            $("#id_departamento").val("");
            $("#id_municipio").val("");
            $("#id_ano_fact").val("");
            $("#id_mes_fact").val("");
            $("#id_comercializador").val("");
            $("#comercializador").focus();
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
            alert("Entra");
            var valor_factura = $("input[name=valor_factura]").val();
            var ajuste_fact = $("input[name=ajuste_fact]").val();
            $("input[name=total_factura]").val(Math.round(valor_factura.replace(/,/g, "") + ajuste_fact.replace(/,/g, "")));
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
        function infoComercializadorFact(id_comercializador, comercializador, nit_comercializador, consulta) {
            if (consulta == 0) {
                $("#id_comercializador").val(id_comercializador);
                $("#comercializador").val(comercializador);
                $("#nit_comercializador").val(nit_comercializador);
                $("#id_departamento").val("");
                $("#departamento").val("");
                $("#id_municipio").val("");
                $("#municipio").val("");
                $("#departamento").focus();
            } else {
                $("#id_comercializador_comer").val(id_comercializador);
                $("#comercializador_comer").val(comercializador);
                $("#nit_comercializador_comer").val(nit_comercializador);
            }
        }
        function comercializadorFact(consulta) {
            window.open("Combos/Comercializador.php?consulta="+consulta, "Popup", "width=550, height=500");
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
            var id_comercializador = $("#id_comercializador").val();
            window.open("Combos/Tipo_Departamento_Comercializador.php?id_consulta="+id_consulta+"&id_comercializador="+id_comercializador, "Popup", "width=400, height=500");
        }
        function infoTipoMunicipio(id_consulta, id_municipio, municipio) {
            if (id_consulta == 1) {
                $("#id_municipio").val(id_municipio);
                $("#municipio").val(municipio);
                $("#periodo_factura").focus();
            }
        }
        function tipoMunicipio(id_consulta) {
            var id_comercializador;
            var id_departamento;
            if (id_consulta == 1) {
                id_comercializador = $("#id_comercializador").val();
                id_departamento = $("#id_departamento").val();
            }
            window.open("Combos/Tipo_Municipio_Comercializador.php?id_departamento="+id_departamento+"&id_consulta="+id_consulta+"&id_comercializador="+id_comercializador, "Popup", "width=400, height=500");
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
            var array_id_ano = <?php echo json_encode($array_id_ano); ?>;
            var array_pagination = <?php echo json_encode($array_pagination); ?>;
            var array_nombre_ano = <?php echo json_encode($array_nombre_ano); ?>;
            paginacionAnos(array_id_ano, array_pagination, array_nombre_ano);
            var id_fact_comercializador_editar = $("#id_fact_comercializador_editar_hidden").val();
            var id_fact_comercializador_eliminar = $("#id_fact_comercializador_eliminar_hidden").val();
            if (id_fact_comercializador_editar != undefined) {
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
                $(".nav-pills a[href='#crear_fact_comercializadores_tab']").tab("show");
                $(".nav-pills a[href='#crear_fact_comercializadores_tab']").text("Actualizar Fact. Comercializador");
                $("#estado_factura").val($("#estado_factura_hidden").val());
            } else {
                if (id_fact_comercializador_eliminar != undefined) {
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
                    $(".nav-pills a[href='#crear_fact_comercializadores_tab']").tab("show");
                    $(".nav-pills a[href='#crear_fact_comercializadores_tab']").text("Eliminar Fact. Comercializador");
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
                        url: "Modelo/Cargar_Paginacion_Fact_Comer.php",
                        dataType: "json",
                        data: "sw=1&busqueda_factura="+busqueda_factura,
                        success: function(data) {
                            $("#pagination-fact_comer").twbsPagination('destroy');
                            $("#pagination-fact_comer").twbsPagination({
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
                                        url: "Modelo/Cargar_Fact_Comer.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_factura="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_fact_comer").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#buscar_archivo_facturacion_info").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_archivo_facturacion;
                    if ($(this).val() == "") {
                        busqueda_archivo_facturacion = " ";
                    } else {
                        busqueda_archivo_facturacion = $(this).val().toUpperCase();
                    }
                    var id_ano = $(".ulclass_info li.active a").attr("tabindex");
                    $("#loading-spinner-"+id_ano).css('display', 'block');
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Archivos.php",
                        dataType: "json",
                        data: "archivo=fact_comer&sw=1&id_ano="+id_ano+"&busqueda_archivo_facturacion="+busqueda_archivo_facturacion,
                        success: function(data) {
                            $("#"+data[0]).twbsPagination('destroy');
                            $("#"+data[0]).twbsPagination({
                                totalPages: data[1],
                                visiblePages: 15,
                                first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                                prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                                next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                                last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                                onPageClick: function(event, page) {
                                    $("#loading-spinner-"+data[3]).css('display', 'block');
                                    $.ajax({
                                        type: "POST",
                                        url: "Modelo/Cargar_Archivos.php",
                                        dataType: "json",
                                        data: "archivo=fact_comer&sw=1&busqueda_archivo_facturacion="+data[4]+"&id_ano="+data[3]+"&nombre_ano="+data[2]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner-"+data[2]).css('display', 'none');
                                            $("#total_archivos_"+data[0]).html('<b>'+data[3]+'</b>');
                                            $("#resultado_archivos_cargados_"+data[0]).html(data[1]);
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
            $("#fecha_factura_comer").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#tab_info_fact_comercializadores").on("shown.bs.tab", function() {
                $("#buscar_factura").focus();
            });
            $("#tab_crear_fact_comercializadores").on("shown.bs.tab", function() {
                $("#comercializador").focus();
            });
            $("#tab_subir_fact_comercializadores").on("shown.bs.tab", function() {
                $("#comercializador_comer").focus();
            });
            $("#tab_archivos_fact_comercializadores").on("shown.bs.tab", function() {
                $("#buscar_archivo_facturacion_info").focus();
            });
            $("#tab_info_fact_comercializadores").on("click", function() {
                $("#buscar_factura").focus();
            });
            $("#tab_crear_fact_comercializadores").on("click", function() {
                $("#comercializador").focus();
            });
            $("#tab_subir_fact_comercializadores").on("click", function() {
                $("#comercializador_comer").focus();
            });
            $(".ulclass_info").on("click", "a", function() {
                $("#buscar_archivo_facturacion_info").focus();
            });
            $("#tab_archivos_fact_comercializadores").on("click", function() {
                $("#buscar_archivo_facturacion_info").focus();
            });
            if (id_fact_comercializador_editar == undefined && id_fact_comercializador_eliminar == undefined) {
                $("#btn_crear_factura_comercializador").click(function() {
                    var fecha_factura = $("input[name=fecha_factura]").val();
                    var estado_factura = $("#estado_factura").val();
                    var comercializador = $("#id_comercializador").val();
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
                    if (comercializador.length == 0) {
                        $("#comercializador").focus();
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
                    $("#btn_crear_factura_comercializador").attr("disabled", true);
                    $("#btn_crear_factura_comercializador").css("pointer-events", "none");
                    $("#btn_crear_factura_comercializador").html("Creando Fact. Comercializador...");
                    $.ajax({
                        type: "POST",
                        data: "fecha_factura="+fecha_factura+
                              "&estado_factura="+estado_factura+
                              "&comercializador="+comercializador+
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
                        url: "Modelo/Crear_Fact_Comer.php",
                        success: function(data) {
                            //alert(data);
                            //$("#observaciones").val(data);
                            document.location.href = 'Facturacion_Comercializadores.php?id_fact_comercializador_editar='+data;
                        }
                    });
                });
            }
            $("#eliminar_fact_comer").click(function() {
                $("#crear_fact_comercializador").submit();
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Fact_Comer.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-fact_comer").twbsPagination('destroy');
                    $("#pagination-fact_comer").twbsPagination({
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
                                url: "Modelo/Cargar_Fact_Comer.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_fact_comer").html(data[0]);
                                }
                            });
                        }
                    });
                }
            });
            $("#upload_files").click(function() {
                var fecha_factura = $("input[name=fecha_factura_comer]").val();
                var ano_factura = $("#ano_factura_comer").val();
                var estado_factura = $("#estado_factura_comer").val();
                var comercializador = $("#id_comercializador_comer").val();
                if (fecha_factura.length == 0) {
                    $("input[name=fecha_factura_comer]").focus();
                    return false;
                }
                if (ano_factura.length == 0) {
                    $("#ano_factura_comer").focus();
                    return false;
                }
                if (estado_factura.length == 0) {
                    $("#estado_factura_comer").focus();
                    return false;
                }
                if (comercializador.length == 0) {
                    $("#comercializador_comer").focus();
                    return false;
                }
                $("#upload_files").attr("disabled", true);
                $("#upload_files").css("pointer-events", "none");
                $("#upload_files").html("Subiendo Archivo(s)...");
                $("html, body").css("cursor", "wait");
                $(".wrapper").css("cursor", "wait");
                $(".tab-content").css("cursor", "wait");
                $("#cargar_fact_comer").ajaxSubmit({
                    beforeSend: function() {
                        $("#progressBarFile").css("display", "block");
                        $("#progressBarFile").width("25%");
                        $("#progressBarFile").text("25%");
                        $("#loading-spinner-progressBar").css("display", "block");
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        $("#progressBarFile").width("50%");
                        $("#progressBarFile").text("50%");
                    },
                    success: function(data) {
                        $("#progressBarFile").width("100%");
                        $("#progressBarFile").text("100%");
                        $("#informacion-cargada").html(data);
                        $("html, body").css("cursor", "default");
                        $(".wrapper").css("cursor", "default");
                        $(".tab-content").css("cursor", "default");
                        $("#modalUpload").modal("show");
                    }
                });
                return false;
            });
            $("#detail_process").click(function() {
                $.ajax ({
                    type: "POST",
                    url: "Modelo/Procesar_Detalles.php",
                    success: function(data) {
                        location.reload();
                    }
                });
            });
            $("#modalUpload").on("hidden.bs.modal", function() {
                $("#files").val("");
                $("#label_files").html("<i class='fas fa-folder-open mr-3'></i> <span>Seleccionar Archivo(s)&hellip;</span>");
                $("#progressBarFile").width("0%");
                $("#progressBarFile").text("0%");
                $("#upload_files").attr("disabled", false);
                $("#upload_files").css("pointer-events", "auto");
                $("#upload_files").html("<i style='font-size: 14px;' class='fas fa-upload mr-3'></i>&nbsp;&nbsp;Subir Archivo");
                $("#loading-spinner-progressBar").css("display", "none");
                location.reload();
            });
            $("#modalDetalleArchivoFacturacion").on('show.bs.modal', function(e) {
                var detalle_id = e.relatedTarget.id;
                $(".modal-title").html("");
                $(".modal-body").html("<div style='margin-bottom: 30px; margin-top: 0px;'><span id='loading-spinner-detalle' style='display: block; float: left;'><img src='Images/squares.gif' /></span></div>");
                $.ajax ({
                    type: "POST",
                    url: "Modelo/Cargar_Detalle_Archivos.php",
                    dataType: "json",
                    data: "archivo=fact_comer&detalle_id="+detalle_id,
                    success: function(data) {
                        $(".modal-title").html(data[0]);
                        $(".modal-body").html(data[1]);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('select[name=estado_factura]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=estado_factura_comer]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=ano_factura_comer]').tooltip({
                container : "body",
                placement : "top"
            });
            $('input[type=text][name=comercializador]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=comercializador_comer]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nit_comercializador]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nit_comercializador_comer]').tooltip({
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
            $('#fecha_factura_comer').tooltip({
                container : "body",
                placement : "top"
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