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
        <title>AGM - Admin. Clientes Especiales</title>
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
    <!--Eliminar Cliente Especial Modal-->
    <div class="modal fade" id="modalEliminarCliEsp" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Eliminar Cliente Especial</h4>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el Cliente?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger btn-sm font background-danger cursor" id="eliminar_cli_esp" name="eliminar_cli_esp"><i class="fas fa-trash-alt fa-fw"></i> Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Eliminar Cliente Especial Modal-->
    <!--Download Modal-->
    <div class="modal fade" id="modalDownload" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Descarga Exitosa</h4>
                </div>
                <div class="modal-body">
                    <p>El Archivo Plano se descargó de forma Exitosa.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Download Modal-->
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-user-tie"></i>
                                                                        <span>Fact. Municipio</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Admin_Especiales.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-address-card"></i>
                                                                                    <span>Admin. Clientes Espc.</span>
                                                                                </a>
                                                                            </li>
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
                                            <h1>Admin. Clientes Especiales</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#informacion_cli_especiales_tab" id="tab_info_cli_especiales" aria-controls="informacion_cli_especiales_tab" role="tab" data-toggle="tab">Información Clientes Especiales</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#crear_cli_especiales_tab" id="tab_crear_cli_especiales" aria-controls="crear_cli_especiales_tab" role="tab" data-toggle="tab">Crear Cliente Especial</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#generar_plano_cli_especiales_tab" id="tab_generar_plano_cli_especiales" aria-controls="generar_plano_cli_especiales_tab" role="tab" data-toggle="tab">Generar Plano Cliente Especial</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="informacion_cli_especiales_tab">
                                                    <input class="form-control input-text input-sm" type="text" placeholder="Buscar Cliente Especial" name="buscar_cli_esp" id="buscar_cli_esp" />
                                                    <br />
                                                    <?php
                                                        $query_select_cli_esp = "SELECT * FROM clientes_especiales_2 ORDER BY ID_COD_DPTO, ID_COD_MPIO";
                                                        $sql_cli_esp = mysqli_query($connection, $query_select_cli_esp);
                                                        if (mysqli_num_rows($sql_cli_esp) != 0) {
                                                            echo "<div class='table-responsive'>";
                                                                echo "<table class='table table-condensed table-hover'>";
                                                                    echo "<thead>";
                                                                        echo "<tr>";
                                                                            echo "<th width=10%>NIC</th>";
                                                                            echo "<th width=35%>CLIENTE ESPECIAL</th>";
                                                                            echo "<th width=13%>DEPARTAMENTO</th>";
                                                                            echo "<th width=17%>MUNICIPIO</th>";
                                                                            echo "<th width=11%>VALOR IMP.</th>";
                                                                            echo "<th width=5%>DETALLE</th>";
                                                                            echo "<th width=5%>ELIMINAR</th>";
                                                                        echo "</tr>";
                                                                    echo "</thead>";
                                                                    echo "<tbody id='resultado_cli_esp'>";
                                                                        
                                                                    echo "</tbody>";
                                                                echo "</table>";
                                                            echo "</div>";
                                                        } else {
                                                            echo "<p class='message'>No se encontraron Cliente Especiales Creados.</p>";
                                                        }
                                                    ?>
                                                    <div id="div-pagination">
                                                        <ul id="pagination-cli_esp"></ul>
                                                        <span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="crear_cli_especiales_tab">
                                                    <?php
                                                        if (isset($_GET['id_cli_especial_editar'])) { ?>
                                                            <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_cli_especial" name="crear_cli_especial" action="<?php echo "Modelo/Crear_Admin_Cli_Esp.php?editar=" . $_GET['id_cli_especial_editar']; ?>" method="post">
                                                        <?php
                                                            $query_select_cli_especial = mysqli_query($connection, "SELECT * FROM clientes_especiales_2 WHERE ID_TABLA = " . $_GET['id_cli_especial_editar']);
                                                            $row_cli_especial = mysqli_fetch_array($query_select_cli_especial);
                                                        ?>
                                                            <input type="hidden" id="id_cli_especial_editar_hidden" name="id_cli_especial_editar_hidden" value="<?php echo $row_cli_especial['ID_TABLA']; ?>" />
                                                        <?php
                                                        } else {
                                                            if (isset($_GET['id_cli_especial_eliminar'])) { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_cli_especial" name="crear_cli_especial" action="<?php echo "Modelo/Crear_Admin_Cli_Esp.php?eliminar=" . $_GET['id_cli_especial_eliminar']; ?>" method="post">
                                                            <?php
                                                                $query_select_cli_especial = mysqli_query($connection, "SELECT * FROM clientes_especiales_2 WHERE ID_TABLA = " . $_GET['id_cli_especial_eliminar']);
                                                                $row_cli_especial = mysqli_fetch_array($query_select_cli_especial);
                                                            ?>
                                                                <input type="hidden" id="id_cli_especial_eliminar_hidden" name="id_cli_especial_eliminar_hidden" value="<?php echo $row_cli_especial['ID_TABLA']; ?>" />
                                                            <?php
                                                            } else { ?>
                                                                <form class="form-horizontal row-bottom-buffer row-top-buffer" id="crear_cli_especial" name="crear_cli_especial" action="<?php echo "Modelo/Crear_Admin_Cli_Esp.php"; ?>" method="post">
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                    ?>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN CLIENTE ESPECIAL / DPTO. & MUNICIPIO</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nic_cliente">NIC:</label>
                                                            <div class="col-xs-3">
                                                                <?php
                                                                    if (isset($_GET['id_cli_especial_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nic_cliente" name="nic_cliente" value="<?php echo $row_cli_especial['NIC']; ?>" maxlength="10" placeholder="NIC" data-toogle="tooltip" title="NIC" onkeypress="return isNumeric(event)" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_cli_especial_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nic_cliente" name="nic_cliente" readonly="readonly" placeholder="NIC" value="<?php echo $row_cli_especial['NIC']; ?>" data-toogle="tooltip" title="NIC" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nic_cliente" name="nic_cliente" maxlength="10" placeholder="NIC" data-toogle="tooltip" title="NIC" onkeypress="return isNumeric(event)" required />
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="nombre_cliente">Nombre:</label>
                                                            <div class="col-xs-7">
                                                                <?php
                                                                    if (isset($_GET['id_cli_especial_editar'])) { ?>
                                                                        <input type="text" class="form-control input-text input-sm" id="nombre_cliente" name="nombre_cliente" value="<?php echo $row_cli_especial['CLIENTE_ESPECIAL']; ?>" maxlength="100" placeholder="Nombre" data-toogle="tooltip" title="NOMBRE" required />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_cli_especial_eliminar'])) { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_cliente" name="nombre_cliente" readonly="readonly" placeholder="Nombre" value="<?php echo $row_cli_especial['CLIENTE_ESPECIAL']; ?>" data-toogle="tooltip" title="NOMBRE" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <input type="text" class="form-control input-text input-sm" id="nombre_cliente" name="nombre_cliente" maxlength="100" placeholder="Nombre" data-toogle="tooltip" title="NOMBRE" required />
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
                                                                    if (isset($_GET['id_cli_especial_editar'])) {
                                                                        $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_cli_especial['ID_COD_DPTO']);
                                                                        $row_departamento = mysqli_fetch_array($query_select_departamento);
                                                                    ?>
                                                                        <input type="hidden" id="id_departamento" name="id_departamento" value="<?php echo $row_departamento['ID_DEPARTAMENTO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="departamento" name="departamento" value="<?php echo $row_departamento['NOMBRE']; ?>" placeholder="Departamento" required="required" data-toggle="tooltip" readonly="readonly" title="DEPARTAMENTO" onclick="tipoDepartamento(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_cli_especial_eliminar'])) {
                                                                            $query_select_departamento = mysqli_query($connection, "SELECT * FROM departamentos_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_cli_especial['ID_COD_DPTO']);
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
                                                                    if (isset($_GET['id_cli_especial_editar'])) {
                                                                        $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_cli_especial['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_cli_especial['ID_COD_MPIO']);
                                                                        $row_municipio = mysqli_fetch_array($query_select_municipio);
                                                                    ?>
                                                                        <input type="hidden" id="id_municipio" name="id_municipio" value="<?php echo $row_municipio['ID_MUNICIPIO']; ?>" required="required" />
                                                                        <input type="text" class="form-control input-text input-sm" id="municipio" name="municipio" value="<?php echo $row_municipio['NOMBRE']; ?>" placeholder="Municipio" required="required" data-toggle="tooltip" readonly="readonly" title="MUNICIPIO" onclick="tipoMunicipio(1)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_cli_especial_eliminar'])) {
                                                                            $query_select_municipio = mysqli_query($connection, "SELECT * FROM municipios_visitas_2 WHERE ID_DEPARTAMENTO = " . $row_cli_especial['ID_COD_DPTO'] . " AND ID_MUNICIPIO = " . $row_cli_especial['ID_COD_MPIO']);
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
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN VALOR IMPORTE / CONCEPTOS</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="valor_importe">Valor Imp.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group" id="valor_importe" data-toogle="tooltip" title="VALOR IMPORTE">
                                                                    <?php
                                                                    if (isset($_GET['id_cli_especial_editar'])) { ?>
                                                                        <span class="input-group-addon">
                                                                            <span class="fas fa-dollar-sign"></span>
                                                                        </span>
                                                                        <input type="text" class="form-control input-text input-sm" name="valor_importe" value="<?php echo $row_cli_especial['VALOR_IMPORTE'] ?>" maxlength="25" placeholder="Valor Importe" onblur="convertValorImporte();" onchange="return convertValorImporte()" onkeypress="return isNumeric(event)" />
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_cli_especial_eliminar'])) { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_importe" value="<?php echo $row_cli_especial['VALOR_IMPORTE'] ?>" placeholder="Valor Importe" onblur="convertValorImporte();" onchange="return convertValorImporte()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        } else { ?>
                                                                            <span class="input-group-addon">
                                                                                <span class="fas fa-dollar-sign"></span>
                                                                            </span>
                                                                            <input type="text" class="form-control input-text input-sm" name="valor_importe" value="" maxlength="25" placeholder="Valor Importe" required="required" onblur="convertValorImporte();" onchange="return convertValorImporte()" onkeypress="return isNumeric(event)" />
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="concepto">Concepto:</label>
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_cli_especial_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="concepto" name="concepto" data-toggle="tooltip" title="CONCEPTO" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_cli_especial_eliminar'])) { ?>
                                                                                <select class="form-control input-text input-sm" id="concepto" name="concepto" disabled="disabled" data-toggle="tooltip" title="CONCEPTO" required>
                                                                            <?php
                                                                            } else { ?>
                                                                                <select class="form-control input-text input-sm" id="concepto" name="concepto" data-toggle="tooltip" title="CONCEPTO" required>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                            <option value="" selected="selected">-</option>
                                                                            <option value="CC700">CC700</option>
                                                                            <option value="CC703">CC703</option>
                                                                            <?php
                                                                                if (isset($_GET['id_cli_especial_editar']) || isset($_GET['id_cli_especial_eliminar'])) { ?>
                                                                                    <input type="hidden" id="concepto_hidden" name="concepto_hidden" value="<?php echo $row_cli_especial['CONCEPTO']; ?>" />
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="tipo_servicio">Tipo Serv.:</label>
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <?php
                                                                        if (isset($_GET['id_cli_especial_editar'])) { ?>
                                                                            <select class="form-control input-text input-sm" id="tipo_servicio" name="tipo_servicio" data-toggle="tooltip" title="TIPO SERVICIO" required>
                                                                        <?php
                                                                        } else {
                                                                            if (isset($_GET['id_cli_especial_eliminar'])) { ?>
                                                                                <select class="form-control input-text input-sm" id="tipo_servicio" name="tipo_servicio" disabled="disabled" data-toggle="tooltip" title="TIPO SERVICIO" required>
                                                                            <?php
                                                                            } else { ?>
                                                                                <select class="form-control input-text input-sm" id="tipo_servicio" name="tipo_servicio" data-toggle="tooltip" title="TIPO SERVICIO" required>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                            <option value="" selected="selected">-</option>
                                                                            <option value="SV500">SV500</option>
                                                                            <?php
                                                                                if (isset($_GET['id_cli_especial_editar']) || isset($_GET['id_cli_especial_eliminar'])) { ?>
                                                                                    <input type="hidden" id="tipo_servicio_hidden" name="tipo_servicio_hidden" value="<?php echo $row_cli_especial['TIPO_SERVICIO']; ?>" />
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
                                                        <div style="margin-bottom: 0px;" class="form-group">
                                                            <div style="text-align: center;" class="col-xs-12">
                                                                <?php
                                                                    if (isset($_GET['id_cli_especial_editar'])) { ?>
                                                                        <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_cliente_especial" type="submit"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Actualizar Cliente Esp.</button>&nbsp;&nbsp;
                                                                        <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Especiales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                    <?php
                                                                    } else {
                                                                        if (isset($_GET['id_cli_especial_eliminar'])) { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_cliente_especial" type="button" data-toggle="modal" data-target="#modalEliminarCliEsp"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Eliminar Cliente Esp.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="document.location.href = 'Admin_Especiales.php'"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        } else { ?>
                                                                            <button class="btn btn-primary btn-sm font background cursor" id="btn_crear_cliente_especial" type="button"><i style="font-size: 14px;" class="fas fa-save"></i>&nbsp;&nbsp;Crear Cliente Esp.</button>&nbsp;&nbsp;
                                                                            <button type="reset" class="btn btn-danger btn-sm font background-danger cursor" onclick="return resetFieldClienteEspecial();"><i style="font-size: 14px;" class="fas fa-times"></i>&nbsp;&nbsp;Cancelar</button>
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="generar_plano_cli_especiales_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="generar_plano_cli_especial" name="generar_plano_cli_especial" action="Modelo/Generar_Plano.php?archivo=cli_esp" method="post">
                                                        <div class="form-group">
                                                            <label style="text-align: left; padding-top: 4px; white-space: nowrap;" class="col-xs-1 control-label row-label" for="fecha_validez">Fecha Val.</label>
                                                            <div class="col-xs-3">
                                                                <div class="input-group date" id="fecha_validez" data-toogle="tooltip" title="FECHA VALIDEZ">
                                                                    <input type="text" class="form-control input-text input-sm" name="fecha_validez" value="" placeholder="Fecha Validez" required="required" onkeypress="return isNothing(event)" onkeydown="return isNothing(event)" />
                                                                    <span class="input-group-addon">
                                                                        <span class="far fa-calendar-alt"></span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2">
                                                                <div class="styled-select">
                                                                    <button class="btn btn-primary btn-sm font background cursor" type="submit" name="btn_generar_plano" id="btn_generar_plano"><i style="font-size: 14px;" class="fas fa-download"></i>&nbsp;&nbsp;Generar Plano</button>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <div style="margin-bottom: 10px;" class="progress">
                                                                    <div id="progressBarFile" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-1">
                                                                <span id="loading-spinner-progressBar" style="display: none; float: left;"><img src="Images/squares.gif" /></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN DEL ARCHIVO PLANO GENERADO</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div id="informacion-plano_especiales"></div>
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
    <script src="Javascript/custom-file-input.js"></script>
    <script src="http://malsup.github.io/jquery.form.js"></script><!--Progress Bar Script with Form-->
    <script>
        function resetFieldClienteEspecial() {
            $("#id_departamento").val("");
            $("#id_municipio").val("");
            $("#nic_cliente").focus();
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
        function convertValorImporte() {
            var valImporte = $("input[name=valor_importe]").val();
            var replaceValImporte = valImporte.replace(/,/g, '');
            var newValImporte = replaceValImporte.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("input[name=valor_importe]").val(newValImporte);
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
                $("#").focus();
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
            $("#buscar_cli_esp").focus();
            var id_cli_especial_editar = $("#id_cli_especial_editar_hidden").val();
            var id_cli_especial_eliminar = $("#id_cli_especial_eliminar_hidden").val();
            if (id_cli_especial_editar != undefined) {
                convertValorImporte();
                $(".nav-pills a[href='#crear_cli_especiales_tab']").tab("show");
                $(".nav-pills a[href='#crear_cli_especiales_tab']").text("Actualizar Cliente Especial");
                $("#concepto").val($("#concepto_hidden").val());
                $("#tipo_servicio").val($("#tipo_servicio_hidden").val());
            } else {
                if (id_cli_especial_eliminar != undefined) {
                    convertValorImporte();
                    $(".nav-pills a[href='#crear_cli_especiales_tab']").tab("show");
                    $(".nav-pills a[href='#crear_cli_especiales_tab']").text("Eliminar Cliente Especial");
                    $("#concepto").val($("#concepto_hidden").val());
                    $("#tipo_servicio").val($("#tipo_servicio_hidden").val());
                }
            }
            $("#buscar_cli_esp").keypress(function(e) {
                if (e.which == 13) {
                    var busqueda_cliente;
                    if ($(this).val() == "") {
                        busqueda_cliente = "";
                    } else {
                        busqueda_cliente = $(this).val().toUpperCase();
                    }
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Admin_Cli_Esp.php",
                        dataType: "json",
                        data: "sw=1&busqueda_cliente="+busqueda_cliente,
                        success: function(data) {
                            $("#pagination-cli_esp").twbsPagination('destroy');
                            $("#pagination-cli_esp").twbsPagination({
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
                                        url: "Modelo/Cargar_Admin_Cli_Esp.php",
                                        dataType: "json",
                                        data: "sw=1&busqueda_cliente="+data[1]+"&page="+page,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_cli_esp").html(data[0]);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#fecha_validez").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $("#tab_info_cli_especiales").on("shown.bs.tab", function() {
                $("#buscar_cli_esp").focus();
            });
            $("#tab_crear_cli_especiales").on("shown.bs.tab", function() {
                $("#nic_cliente").focus();
            });
            $("#tab_generar_plano_cli_especiales").on("shown.bs.tab", function() {
                $("input[name=fecha_validez]").focus();
            });
            $("#tab_info_cli_especiales").on("click", function() {
                $("#buscar_cli_esp").focus();
            });
            $("#tab_crear_cli_especiales").on("click", function() {
                $("#nic_cliente").focus();
            });
            $("#tab_generar_plano_cli_especiales").on("click", function() {
                $("input[name=fecha_validez]").focus();
            });
            if (id_cli_especial_editar == undefined && id_cli_especial_eliminar == undefined) {
                $("#btn_crear_cliente_especial").click(function() {
                    var nic_cliente = $("#nic_cliente").val();
                    var nombre_cliente = $("#nombre_cliente").val();
                    var departamento = $("#id_departamento").val();
                    var municipio = $("#id_municipio").val();
                    var valor_importe = $("input[name=valor_importe]").val();
                    if (valor_importe != "") {
                        valor_importe = valor_importe.replace(/,/g, "");
                    }
                    var concepto = $("#concepto").val();
                    var tipo_servicio = $("#tipo_servicio").val();
                    if (nic_cliente.length == 0) {
                        $("#nic_cliente").focus();
                        return false;
                    }
                    if (nombre_cliente.length == 0) {
                        $("#nombre_cliente").focus();
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
                    if (valor_importe.length == 0) {
                        $("input[name=valor_importe]").focus();
                        return false;
                    }
                    if (concepto.length == 0) {
                        $("#concepto").focus();
                        return false;
                    }
                    if (tipo_servicio.length == 0) {
                        $("#tipo_servicio").focus();
                        return false;
                    }
                    $("#btn_crear_cliente_especial").attr("disabled", true);
                    $("#btn_crear_cliente_especial").css("pointer-events", "none");
                    $("#btn_crear_cliente_especial").html("Creando Cliente Especial...");
                    $.ajax({
                        type: "POST",
                        data: "nic_cliente="+nic_cliente+
                              "&nombre_cliente="+nombre_cliente+
                              "&departamento="+departamento+
                              "&municipio="+municipio+
                              "&valor_importe="+valor_importe+
                              "&concepto="+concepto+
                              "&tipo_servicio="+tipo_servicio,
                        url: "Modelo/Crear_Admin_Cli_Esp.php",
                        success: function(data) {
                            document.location.href = 'Admin_Especiales.php?id_cli_especial_editar='+data;
                        }
                    });
                });
            }
            $("#eliminar_cli_esp").click(function() {
                $("#crear_cli_especial").submit();
            });
            $.ajax({
                type: "POST",
                url: "Modelo/Cargar_Paginacion_Admin_Cli_Esp.php",
                dataType: "json",
                data: "sw=0",
                success: function(data) {
                    $("#pagination-cli_esp").twbsPagination('destroy');
                    $("#pagination-cli_esp").twbsPagination({
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
                                url: "Modelo/Cargar_Admin_Cli_Esp.php",
                                dataType: "json",
                                data: "sw=0&page="+page,
                                success: function(data) {
                                    $("#loading-spinner").css('display', 'none');
                                    $("#resultado_cli_esp").html(data[0]);
                                }
                            });
                        }
                    });
                }
            });
            $("#btn_generar_plano").click(function() {
                var fecha_validez = $("input[name=fecha_validez]").val();
                if (fecha_validez.length == 0) {
                    $("input[name=fecha_validez]").focus();
                    return false;
                }
                $("#btn_generar_plano").attr("disabled", true);
                $("#btn_generar_plano").css("pointer-events", "none");
                $("#btn_generar_plano").html("Generando Plano...");
                $("html, body").css("cursor", "wait");
                $(".wrapper").css("cursor", "wait");
                $(".tab-content").css("cursor", "wait");
                $("#generar_plano_cli_especial").ajaxSubmit({
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
                        $("#informacion-plano_especiales").html(data);
                        $("html, body").css("cursor", "default");
                        $(".wrapper").css("cursor", "default");
                        $(".tab-content").css("cursor", "default");
                        $("#modalDownload").modal("show");
                    }
                });
                return false;
            });
            $("#modalDownload").on("hidden.bs.modal", function() {
                $("#files").val("");
                $("#label_files").html("<i class='fas fa-folder-open mr-3'></i> <span>Seleccionar Archivo(s)&hellip;</span>");
                $("#progressBarFile").width("0%");
                $("#progressBarFile").text("0%");
                $("#btn_generar_plano").attr("disabled", false);
                $("#btn_generar_plano").css("pointer-events", "auto");
                $("#btn_generar_plano").html("<i style='font-size: 14px;' class='fas fa-download mr-3'></i>&nbsp;&nbsp;Generar Plano");
                $("#loading-spinner-progressBar").css("display", "none");
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[type=text][name=nic_cliente]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=nombre_cliente]').tooltip({
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
            $('select[name=concepto]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=tipo_servicio]').tooltip({
                container : "body",
                placement : "top"
            });
            $('#valor_importe').tooltip({
                container: "body",
                placement: "top"
            });
            $('#fecha_validez').tooltip({
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