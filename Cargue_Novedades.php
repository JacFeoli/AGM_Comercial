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
        <title>AGM - Cargue Novedades</title>
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
                                                                                <a href='Cargue_Novedades.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-alt"></i>
                                                                                    <span>Cargue Novedades</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Archivos_Novedades.php'>
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
                                            <h1>Cargue Novedades</h1>
                                            <h2></h2>
                                            <ul class="nav nav-pills" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#cargar_novedad_tab" id="tab_cargar_novedad" aria-controls="cargar_novedad_tab" role="tab" data-toggle="tab">Cargar Novedad</a>
                                                </li>
                                            </ul>
                                            <h2></h2>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="cargar_novedad_tab">
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer" id="cargar_novedad" name="cargar_novedad" action="" method="post" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <div class="col-xs-2">
                                                                <div class="styled-select">
                                                                    <select class="form-control input-text input-sm" id="ano_factura" name="ano_factura" data-toggle="tooltip" title="AÑO FACTURACIÓN" required>
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
                                                                    <select class="form-control input-text input-sm" id="mes_consolidado" name="mes_consolidado" data-toggle="tooltip" title="MES CONSOLIDADO" required>
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
                                                                    function sanear_string($string) {
                                                                        $string = trim($string);
                                                                        $string = str_replace(
                                                                            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
                                                                            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
                                                                            $string
                                                                        );
                                                                        $string = str_replace(
                                                                            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                                                                            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                                                                            $string
                                                                        );
                                                                        $string = str_replace(
                                                                            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                                                                            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                                                                            $string
                                                                        );
                                                                        $string = str_replace(
                                                                            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
                                                                            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
                                                                            $string
                                                                        );
                                                                        $string = str_replace(
                                                                            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                                                                            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                                                                            $string
                                                                        );
                                                                        $string = str_replace(
                                                                            array('ñ', 'Ñ', 'ç', 'Ç'),
                                                                            array('n', 'N', 'c', 'C',),
                                                                            $string
                                                                        );
                                                                        return $string;
                                                                    }
                                                                    if (isset($_POST['upload_files'])) {
                                                                        $filename = $_FILES['files']['name'];
                                                                        $query_select_ruta = mysqli_query($connection, "SELECT * FROM archivos_cargados_novedades_2 WHERE RUTA = '" . $filename . "'");
                                                                        if (mysqli_num_rows($query_select_ruta) == 0) {
                                                                            if (substr($filename, 0, 4) == 'NOVE') {
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
                                                                                echo $fullpath = "D:/BASES DE DATOS/Consolidados/" . $ano_factura . "/". $mes_consolidado . "/Novedades/" . $departamento . "/" . $municipio . "/" . $filename;
                                                                                echo "<br />";
                                                                                $data = file($fullpath);
                                                                                $i = 0;
                                                                                $j = 0;
                                                                                $registros = array();
                                                                                mysqli_query($connection, "INSERT INTO archivos_cargados_novedades_2 (ANO_FACTURA, ID_MES_FACTURA, MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                                                                                 . "VALUES ('" . $ano_factura . "', '" . $id_mes . "', '" . strtoupper($mes_consolidado) . "', "
                                                                                                                                         . "'" . strtoupper($departamento) . "', '" . strtoupper($municipio) . "', "
                                                                                                                                         . "'" . $filename . "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                                                                                $query_select_filename = mysqli_query($connection, "SELECT * FROM archivos_cargados_novedades_2 WHERE RUTA = '" . $filename . "'");
                                                                                $row_filename = mysqli_fetch_array($query_select_filename);
                                                                                $id_tabla_ruta = $row_filename['ID_TABLA'];
                                                                                foreach ($data as $lines) {
                                                                                    $registros[] = explode("|", $lines);
                                                                                    $nic = trim($registros[$i][0]);
                                                                                    if ($nic != 0) {
                                                                                        $nis = trim($registros[$i][1]);
                                                                                        $cod_tarifa_actual = trim($registros[$i][6]);
                                                                                        $cod_tarifa_anterior = trim($registros[$i][7]);
                                                                                        $fecha_cambio_tarifa = trim(substr($registros[$i][8], 0, 4) . "-" . substr($registros[$i][8], 4, 2) . "-" . substr($registros[$i][8], 6, 2));
                                                                                        if ($registros[$i][10] != "") {
                                                                                            $query_select_tipo_novedad = mysqli_query($connection, "SELECT * FROM tipo_novedades_2 WHERE NOMBRE = '" . strtoupper(sanear_string(utf8_encode($registros[$i][10]))) . "'");
                                                                                            $row_tipo_novedad = mysqli_fetch_array($query_select_tipo_novedad);
                                                                                            if (mysqli_num_rows($query_select_tipo_novedad) == 0) {
                                                                                                mysqli_query($connection, "INSERT INTO tipo_novedades_2 (NOMBRE) VALUES ('" . strtoupper(sanear_string(utf8_encode($registros[$i][10]))) . "')");
                                                                                                $query_select_new_tipo_novedad = mysqli_query($connection, "SELECT * FROM tipo_novedades_2 WHERE NOMBRE = '" . strtoupper(sanear_string(utf8_encode($registros[$i][10]))) . "'");
                                                                                                $row_new_tipo_novedad = mysqli_fetch_array($query_select_new_tipo_novedad);
                                                                                                echo "Registro agregado en la tabla 'tipo_novedades_2': " . strtoupper(sanear_string(utf8_encode($registros[$i][10])));
                                                                                                echo "<br />";
                                                                                                $id_tipo_novedad = $row_new_tipo_novedad['ID_TIPO_NOVEDAD'];
                                                                                            } else {
                                                                                                $id_tipo_novedad = $row_tipo_novedad['ID_TIPO_NOVEDAD'];
                                                                                            }
                                                                                        } else {
                                                                                            $id_tipo_novedad = 1;
                                                                                        }
                                                                                        $query_select_estado_actual_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE NOMBRE = '" . strtoupper(sanear_string(utf8_encode($registros[$i][11]))) . "'");
                                                                                        $row_estado_actual_suministro = mysqli_fetch_array($query_select_estado_actual_suministro);
                                                                                        if (mysqli_num_rows($query_select_estado_actual_suministro) == 0) {
                                                                                            mysqli_query($connection, "INSERT INTO estados_suministro_2 (NOMBRE) VALUES ('" . strtoupper(sanear_string(utf8_encode($registros[$i][11]))) . "')");
                                                                                            $query_select_new_estado_actual_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE NOMBRE = '" . strtoupper(sanear_string(utf8_encode($registros[$i][11]))) . "'");
                                                                                            $row_new_estado_actual_suministro = mysqli_fetch_array($query_select_new_estado_actual_suministro);
                                                                                            echo "Registro agregado en la tabla 'estados_suministro_2': " . strtoupper(sanear_string(utf8_encode($registros[$i][11])));
                                                                                            echo "<br />";
                                                                                            $id_estado_actual_suministro = $row_new_estado_actual_suministro['ID_ESTADO_SUMINISTRO'];
                                                                                        } else {
                                                                                            $id_estado_actual_suministro = $row_estado_actual_suministro['ID_ESTADO_SUMINISTRO'];
                                                                                        }
                                                                                        $query_select_estado_anterior_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE NOMBRE = '" . strtoupper(sanear_string(utf8_encode($registros[$i][12]))) . "'");
                                                                                        $row_estado_anterior_suministro = mysqli_fetch_array($query_select_estado_anterior_suministro);
                                                                                        if (mysqli_num_rows($query_select_estado_anterior_suministro) == 0) {
                                                                                            mysqli_query($connection, "INSERT INTO estados_suministro_2 (NOMBRE) VALUES ('" . strtoupper(sanear_string(utf8_encode($registros[$i][12]))) . "')");
                                                                                            $query_select_new_estado_anterior_suministro = mysqli_query($connection, "SELECT * FROM estados_suministro_2 WHERE NOMBRE = '" . strtoupper(sanear_string(utf8_encode($registros[$i][12]))) . "'");
                                                                                            $row_new_estado_anterior_suministro = mysqli_fetch_array($query_select_new_estado_anterior_suministro);
                                                                                            echo "Registro agregado en la tabla 'estados_suministro_2': " . strtoupper(sanear_string(utf8_encode($registros[$i][12])));
                                                                                            echo "<br />";
                                                                                            $id_estado_anterior_suministro = $row_new_estado_anterior_suministro['ID_ESTADO_SUMINISTRO'];
                                                                                        } else {
                                                                                            $id_estado_anterior_suministro = $row_estado_anterior_suministro['ID_ESTADO_SUMINISTRO'];
                                                                                        }
                                                                                        mysqli_query($connection, "INSERT INTO novedades_" . strtolower($mes_consolidado) . $ano_factura . "_2 (NIC, NIS, COD_TARIFA_ACTUAL, COD_TARIFA_ANTERIOR, "
                                                                                                                                        . "FECHA_CAMBIO_TARIFA, ID_TIPO_NOVEDAD, ID_ESTADO_ACTUAL, ID_ESTADO_ANTERIOR, "
                                                                                                                                        . "ANO_NOVEDAD, MES_NOVEDAD, ID_TABLA_RUTA, FECHA_CREACION, ID_USUARIO) "
                                                                                                                               . "VALUES ('$nic', '$nis', '$cod_tarifa_actual', '$cod_tarifa_anterior', '$fecha_cambio_tarifa', "
                                                                                                                                       . "'$id_tipo_novedad', '$id_estado_actual_suministro', '$id_estado_anterior_suministro', "
                                                                                                                                       . "'$ano_factura', '$id_mes', '$id_tabla_ruta', '$fecha_creacion', '$id_usuario')");
                                                                                        $j++;
                                                                                    }
                                                                                    $i++;
                                                                                }
                                                                                $query_select_totales = mysqli_query($connection, "SELECT COUNT(*) "
                                                                                                                                . "  FROM novedades_" . strtolower($mes_consolidado) . $ano_factura . "_2 "
                                                                                                                                . " WHERE ID_TABLA_RUTA = " . $id_tabla_ruta);
                                                                                $row_totales = mysqli_fetch_array($query_select_totales);
                                                                                echo "<br />";
                                                                                echo "<p class='message'>Registros cargados: " . $j . ". Consulta: " . $row_totales['COUNT(*)'] . ".</p>";
                                                                                echo "<p class='message'>Archivo cargado con Exito.</p>";
                                                                            } else {
                                                                                echo "<p class='message'>El archivo que intenta cargar no es de Novedades. Favor verificar la Información.</p>";
                                                                            }
                                                                        } else {
                                                                            echo "<p class='message'>El archivo que intenta cargar ya se encuentra registrado en la Base de Datos. Favor verificar la Información.</p>";
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
    <script src="Javascript/custom-file-input.js"></script>
    <!--<script src="http://malsup.github.io/jquery.form.js"></script>Progress Bar Script with Form-->
    <script src="Javascript/jquery.form.js"></script><!--Progress Bar Script with Form-->
    <script>
        
    </script>
    <script>
        $(document).ready(function() {
            $("#ano_factura").focus();
            $("#ano_factura").change(function() {
                var ano_factura = $(this).val();
                $("#loading-spinner").css("display", "block");
                $.ajax({
                    type: "POST",
                    data: "sw=nove&ano_factura="+ano_factura,
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
                    data: "sw=nove&ano_factura="+ano_factura+"&mes_consolidado="+mes_consolidado,
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
                    data: "sw=nove&ano_factura="+ano_factura+"&mes_consolidado="+mes_consolidado+"&departamento="+departamento,
                    url: "Modelo/Cargar_Municipios.php",
                    success: function(data) {
                        $("#loading-spinner").css("display", "none");
                        $("#municipio").html(data);
                        $("#municipio").focus();
                    }
                });
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
            $('select[name=mes_factura]').tooltip({
                container : "body",
                placement : "bottom"
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