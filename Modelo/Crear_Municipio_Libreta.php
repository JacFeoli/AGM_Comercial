<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if (isset($_GET['editar'])) {
            $departamento = $_POST['id_departamento'];
            $municipio = $_POST['id_municipio'];
            $nombre_alcalde = strtoupper($_POST['nombre_alcalde']);
            $nombre_sec_hacienda = strtoupper($_POST['nombre_sec_hacienda']);
            $nombre_interventor = strtoupper($_POST['nombre_interventor']);
            $direccion_alcaldia = strtoupper($_POST['direccion_alcaldia']);
            $correo_electronico = strtolower($_POST['correo_electronico']);
            $tipo_cliente = $_POST['tipo_cliente'];
            $clase_contrato = $_POST['clase_contrato'];
            $concesion = $_POST['id_concesion'];
            $empresa = $_POST['id_empresa'];
            $direccion_agm_municipio = strtoupper($_POST['direccion_agm_municipio']);
            $direccion_agm_principal = strtoupper($_POST['direccion_agm_principal']);
            $identificacion_rep_legal = $_POST['identificacion_rep_legal'];
            $nombre_rep_legal = strtoupper($_POST['nombre_rep_legal']);
            $operador = $_POST['id_operador'];
            $no_contrato_concesion = strtoupper($_POST['no_contrato_concesion']);
            $fecha_firma_contrato = $_POST['fecha_firma_contrato'];
            $fecha_inicio_contrato = $_POST['fecha_inicio_contrato'];
            $fecha_fin_contrato = $_POST['fecha_fin_contrato'];
            $duracion_contrato = $_POST['duracion_contrato'];
            $otro_si_contrato = strtoupper($_POST['otro_si_contrato']);
            $fecha_otro_si_contrato = $_POST['fecha_otro_si_contrato'];
            $objeto_contrato = strtoupper($_POST['objeto_contrato']);
            $contrato_energia = strtoupper($_POST['contrato_energia']);
            $periodicidad_contrato = $_POST['periodicidad_contrato'];
            $fecha_firma_contrato_f_r = $_POST['fecha_firma_contrato_f_r'];
            $fecha_ven_ini_contrato_f_r = $_POST['fecha_ven_ini_contrato_f_r'];
            $fecha_ven_act_contrato_f_r = $_POST['fecha_ven_act_contrato_f_r'];
            $duracion_contrato_f_r = $_POST['duracion_contrato_f_r'];
            $en_ejecucion = $_POST['en_ejecucion'];
            $no_ultimo_acuerdo_tarifas = strtoupper($_POST['no_ultimo_acuerdo_tarifas']);
            $no_acuerdo_vigente = strtoupper($_POST['no_acuerdo_vigente']);
            $estatuto_tributario = strtoupper($_POST['estatuto_tributario']);
            $observaciones = strtoupper($_POST['observaciones']);
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['id_user'];
            mysqli_query($connection, "UPDATE municipios_libreta_2
                                          SET ID_DEPARTAMENTO = '" . $departamento . "', "
                                          . " ID_MUNICIPIO = '" . $municipio . "', "
                                          . " NOMBRE_ALCALDE = '" . $nombre_alcalde. "', "
                                          . " NOMBRE_SEC_HACIENDA = '" . $nombre_sec_hacienda . "', "
                                          . " NOMBRE_INTERVENTOR = '" . $nombre_interventor . "', "
                                          . " DIRECCION_ALCALDIA = '" . $direccion_alcaldia . "', "
                                          . " CORREO_ELECTRONICO = '" . $correo_electronico . "', "
                                          . " ID_TIPO_CLIENTE = '" . $tipo_cliente . "', "
                                          . " CLASE_CONTRATO = '" . $clase_contrato . "', "
                                          . " ID_CONCESION = '" . $concesion . "', "
                                          . " ID_EMPRESA = '" . $empresa . "', "
                                          . " DIRECCION_AGM_MUNICIPIO = '" . $direccion_agm_municipio . "', "
                                          . " DIRECCION_AGM_PRINCIPAL = '" . $direccion_agm_principal . "', "
                                          . " IDENTIFICACION_REP_LEGAL = '" . $identificacion_rep_legal . "', "
                                          . " REPR_LEGAL = '" . $nombre_rep_legal . "', "
                                          . " ID_OPERADOR = '" . $operador . "', "
                                          . " NO_CONTRATO_CONCESION = '" . $no_contrato_concesion . "', "
                                          . " FECHA_FIRMA_CONTRATO = '" . $fecha_firma_contrato . "', "
                                          . " FECHA_INICIO_CONTRATO = '" . $fecha_inicio_contrato . "', "
                                          . " FECHA_FIN_CONTRATO = '" . $fecha_fin_contrato . "', "
                                          . " DURACION_CONTRATO = '" . $duracion_contrato . "', "
                                          . " OTRO_SI_CONTRATO = '" . $otro_si_contrato . "', "
                                          . " FECHA_OTRO_SI_CONTRATO = '" . $fecha_otro_si_contrato . "', "
                                          . " OBJETO_CONTRATO = '" . $objeto_contrato . "', "
                                          . " CONTRATO_ENERGIA = '" . $contrato_energia . "', "
                                          . " PERIODICIDAD_RENOVACION = '" . $periodicidad_contrato . "', "
                                          . " FECHA_FIRMA_CONTRATOS_F_R = '" . $fecha_firma_contrato_f_r . "', "
                                          . " FECHA_VENC_CONTRATOS_INI_F_R = '" . $fecha_ven_ini_contrato_f_r . "', "
                                          . " FECHA_VENC_CONTRATOS_ACT_F_R = '" . $fecha_ven_act_contrato_f_r . "', "
                                          . " DURACION_CONTRATOS_F_R = '" . $duracion_contrato_f_r . "', "
                                          . " EN_EJECUCION = '" . $en_ejecucion . "', "
                                          . " NO_ULTIMO_ACUERDO_TARIFAS = '" . $no_ultimo_acuerdo_tarifas . "', "
                                          . " NO_ACUERDO_VIGENTE = '" . $no_acuerdo_vigente . "', "
                                          . " ESTATUTO_TRIBUTARIO = '" . $estatuto_tributario . "', "
                                          . " OBSERVACIONES = '" . $observaciones . "', "
                                          . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                          . " ID_USUARIO = '" . $id_usuario. "' "
                                    . " WHERE ID_MUNICIPIO_LIBRETA = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM municipios_libreta_2 WHERE ID_MUNICIPIO_LIBRETA = " . $_GET['eliminar']);
            } else {
                if (isset($_POST['guardar'])) {
                    $departamento = $_POST['departamento'];
                    $municipio = $_POST['municipio'];
                    $nombre_alcalde = strtoupper($_POST['nombre_alcalde']);
                    $nombre_sec_hacienda = strtoupper($_POST['nombre_sec_hacienda']);
                    $nombre_interventor = strtoupper($_POST['nombre_interventor']);
                    $direccion_alcaldia = strtoupper($_POST['direccion_alcaldia']);
                    $correo_electronico = strtolower($_POST['correo_electronico']);
                    $tipo_cliente = $_POST['tipo_cliente'];
                    $clase_contrato = $_POST['clase_contrato'];
                    $concesion = $_POST['concesion'];
                    $empresa = $_POST['empresa'];
                    $direccion_agm_municipio = strtoupper($_POST['direccion_agm_municipio']);
                    $direccion_agm_principal = strtoupper($_POST['direccion_agm_principal']);
                    $identificacion_rep_legal = $_POST['identificacion_rep_legal'];
                    $nombre_rep_legal = strtoupper($_POST['nombre_rep_legal']);
                    $operador = $_POST['operador'];
                    $no_contrato_concesion = strtoupper($_POST['no_contrato_concesion']);
                    $fecha_firma_contrato = $_POST['fecha_firma_contrato'];
                    $fecha_inicio_contrato = $_POST['fecha_inicio_contrato'];
                    $fecha_fin_contrato = $_POST['fecha_fin_contrato'];
                    $duracion_contrato = $_POST['duracion_contrato'];
                    $otro_si_contrato = strtoupper($_POST['otro_si_contrato']);
                    $fecha_otro_si_contrato = $_POST['fecha_otro_si_contrato'];
                    $objeto_contrato = strtoupper($_POST['objeto_contrato']);
                    $contrato_energia = strtoupper($_POST['contrato_energia']);
                    $periodicidad_contrato = $_POST['periodicidad_contrato'];
                    $fecha_firma_contrato_f_r = $_POST['fecha_firma_contrato_f_r'];
                    $fecha_ven_ini_contrato_f_r = $_POST['fecha_ven_ini_contrato_f_r'];
                    $fecha_ven_act_contrato_f_r = $_POST['fecha_ven_act_contrato_f_r'];
                    $duracion_contrato_f_r = $_POST['duracion_contrato_f_r'];
                    $en_ejecucion = $_POST['en_ejecucion'];
                    $no_ultimo_acuerdo_tarifas = strtoupper($_POST['no_ultimo_acuerdo_tarifas']);
                    $no_acuerdo_vigente = strtoupper($_POST['no_acuerdo_vigente']);
                    $estatuto_tributario = strtoupper($_POST['estatuto_tributario']);
                    $observaciones = strtoupper($_POST['observaciones']);
                    $fecha_creacion = date('Y-m-d H:i:s');
                    $fecha_actualizacion = '0000-00-00';
                    $id_usuario = $_SESSION['id_user'];
                    mysqli_query($connection, "INSERT INTO historico_municipios_libreta_2 (ID_MUNICIPIO_LIBRETA, ID_DEPARTAMENTO, ID_MUNICIPIO, NOMBRE_ALCALDE,
                                                                                           NOMBRE_SEC_HACIENDA, NOMBRE_INTERVENTOR, DIRECCION_ALCALDIA,
                                                                                           CORREO_ELECTRONICO, ID_TIPO_CLIENTE, CLASE_CONTRATO, ID_CONCESION,
                                                                                           ID_EMPRESA, DIRECCION_AGM_MUNICIPIO, DIRECCION_AGM_PRINCIPAL,
                                                                                           IDENTIFICACION_REP_LEGAL, REPR_LEGAL, ID_OPERADOR,
                                                                                           NO_CONTRATO_CONCESION, FECHA_FIRMA_CONTRATO, FECHA_INICIO_CONTRATO,
                                                                                           FECHA_FIN_CONTRATO, DURACION_CONTRATO, OTRO_SI_CONTRATO,
                                                                                           FECHA_OTRO_SI_CONTRATO, OBJETO_CONTRATO, CONTRATO_ENERGIA, PERIODICIDAD_RENOVACION,
                                                                                           FECHA_FIRMA_CONTRATOS_F_R, FECHA_VENC_CONTRATOS_INI_F_R,
                                                                                           FECHA_VENC_CONTRATOS_ACT_F_R, DURACION_CONTRATOS_F_R, EN_EJECUCION,
                                                                                           NO_ULTIMO_ACUERDO_TARIFAS, NO_ACUERDO_VIGENTE, ESTATUTO_TRIBUTARIO,
                                                                                           OBSERVACIONES, FECHA_CREACION, FECHA_ACTUALIZACION, ID_USUARIO)
                                                                                   VALUES ('" . $_POST['guardar'] . "', '" . $departamento .
                                                                                           "', '" . $municipio . "', '" . $nombre_alcalde .
                                                                                           "', '" . $nombre_sec_hacienda .  "', '" . $nombre_interventor .
                                                                                           "', '" . $direccion_alcaldia . "', '" . $correo_electronico .
                                                                                           "', '" . $tipo_cliente . "', '" . $clase_contrato . "', '" . $concesion .
                                                                                           "', '" . $empresa . "', '" . $direccion_agm_municipio .
                                                                                           "', '" . $direccion_agm_principal . "', '" . $identificacion_rep_legal .
                                                                                           "', '" . $nombre_rep_legal . "', '" . $operador .
                                                                                           "', '" . $no_contrato_concesion . "', '" . $fecha_firma_contrato .
                                                                                           "', '" . $fecha_inicio_contrato . "', '" . $fecha_fin_contrato .
                                                                                           "', '" . $duracion_contrato . "', '" . $otro_si_contrato .
                                                                                           "', '" . $fecha_otro_si_contrato . "', '" . $objeto_contrato . "', '" . $contrato_energia .
                                                                                           "', '" . $periodicidad_contrato . "', '" . $fecha_firma_contrato_f_r .
                                                                                           "', '" . $fecha_ven_ini_contrato_f_r . "', '" . $fecha_ven_act_contrato_f_r .
                                                                                           "', '" . $duracion_contrato_f_r . "', '" . $en_ejecucion .
                                                                                           "', '" . $no_ultimo_acuerdo_tarifas . "', '" . $no_acuerdo_vigente .
                                                                                           "', '" . $estatuto_tributario . "', '" . $observaciones .
                                                                                           "', '" . $fecha_creacion . "', '" . $fecha_actualizacion .
                                                                                           "', '" . $id_usuario . "')");
                } else {
                    $departamento = $_POST['departamento'];
                    $municipio = $_POST['municipio'];
                    $nombre_alcalde = strtoupper($_POST['nombre_alcalde']);
                    $nombre_sec_hacienda = strtoupper($_POST['nombre_sec_hacienda']);
                    $nombre_interventor = strtoupper($_POST['nombre_interventor']);
                    $direccion_alcaldia = strtoupper($_POST['direccion_alcaldia']);
                    $correo_electronico = strtolower($_POST['correo_electronico']);
                    $tipo_cliente = $_POST['tipo_cliente'];
                    $clase_contrato = $_POST['clase_contrato'];
                    $concesion = $_POST['concesion'];
                    $empresa = $_POST['empresa'];
                    $direccion_agm_municipio = strtoupper($_POST['direccion_agm_municipio']);
                    $direccion_agm_principal = strtoupper($_POST['direccion_agm_principal']);
                    $identificacion_rep_legal = $_POST['identificacion_rep_legal'];
                    $nombre_rep_legal = strtoupper($_POST['nombre_rep_legal']);
                    $operador = $_POST['operador'];
                    $no_contrato_concesion = strtoupper($_POST['no_contrato_concesion']);
                    $fecha_firma_contrato = $_POST['fecha_firma_contrato'];
                    $fecha_inicio_contrato = $_POST['fecha_inicio_contrato'];
                    $fecha_fin_contrato = $_POST['fecha_fin_contrato'];
                    $duracion_contrato = $_POST['duracion_contrato'];
                    $otro_si_contrato = strtoupper($_POST['otro_si_contrato']);
                    $fecha_otro_si_contrato = $_POST['fecha_otro_si_contrato'];
                    $objeto_contrato = strtoupper($_POST['objeto_contrato']);
                    $contrato_energia = strtoupper($_POST['contrato_energia']);
                    $periodicidad_contrato = $_POST['periodicidad_contrato'];
                    $fecha_firma_contrato_f_r = $_POST['fecha_firma_contrato_f_r'];
                    $fecha_ven_ini_contrato_f_r = $_POST['fecha_ven_ini_contrato_f_r'];
                    $fecha_ven_act_contrato_f_r = $_POST['fecha_ven_act_contrato_f_r'];
                    $duracion_contrato_f_r = $_POST['duracion_contrato_f_r'];
                    $en_ejecucion = $_POST['en_ejecucion'];
                    $no_ultimo_acuerdo_tarifas = strtoupper($_POST['no_ultimo_acuerdo_tarifas']);
                    $no_acuerdo_vigente = strtoupper($_POST['no_acuerdo_vigente']);
                    $estatuto_tributario = strtoupper($_POST['estatuto_tributario']);
                    $observaciones = strtoupper($_POST['observaciones']);
                    $fecha_creacion = date('Y-m-d H:i:s');
                    $fecha_actualizacion = '0000-00-00';
                    $id_usuario = $_SESSION['id_user'];
                    mysqli_query($connection, "INSERT INTO municipios_libreta_2 (ID_DEPARTAMENTO, ID_MUNICIPIO, NOMBRE_ALCALDE, NOMBRE_SEC_HACIENDA,
                                                                                 NOMBRE_INTERVENTOR, DIRECCION_ALCALDIA, CORREO_ELECTRONICO,
                                                                                 ID_TIPO_CLIENTE, CLASE_CONTRATO, ID_CONCESION, ID_EMPRESA, DIRECCION_AGM_MUNICIPIO,
                                                                                 DIRECCION_AGM_PRINCIPAL, IDENTIFICACION_REP_LEGAL, REPR_LEGAL, ID_OPERADOR,
                                                                                 NO_CONTRATO_CONCESION, FECHA_FIRMA_CONTRATO, FECHA_INICIO_CONTRATO,
                                                                                 FECHA_FIN_CONTRATO, DURACION_CONTRATO, OTRO_SI_CONTRATO,
                                                                                 FECHA_OTRO_SI_CONTRATO, OBJETO_CONTRATO, CONTRATO_ENERGIA, PERIODICIDAD_RENOVACION,
                                                                                 FECHA_FIRMA_CONTRATOS_F_R, FECHA_VENC_CONTRATOS_INI_F_R,
                                                                                 FECHA_VENC_CONTRATOS_ACT_F_R, DURACION_CONTRATOS_F_R, EN_EJECUCION,
                                                                                 NO_ULTIMO_ACUERDO_TARIFAS, NO_ACUERDO_VIGENTE, ESTATUTO_TRIBUTARIO,
                                                                                 OBSERVACIONES, FECHA_CREACION, FECHA_ACTUALIZACION, ID_USUARIO)
                                                                         VALUES ('" . $departamento . "', '" . $municipio . "', '" . $nombre_alcalde .
                                                                                "', '" . $nombre_sec_hacienda . "', '" . $nombre_interventor .
                                                                                "', '" . $direccion_alcaldia . "', '" . $correo_electronico .
                                                                                "', '" . $tipo_cliente . "', '" . $clase_contrato . "', '" . $concesion .
                                                                                "', '" . $empresa . "', '" . $direccion_agm_municipio .
                                                                                "', '" . $direccion_agm_principal . "', '" . $identificacion_rep_legal .
                                                                                "', '" . $nombre_rep_legal . "', '" . $operador .
                                                                                "', '" . $no_contrato_concesion . "', '" . $fecha_firma_contrato .
                                                                                "', '" . $fecha_inicio_contrato . "', '" . $fecha_fin_contrato .
                                                                                "', '" . $duracion_contrato . "', '" . $otro_si_contrato .
                                                                                "', '" . $fecha_otro_si_contrato . "', '" . $objeto_contrato . "', '" . $contrato_energia .
                                                                                "', '" . $periodicidad_contrato . "', '" . $fecha_firma_contrato_f_r .
                                                                                "', '" . $fecha_ven_ini_contrato_f_r . "', '" . $fecha_ven_act_contrato_f_r .
                                                                                "', '" . $duracion_contrato_f_r . "', '" . $en_ejecucion .
                                                                                "', '" . $no_ultimo_acuerdo_tarifas . "', '" . $no_acuerdo_vigente .
                                                                                "', '" . $estatuto_tributario . "', '" . $observaciones .
                                                                                "', '" . $fecha_creacion . "', '" . $fecha_actualizacion .
                                                                                "', '" . $id_usuario . "')");
                    $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE "
                                                                             . "  FROM municipios_visitas_2 "
                                                                             . " WHERE ID_DEPARTAMENTO = " . $departamento . ""
                                                                             . "   AND ID_MUNICIPIO = " . $municipio);
                    $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                    if (!file_exists("../Files/" . $row_nombre_municipio['NOMBRE'])) {
                        mkdir("../Files/" . $row_nombre_municipio['NOMBRE'], 0777, true);
                    }
                }
            }
        }
        echo "<script>";
            echo "document.location.href = '../Municipios_Libretas.php'";
        echo "</script>";
    }
?>