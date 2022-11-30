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
        <title>AGM - Cargue Catastro</title>
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
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Electricaribe.php");?>
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
                                                                    <a href="#">
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-upload"></i>
                                                                        <span>Cargue Archivos</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Cargue_Catastro.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-contract"></i>
                                                                                    <span>Cargue Catastro</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Archivos_Catastro.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-copy"></i>
                                                                                    <span>Archivos Cargados</span>
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
                                            <h1>Cargue Catastro</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#cargar_catastro_tab" id="tab_cargar_catastro" aria-controls="cargar_catastro_tab" role="tab" data-toggle="tab">Cargar Catastro</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="cargar_catastro_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_catastro" name="cargar_catastro" action="Modelo/Subir_Archivos_Mes.php?archivo=catastro" method="post" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <div class="col-xs-2">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="ano_factura" name="ano_factura" data-toggle="tooltip" title="AÑO FACTURACIÓN">
                                                                        <option value="" selected="selected">-</option>
                                                                        <!--<option value="2017">2017</option>
                                                                        <option value="2018">2018</option>
                                                                        <option value="2019">2019</option>-->
                                                                        <option value="2020">2020</option>
                                                                        <option value="2021">2021</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="mes_consolidado" name="mes_consolidado" data-toggle="tooltip" title="MES CONSOLIDADO">
                                                                        <option value="" selected="selected">-</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="departamento" name="departamento" data-toggle="tooltip" title="DEPARTAMENTO">
                                                                        <option value="" selected="selected">-</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="municipio" name="municipio" data-toggle="tooltip" title="MUNICIPIO">
                                                                        <option value="" selected="selected">-</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-1">
                                                                <span id="loading-spinner" style="display: none; float: left;"><img src="Images/squares.gif" /></span>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 5px;" class="form-group">
                                                            <div class="col-xs-12">
                                                                <div class="styled-select">
                                                                    <input type="file" name="files[]" id="files" class="inputfile inputfile-1" data-multiple-caption="{count} Archivos Seleccionados" multiple />
                                                                    <label id="label_files" for="files"><i class="fas fa-folder-open"></i> <span>Seleccionar Archivo(s)&hellip;</span></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div style="margin-bottom: 5px;" class="form-group">
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
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <h2 class="text-divider"><span style="background-color: none;">INFORMACIÓN DEL ARCHIVO CARGADO</span></h2>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div id="informacion-cargada">
                                                                    
                                                                </div>
                                                                <?php
                                                                    /*if (isset($_POST['upload_files'])) {
                                                                        $filename = $_FILES['files']['name'];
                                                                        $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 WHERE RUTA = '" . $filename . "'");
                                                                        if (mysqli_num_rows($query_select_ruta) == 0) {
                                                                            if (substr($filename, 0, 4) == 'CATA') {
                                                                                $total_deuda_corriente = 0;
                                                                                $total_deuda_cuota = 0;
                                                                                $ano_factura = $_POST['ano_factura'];
                                                                                $mes_consolidado = $_POST['mes_consolidado'];
                                                                                $departamento = $_POST['departamento'];
                                                                                $municipio = $_POST['municipio'];
                                                                                switch ($mes_consolidado) {
                                                                                    case "Enero": $id_mes = 1; break;
                                                                                    case "Febrero": $id_mes = 2; break;
                                                                                    case "Marzo": $id_mes = 3; break;
                                                                                    case "Abril": $id_mes = 4; break;
                                                                                    case "Mayo": $id_mes = 5; break;
                                                                                    case "Junio": $id_mes = 6; break;
                                                                                    case "Julio": $id_mes = 7; break;
                                                                                    case "Agosto": $id_mes = 8; break;
                                                                                    case "Septiembre": $id_mes = 9; break;
                                                                                    case "Octubre": $id_mes = 10; break;
                                                                                    case "Noviembre": $id_mes = 11; break;
                                                                                    case "Diciembre": $id_mes = 12; break;
                                                                                }
                                                                                $registros = array();
                                                                                $fecha_creacion = date('Y-m-d');
                                                                                $id_usuario = $_SESSION['id_user'];
                                                                                echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/". $mes_consolidado . "/Catastro/" . $departamento . "/" . $municipio . "/" . $filename;
                                                                                echo "<br />";
                                                                                $data = file($fullpath);
                                                                                $i = 0;
                                                                                $registros = array();
                                                                                mysqli_query($connection, "INSERT INTO archivos_cargados_catastro_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                                                                                 . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                                                                                                                                         . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', "
                                                                                                                                         . "'" . $filename . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                                                                                $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 WHERE RUTA = '" . $filename . "'");
                                                                                $row_filename = mysqli_fetch_array($query_select_filename);
                                                                                $id_tabla_ruta = $row_filename['ID_TABLA'];
                                                                                foreach ($data as $lines) {
                                                                                    $registros[] = explode("|", $lines);
                                                                                    //echo implode("\t", $registros[$i]);
                                                                                    $query_select_tipo_servicio = mysqli_query($connection, "SELECT * FROM tipo_servicios_2 WHERE COD_TIPO_SERVICIO = '" . strtoupper(trim($registros[$i][0])) . "'");
                                                                                    $row_tipo_servicio = mysqli_fetch_array($query_select_tipo_servicio);
                                                                                    if (mysqli_num_rows($query_select_tipo_servicio) == 0) {
                                                                                        mysqli_query($connection, "INSERT INTO tipo_servicios_2 (COD_TIPO_SERVICIO, NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][0])) . "', '" . strtoupper(trim($registros[$i][1])) . "')");
                                                                                        echo "<br />";
                                                                                        $query_select_new_tipo_servicio = mysqli_query($connection, "SELECT * FROM tipo_servicios_2 WHERE COD_TIPO_SERVICIO = '" . strtoupper(trim($registros[$i][0])) . "'");
                                                                                        $row_new_tipo_servicio = mysqli_fetch_array($query_select_new_tipo_servicio);
                                                                                        echo "Registro agregado en la tabla 'tipo_servicios_2': " . strtoupper(trim($registros[$i][0])) . " - " . strtoupper(trim($registros[$i][1]));
                                                                                        echo "<br />";
                                                                                        $id_tipo_servicio = trim($row_new_tipo_servicio['ID_TIPO_SERVICIO']);
                                                                                    } else {
                                                                                        $id_tipo_servicio = trim($row_tipo_servicio['ID_TIPO_SERVICIO']);
                                                                                    }
                                                                                    $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(str_replace(" ", "_", trim($registros[$i][3]))) . "'");
                                                                                    $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                                                                    if (mysqli_num_rows($query_select_tarifa) == 0) {
                                                                                        mysqli_query($connection, "INSERT INTO tarifas_2 (COD_TARIFA, NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][2])) . "', '" . strtoupper(trim(str_replace(" ", "_", $registros[$i][3]))) . "')");
                                                                                        $query_select_new_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(str_replace(" ", "_", trim($registros[$i][3]))) . "'");
                                                                                        $row_new_tarifa = mysqli_fetch_array($query_select_new_tarifa);
                                                                                        echo "Registro agregado en la tabla 'tarifas_2': " . strtoupper(trim($registros[$i][2])) . " - " . strtoupper(str_replace(" ", "_", trim($registros[$i][3])));
                                                                                        echo "<br />";
                                                                                        $id_tarifa = trim($row_new_tarifa['ID_TARIFA']);
                                                                                    } else {
                                                                                        if ($row_tarifa['COD_TARIFA'] == "") {
                                                                                            mysqli_query($connection, "UPDATE tarifas_2 SET COD_TARIFA = '" . strtoupper(trim($registros[$i][2])) . "' "
                                                                                                                                   . "WHERE NOMBRE = '" . strtoupper(str_replace(" ", "_", trim($registros[$i][3]))) . "'");
                                                                                            echo "Registro actualizado en la tabla 'tarifas_2': " . strtoupper(trim($registros[$i][2])) . " - " . strtoupper(str_replace(" ", "_", trim($registros[$i][3])));
                                                                                            echo "<br />";
                                                                                        }
                                                                                        $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                                                                                    }
                                                                                    $nic = trim($registros[$i][4]);
                                                                                    $nis = trim($registros[$i][5]);
                                                                                    $nombre_propietario = strtoupper(trim(str_replace("'", "", $registros[$i][6])));
                                                                                    $direccion_vivienda = strtoupper(trim(str_replace("'", "", $registros[$i][7])));
                                                                                    $consumo_facturado = trim(str_replace(",", ".", $registros[$i][8]));
                                                                                    $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_2 WHERE NOMBRE = '" . strtoupper(str_replace("_", " ", trim($registros[$i][9]))) . "'");
                                                                                    $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                                                                                    $id_departamento = $row_id_municipio["ID_DEPARTAMENTO"];
                                                                                    $id_municipio = $row_id_municipio["ID_MUNICIPIO"];
                                                                                    $query_select_corregimiento = mysqli_query($connection, "SELECT * "
                                                                                                                                            . "FROM corregimientos_2 "
                                                                                                                                           . "WHERE ID_DEPARTAMENTO = " . $id_departamento . " "
                                                                                                                                             . "AND ID_MUNICIPIO = " . $id_municipio . " "
                                                                                                                                             . "AND NOMBRE = '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . "'");
                                                                                    $row_corregimiento = mysqli_fetch_array($query_select_corregimiento);
                                                                                    if (mysqli_num_rows($query_select_corregimiento) == 0) {
                                                                                        $query_select_id_corregimiento = mysqli_query($connection, "SELECT * FROM facturacion_enero2019_2 WHERE NIC = " . $nic . " LIMIT 1");
                                                                                        if (mysqli_num_rows($query_select_id_corregimiento) != 0) {
                                                                                            $row_id_corregimiento = mysqli_fetch_array($query_select_id_corregimiento);
                                                                                            $id_corregimiento = $row_id_corregimiento['ID_COD_CORREG'];
                                                                                            mysqli_query($connection, "INSERT INTO corregimientos_2 (ID_DEPARTAMENTO, ID_MUNICIPIO, ID_CORREGIMIENTO, NOMBRE) "
                                                                                                                            . "VALUES ('$id_departamento', '$id_municipio', '$id_corregimiento', '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . "')");
                                                                                            $query_select_new_corregimiento = mysqli_query($connection, "SELECT MAX(ID_TABLA) AS ID_TABLA FROM corregimientos_2");
                                                                                            $row_new_corregimiento = mysqli_fetch_array($query_select_new_corregimiento);
                                                                                            $id_corregimiento = $row_new_corregimiento['ID_TABLA'];
                                                                                            echo "Registro agregado en la tabla 'corregimientos_2': " . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10])));
                                                                                            echo "<br />";
                                                                                            
                                                                                        } else {
                                                                                            $query_select_id_corregimiento = mysqli_query($connection, "SELECT * FROM recaudo_enero2019_2 WHERE NIC = " . $nic . " LIMIT 1");
                                                                                            if (mysqli_num_rows($query_select_id_corregimiento) != 0) {
                                                                                                $row_id_corregimiento = mysqli_fetch_array($query_select_id_corregimiento);
                                                                                                $id_corregimiento = $row_id_corregimiento['ID_COD_CORREG'];
                                                                                                mysqli_query($connection, "INSERT INTO corregimientos_2 (ID_DEPARTAMENTO, ID_MUNICIPIO, ID_CORREGIMIENTO, NOMBRE) "
                                                                                                                                . "VALUES ('$id_departamento', '$id_municipio', '$id_corregimiento', '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . "')");
                                                                                                $query_select_new_corregimiento = mysqli_query($connection, "SELECT MAX(ID_TABLA) AS ID_TABLA FROM corregimientos_2");
                                                                                                $row_new_corregimiento = mysqli_fetch_array($query_select_new_corregimiento);
                                                                                                $id_corregimiento = $row_new_corregimiento['ID_TABLA'];
                                                                                                echo "Registro agregado en la tabla 'corregimientos_2': " . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10])));
                                                                                                echo "<br />";
                                                                                            } else {
                                                                                                $query_select_max_id_corregimiento = mysqli_query($connection, "SELECT MAX(ID_CORREGIMIENTO) AS ID_CORREGIMIENTO "
                                                                                                                                                               . "FROM corregimientos_2 "
                                                                                                                                                              . "WHERE ID_DEPARTAMENTO = " . $id_departamento . " "
                                                                                                                                                                . "AND ID_MUNICIPIO = " . $id_municipio);
                                                                                                $row_max_id_corregimiento = mysqli_fetch_array($query_select_max_id_corregimiento);
                                                                                                $id_corregimiento = $row_max_id_corregimiento['ID_CORREGIMIENTO'] + 1;
                                                                                                mysqli_query($connection, "INSERT INTO corregimientos_2 (ID_DEPARTAMENTO, ID_MUNICIPIO, ID_CORREGIMIENTO, NOMBRE) "
                                                                                                                                . "VALUES ('$id_departamento', '$id_municipio', '$id_corregimiento', '" . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10]))) . "')");
                                                                                                $query_select_new_corregimiento = mysqli_query($connection, "SELECT MAX(ID_TABLA) AS ID_TABLA FROM corregimientos_2");
                                                                                                $row_new_corregimiento = mysqli_fetch_array($query_select_new_corregimiento);
                                                                                                $id_corregimiento = $row_new_corregimiento['ID_TABLA'];
                                                                                                echo "Registro agregado en la tabla 'corregimientos_2': " . strtoupper(trim(str_replace("Ñ", "N", $registros[$i][10])));
                                                                                                echo "<br />";
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        $id_corregimiento = $row_corregimiento['ID_TABLA'];
                                                                                    }
                                                                                    $deuda_corriente = trim(str_replace(",", ".", $registros[$i][11]));
                                                                                    $deuda_cuota = trim(str_replace(",", ".", $registros[$i][12]));
                                                                                    $query_select_estado_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][13])) . "'");
                                                                                    $row_estado_suministro = mysqli_fetch_array($query_select_estado_suministro);
                                                                                    if (mysqli_num_rows($query_select_estado_suministro) == 0) {
                                                                                        mysqli_query($connection, "INSERT INTO estados_suministro_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][13])) . "')");
                                                                                        $query_select_new_estado_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][13])) . "'");
                                                                                        $row_new_estado_suministro = mysqli_fetch_array($query_select_new_estado_suministro);
                                                                                        echo "Registro agregado en la tabla 'estados_suministro_2': " . strtoupper(trim($registros[$i][13]));
                                                                                        echo "<br />";
                                                                                        $id_estado_suministro = trim($row_new_estado_suministro['ID_ESTADO_SUMINISTRO']);
                                                                                    } else {
                                                                                        $id_estado_suministro = trim($row_estado_suministro['ID_ESTADO_SUMINISTRO']);
                                                                                    }
                                                                                    $total_deuda_corriente = $total_deuda_corriente + $registros[$i][11];
                                                                                    $total_deuda_cuota = $total_deuda_cuota + $registros[$i][12];
                                                                                    mysqli_query($connection, "INSERT INTO catastro_" . strtolower($mes_consolidado) . $ano_factura . "_2 (ID_TIPO_SERVICIO, ID_TARIFA, NIC, NIS, "
                                                                                                                                        . "NOMBRE_PROPIETARIO, DIRECCION_VIVIENDA, CONSUMO_FACTURADO, ID_COD_DPTO, ID_COD_MPIO, "
                                                                                                                                        . "ID_COD_CORREG, DEUDA_CORRIENTE, DEUDA_CUOTA, ID_ESTADO_SUMINISTRO, ANO_CATASTRO, MES_CATASTRO, "
                                                                                                                                        . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                                                                               . "VALUES ('$id_tipo_servicio', '$id_tarifa', '$nic', '$nis', '$nombre_propietario', '$direccion_vivienda', "
                                                                                                                                       . "'$consumo_facturado', '$id_departamento', '$id_municipio', '$id_corregimiento', '$deuda_corriente', "
                                                                                                                                       . "'$deuda_cuota', '$id_estado_suministro', '$ano_factura', '$id_mes', '$id_tabla_ruta', "
                                                                                                                                       . "'$fecha_creacion', '$id_usuario')");
                                                                                    $i++;
                                                                                }
                                                                                $query_select_totales = mysqli_query($connection, "SELECT COUNT(*), SUM(DEUDA_CORRIENTE), SUM(DEUDA_CUOTA) "
                                                                                                                                . "  FROM catastro_" . strtolower($mes_consolidado) . $ano_factura . "_2 "
                                                                                                                                . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                                                                                $row_totales = mysqli_fetch_array($query_select_totales);
                                                                                echo "<br />";
                                                                                echo "<p class='message'>Registros cargados: " . $i . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                                                                                echo "<p class='message'>Valor Deuda Corriente cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_deuda_corriente, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(DEUDA_CORRIENTE)'], 0, ',', '.') . ".</p>";
                                                                                echo "<p class='message'>Valor Deuda Cuota cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_deuda_cuota, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(DEUDA_CUOTA)'], 0, ',', '.') . ".</p>";
                                                                                echo "<p class='message'>Archivo cargado con Exito.</p>";
                                                                            } else {
                                                                                echo "<p class='message'>El archivo que intenta cargar no es de Catastro. Favor verificar la Información.</p>";
                                                                            }
                                                                        } else {
                                                                            echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
                                                                        }
                                                                    }*/
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!--<script src="http://malsup.github.io/jquery.form.js"></script>Progress Bar Script with Form-->
    <script src="Javascript/bootstrap.min.js"></script>
    <script src="Javascript/menu.js"></script>
    <script src="Javascript/moment-with-locales.js"></script>
    <script src="Javascript/bootstrap-datetimepicker.js"></script>
    <script src="Javascript/jquery.twbsPagination.js"></script>
    <script src="Javascript/custom-file-input.js"></script>
    <script src="Javascript/jquery.form.js"></script><!--Progress Bar Script with Form-->
    <script>
        $(document).ready(function() {
            $("#ano_factura").focus();
            $("#ano_factura").change(function() {
                var ano_factura = $(this).val();
                $("#loading-spinner").css("display", "block");
                $.ajax({
                    type: "POST",
                    data: "sw=cata&ano_factura="+ano_factura,
                    url: "Modelo/Cargar_Meses.php",
                    success: function(data) {
                        $("#loading-spinner").css("display", "none");
                        $("#mes_consolidado").html(data);
                        $("#mes_consolidado").focus();
                    }
                });
            });
            $("#mes_consolidado").change(function() {
                var ano_factura = $("#ano_factura").val();
                var mes_consolidado = $(this).val();
                $("#loading-spinner").css("display", "block");
                $.ajax({
                    type: "POST",
                    data: "sw=cata&ano_factura="+ano_factura+"&mes_consolidado="+mes_consolidado,
                    url: "Modelo/Cargar_Departamentos.php",
                    success: function(data) {
                        $("#loading-spinner").css("display", "none");
                        $("#departamento").html(data);
                        $("#departamento").focus();
                    }
                });
            });
            $("#departamento").change(function() {
                var ano_factura = $("#ano_factura").val()
                var mes_consolidado = $("#mes_consolidado").val();
                var departamento = $(this).val();
                $("#loading-spinner").css("display", "block");
                $.ajax({
                    type: "POST",
                    data: "sw=cata&ano_factura="+ano_factura+"&mes_consolidado="+mes_consolidado+"&departamento="+departamento,
                    url: "Modelo/Cargar_Municipios.php",
                    success: function(data) {
                        $("#loading-spinner").css("display", "none");
                        $("#municipio").html(data);
                        $("#municipio").focus();
                    }
                });
            });
            $("#upload_files").click(function() {
                var ano_factura = $("#ano_factura").val();
                var mes_consolidado = $("#mes_consolidado").val();
                var departamento = $("#departamento").val();
                var municipio = $("#municipio").val();
                if (ano_factura.length == 0) {
                    $("#ano_factura").focus();
                }
                if (mes_consolidado.length == 0) {
                    $("#mes_consolidado").focus();
                }
                if (departamento.length == 0) {
                    $("#departamento").focus();
                }
                if (municipio.length == 0) {
                    $("#municipio").focus();
                }
                $("#upload_files").attr("disabled", true);
                $("#upload_files").css("pointer-events", "none");
                $("#upload_files").html("Subiendo Archivo(s)...");
                $("html, body").css("cursor", "wait");
                $(".wrapper").css("cursor", "wait");
                $(".tab-content").css("cursor", "wait");
                $("#cargar_catastro").ajaxSubmit({
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
                        $("#progressBarFile").addClass("progress-bar-success");
                        $("#informacion-cargada").html(data);
                        $("html, body").css("cursor", "default");
                        $(".wrapper").css("cursor", "default");
                        $(".tab-content").css("cursor", "default");
                        $("#modalUpload").modal("show");
                    }
                });
                return false;
            });
            $("#modalUpload").on("hidden.bs.modal", function() {
                $("#ano_factura").prop("selectedIndex", 0);
                $("#mes_consolidado").prop("selectedIndex", 0);
                $("#departamento").prop("selectedIndex", 0);
                $("#municipio").prop("selectedIndex", 0);
                $("#files").val("");
                $("#label_files").html("<i class='fas fa-folder-open mr-3'></i> <span>Seleccionar Archivo(s)&hellip;</span>");
                $("#progressBarFile").width("0%");
                $("#progressBarFile").text("0%");
                $("#progressBarFile").removeClass("progress-bar-success");
                $("#upload_files").attr("disabled", false);
                $("#upload_files").css("pointer-events", "auto");
                $("#upload_files").html("<i style='font-size: 14px;' class='fas fa-upload mr-3'></i>&nbsp;&nbsp;Subir Archivo");
                $("#loading-spinner-progressBar").css("display", "none");
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('select[name=mes_consolidado]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=departamento]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=municipio]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=ano_factura]').tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_electricaribe").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>