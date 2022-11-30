<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Historico_Municipio.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        $id_municipio_libreta_historico = $_POST['id_municipio_libreta_historico'];
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_HISTORICO_MUNICIPIO * ($page - 1);
        }
        $query_historico_municipio = mysqli_query($connection, "SELECT *, DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO
                                                                  FROM historico_municipios_libreta_2 HML, departamentos_visitas_2 DEPT,
                                                                       municipios_visitas_2 MUN
                                                                 WHERE HML.ID_DEPARTAMENTO = DEPT.ID_DEPARTAMENTO
                                                                   AND HML.ID_MUNICIPIO = MUN.ID_MUNICIPIO
                                                                   AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                   AND HML.ID_MUNICIPIO_LIBRETA = " . $id_municipio_libreta_historico . "
                                                                 ORDER BY DEPT.NOMBRE, MUN.NOMBRE");
        $mun_count = 1;
        $table = "";
        while ($row_historico_municipio = mysqli_fetch_assoc($query_historico_municipio)) {
            if ($mun_count == 1) {
                $table = $table . "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>" . $row_historico_municipio['DEPARTAMENTO'] . " - " . $row_historico_municipio['MUNICIPIO'] . "</p>";
            }
            $table = $table . "<div class='panel panel-default'>";
                $table = $table . "<div style='padding: 5px 5px;' class='panel-heading'>";
                    $table = $table . "<h4 style='font-size: 11px;' class='panel-title'>";
                        $table = $table . "<a style='font-size: 11px;' data-toggle='collapse' data-parent='#accordion_historico_municipio' href='#collapse" . $mun_count . "'>ALCALDE: " . $row_historico_municipio['NOMBRE_ALCALDE'] . "</a>";
                    $table = $table . "</h4>";
                $table = $table . "</div>";
                $table = $table . "<div id='collapse" . $mun_count . "' class='panel-collapse collapse'>";
                    $table = $table . "<div style='font-size: 11px; background-color: #D0DEE7;' class='panel-body'>";
                        $table = $table . "<div class='row'><div class='col-xs-12 col-md-3 col-sm-3'>";
                                if ($row_historico_municipio['ID_TIPO_CLIENTE'] == 1) {
                                    $table = $table . "<b>TIPO CLIENTE: </b>" . "REGULADO";
                                } else {
                                    $table = $table . "<b>TIPO CLIENTE: </b>" . "NO REGULADO";
                                }
                            $table = $table . "</div></div>";
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-4 col-sm-4'><b>SEC. HACIENDA: </b>" . $row_historico_municipio['NOMBRE_SEC_HACIENDA'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-4 col-sm-3'><b>INTERVENTOR: </b>" . $row_historico_municipio['NOMBRE_INTERVENTOR'] . "</div>";
                        $table = $table . "</div>";
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>DIRECCIÓN ALC.: </b>" . $row_historico_municipio['DIRECCION_ALCALDIA'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-5 col-sm-5'><b>CORREO ELECTRÓNICO: </b>" . $row_historico_municipio['CORREO_ELECTRONICO'] . "</div>";
                        $table = $table . "</div>";
                        $table = $table . "<div class='row'><div class='col-xs-12 col-md-12 col-sm-12'><div class='divider'></div></div></div>";
                        $query_select_concesion = mysqli_query($connection, "SELECT NOMBRE FROM concesiones_2 WHERE ID_CONCESION = " . $row_historico_municipio['ID_CONCESION']);
                        $row_concesion = mysqli_fetch_array($query_select_concesion);
                        $query_select_empresa = mysqli_query($connection, "SELECT NOMBRE FROM empresas_2 WHERE ID_EMPRESA = " . $row_historico_municipio['ID_EMPRESA']);
                        $row_empresa = mysqli_fetch_array($query_select_empresa);
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-6 col-sm-6'><b>CONCESIÓN: </b>" . $row_concesion['NOMBRE'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-6 col-sm-6'><b>EMPRESA: </b>" . $row_empresa['NOMBRE'] . "</div>";
                        $table = $table . "</div>";
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-6 col-sm-6'><b>DIR. AGM MUNC.: </b>" . $row_historico_municipio['DIRECCION_AGM_MUNICIPIO'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-6 col-sm-6'><b>DIR. AGM PRINC.: </b>" . $row_historico_municipio['DIRECCION_AGM_PRINCIPAL'] . "</div>";
                        $table = $table . "</div>";
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-4 col-sm-4'><b>REP. LEGAL: </b>" . $row_historico_municipio['REPR_LEGAL'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-2 col-sm-2'><b>CÉDULA: </b>" . $row_historico_municipio['IDENTIFICACION_REP_LEGAL'] . "</div>";
                        $table = $table . "</div>";
                        $query_select_operador = mysqli_query($connection, "SELECT NOMBRE FROM operadores_2 WHERE ID_OPERADOR = " . $row_historico_municipio['ID_OPERADOR']);
                        $row_operador = mysqli_fetch_array($query_select_operador);
                        $table = $table . "<div class='row'><div class='col-xs-12 col-md-12 col-sm-12'><b>OPERADOR: </b>" . $row_operador['NOMBRE']. "</div></div>";
                        $table = $table . "<div class='row'><div class='col-xs-12 col-md-12 col-sm-12'><div class='divider'></div></div></div>";
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>NO. CONTRATO: </b>" . $row_historico_municipio['NO_CONTRATO_CONCESION'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>FECHA FIRMA: </b>" . $row_historico_municipio['FECHA_FIRMA_CONTRATO'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>FECHA INICIO: </b>" . $row_historico_municipio['FECHA_INICIO_CONTRATO'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>FECHA FIN: </b>" . $row_historico_municipio['FECHA_FIN_CONTRATO'] . "</div>";
                        $table = $table . "</div>";
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>DURACIÓN: </b>" . $row_historico_municipio['DURACION_CONTRATO'] . " AÑOS</div>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>OTRO SÍ: </b>" . $row_historico_municipio['OTRO_SI_CONTRATO'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>FECHA OTRO SÍ: </b>" . $row_historico_municipio['FECHA_OTRO_SI_CONTRATO'] . "</div>";
                        $table = $table . "</div>";
                        $table = $table . "<div class='row'><div class='col-xs-12 col-md-12 col-sm-12'><div class='divider'></div></div></div>";
                        $query_select_tipo_contrato = mysqli_query($connection, "SELECT NOMBRE FROM tipo_contratos_2 WHERE ID_TIPO_CONTRATO = " . $row_historico_municipio['ID_TIPO_CONTRATO']);
                        $row_tipo_contrato = mysqli_fetch_array($query_select_tipo_contrato);
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-4 col-sm-4'><b>TIPO CONTRATO: </b>" . $row_tipo_contrato['NOMBRE'] . "</div>";
                            if ($row_historico_municipio['PERIODICIDAD_RENOVACION'] == 1) {
                                $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>PERIODICIDAD: </b>" . "ANUAL" . "</div>";
                            } else {
                                $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>PERIODICIDAD: </b>" . "NO HAY CONTRATO" . "</div>";
                            }
                            if ($row_historico_municipio['EN_EJECUCION'] == 1) {
                                $table = $table . "<div class='col-xs-12 col-md-4 col-sm-4'><b>EN EJECUCIÓN: </b>" . "SI" . "</div>";
                            } else {
                                $table = $table . "<div class='col-xs-12 col-md-4 col-sm-4'><b>EN EJECUCIÓN: </b>" . "NO" . "</div>";
                            }
                        $table = $table . "</div>";
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>DURACIÓN (F & R): </b>" . $row_historico_municipio['DURACION_CONTRATOS_F_R'] . " AÑOS</div>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>FECHA FIRMA (F & R): </b>" . $row_historico_municipio['FECHA_FIRMA_CONTRATOS_F_R'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>FECHA INICIAL (F & R): </b>" . $row_historico_municipio['FECHA_VENC_CONTRATOS_INI_F_R'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>FECHA ACTUAL (F & R): </b>" . $row_historico_municipio['FECHA_VENC_CONTRATOS_ACT_F_R'] . "</div>";
                        $table = $table . "</div>";
                        $table = $table . "<div class='row'><div class='col-xs-12 col-md-12 col-sm-12'><div class='divider'></div></div></div>";
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-3 col-sm-3'><b>ULT. ACUERDO: </b>" . $row_historico_municipio['NO_ULTIMO_ACUERDO_TARIFAS'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-4 col-sm-4'><b>ACUERDO VIG.: </b>" . $row_historico_municipio['NO_ACUERDO_VIGENTE'] . "</div>";
                            $table = $table . "<div class='col-xs-12 col-md-4 col-sm-4'><b>EST. TRIB.: </b>" . $row_historico_municipio['ESTATUTO_TRIBUTARIO'] . "</div>";
                        $table = $table . "</div>";
                        $table = $table . "<div class='row'><div class='col-xs-12 col-md-12 col-sm-12'><div class='divider'></div></div></div>";
                        $table = $table . "<div class='row'>";
                            $table = $table . "<div class='col-xs-12 col-md-12 col-sm-12'><b>OBSERVACIONES: </b>" . $row_historico_municipio['OBSERVACIONES'] . "</div>";
                        $table = $table . "</div>";
                    $table = $table . "</div>";
                $table = $table . "</div>";
            $table = $table . "</div>";
            $mun_count = $mun_count + 1;
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>