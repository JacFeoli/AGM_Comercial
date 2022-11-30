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
        <title>AGM - Cargue Recaudo</title>
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
                                                                                <a href='Cargue_Recaudo.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-invoice-dollar"></i>
                                                                                    <span>Cargue Recaudo</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Archivos_Recaudo.php'>
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
                                            <h1>Cargue Recaudo</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#cargar_recaudo_tab" id="tab_cargar_recaudo" aria-controls="cargar_recaudo_tab" role="tab" data-toggle="tab">Cargar Recaudo</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="cargar_recaudo_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_recaudo" name="cargar_recaudo" action="Modelo/Subir_Archivos_Mes.php?archivo=recaudo" method="post" enctype="multipart/form-data">
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
                                                            <div class="col-xs-4">
                                                                <div class="btn-group" data-toggle="buttons">
                                                                    <label class="btn btn-primary cursor font background" name="label_tipo_poblacion" data-toogle="tooltip" title="TIPO POBLACION - RURAL">
                                                                        <input type="radio" class="form-control input-text input-sm" id="tipo_poblacion" name="tipo_poblacion" value="1" required />Rural
                                                                    </label>
                                                                    <label class="btn btn-primary cursor font background" name="label_tipo_poblacion" data-toogle="tooltip" title="TIPO POBLACION - URBANO">
                                                                        <input type="radio" class="form-control input-text input-sm" id="tipo_poblacion" name="tipo_poblacion" value="2" required />Urbano
                                                                    </label>
                                                                </div>
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
                                                                        $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 WHERE RUTA = '" . $filename . "'");
                                                                        if (mysqli_num_rows($query_select_ruta) == 0) {
                                                                            if (substr($filename, 0, 4) == 'RECA') {
                                                                                $total_importe_trans = 0;
                                                                                $total_valor_recibo = 0;
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
                                                                                echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/". $mes_consolidado . "/Recaudo/" . $departamento . "/" . $municipio . "/" . $filename;
                                                                                echo "<br />";
                                                                                $data = file($fullpath);
                                                                                $i = 0;
                                                                                mysqli_query($connection, "INSERT INTO archivos_cargados_recaudo_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                                                                                 . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                                                                                                                                         . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', "
                                                                                                                                         . "'" . $filename . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                                                                                $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 WHERE RUTA = '" . $filename . "'");
                                                                                $row_filename = mysqli_fetch_array($query_select_filename);
                                                                                $id_tabla_ruta = $row_filename['ID_TABLA'];
                                                                                foreach ($data as $lines) {
                                                                                    $registros[] = explode("\t", $lines);
                                                                                    //echo implode("\t", $registros[$i]);
                                                                                    $fecha_proc_reg = trim(substr($registros[$i][0], 0, 4) . "-" . substr($registros[$i][0], 4, 2) . "-" . substr($registros[$i][0], 6, 2));
                                                                                    $cod_oper_cont = trim($registros[$i][1]);
                                                                                    $nic = trim($registros[$i][2]);
                                                                                    $nis = trim($registros[$i][3]);
                                                                                    $sec_nis = trim($registros[$i][4]);
                                                                                    $sec_rec = trim($registros[$i][5]);
                                                                                    $fecha_fact_lect = substr($registros[$i][6], 0, 4) . "-" . substr($registros[$i][6], 4, 2) . "-" . substr($registros[$i][6], 6, 2);
                                                                                    $query_select_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                                                                                    $row_tipo_cliente = mysqli_fetch_array($query_select_tipo_cliente);
                                                                                    if (mysqli_num_rows($query_select_tipo_cliente) == 0) {
                                                                                        mysqli_query($connection, "INSERT INTO tipo_clientes_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][7])) . "')");
                                                                                        echo "<br />";
                                                                                        $query_select_new_tipo_cliente = mysqli_query($connection, "SELECT * FROM tipo_clientes_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][7])) . "'");
                                                                                        $row_new_tipo_cliente = mysqli_fetch_array($query_select_new_tipo_cliente);
                                                                                        echo "Registro agregado en la tabla 'tipo_clientes_2': " . strtoupper(trim($registros[$i][7]));
                                                                                        echo "<br />";
                                                                                        $id_tipo_cliente = trim($row_new_tipo_cliente['ID_TIPO_CLIENTE']);
                                                                                    } else {
                                                                                        $id_tipo_cliente = trim($row_tipo_cliente['ID_TIPO_CLIENTE']);
                                                                                    }
                                                                                    $query_select_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                                                                                    $row_tarifa = mysqli_fetch_array($query_select_tarifa);
                                                                                    if (mysqli_num_rows($query_select_tarifa) == 0) {
                                                                                        mysqli_query($connection, "INSERT INTO tarifas_2 (NOMBRE) VALUES ('" . strtoupper(trim($registros[$i][8])) . "')");
                                                                                        $query_select_new_tarifa = mysqli_query($connection, "SELECT * FROM tarifas_2 WHERE NOMBRE = '" . strtoupper(trim($registros[$i][8])) . "'");
                                                                                        $row_new_tarifa = mysqli_fetch_array($query_select_new_tarifa);
                                                                                        echo "Registro agregado en la tabla 'tarifas_2': " . strtoupper(trim($registros[$i][8]));
                                                                                        echo "<br />";
                                                                                        $id_tarifa = trim($row_new_tarifa['ID_TARIFA']);
                                                                                    } else {
                                                                                        $id_tarifa = trim($row_tarifa['ID_TARIFA']);
                                                                                    }
                                                                                    $id_estado_contrato = trim($registros[$i][9]);
                                                                                    $concepto = trim($registros[$i][10]);
                                                                                    $importe_trans = trim(str_replace(",", ".", $registros[$i][11]));
                                                                                    $total_importe_trans = $total_importe_trans + $registros[$i][11];
                                                                                    //$importe_trans = floatval(str_replace(",", ".", str_replace(".", "", $registros[$i][11])));
                                                                                    $fecha_trans = substr($registros[$i][12], 0, 4) . "-" . substr($registros[$i][12], 4, 2) . "-" . substr($registros[$i][12], 6, 2);
                                                                                    $valor_recibo = trim(str_replace(",", ".", $registros[$i][13]));
                                                                                    $total_valor_recibo = $total_valor_recibo + $registros[$i][13];
                                                                                    $id_sector_dpto = trim($registros[$i][14]);
                                                                                    $id_cod_mpio = trim($registros[$i][15]);
                                                                                    $id_cod_correg = trim($registros[$i][16]);
                                                                                    $id_cod_depto = trim($registros[$i][17]);
                                                                                    mysqli_query($connection, "INSERT INTO recaudo_" . strtolower($mes_consolidado) . $ano_factura . "_2 (FECHA_PROC_REG, COD_OPER_CONT, NIC, NIS, SEC_NIS, SEC_REC, FECHA_FACT_LECT, ID_TIPO_CLIENTE, "
                                                                                                                                        . "ID_TARIFA, ID_ESTADO_CONTRATO, CONCEPTO, IMPORTE_TRANS, FECHA_TRANS, VALOR_RECIBO, "
                                                                                                                                        . "ID_SECTOR_DPTO, ID_COD_MPIO, ID_COD_CORREG, ID_COD_DPTO, ANO_FACTURA, MES_FACTURA, "
                                                                                                                                        . "ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                                                                               . "VALUES ('$fecha_proc_reg', '$cod_oper_cont', '$nic', '$nis', '$sec_nis', '$sec_rec', '$fecha_fact_lect', "
                                                                                                                                       . "'$id_tipo_cliente', '$id_tarifa', '$id_estado_contrato', '$concepto', '$importe_trans', "
                                                                                                                                       . "'$fecha_trans', '$valor_recibo', '$id_sector_dpto', '$id_cod_mpio', '$id_cod_correg', "
                                                                                                                                       . "'$id_cod_depto', '$ano_factura', '$id_mes', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                                                                                    $i++;
                                                                                }
                                                                                $query_select_totales = mysqli_query($connection, "SELECT COUNT(*), SUM(IMPORTE_TRANS), SUM(VALOR_RECIBO) "
                                                                                                                                . "  FROM recaudo_" . strtolower($mes_consolidado) . $ano_factura . "_2 "
                                                                                                                                . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                                                                                $row_totales = mysqli_fetch_array($query_select_totales);
                                                                                echo "<br />";
                                                                                echo "<p class='message'>Registros cargados: " . $i . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                                                                                echo "<p class='message'>Valor Importe cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_importe_trans, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(IMPORTE_TRANS)'], 0, ',', '.') . ".</p>";
                                                                                echo "<p class='message'>Valor Recibo cargado: " . "<b style='font-size: 14px'>$ </b>" . number_format($total_valor_recibo, 0, ',', '.') . ". Consulta: " . "<b style='font-size: 14px'>$ </b>" . number_format($row_totales['SUM(VALOR_RECIBO)'], 0, ',', '.') . ".</p>";
                                                                                echo "<p class='message'>Archivo cargado con Exito.</p>";
                                                                            } else {
                                                                                echo "<p class='message'>El archivo que intenta cargar no es de Recaudo. Favor verificar la Información.</p>";
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
    <script src="Javascript/bootstrap.min.js"></script>
    <script src="Javascript/menu.js"></script>
    <script src="Javascript/moment-with-locales.js"></script>
    <script src="Javascript/bootstrap-datetimepicker.js"></script>
    <script src="Javascript/jquery.twbsPagination.js"></script>
    <script src="Javascript/custom-file-input.js"></script>
    <!--<script src="http://malsup.github.io/jquery.form.js"></script>Progress Bar Script with Form-->
    <script src="Javascript/jquery.form.js"></script><!--Progress Bar Script with Form-->
    <script>
        $(document).ready(function() {
            $("#ano_factura").focus();
            $("#ano_factura").change(function() {
                var ano_factura = $(this).val();
                $("#loading-spinner").css("display", "block");
                $.ajax({
                    type: "POST",
                    data: "sw=reca&ano_factura="+ano_factura,
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
                    data: "sw=reca&ano_factura="+ano_factura+"&mes_consolidado="+mes_consolidado,
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
                    data: "sw=reca&ano_factura="+ano_factura+"&mes_consolidado="+mes_consolidado+"&departamento="+departamento,
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
                var tipo_poblacion = $("input[name=tipo_poblacion]:checked").val();
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
                if (tipo_poblacion == undefined) {
                    $("#tipo_poblacion").focus();
                    return false;
                }
                $("#upload_files").attr("disabled", true);
                $("#upload_files").css("pointer-events", "none");
                $("#upload_files").html("Subiendo Archivo(s)...");
                $("html, body").css("cursor", "wait");
                $(".wrapper").css("cursor", "wait");
                $(".tab-content").css("cursor", "wait");
                $("#cargar_recaudo").ajaxSubmit({
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
                $('label[name=label_tipo_poblacion]').attr("disabled", false);
                $('label[name=label_tipo_poblacion]').attr("readonly", false);
                $('label[name=label_tipo_poblacion]').removeClass("active");
                $("#tipo_poblacion").prop("checked", false);
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
            $('label[name=label_tipo_poblacion]').tooltip({
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